CREATE SCHEMA `arcs_designer`;
USE `arcs_designer`;


CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,

    `email_address` VARCHAR(191) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `display_name` VARCHAR(32) NOT NULL,

    `created_at` TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`)
) ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `login_tokens` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,

    `user_id` INT UNSIGNED NOT NULL,
    `selector` CHAR(16) NOT NULL,
    `token_hash` CHAR(64) NOT NULL,
    `expires` DATETIME NOT NULL,

    `created_at` TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY (`selector`),
    UNIQUE KEY (`token_hash`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `cards` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,

    `name` VARCHAR(32) NOT NULL,

    `created_at` TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`)
) ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `affinities` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,

    `name` VARCHAR(30) NOT NULL,
    `purple` BOOL NOT NULL,
    `red` BOOL NOT NULL,
    `orange` BOOL NOT NULL,
    `yellow` BOOL NOT NULL,
    `green` BOOL NOT NULL,
    `blue` BOOL NOT NULL,

    `created_at` TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    CONSTRAINT `max_aspects` CHECK (
        `purple` + `red` + `orange` + `yellow` + `green` + `blue` <= 3
    )
) ENGINE = InnoDB;


INSERT INTO `affinities` (
    `id`,
    `name`,
    `purple`,
    `red`,
    `orange`,
    `yellow`,
    `green`,
    `blue`
) VALUES
(1, '[None]', 0, 0, 0, 0, 0, 0),
(2, 'Purple', 1, 0, 0, 0, 0, 0),
(3, 'Red', 0, 1, 0, 0, 0, 0),
(4, 'Orange', 0, 0, 1, 0, 0, 0),
(5, 'Yellow', 0, 0, 0, 1, 0, 0),
(6, 'Green', 0, 0, 0, 0, 1, 0),
(7, 'Blue', 0, 0, 0, 0, 0, 1),
(8, 'Purple / Red', 1, 1, 0, 0, 0, 0),
(9, 'Purple / Orange', 1, 0, 1, 0, 0, 0),
(10, 'Purple / Yellow', 1, 0, 0, 1, 0, 0),
(11, 'Red / Orange', 0, 1, 1, 0, 0, 0),
(12, 'Red / Yellow', 0, 1, 0, 1, 0, 0),
(13, 'Red / Green', 0, 1, 0, 0, 1, 0),
(14, 'Orange / Yellow', 0, 0, 1, 1, 0, 0),
(15, 'Orange / Green', 0, 0, 1, 0, 1, 0),
(16, 'Orange / Blue', 0, 0, 1, 0, 0, 1),
(17, 'Yellow / Green', 0, 0, 0, 1, 1, 0),
(18, 'Yellow / Blue', 0, 0, 0, 1, 0, 1),
(19, 'Green / Blue', 0, 0, 0, 0, 1, 1),
(20, 'Green / Purple', 1, 0, 0, 0, 1, 0),
(21, 'Blue / Purple', 1, 0, 0, 0, 0, 1),
(22, 'Blue / Red', 0, 1, 0, 0, 0, 1),
(23, 'Purple / Red / Orange', 1, 1, 1, 0, 0, 0),
(24, 'Purple / Red / Yellow', 1, 1, 0, 1, 0, 0),
(25, 'Purple / Orange / Yellow', 1, 0, 1, 1, 0, 0),
(26, 'Purple / Orange / Green', 1, 0, 1, 0, 1, 0),
(27, 'Red / Orange / Yellow', 0, 1, 1, 1, 0, 0),
(28, 'Red / Orange / Green', 0, 1, 1, 0, 1, 0),
(29, 'Red / Yellow / Green', 0, 1, 0, 1, 1, 0),
(30, 'Red / Yellow / Blue', 0, 1, 0, 1, 0, 1),
(31, 'Orange / Yellow / Green', 0, 0, 1, 1, 1, 0),
(32, 'Orange / Yellow / Blue', 0, 0, 1, 1, 0, 1),
(33, 'Orange / Green / Blue', 0, 0, 1, 0, 1, 1),
(34, 'Yellow / Green / Blue', 0, 0, 0, 1, 1, 1),
(35, 'Yellow / Green / Purple', 1, 0, 0, 1, 1, 0),
(36, 'Yellow / Blue / Purple', 1, 0, 0, 1, 0, 1),
(37, 'Green / Blue / Purple', 1, 0, 0, 0, 1, 1),
(38, 'Green / Blue / Red', 0, 1, 0, 0, 1, 1),
(39, 'Green / Purple / Red', 1, 1, 0, 0, 1, 0),
(40, 'Blue / Purple / Red', 1, 1, 0, 0, 0, 1),
(41, 'Blue / Purple / Orange', 1, 0, 1, 0, 0, 1),
(42, 'Blue / Red / Orange', 0, 1, 1, 0, 0, 1);


CREATE TABLE IF NOT EXISTS `card_iterations` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,

    `card_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(32) NOT NULL,
    `affinity_id` INT UNSIGNED NULL,
    `cost` INT UNSIGNED NULL,
    `enflowable` BOOL NULL,
    `speed_modifier` VARCHAR(4) NULL,
    `zone_modifier` VARCHAR(6) NULL,
    `starting_life` INT UNSIGNED NULL,
    `burden` INT UNSIGNED NULL,
    `card_type` VARCHAR(8) NULL,
    `rules_text` TEXT NOT NULL,
    `attack` INT SIGNED NULL,
    `defense` INT SIGNED NULL,

    `created_at` TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    FOREIGN KEY `card_id` (`card_id`) REFERENCES `cards` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    FOREIGN KEY `affinity_id` (`affinity_id`) REFERENCES `affinities` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `current_iterations` (
    `card_id` INT UNSIGNED NOT NULL,
    `iteration_id` INT UNSIGNED NOT NULL,

    `created_at` TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`card_id`, `iteration_id`),
    FOREIGN KEY `card_id` (`card_id`) REFERENCES `cards` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    FOREIGN KEY `iteration_id` (`iteration_id`) REFERENCES `card_iterations` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `card_comments` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,

    `card_id` INT UNSIGNED NOT NULL,
    `iteration_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `text` TEXT NOT NULL,

    `created_at` TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    FOREIGN KEY `card_id` (`card_id`) REFERENCES `cards` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    FOREIGN KEY `iteration_id` (`iteration_id`) REFERENCES `card_iterations` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    FOREIGN KEY `user_id` (`user_id`) REFERENCES `users` (`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE = InnoDB;
