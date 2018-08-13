CREATE TABLE IF NOT EXISTS `climber_cron` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `name` TEXT,
    `cmd`	TEXT,
    `sys_cmd` INTEGER DEFAULT 0,
    `repeat` INTEGER DEFAULT 0,
    `status`	INTEGER DEFAULT NULL,
    `in_action` INTEGER DEFAULT 0,
    `error` TEXT,
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