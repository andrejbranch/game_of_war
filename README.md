Game Of War
=========
The game of war card game written in php

Installation
------------

Edit the database config file depending on your database configuration in config/database.yml

    database:
        driver: pdo_mysql
        user: root
        password: ''
        dbname: game_of_war

Replace the user and password if necessary

Create the game_of_war database

    mysql -u root -p
    create database game_of_war

From the root directory tell doctrine to build the database schema and generate proxies

    php vendor/bin/doctrine orm:schema-tool:create
    php vendor/bin/doctrine orm:generate-proxies

Start the Game
--------------

    ./bin/game_of_war start


All game results are logged to logs/game_of_war.log
