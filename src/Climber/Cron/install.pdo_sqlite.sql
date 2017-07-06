CREATE TABLE IF NOT EXISTS `climber_cron` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `name` TEXT,
    `cmd`	TEXT,
    `repeat` INTEGER DEFAULT 0,
    `error`	INTEGER DEFAULT 0,
    `interval`	INTEGER,
    `last_execution`	INTEGER,
    `next_execution`	INTEGER,
    `enabled`	INTEGER DEFAULT 1
);

CREATE TABLE IF NOT EXISTS `climber_console` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `data` TEXT,
    `created_at` INTEGER
);