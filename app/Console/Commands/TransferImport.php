<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class TransferImport extends Command
{
    protected $signature = 'transfer:import '
        . '{--connection= : Target DB connection name (default: config database.default)} '
        . '{--path= : Input directory containing *.ndjson (default: storage/app/transfer)} '
        . '{--truncate : Truncate tables before import} '
        . '{--tables= : Comma-separated table list to import} '
        . '{--exclude= : Comma-separated tables to exclude} '
        . '{--batch=500 : Insert batch size}';

    protected $description = 'Import NDJSON dumps into the target DB, preserving IDs when present';

    public function handle(): int
    {
        $connection = $this->option('connection') ?: config('database.default');
        $driver = config("database.connections.$connection.driver");
        if (!$driver) {
            $this->error("Connection '$connection' not found in config/database.php");
            return self::FAILURE;
        }

        $inputDir = rtrim($this->option('path') ?: storage_path('app/transfer'), DIRECTORY_SEPARATOR);
        if (!is_dir($inputDir)) {
            $this->error("Input directory not found: $inputDir");
            return self::FAILURE;
        }

        $tables = $this->resolveTables($inputDir, $this->option('tables'), $this->option('exclude'));
        if (empty($tables)) {
            $this->warn('No tables found to import.');
            return self::SUCCESS;
        }

        $this->info("Importing into connection '$connection' (driver: $driver) from $inputDir");

        $batchSize = (int) $this->option('batch') ?: 500;
        $truncate = (bool) $this->option('truncate');

        $this->setForeignKeys($connection, $driver, false);
        try {
            foreach ($tables as $table) {
                $file = $inputDir . DIRECTORY_SEPARATOR . $table . '.ndjson';
                if (!is_file($file)) {
                    $this->warn("- $table: file not found, skipping");
                    continue;
                }

                if (!Schema::connection($connection)->hasTable($table)) {
                    $this->warn("- $table: table not found in target DB, skipping");
                    continue;
                }

                $columns = Schema::connection($connection)->getColumnListing($table);
                $this->line("- $table (columns: " . implode(',', $columns) . ")");

                if ($truncate) {
                    $this->truncateTable($connection, $driver, $table);
                }

                $fh = fopen($file, 'r');
                if (!$fh) {
                    $this->error("  Failed to open: $file");
                    continue;
                }

                $buffer = [];
                $count = 0;
                while (($line = fgets($fh)) !== false) {
                    $line = trim($line);
                    if ($line === '') continue;
                    $row = json_decode($line, true);
                    if (!is_array($row)) continue;
                    // keep only known columns to avoid errors
                    $row = array_intersect_key($row, array_flip($columns));
                    $buffer[] = $row;
                    if (count($buffer) >= $batchSize) {
                        DB::connection($connection)->table($table)->insert($buffer);
                        $count += count($buffer);
                        $buffer = [];
                    }
                }
                if (!empty($buffer)) {
                    DB::connection($connection)->table($table)->insert($buffer);
                    $count += count($buffer);
                }
                fclose($fh);
                $this->line("  → imported $count rows from $file");
            }
        } finally {
            $this->setForeignKeys($connection, $driver, true);
        }

        $this->info('Import completed.');
        return self::SUCCESS;
    }

    /**
     * @return array<int, string>
     */
    private function resolveTables(string $inputDir, ?string $tablesOpt, ?string $excludeOpt): array
    {
        if ($tablesOpt) {
            $tables = array_values(array_filter(array_map('trim', explode(',', $tablesOpt))));
        } else {
            $files = glob($inputDir . DIRECTORY_SEPARATOR . '*.ndjson') ?: [];
            $tables = array_map(function ($f) {
                return basename($f, '.ndjson');
            }, $files);
        }
        $excludes = $excludeOpt ? array_map('trim', explode(',', $excludeOpt)) : [];
        if (!empty($excludes)) {
            $tables = array_values(array_filter($tables, fn ($t) => !in_array($t, $excludes, true)));
        }
        sort($tables);
        return $tables;
    }

    private function setForeignKeys(string $connection, string $driver, bool $enable): void
    {
        if ($driver === 'mysql') {
            DB::connection($connection)->statement('SET FOREIGN_KEY_CHECKS=' . ($enable ? '1' : '0'));
        } elseif ($driver === 'sqlite') {
            DB::connection($connection)->statement('PRAGMA foreign_keys = ' . ($enable ? 'ON' : 'OFF'));
        } elseif ($driver === 'pgsql') {
            DB::connection($connection)->statement("SET session_replication_role = '" . ($enable ? 'origin' : 'replica') . "'");
        }
    }

    private function truncateTable(string $connection, string $driver, string $table): void
    {
        if ($driver === 'mysql') {
            DB::connection($connection)->statement("TRUNCATE TABLE `{$table}`");
        } elseif ($driver === 'sqlite') {
            DB::connection($connection)->statement("DELETE FROM \"{$table}\"");
        } elseif ($driver === 'pgsql') {
            DB::connection($connection)->statement("TRUNCATE TABLE \"{$table}\" RESTART IDENTITY CASCADE");
        }
    }
}

