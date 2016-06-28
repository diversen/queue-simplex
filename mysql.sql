DROP TABLE  IF EXISTS `systemqueue`;

CREATE TABLE `systemqueue` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uniqueid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `done` tinyint(1) unsigned DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_name_uniqueid` (`name`,`uniqueid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
