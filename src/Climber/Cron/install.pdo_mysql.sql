CREATE TABLE IF NOT EXISTS `climber_cron` (
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT,
    `name` varchar(100),
    `cmd`	TEXT,
    `sys_cmd` TINYINT DEFAULT 0,
    `repeat` INTEGER DEFAULT 0,
    `error`	TINYINT DEFAULT 0,
    `interval`	INTEGER,
    `last_execution`	INTEGER,
    `next_execution`	INTEGER,
    `enabled`	TINYINT DEFAULT 1
);

CREATE TABLE IF NOT EXISTS `climber_console` (
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT,
    `data` TEXT,
    `created_at` INTEGER
);