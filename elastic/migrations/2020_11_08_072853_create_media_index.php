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
            // Mapping
            $mapping->text('thumbnail');
            $mapping->text('embed');
            $mapping->text('album');
            $mapping->text('title');
            $mapping->keyword('categories');
            $mapping->text('author');
            $mapping->float('duration');
            $mapping->float('views');
            $mapping->float('likes');
            $mapping->float('dislikes');
            $mapping->dateRange('created_at');
            $mapping->dateRange('updated_at');

            // Settings
            $settings->analysis([
                'properties' => [
                    'categories' => [
                        'type' => 'text',
                        'fields' => [
                            'raw' => [
                                'type' => 'keyword',
                            ],
                        ],
                    ],
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
