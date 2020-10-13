# Laravel 8.x / Vue.js Media SPA

A Single page application for media sources (eg. Videos, Images, Music etc) based on Laravel 7.x and Vue.js.

## Installation
### Docker
#### Install laradock
 If you plan on using Laradock you must first pull it's submodule repo.
 
 `cd laradock`
 
 `git submodule update --init --recursive`
 
#### Launch apps
 `docker-compose up -d nginx mysql phpmyadmin redis workspace`


#### Import CSV via console command.
The following columns must be present in the `.csv` file.

`embed, thumbnail, album, title, categories, author, duration, views, likes, dislikes`
 
 Import command: 
 
`php artistan csv:import`

## Scripts

Watch

`npm run watch`

Dev

`npm run dev`

Production

`npm run prod`
