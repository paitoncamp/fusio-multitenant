-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.11-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             10.3.0.5771
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table fusio202.fusio_config
CREATE TABLE IF NOT EXISTS `fusio_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL DEFAULT 1,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_676F5DC45E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table fusio202.fusio_config: ~27 rows (approximately)
/*!40000 ALTER TABLE `fusio_config` DISABLE KEYS */;
INSERT INTO `fusio_config` (`id`, `type`, `name`, `description`, `value`) VALUES
	(1, 2, 'app_approval', 'If true the status of a new app is PENDING so that an administrator has to manually activate the app', '0'),
	(2, 3, 'app_consumer', 'The max amount of apps a consumer can register', '16'),
	(3, 1, 'authorization_url', 'Url where the user can authorize for the OAuth2 flow', ''),
	(4, 3, 'consumer_subscription', 'The max amount of subscriptions a consumer can add', '8'),
	(5, 1, 'info_title', 'The title of the application', 'Fusio'),
	(6, 1, 'info_description', 'A short description of the application. CommonMark syntax MAY be used for rich text representation', ''),
	(7, 1, 'info_tos', 'A URL to the Terms of Service for the API. MUST be in the format of a URL', ''),
	(8, 1, 'info_contact_name', 'The identifying name of the contact person/organization', ''),
	(9, 1, 'info_contact_url', 'The URL pointing to the contact information. MUST be in the format of a URL', ''),
	(10, 1, 'info_contact_email', 'The email address of the contact person/organization. MUST be in the format of an email address', ''),
	(11, 1, 'info_license_name', 'The license name used for the API', ''),
	(12, 1, 'info_license_url', 'A URL to the license used for the API. MUST be in the format of a URL', ''),
	(13, 1, 'mail_register_subject', 'Subject of the activation mail', 'Fusio registration'),
	(14, 6, 'mail_register_body', 'Body of the activation mail', 'Hello {name},\nyou have successful registered at Fusio. To activate you account please visit the following url: http://localhost/fusio_2.0.2/public/developer/register/activate/{token} '),
	(15, 1, 'mail_pw_reset_subject', 'Subject of the password reset mail', 'Fusio password reset'),
	(16, 6, 'mail_pw_reset_body', 'Body of the password reset mail', 'Hello {name},\nyou have requested to reset your password. To set a new password please visit the following link: http://localhost/fusio_2.0.2/public/developer/password/confirm/{token}\nPlease ignore this email if you have not requested a password reset. '),
	(17, 1, 'mail_sender', 'Email address which is used in the "From" header', ''),
	(18, 1, 'provider_facebook_secret', 'Facebook app secret', ''),
	(19, 1, 'provider_google_secret', 'Google app secret', ''),
	(20, 1, 'provider_github_secret', 'GitHub app secret', ''),
	(21, 1, 'recaptcha_secret', 'ReCaptcha secret', '6LeQUbYZAAAAAMrSlRoCwVdG4Tt1KTDXvK4C22Wd'),
	(22, 1, 'role_default', 'Default role which a user gets assigned on registration', 'Consumer'),
	(23, 3, 'points_default', 'The default amount of points which a user receives if he registers', '0'),
	(24, 1, 'system_mailer', 'Optional a SMTP connection which is used as mailer', ''),
	(25, 1, 'system_dispatcher', 'Optional a HTTP or message queue connection which is used to dispatch events', ''),
	(26, 3, 'user_pw_length', 'Minimal required password length', '8'),
	(27, 2, 'user_approval', 'Whether the user needs to activate the account through an email', '1'),
	(28, 1, 'tenant_role_default', 'Default role which a tenant gets assigned on registration', 'Tenant-Owner'),
	(29, 1, 'tenant_member_role_default', 'Default role which a tenant member gets assigned on registered', 'Tenant-Member');
/*!40000 ALTER TABLE `fusio_config` ENABLE KEYS */;

-- Dumping structure for table fusio202.fusio_role
CREATE TABLE IF NOT EXISTS `fusio_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8C7AB57D5E237E06` (`name`),
  KEY `IDX_8C7AB57D12469DE2` (`category_id`),
  CONSTRAINT `role_category_id` FOREIGN KEY (`category_id`) REFERENCES `fusio_category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table fusio202.fusio_role: ~5 rows (approximately)
/*!40000 ALTER TABLE `fusio_role` DISABLE KEYS */;
INSERT INTO `fusio_role` (`id`, `category_id`, `status`, `name`) VALUES
	(1, 1, 1, 'Administrator'),
	(2, 1, 1, 'Backend'),
	(3, 1, 1, 'Consumer'),
	(4, 3, 1, 'Tenant-Owner'),
	(5, 3, 1, 'Tenant-Member');
