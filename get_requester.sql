/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0 */;
/*!40101 SET @OLD_SQL_MODE = @@SQL_MODE, SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES = @@SQL_NOTES, SQL_NOTES = 0 */;

CREATE DATABASE IF NOT EXISTS `get_request_tracker` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `get_request_tracker`;

CREATE TABLE IF NOT EXISTS `requests`
(
    `id`           int(11)                                     NOT NULL AUTO_INCREMENT,
    `response`     int(11)                                     NOT NULL,
    `size`         int(11)                                              DEFAULT NULL,
    `connect_time` float                                                DEFAULT NULL,
    `total_time`   int(11)                                              DEFAULT NULL,
    `saved_as`     varchar(255) CHARACTER SET utf8 COLLATE utf8_bin     DEFAULT NULL,
    `url_uid`      char(8) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
    `datetime`     datetime                                    NOT NULL DEFAULT current_timestamp(),
    `request_ip`   varchar(255)                                         DEFAULT NULL,
    `response_ip`  varchar(255)                                         DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE IF NOT EXISTS `request_urls`
(
    `uid` char(8) CHARACTER SET utf8 COLLATE utf8_bin      NOT NULL,
    `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
    PRIMARY KEY (`uid`, `url`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

/*!40101 SET SQL_MODE = IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS = IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES = IFNULL(@OLD_SQL_NOTES, 1) */;
