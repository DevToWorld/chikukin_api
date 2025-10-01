<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 既存のテーブルが作成済みかチェック
        if (Schema::hasTable('block_types')) {
            return; // 既に作成済みの場合はスキップ
        }

        // ULID生成関数の作成（PostgreSQL用）
        if (config('database.default') === 'pgsql') {
            // 既存の関数がある場合はスキップ
            $functionExists = DB::select("SELECT 1 FROM pg_proc WHERE proname = 'generate_ulid'");
            if (empty($functionExists)) {
                DB::unprepared('
                    CREATE OR REPLACE FUNCTION generate_ulid() RETURNS TEXT AS $$
                    DECLARE
                        encoding   TEXT := \'0123456789ABCDEFGHJKMNPQRSTVWXYZ\';
                        timestamp  BIGINT;
                        output     TEXT := \'\';
                        randpart   TEXT := \'\';
                        i          INTEGER;
                    BEGIN
                        timestamp := EXTRACT(EPOCH FROM NOW() AT TIME ZONE \'UTC\') * 1000;
                        
                        FOR i IN 1..10 LOOP
                            output := SUBSTRING(encoding, ((timestamp % 32) + 1)::INTEGER, 1) || output;
                            timestamp := timestamp / 32;
                        END LOOP;
                        
                        FOR i IN 1..16 LOOP
                            randpart := randpart || SUBSTRING(encoding, (FLOOR(RANDOM() * 32) + 1)::INTEGER, 1);
                        END LOOP;
                        
                        RETURN output || randpart;
                    END;
                    $$ LANGUAGE plpgsql;
                ');
            }
        }

        // 1. ブロックタイプ定義テーブル
        Schema::create('block_types', function (Blueprint $table) {
            $table->string('id')->primary()->default(DB::raw('generate_ulid()'));
            $table->string('name')->unique();
            $table->string('label');
            $table->string('icon', 50)->nullable();
            $table->text('description')->nullable();
            $table->json('schema_definition');
            $table->json('default_content')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. ページマスタテーブル
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->string('id')->primary()->default(DB::raw('generate_ulid()'));
            $table->string('page_key')->unique();
            $table->string('title');
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('og_image')->nullable();
            $table->string('template')->default('default');
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->integer('version')->default(1);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            
            $table->index('page_key');
            $table->index(['is_published', 'published_at']);
        });

        // 3. ページブロックテーブル
        Schema::create('page_blocks', function (Blueprint $table) {
            $table->string('id')->primary()->default(DB::raw('generate_ulid()'));
            $table->string('page_id');
            $table->string('block_type_id');
            $table->string('parent_block_id')->nullable();
            $table->string('title')->nullable();
            $table->json('content');
            $table->binary('media_data')->nullable();
            $table->string('media_type', 50)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            
            $table->foreign('page_id')->references('id')->on('cms_pages')->onDelete('cascade');
            $table->foreign('block_type_id')->references('id')->on('block_types');
            
            $table->index('page_id');
            $table->index('block_type_id');
            $table->index(['is_published', 'published_at']);
            $table->index(['page_id', 'sort_order']);
            $table->index('parent_block_id');
        });

        // Postgresでは同一テーブルの自己参照FKはテーブル作成後に付与するのが安全
        if (Schema::hasTable('page_blocks')) {
            Schema::table('page_blocks', function (Blueprint $table) {
                try {
                    $table->foreign('parent_block_id')->references('id')->on('page_blocks')->onDelete('cascade');
                } catch (\Throwable $e) {
                    // 既に付与済み、または他DBのためスキップ
                }
            });
        }

        // 4. 再利用可能ブロックテーブル
        Schema::create('reusable_blocks', function (Blueprint $table) {
            $table->string('id')->primary()->default(DB::raw('generate_ulid()'));
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('block_type_id');
            $table->json('content');
            $table->binary('media_data')->nullable();
            $table->string('media_type', 50)->nullable();
            $table->string('category', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            
            $table->foreign('block_type_id')->references('id')->on('block_types');
            
            $table->index('slug');
            $table->index('category');
        });

        // 5. ブロックバージョン履歴テーブル
        Schema::create('block_versions', function (Blueprint $table) {
            $table->string('id')->primary()->default(DB::raw('generate_ulid()'));
            $table->string('page_block_id')->nullable();
            $table->string('reusable_block_id')->nullable();
            $table->json('content');
            $table->binary('media_data')->nullable();
            $table->string('media_type', 50)->nullable();
            $table->integer('version_number');
            $table->text('change_description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamp('created_at')->useCurrent();
            
            $table->foreign('page_block_id')->references('id')->on('page_blocks')->onDelete('cascade');
            $table->foreign('reusable_block_id')->references('id')->on('reusable_blocks')->onDelete('cascade');
        });

        // 6. メディア管理テーブル（画像DB保存）
        Schema::create('cms_media', function (Blueprint $table) {
            $table->string('id')->primary()->default(DB::raw('generate_ulid()'));
            $table->string('filename');
            $table->text('alt_text')->nullable();
            $table->string('media_type', 50);
            $table->binary('media_data');
            $table->integer('file_size')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->json('metadata')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users');
            $table->timestamps();
            
            $table->index('media_type');
        });

        // 7. ページ画像関連テーブル
        Schema::create('page_media', function (Blueprint $table) {
            $table->string('id')->primary()->default(DB::raw('generate_ulid()'));
            $table->string('page_id');
            $table->string('media_id');
            $table->string('usage_type', 50)->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamp('created_at')->useCurrent();
            
            $table->foreign('page_id')->references('id')->on('cms_pages')->onDelete('cascade');
            $table->foreign('media_id')->references('id')->on('cms_media')->onDelete('cascade');
            
            $table->index('page_id');
        });

        // 標準ブロックタイプの挿入
        $this->seedBlockTypes();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_media');
        Schema::dropIfExists('cms_media');
        Schema::dropIfExists('block_versions');
        Schema::dropIfExists('reusable_blocks');
        Schema::dropIfExists('page_blocks');
        Schema::dropIfExists('cms_pages');
        Schema::dropIfExists('block_types');
        
        if (config('database.default') === 'pgsql') {
            DB::unprepared('DROP FUNCTION IF EXISTS generate_ulid() CASCADE');
        }
    }

    /**
     * ブロックタイプの初期データを挿入
     */
    private function seedBlockTypes(): void
    {
        $blockTypes = [
            [
                'name' => 'heading',
                'label' => '見出し',
                'icon' => '📌',
                'description' => '見出しテキストブロック',
                'schema_definition' => json_encode([
                    'type' => 'object',
                    'properties' => [
                        'text' => ['type' => 'string'],
                        'level' => ['type' => 'number', 'enum' => [1, 2, 3, 4, 5, 6]],
                        'alignment' => ['type' => 'string', 'enum' => ['left', 'center', 'right']]
                    ],
                    'required' => ['text', 'level']
                ]),
                'default_content' => json_encode(['text' => '見出しテキスト', 'level' => 2, 'alignment' => 'left'])
            ],
            [
                'name' => 'text',
                'label' => 'テキスト',
                'icon' => '📝',
                'description' => 'リッチテキストブロック',
                'schema_definition' => json_encode([
                    'type' => 'object',
                    'properties' => [
                        'content' => ['type' => 'string'],
                        'format' => ['type' => 'string', 'enum' => ['html', 'markdown', 'plain']]
                    ],
                    'required' => ['content']
                ]),
                'default_content' => json_encode(['content' => '', 'format' => 'html'])
            ],
            [
                'name' => 'image',
                'label' => '画像',
                'icon' => '🖼️',
                'description' => '画像ブロック',
                'schema_definition' => json_encode([
                    'type' => 'object',
                    'properties' => [
                        'media_id' => ['type' => 'string'],
                        'url' => ['type' => 'string'],
                        'alt' => ['type' => 'string'],
                        'caption' => ['type' => 'string'],
                        'width' => ['type' => 'string'],
                        'height' => ['type' => 'string'],
                        'alignment' => ['type' => 'string', 'enum' => ['left', 'center', 'right', 'full']]
                    ]
                ]),
                'default_content' => json_encode(['alignment' => 'center'])
            ],
            [
                'name' => 'columns',
                'label' => 'カラム',
                'icon' => '⬚⬚',
                'description' => '複数カラムレイアウト',
                'schema_definition' => json_encode([
                    'type' => 'object',
                    'properties' => [
                        'columns' => ['type' => 'number', 'enum' => [2, 3, 4]],
                        'gap' => ['type' => 'string'],
                        'responsive' => ['type' => 'boolean']
                    ],
                    'required' => ['columns']
                ]),
                'default_content' => json_encode(['columns' => 2, 'gap' => '20px', 'responsive' => true])
            ],
            [
                'name' => 'html',
                'label' => 'HTML',
                'icon' => '</>',
                'description' => 'カスタムHTMLブロック',
                'schema_definition' => json_encode([
                    'type' => 'object',
                    'properties' => [
                        'code' => ['type' => 'string']
                    ],
                    'required' => ['code']
                ]),
                'default_content' => json_encode(['code' => ''])
            ],
            [
                'name' => 'spacer',
                'label' => 'スペーサー',
                'icon' => '↕️',
                'description' => '余白調整ブロック',
                'schema_definition' => json_encode([
                    'type' => 'object',
                    'properties' => [
                        'height' => ['type' => 'string'],
                        'responsive' => [
                            'type' => 'object',
                            'properties' => [
                                'mobile' => ['type' => 'string'],
                                'tablet' => ['type' => 'string'],
                                'desktop' => ['type' => 'string']
                            ]
                        ]
                    ],
                    'required' => ['height']
                ]),
                'default_content' => json_encode(['height' => '30px'])
            ],
            [
                'name' => 'button',
                'label' => 'ボタン',
                'icon' => '🔘',
                'description' => 'ボタンリンクブロック',
                'schema_definition' => json_encode([
                    'type' => 'object',
                    'properties' => [
                        'text' => ['type' => 'string'],
                        'url' => ['type' => 'string'],
                        'style' => ['type' => 'string', 'enum' => ['primary', 'secondary', 'outline']],
                        'size' => ['type' => 'string', 'enum' => ['small', 'medium', 'large']],
                        'alignment' => ['type' => 'string', 'enum' => ['left', 'center', 'right']],
                        'target' => ['type' => 'string', 'enum' => ['_self', '_blank']]
                    ],
                    'required' => ['text', 'url']
                ]),
                'default_content' => json_encode(['text' => 'ボタンテキスト', 'url' => '#', 'style' => 'primary', 'size' => 'medium', 'alignment' => 'left', 'target' => '_self'])
            ],
            [
                'name' => 'list',
                'label' => 'リスト',
                'icon' => '📋',
                'description' => '箇条書きリストブロック',
                'schema_definition' => json_encode([
                    'type' => 'object',
                    'properties' => [
                        'type' => ['type' => 'string', 'enum' => ['bullet', 'number']],
                        'items' => [
                            'type' => 'array',
                            'items' => ['type' => 'string']
                        ]
                    ],
                    'required' => ['type', 'items']
                ]),
                'default_content' => json_encode(['type' => 'bullet', 'items' => []])
            ],
            [
                'name' => 'table',
                'label' => 'テーブル',
                'icon' => '📊',
                'description' => '表ブロック',
                'schema_definition' => json_encode([
                    'type' => 'object',
                    'properties' => [
                        'headers' => [
                            'type' => 'array',
                            'items' => ['type' => 'string']
                        ],
                        'rows' => [
                            'type' => 'array',
                            'items' => [
                                'type' => 'array',
                                'items' => ['type' => 'string']
                            ]
                        ],
                        'striped' => ['type' => 'boolean'],
                        'bordered' => ['type' => 'boolean']
                    ],
                    'required' => ['headers', 'rows']
                ]),
                'default_content' => json_encode(['headers' => [], 'rows' => [], 'striped' => true, 'bordered' => true])
            ],
            [
                'name' => 'accordion',
                'label' => 'アコーディオン',
                'icon' => '📂',
                'description' => '折りたたみコンテンツ',
                'schema_definition' => json_encode([
                    'type' => 'object',
                    'properties' => [
                        'items' => [
                            'type' => 'array',
                            'items' => [
                                'type' => 'object',
                                'properties' => [
                                    'title' => ['type' => 'string'],
                                    'content' => ['type' => 'string']
                                ]
                            ]
                        ],
                        'allowMultiple' => ['type' => 'boolean']
                    ],
                    'required' => ['items']
                ]),
                'default_content' => json_encode(['items' => [], 'allowMultiple' => false])
            ],
            [
                'name' => 'video',
                'label' => '動画',
                'icon' => '🎬',
                'description' => '動画埋め込みブロック',
                'schema_definition' => json_encode([
                    'type' => 'object',
                    'properties' => [
                        'url' => ['type' => 'string'],
                        'type' => ['type' => 'string', 'enum' => ['youtube', 'vimeo', 'direct']],
                        'autoplay' => ['type' => 'boolean'],
                        'controls' => ['type' => 'boolean'],
                        'loop' => ['type' => 'boolean']
                    ],
                    'required' => ['url', 'type']
                ]),
                'default_content' => json_encode(['type' => 'youtube', 'controls' => true, 'autoplay' => false, 'loop' => false])
            ],
            [
                'name' => 'card',
                'label' => 'カード',
                'icon' => '🃏',
                'description' => 'カード型コンテンツ',
                'schema_definition' => json_encode([
                    'type' => 'object',
                    'properties' => [
                        'title' => ['type' => 'string'],
                        'description' => ['type' => 'string'],
                        'image' => ['type' => 'string'],
                        'link' => ['type' => 'string'],
                        'linkText' => ['type' => 'string']
                    ]
                ]),
                'default_content' => json_encode(['title' => '', 'description' => '', 'linkText' => '詳細を見る'])
            ]
        ];

        foreach ($blockTypes as $blockType) {
            DB::table('block_types')->insertOrIgnore($blockType);
        }
    }
};
