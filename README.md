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

    CREATE TABLE `games` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `player1_id` int(11) NOT NULL,
      `player2_id` int(11) NOT NULL,
      `winning_player_id` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `UNIQ_FF232B31C0990423` (`player1_id`),
      UNIQUE KEY `UNIQ_FF232B31D22CABCD` (`player2_id`),
      UNIQUE KEY `UNIQ_FF232B3187E2974C` (`winning_player_id`),
      CONSTRAINT `FK_FF232B3187E2974C` FOREIGN KEY (`winning_player_id`) REFERENCES `players` (`id`),
      CONSTRAINT `FK_FF232B31C0990423` FOREIGN KEY (`player1_id`) REFERENCES `players` (`id`),
      CONSTRAINT `FK_FF232B31D22CABCD` FOREIGN KEY (`player2_id`) REFERENCES `players` (`id`)
    )

    CREATE TABLE `rounds` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `player1_card_played_id` int(11) DEFAULT NULL,
      `player2_card_played_id` int(11) DEFAULT NULL,
      `winning_player_id` int(11) DEFAULT NULL,
      `game_id` int(11) NOT NULL,
      `is_war` tinyint(1) NOT NULL,
      PRIMARY KEY (`id`),
      KEY `IDX_3A7FD554843CBB20` (`player1_card_played_id`),
      KEY `IDX_3A7FD554ADF40FD2` (`player2_card_played_id`),
      KEY `IDX_3A7FD55487E2974C` (`winning_player_id`),
      KEY `IDX_3A7FD554E48FD905` (`game_id`),
      CONSTRAINT `FK_3A7FD554843CBB20` FOREIGN KEY (`player1_card_played_id`) REFERENCES `cards` (`id`),
      CONSTRAINT `FK_3A7FD55487E2974C` FOREIGN KEY (`winning_player_id`) REFERENCES `players` (`id`),
      CONSTRAINT `FK_3A7FD554ADF40FD2` FOREIGN KEY (`player2_card_played_id`) REFERENCES `cards` (`id`),
      CONSTRAINT `FK_3A7FD554E48FD905` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`)
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