/*!40000 ALTER TABLE `fusio_role` ENABLE KEYS */;

-- Dumping structure for table fusio202.fusio_role_scope
CREATE TABLE IF NOT EXISTS `fusio_role_scope` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `scope_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_9A18228D60322AC682B5931` (`role_id`,`scope_id`),
  KEY `IDX_9A18228682B5931` (`scope_id`),
  KEY `IDX_9A18228D60322AC` (`role_id`),
  CONSTRAINT `role_scope_role_id` FOREIGN KEY (`role_id`) REFERENCES `fusio_role` (`id`),
  CONSTRAINT `role_scope_scope_id` FOREIGN KEY (`scope_id`) REFERENCES `fusio_scope` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table fusio202.fusio_role_scope: ~37 rows (approximately)
/*!40000 ALTER TABLE `fusio_role_scope` DISABLE KEYS */;
INSERT INTO `fusio_role_scope` (`id`, `role_id`, `scope_id`) VALUES
	(2, 1, 1),
	(3, 1, 2),
	(1, 1, 3),
	(5, 2, 1),
	(4, 2, 3),
	(110, 3, 2),
	(109, 3, 3),
	(108, 3, 27),
	(107, 3, 28),
	(106, 3, 29),
	(105, 3, 30),
	(104, 3, 31),
	(103, 3, 32),
	(102, 3, 33),
	(101, 3, 34),
	(89, 4, 2),
	(88, 4, 3),
	(87, 4, 27),
	(86, 4, 28),
	(85, 4, 29),
	(84, 4, 30),
	(83, 4, 31),
	(82, 4, 32),
	(81, 4, 33),
	(80, 4, 34),
	(79, 4, 45),
	(100, 5, 2),
	(99, 5, 3),
	(98, 5, 27),
	(97, 5, 28),
	(96, 5, 29),
	(95, 5, 30),
	(94, 5, 31),
	(93, 5, 32),
	(92, 5, 33),
	(91, 5, 34),
	(90, 5, 44);
/*!40000 ALTER TABLE `fusio_role_scope` ENABLE KEYS */;

-- Dumping structure for table fusio202.fusio_scope
CREATE TABLE IF NOT EXISTS `fusio_scope` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL DEFAULT 1,
  `status` int(11) NOT NULL DEFAULT 1,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_83A7C32B5E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table fusio202.fusio_scope: ~37 rows (approximately)
/*!40000 ALTER TABLE `fusio_scope` DISABLE KEYS */;
INSERT INTO `fusio_scope` (`id`, `category_id`, `status`, `name`, `description`) VALUES
	(1, 2, 1, 'backend', ''),
	(2, 3, 1, 'consumer', ''),
	(3, 5, 1, 'authorization', ''),
	(4, 1, 1, 'default', ''),
	(5, 2, 1, 'backend.account', ''),
	(6, 2, 1, 'backend.action', ''),
	(7, 2, 1, 'backend.app', ''),
	(8, 2, 1, 'backend.audit', ''),
	(9, 2, 1, 'backend.category', ''),
	(10, 2, 1, 'backend.config', ''),
	(11, 2, 1, 'backend.connection', ''),
	(12, 2, 1, 'backend.cronjob', ''),
	(13, 2, 1, 'backend.dashboard', ''),
	(14, 2, 1, 'backend.event', ''),
	(15, 2, 1, 'backend.log', ''),
	(16, 2, 1, 'backend.marketplace', ''),
	(17, 2, 1, 'backend.plan', ''),
	(18, 2, 1, 'backend.rate', ''),
	(19, 2, 1, 'backend.role', ''),
	(20, 2, 1, 'backend.route', ''),
	(21, 2, 1, 'backend.schema', ''),
	(22, 2, 1, 'backend.scope', ''),
	(23, 2, 1, 'backend.sdk', ''),
	(24, 2, 1, 'backend.statistic', ''),
	(25, 2, 1, 'backend.transaction', ''),
	(26, 2, 1, 'backend.user', ''),
	(27, 3, 1, 'consumer.app', ''),
	(28, 3, 1, 'consumer.event', ''),
	(29, 3, 1, 'consumer.grant', ''),
	(30, 3, 1, 'consumer.plan', ''),
	(31, 3, 1, 'consumer.scope', ''),
	(32, 3, 1, 'consumer.subscription', ''),
	(33, 3, 1, 'consumer.transaction', ''),
	(34, 3, 1, 'consumer.user', ''),
	(35, 4, 1, 'system', ''),
	(36, 1, 1, 'todo', ''),
	(37, 1, 1, 'contract', ''),
	(38, 1, 1, 'customer', ''),
	(39, 1, 1, 'product', ''),
	(40, 1, 1, 'page', ''),
	(41, 1, 1, 'post', ''),
	(42, 1, 1, 'comment', ''),
	(43, 1, 1, 'tenancy', ''),
	(44, 1, 1, 'tenant-member', 'Tenant Member scope'),
	(45, 1, 1, 'tenant-owner', 'Tenant Owner Scope');
