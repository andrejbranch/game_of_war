# game_of_war
Game of war php project



Game Of War
=========

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
    create database quore_fun

From the root directory tell doctrine to build the database schema and generate proxies

    php vendor/bin/doctrine orm:schema-tool:create
    php vendor/bin/doctrine orm:generate-proxies

Start the Game
--------------

    ./bin/game_of_war start
