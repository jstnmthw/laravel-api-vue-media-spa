# Laravel 8.x / Vue.js Media SPA
A Single page application for media sources (eg. Videos, Images, Music etc) based on Laravel 8.x and Vue.js.

## Installation
#### Laradock
If you plan on using Laradock you must first pull it's submodule repo.
 
`cd laradock`

`git submodule update --init --recursive`
 
#### Launch apps
 `docker-compose up -d nginx mysql phpmyadmin redis workspace`

#### Start bash
`docker-compose exec workspace`

#### Migrate databases
`php artisan migrate`
 
##### Import CSV via console command. (Optional)
The following columns must be present in the `.csv` file.

`embed, thumbnail, album, title, categories, author, duration, views, likes, dislikes`

Import command: 

`php artistan csv:import`

#### Elastic Search
##### Create index
You can change the name of the index that you create in `App\MediaIndex.php` or use the default `media_idx`.

`php artisan elastic:create-index media_idx`

##### Import Models
`php artisan scout:import`

## Scripts

Watch

`npm run watch`

Dev

`npm run dev`

Production

`npm run prod`