/*!40000 ALTER TABLE `fusio_scope` ENABLE KEYS */;

-- Dumping structure for table fusio202.fusio_scope_routes
CREATE TABLE IF NOT EXISTS `fusio_scope_routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scope_id` int(11) NOT NULL,
  `route_id` int(11) NOT NULL,
  `allow` smallint(6) NOT NULL,
  `methods` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_ACCB7E1F682B5931` (`scope_id`),
  KEY `IDX_ACCB7E1F34ECB4E6` (`route_id`),
  CONSTRAINT `scope_routes_route_id` FOREIGN KEY (`route_id`) REFERENCES `fusio_routes` (`id`),
  CONSTRAINT `scope_routes_scope_id` FOREIGN KEY (`scope_id`) REFERENCES `fusio_scope` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=143 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table fusio202.fusio_scope_routes: ~116 rows (approximately)
/*!40000 ALTER TABLE `fusio_scope_routes` DISABLE KEYS */;
INSERT INTO `fusio_scope_routes` (`id`, `scope_id`, `route_id`, `allow`, `methods`) VALUES
	(1, 5, 7, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(2, 5, 8, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(3, 6, 9, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(4, 6, 10, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(5, 6, 11, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(6, 6, 12, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(7, 6, 13, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(8, 7, 14, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(9, 7, 15, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(10, 7, 16, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(11, 7, 17, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(12, 7, 18, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(13, 8, 19, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(14, 8, 20, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(15, 9, 21, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(16, 9, 22, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(17, 10, 23, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(18, 10, 24, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(19, 11, 25, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(20, 11, 26, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(21, 11, 27, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(22, 11, 28, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(23, 12, 29, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(24, 12, 30, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(25, 13, 31, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(26, 14, 32, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(27, 14, 33, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(28, 14, 34, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(29, 14, 35, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(30, 15, 36, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(31, 15, 37, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(32, 15, 38, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(33, 15, 39, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(34, 16, 40, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(35, 16, 41, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(36, 17, 42, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(37, 17, 43, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(38, 17, 44, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(39, 17, 45, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(40, 17, 46, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(41, 17, 47, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(42, 18, 48, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(43, 18, 49, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(44, 19, 50, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(45, 19, 51, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(46, 20, 52, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(47, 20, 53, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(48, 20, 54, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(49, 20, 55, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(50, 21, 56, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(51, 21, 57, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(52, 21, 58, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(53, 21, 59, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(54, 22, 60, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(55, 22, 61, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(56, 22, 62, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(57, 23, 63, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(58, 24, 64, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(59, 24, 65, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(60, 24, 66, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(61, 24, 67, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(62, 24, 68, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(63, 24, 69, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(64, 24, 70, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(65, 24, 71, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(66, 24, 72, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(67, 24, 73, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(68, 25, 74, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(69, 25, 75, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(70, 26, 76, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(71, 26, 77, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(72, 27, 78, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(73, 27, 79, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(74, 28, 80, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(75, 29, 81, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(76, 29, 82, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(77, 30, 83, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(78, 30, 84, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(79, 30, 85, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(80, 30, 86, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(81, 30, 87, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(82, 30, 88, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(83, 31, 89, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(84, 32, 90, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(85, 32, 91, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(86, 33, 92, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(87, 33, 93, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(88, 33, 94, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(89, 33, 95, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(90, 34, 96, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(91, 34, 97, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(92, 34, 98, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(93, 34, 99, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(94, 34, 100, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(95, 34, 101, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(96, 34, 102, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(97, 34, 103, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(98, 3, 109, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(99, 3, 110, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(108, 37, 113, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(109, 37, 114, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(110, 38, 115, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(111, 38, 116, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(112, 39, 117, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(113, 39, 118, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(114, 36, 111, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(115, 36, 112, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(125, 40, 119, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(126, 40, 120, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(127, 41, 121, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(128, 41, 122, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(130, 42, 124, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(131, 42, 123, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(132, 43, 125, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(135, 43, 126, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(137, 43, 127, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(139, 43, 128, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(140, 45, 128, 1, 'GET|POST|PUT|PATCH|DELETE'),
	(141, 45, 127, 1, 'DELETE|PATCH|PUT|POST|GET'),
	(142, 45, 126, 1, 'GET|POST|PUT|PATCH|DELETE');
/*!40000 ALTER TABLE `fusio_scope_routes` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
