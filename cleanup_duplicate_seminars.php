<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Laravelアプリケーションを起動
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Railway PostgreSQL 重複セミナーデータ整理 ===\n\n";

function confirmAction($message) {
    echo $message . " (y/N): ";
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    fclose($handle);
    return trim(strtolower($line)) === 'y';
}

try {
    // データベース接続確認
    $dbConfig = config('database.connections.pgsql');
    echo "データベース接続: {$dbConfig['host']}:{$dbConfig['port']}/{$dbConfig['database']}\n\n";

    // セミナーテーブルの存在確認
    if (!Schema::hasTable('seminars')) {
        echo "❌ seminarsテーブルが存在しません\n";
        exit(1);
    }

    // 全セミナーデータを取得
    $seminars = DB::table('seminars')->orderBy('created_at', 'asc')->get();
    echo "📊 総セミナー数: " . $seminars->count() . "\n\n";

    if ($seminars->count() <= 10) {
        echo "✅ セミナー数が正常範囲内です。クリーンアップの必要はありません。\n";
        exit(0);
    }

    // 重複チェック（タイトル、日付、開始時間で重複を判定）
    $duplicateGroups = [];
    $uniqueSeminars = [];
    $seen = [];

    foreach ($seminars as $seminar) {
        $key = trim($seminar->title) . '|' . $seminar->date . '|' . $seminar->start_time;
        
        if (isset($seen[$key])) {
            // 重複発見
            if (!isset($duplicateGroups[$key])) {
                $duplicateGroups[$key] = [
                    'original' => $seen[$key],
                    'duplicates' => []
                ];
            }
            $duplicateGroups[$key]['duplicates'][] = $seminar;
        } else {
            $seen[$key] = $seminar;
            $uniqueSeminars[] = $seminar;
        }
    }

    $totalDuplicates = 0;
    foreach ($duplicateGroups as $group) {
        $totalDuplicates += count($group['duplicates']);
    }

    echo "🔍 分析結果:\n";
    echo "  - ユニークセミナー: " . count($uniqueSeminars) . "件\n";
    echo "  - 重複グループ: " . count($duplicateGroups) . "グループ\n";
    echo "  - 重複データ: {$totalDuplicates}件\n";
    echo "  - 削除後の予想数: " . (count($uniqueSeminars)) . "件\n\n";

    if (empty($duplicateGroups)) {
        echo "✅ 重複データは見つかりませんでした\n";
        exit(0);
    }

    // 重複データの詳細表示
    echo "📋 重複データの詳細:\n";
    $groupIndex = 1;
    foreach ($duplicateGroups as $key => $group) {
        echo "\nグループ {$groupIndex}: {$group['original']->title}\n";
        echo "  保持: ID={$group['original']->id} (作成日: {$group['original']->created_at})\n";
        foreach ($group['duplicates'] as $duplicate) {
            echo "  削除予定: ID={$duplicate->id} (作成日: {$duplicate->created_at})\n";
        }
        $groupIndex++;
        
        if ($groupIndex > 5) {
            $remaining = count($duplicateGroups) - 5;
            if ($remaining > 0) {
                echo "  ... 他 {$remaining} グループ\n";
            }
            break;
        }
    }

    echo "\n⚠️  注意: この操作は重複データを永久に削除します！\n";
    echo "     セミナー登録データも連動して削除される可能性があります。\n\n";

    if (!confirmAction("重複データを削除しますか？")) {
        echo "キャンセルされました。\n";
        exit(0);
    }

    // バックアップ作成の提案
    if (confirmAction("削除前にデータをバックアップしますか？")) {
        $timestamp = date('Y-m-d_H-i-s');
        $backupFile = "seminar_backup_{$timestamp}.sql";
        
        echo "📦 バックアップを作成中...\n";
        // PostgreSQLのpg_dumpコマンドを実行（要調整）
        $dumpCommand = sprintf(
            'pg_dump -h %s -p %s -U %s -d %s -t seminars -t seminar_registrations > %s',
            $dbConfig['host'],
            $dbConfig['port'],
            $dbConfig['username'],
            $dbConfig['database'],
            $backupFile
        );
        
        echo "バックアップコマンド: {$dumpCommand}\n";
        echo "手動でバックアップを作成してから続行してください。\n\n";
        
        if (!confirmAction("バックアップ完了後、削除を続行しますか？")) {
            echo "キャンセルされました。\n";
            exit(0);
        }
    }

    // 重複データの削除実行
    echo "🗑️  重複データを削除中...\n";
    $deletedCount = 0;
    
    DB::beginTransaction();
    
    try {
        foreach ($duplicateGroups as $group) {
            foreach ($group['duplicates'] as $duplicate) {
                // まず、関連するセミナー登録データを削除
                $registrationsDeleted = DB::table('seminar_registrations')
                    ->where('seminar_id', $duplicate->id)
                    ->delete();
                
                if ($registrationsDeleted > 0) {
                    echo "  関連登録データ削除: {$registrationsDeleted}件 (セミナーID: {$duplicate->id})\n";
                }
                
                // セミナーデータを削除
                DB::table('seminars')->where('id', $duplicate->id)->delete();
                $deletedCount++;
                
                echo "  削除: ID={$duplicate->id}, タイトル={$duplicate->title}\n";
            }
        }
        
        DB::commit();
        
        echo "\n✅ 削除完了:\n";
        echo "  - 削除されたセミナー: {$deletedCount}件\n";
        echo "  - 残存セミナー: " . count($uniqueSeminars) . "件\n";
        
        // 最終確認
        $finalCount = DB::table('seminars')->count();
        echo "  - データベース確認: {$finalCount}件\n";
        
    } catch (Exception $e) {
        DB::rollback();
        echo "❌ 削除中にエラーが発生しました: " . $e->getMessage() . "\n";
        echo "ロールバックされました。\n";
        exit(1);
    }

} catch (Exception $e) {
    echo "❌ エラーが発生しました: " . $e->getMessage() . "\n";
    echo "スタックトレース:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

echo "\n=== クリーンアップ完了 ===\n";
