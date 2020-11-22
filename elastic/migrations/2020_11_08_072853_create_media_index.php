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
        Index::create('media', function (Mapping $mapping, Settings $settings) {
            // Mappings
            $mapping->text('embed');
            $mapping->text('thumbnail');
            $mapping->text('album');
            $mapping->text('title', [
                'fields' => [
                    'alphanumeric' => [
                        'type' => 'text',
                        'analyzer' => 'alphanumericStringAnalyzer'
                    ],
                    'raw' => [
                        'type' => 'keyword'
                    ],
//                    'normalize' => [
//                        'type' => 'keyword',
//                        'normalizer' => 'title_normalizer',
//                        'analyzer' => 'alphanumericStringAnalyzer'
//                    ],
//                    'keyword' => [
//                        'type' => 'keyword'
//                    ]
                ]
            ]);
            $mapping->text('categories');
            $mapping->text('author');
            $mapping->float('duration');
            $mapping->float('views');
            $mapping->float('likes');
            $mapping->float('dislikes');
            $mapping->date('created_at');

            // Analysis
            $settings->analysis([
//                'normalizer' => [
//                    'title_normalizer' => [
//                        'type' => 'custom',
//                        'filter' => [
//                            'lowercase',
//                            'asciifolding'
//                        ]
//                    ]
//                ],
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
                        'pattern' => '[^A-Za-z0-9]',
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
