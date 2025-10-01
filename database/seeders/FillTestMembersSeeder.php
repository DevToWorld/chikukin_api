<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;

class FillTestMembersSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            'test1@example.com' => [
                'phone' => '090-1111-2222',
                'postal_code' => '100-0001',
                'address' => '東京都千代田区千代田1-1',
                'position' => '部長',
                'department' => '営業部',
                'concerns' => '新規顧客開拓、DX推進',
                'notes' => '面談希望：水曜午前',
            ],
            'test2@example.com' => [
                'phone' => '090-2222-3333',
                'postal_code' => '530-0001',
                'address' => '大阪府大阪市北区梅田1-1-1',
                'position' => '課長',
                'department' => '管理部',
                'concerns' => '人材採用、教育',
                'notes' => 'オンライン面談可',
            ],
            'test3@example.com' => [
                'phone' => '090-3333-4444',
                'postal_code' => '812-0011',
                'address' => '福岡県福岡市博多区博多駅前1-1-1',
                'position' => '主任',
                'department' => '企画部',
                'concerns' => '市場調査、コスト管理',
                'notes' => 'メール連絡希望',
            ],
        ];

        foreach ($rows as $email => $vals) {
            $m = Member::where('email', $email)->first();
            if (!$m) continue;
            // Memberモデルのencryptedキャストにより保存時に暗号化される
            $m->fill($vals);
            $m->save();
        }
    }
}

