# Laravel 8.x / Vue.js Media SPA
A single page application for media sources (eg. videos, images, music etc) written with Laravel 8.x and Vue.js.

## Installation
#### 1.) Laradock (Optional)
If you plan on using Laradock you must first pull it's submodule repo.
 
`git submodule update --init --recursive`
 
##### Launch apps
 `docker-compose up -d nginx mysql phpmyadmin redis elasticsearch workspace`

##### Start bash
`docker-compose exec workspace`

##### Docker Bash Bug Fix (Windows)
Use the following fix after logging into Docker container bash via Windows terminal and getting the following error: `bash: $'\r': command not found`

`sed -i 's/\r$//' /root/aliases.sh`

#### 2.) Migrate databases
`php artisan migrate`
 
##### Import CSV via console command. (Optional)
The following columns must be present in the `.csv` file.

`embed, thumbnail, album, title, categories, author, duration, views, likes, dislikes`

Import command: 

`php artistan csv:import`

#### 3.) Install Elastic Search
##### Create index
You can change the name of the index that you create in `App\MediaIndex.php` or use the default `media_idx`.

`php artisan elastic:create-index 'App\MediaIndex'`

##### Import Models
`php artisan scout:import 'App\Media'`

#### 4.) Composer
`composer install`

## Scripts

Watch

`npm run watch`

Dev

`npm run dev`

Production

`npm run prod`
