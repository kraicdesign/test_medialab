CREATE TABLE `strings` (
  `hash` char(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `string` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  UNIQUE KEY `hash` (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci