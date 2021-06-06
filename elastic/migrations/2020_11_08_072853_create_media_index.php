<?php
declare(strict_types=1);

use ElasticAdapter\Indices\Mapping;
use ElasticAdapter\Indices\Settings;
use ElasticMigrations\Facades\Index;
use ElasticMigrations\MigrationInterface;

final class CreateMediaIndex implements MigrationInterface
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        Index::drop('media');
        Index::create('media', function (Mapping $mapping, Settings $settings) {
            // Mappings
            $mapping->text('unique_key');
            $mapping->text('url');
            $mapping->text('thumbnail');
            $mapping->text('title', [
                'fields' => [
                    'alphanumeric' => [
                        'type' => 'text',
                        'analyzer' => 'alphanumericStringAnalyzer'
                    ],
                    'raw' => [
                        'type' => 'keyword'
                    ]
                ]
            ]);
            $mapping->text('slug');
            $mapping->text('author');
            $mapping->text('album');
            $mapping->object('categories', [
                'properties' => [
                    'name' => [
                        'type' => 'text',
                        'analyzer' => 'alphanumericStringAnalyzer',
                        'fields' => [
                            'keyword' => [
                                'type' => 'keyword',
                                'ignore_above' => 256
                            ]
                        ]
                    ],
                    'url' => [
                        'type' => 'text',
                        'fields' => [
                            'keyword' => [
                                'type' => 'keyword',
                                'ignore_above' => 256
                            ]
                        ]
                    ]
                ]
            ]);
            $mapping->float('duration');
            $mapping->float('views');
            $mapping->float('likes');
            $mapping->float('dislikes');
            $mapping->date('created_at');

            // Analysis
            $settings->analysis([
                'analyzer' => [
                    'alphanumericStringAnalyzer' => [
                        'filter' => 'lowercase',
                        'char_filter' => [
                            'specialCharactersFilter'
                        ],
                        'type' => 'custom',
                        'tokenizer' => 'standard'
                    ]
                ],
                'char_filter' => [
                    'specialCharactersFilter'=> [
                        'pattern' => '/[^\\p{L} 0-9]/um',
                        'type' => 'pattern_replace',
                        'replacement' => ''
                    ]
                ],
            ]);
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Index::drop('media');
    }
}
