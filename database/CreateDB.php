<?php

namespace database;

class CreateDB extends Database
{
    private $createTableQueries =
    [
        "CREATE TABLE `banners` (
`id` int(10) UNSIGNED NOT NULL,
`image` text NOT NULL,
`url` varchar(255) NOT NULL,
`created_at` datetime NOT NULL,
`updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;",

        "CREATE TABLE `categories` (
`id` int(10) UNSIGNED NOT NULL,
`name` varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
`created_at` datetime NOT NULL,
`updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;",

        "CREATE TABLE `comments` (
`id` int(10) UNSIGNED NOT NULL,
`user_id` int(10) UNSIGNED NOT NULL,
`post_id` int(10) UNSIGNED NOT NULL,
`comment` text NOT NULL,
`status` enum('unseen','seen','approved') NOT NULL DEFAULT 'unseen',
`created_at` datetime NOT NULL,
`updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;",

        "CREATE TABLE `menus` (
`id` int(10) UNSIGNED NOT NULL,
`name` varchar(120) NOT NULL,
`url` varchar(255) NOT NULL,
`parent_id` int(10) UNSIGNED DEFAULT NULL,
`created_at` datetime NOT NULL,
`updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;",

        "CREATE TABLE `posts` (
`id` int(11) UNSIGNED NOT NULL,
`title` varchar(120) NOT NULL,
`summary` text NOT NULL,
`body` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
`view` int(11) NOT NULL DEFAULT 0,
`user_id` int(11) UNSIGNED NOT NULL,
`cat_id` int(11) UNSIGNED NOT NULL,
`image` text NOT NULL,
`status` enum('enable','disable') NOT NULL DEFAULT 'disable',
`selelcted` tinyint(4) NOT NULL DEFAULT 0,
`breaking_news` tinyint(4) NOT NULL DEFAULT 0,
`published_at` datetime NOT NULL,
`created_at` datetime NOT NULL,
`updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;",

        "CREATE TABLE `setting` (
`id` int(10) UNSIGNED NOT NULL,
`title` varchar(120) DEFAULT NULL,
`description` text DEFAULT NULL,
`keywords` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
`logo` text DEFAULT NULL,
`icon` text DEFAULT NULL,
`created_at` datetime NOT NULL,
`updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;",

        "CREATE TABLE `users` (
`id` int(10) UNSIGNED NOT NULL,
`username` varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
`email` varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
`password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
`permission` enum('user','admin') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'user',
`verify_token` varchar(255) DEFAULT NULL,
`is_active` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 => inactive , 1 => active',
`forgot_token` varchar(255) DEFAULT NULL,
`forgot_token_expire` varchar(255) DEFAULT NULL,
`created_at` datetime NOT NULL,
`updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;",

        "ALTER TABLE `banners`
ADD PRIMARY KEY (`id`);",

        "ALTER TABLE `categories`
ADD PRIMARY KEY (`id`);",

        "ALTER TABLE `comments`
ADD PRIMARY KEY (`id`),
ADD KEY `post_id` (`user_id`);",

        "ALTER TABLE `menus`
ADD PRIMARY KEY (`id`),
ADD KEY `parent_id` (`parent_id`);",

        "ALTER TABLE `posts`
ADD PRIMARY KEY (`id`),
ADD KEY `user_id` (`user_id`),
ADD KEY `cat_id` (`cat_id`);",

        "ALTER TABLE `setting`
ADD PRIMARY KEY (`id`);",

        "ALTER TABLE `users`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `email` (`email`);",

        "ALTER TABLE `banners`
MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;",

        "ALTER TABLE `categories`
MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;",

        "ALTER TABLE `comments`
MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;",

        "ALTER TABLE `menus`
MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;",

        "ALTER TABLE `posts`
MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;",

        "ALTER TABLE `setting`
MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;",

        "ALTER TABLE `users`
MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;",

        "ALTER TABLE `comments`
ADD CONSTRAINT `post_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `user_id2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;",

        "ALTER TABLE `menus`
ADD CONSTRAINT `parent_id` FOREIGN KEY (`parent_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;",

        "ALTER TABLE `posts`
ADD CONSTRAINT `cat_id` FOREIGN KEY (`cat_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;"
    ];

    public function run()
    {

        foreach ($this->createTableQueries as $createTableQuery) {

            $this->createTable($createTableQuery);
        }
    }
}
