CREATE TABLE IF NOT EXISTS `climber_cron` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `name` TEXT,
    `cmd`	TEXT,
    `repeat` INTEGER DEFAULT 0,
    `error`	INTEGER,
    `interval`	INTEGER,
    `last_execution`	INTEGER,
    `next_execution`	INTEGER
);

CREATE TABLE IF NOT EXISTS `climber_console` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `data` TEXT,
    `dt_created` INTEGER
);