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

Schema
-------

    CREATE TABLE `cards` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
      `suit` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
      `power` int(11) NOT NULL,
      PRIMARY KEY (`id`)
    )

    CREATE TABLE `players` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
      PRIMARY KEY (`id`)
    )

    CREATE TABLE `player_cards` (
      `player_id` int(11) NOT NULL,
      `card_id` int(11) NOT NULL,
      PRIMARY KEY (`player_id`,`card_id`),
      KEY `IDX_BBB023BB99E6F5DF` (`player_id`),
      KEY `IDX_BBB023BB4ACC9A20` (`card_id`),
      CONSTRAINT `FK_BBB023BB4ACC9A20` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`),
      CONSTRAINT `FK_BBB023BB99E6F5DF` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`)
    )

Start the Game
--------------

    ./bin/game_of_war start


All game results are logged to logs/game_of_war.log
