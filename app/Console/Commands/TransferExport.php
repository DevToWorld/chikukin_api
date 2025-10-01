<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TransferExport extends Command
{
    protected $signature = 'transfer:export '
        . '{--connection= : Source DB connection name (default: config database.default)} '
        . '{--path= : Output directory (default: storage/app/transfer)} '
        . '{--tables= : Comma-separated table list to export} '
        . '{--exclude= : Comma-separated table list to exclude} '
        . '{--chunk=1000 : Rows per batch}';

    protected $description = 'Export all tables as NDJSON for cross-DB transfer (SQLite/Postgres/MySQL)';

    public function handle(): int
    {
        $connection = $this->option('connection') ?: config('database.default');
        $driver = config("database.connections.$connection.driver");
        if (!$driver) {
            $this->error("Connection '$connection' not found in config/database.php");
            return self::FAILURE;
        }

        $outputDir = $this->option('path') ?: storage_path('app/transfer');
        File::ensureDirectoryExists($outputDir);

        $tables = $this->resolveTables($connection, $driver, $this->option('tables'), $this->option('exclude'));
        if (empty($tables)) {
            $this->warn('No tables found to export.');
            return self::SUCCESS;
        }

        $this->info("Exporting from connection '$connection' (driver: $driver) → $outputDir");

        $meta = [
            'connection' => $connection,
            'driver' => $driver,
            'exported_at' => now()->toIso8601String(),
            'tables' => [],
        ];

        $chunk = (int) $this->option('chunk') ?: 1000;

        foreach ($tables as $table) {
            $count = DB::connection($connection)->table($table)->count();
            $this->line("- $table ($count rows)");

            $filePath = rtrim($outputDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $table . '.ndjson';
            $fh = fopen($filePath, 'w');
            if (!$fh) {
                $this->error("  Failed to open file: $filePath");
                continue;
            }

            // Export via offset/limit to avoid requiring PKs
            $exported = 0;
            for ($offset = 0; $offset < $count; $offset += $chunk) {
                $rows = DB::connection($connection)
                    ->table($table)
                    ->offset($offset)
                    ->limit($chunk)
                    ->get();
                foreach ($rows as $row) {
                    $data = json_encode((array) $row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    fwrite($fh, $data . "\n");
                    $exported++;
                }
            }

            fclose($fh);
            $meta['tables'][] = ['name' => $table, 'rows' => $count, 'file' => basename($filePath)];
            $this->line("  → saved: $filePath ($exported lines)");
        }

        file_put_contents(
            rtrim($outputDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'schema.json',
            json_encode($meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        $this->info('Export completed.');
        return self::SUCCESS;
    }

    /**
     * @return array<int, string>
     */
    private function resolveTables(string $connection, string $driver, ?string $tablesOpt, ?string $excludeOpt): array
    {
        if ($tablesOpt) {
            $tables = array_values(array_filter(array_map('trim', explode(',', $tablesOpt))));
        } else {
            $tables = $this->listTables($connection, $driver);
        }

        $excludes = $excludeOpt ? array_map('trim', explode(',', $excludeOpt)) : [];
        if (!empty($excludes)) {
            $tables = array_values(array_filter($tables, fn ($t) => !in_array($t, $excludes, true)));
        }
        sort($tables);
        return $tables;
    }

    /**
     * @return array<int, string>
     */
    private function listTables(string $connection, string $driver): array
    {
        $tables = [];
        if ($driver === 'sqlite') {
            $rows = DB::connection($connection)->select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
            foreach ($rows as $r) { $tables[] = $r->name; }
        } elseif ($driver === 'pgsql') {
            $rows = DB::connection($connection)->select("SELECT tablename FROM pg_tables WHERE schemaname='public'");
            foreach ($rows as $r) { $tables[] = $r->tablename; }
        } elseif ($driver === 'mysql') {
            $rows = DB::connection($connection)->select('SHOW TABLES');
            foreach ($rows as $r) {
                // The column name is dynamic (Tables_in_{db}) → pick first value
                $vals = array_values((array) $r);
                if (!empty($vals)) { $tables[] = $vals[0]; }
            }
        }
        return $tables;
    }
}

