-- Aurora Restaurant Database Backup
-- Generated: 2026-04-07 16:18:26

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `customer_sessions`;
CREATE TABLE `customer_sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) NOT NULL,
  `table_id` int(10) unsigned NOT NULL,
  `order_id` int(10) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_session_id` (`session_id`),
  KEY `idx_table_active` (`table_id`,`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=309 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `customer_sessions` VALUES ('1', '4h9mq0ckfmghi9ni3e7hkgr27k', '1', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-17 19:07:47', '2026-03-17 19:37:47', '2026-03-17 19:07:47');
INSERT INTO `customer_sessions` VALUES ('2', 'u76o6dpolbpnem292njglu0n31', '2', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-17 19:08:13', '2026-03-17 19:38:13', '2026-03-17 19:08:13');
INSERT INTO `customer_sessions` VALUES ('3', 'dl1u1arrkfp1jt57e611rm14ol', '28', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-17 19:11:24', '2026-03-17 19:41:24', '2026-03-17 19:11:24');
INSERT INTO `customer_sessions` VALUES ('4', 'k1vqer89fd6mcaodui959lc94u', '1', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-17 19:21:08', '2026-03-17 19:51:08', '2026-03-17 19:21:08');
INSERT INTO `customer_sessions` VALUES ('5', 'gqijh3f69va64doa2d5bu8c9tr', '1', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-17 19:25:38', '2026-03-17 19:55:38', '2026-03-17 19:21:56');
INSERT INTO `customer_sessions` VALUES ('6', 'rrvdnl6pgo3b3etu7gpgople8p', '12', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-17 19:27:34', '2026-03-17 19:57:34', '2026-03-17 19:25:52');
INSERT INTO `customer_sessions` VALUES ('7', 'ro6dtb9cs0o2mpqkmtok1onbji', '6', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-17 19:33:50', '2026-03-17 20:03:50', '2026-03-17 19:33:19');
INSERT INTO `customer_sessions` VALUES ('8', 'cu0rl0q4flcp73fpn9gns57njh', '1', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-17 21:44:19', '2026-03-18 21:44:19', '2026-03-17 19:35:08');
INSERT INTO `customer_sessions` VALUES ('9', '8kouqchg3ep9n11mcd39q280hc', '28', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-17 19:48:54', '2026-03-17 20:18:54', '2026-03-17 19:48:54');
INSERT INTO `customer_sessions` VALUES ('10', 'm1ill4h7m9n1m35bstbf69oggi', '28', NULL, '222.253.191.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Mobile Safari/537.36', '1', '2026-03-17 19:49:23', '2026-03-17 20:19:23', '2026-03-17 19:49:23');
INSERT INTO `customer_sessions` VALUES ('11', 'dh3j0vh3dbh148vi2tc7gl1ek8', '1', NULL, '222.253.191.65', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '1', '2026-03-17 21:17:12', '2026-03-18 21:17:12', '2026-03-17 21:17:12');
INSERT INTO `customer_sessions` VALUES ('13', 'r03redi7dachirj4pb0efltlto', '3', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-17 21:17:44', '2026-03-18 21:17:44', '2026-03-17 21:17:44');
INSERT INTO `customer_sessions` VALUES ('17', 'ph7n9bueatdr00mt8tj7n815fm', '1', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-17 21:23:53', '2026-03-18 21:23:53', '2026-03-17 21:23:53');
INSERT INTO `customer_sessions` VALUES ('27', 'ev5d51u1jocn7admdfgmsm4ffg', '1', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-17 21:44:56', '2026-03-18 21:44:56', '2026-03-17 21:44:56');
INSERT INTO `customer_sessions` VALUES ('28', '55onaj18n2qqfl47tnp3usf6gr', '21', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-17 21:46:45', '2026-03-18 21:46:45', '2026-03-17 21:45:09');
INSERT INTO `customer_sessions` VALUES ('31', 'ak6lf5e3u96gei6kdovnlblkhl', '12', NULL, '118.70.230.232', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 09:52:52', '2026-03-19 09:52:52', '2026-03-18 09:50:34');
INSERT INTO `customer_sessions` VALUES ('34', '9g9q3s2vdhjn3jccaniqccjjhp', '1', NULL, '118.70.230.232', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 09:53:02', '2026-03-19 09:53:02', '2026-03-18 09:53:02');
INSERT INTO `customer_sessions` VALUES ('35', 'fb0fflnkclmpqu92dp5hjjdmh9', '4', NULL, '118.70.230.232', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '1', '2026-03-18 10:49:49', '2026-03-19 10:49:49', '2026-03-18 10:49:49');
INSERT INTO `customer_sessions` VALUES ('36', 'cfe628klq7cquesneid5dvekj8', '12', NULL, '118.70.230.232', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '1', '2026-03-18 10:50:33', '2026-03-19 10:50:33', '2026-03-18 10:50:33');
INSERT INTO `customer_sessions` VALUES ('37', '578rmiqo7pv7632iu0i5nmdkb8', '10', NULL, '118.70.230.232', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 11:00:45', '2026-03-19 11:00:45', '2026-03-18 11:00:45');
INSERT INTO `customer_sessions` VALUES ('38', 'cqos6peiqauf32re8m1rhjsioi', '14', NULL, '118.70.230.232', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 11:14:45', '2026-03-19 11:14:45', '2026-03-18 11:14:45');
INSERT INTO `customer_sessions` VALUES ('39', 't9d324s0asl14s5b3efsm1a0nt', '1', NULL, '118.70.230.232', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 11:26:00', '2026-03-19 11:26:00', '2026-03-18 11:17:30');
INSERT INTO `customer_sessions` VALUES ('41', 'fb4ig0cg8j47h7nqr5nrc34tg1', '14', NULL, '118.70.230.232', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 11:17:47', '2026-03-19 11:17:47', '2026-03-18 11:17:47');
INSERT INTO `customer_sessions` VALUES ('44', '61d9e4mmupffqftqh6fck14jge', '14', NULL, '115.74.225.100', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148 Zalo iOS/692 ZaloTheme/dark ZaloLanguage/vn', '1', '2026-03-18 13:09:49', '2026-03-19 13:09:49', '2026-03-18 13:09:49');
INSERT INTO `customer_sessions` VALUES ('45', '4jl7l7hi09a3b8b3ta0aj9gugt', '33', NULL, '115.74.225.100', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', '1', '2026-03-18 14:12:00', '2026-03-19 14:12:00', '2026-03-18 13:11:16');
INSERT INTO `customer_sessions` VALUES ('46', '9ul40bqb6brqr2bvs6p52em405', '1', NULL, '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '1', '2026-03-18 13:13:08', '2026-03-19 13:13:08', '2026-03-18 13:13:08');
INSERT INTO `customer_sessions` VALUES ('48', 's5h7j16tctgiv5458j4c9onm6n', '1', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148 Zalo iOS/698 ZaloTheme/light ZaloLanguage/vn', '1', '2026-03-18 13:59:03', '2026-03-19 13:59:03', '2026-03-18 13:32:13');
INSERT INTO `customer_sessions` VALUES ('49', '8eitmqafgmgrilelcmnjq5t74k', '2', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 13:54:10', '2026-03-19 13:54:10', '2026-03-18 13:54:10');
INSERT INTO `customer_sessions` VALUES ('50', '5qokp2gneupoevjmibfr6ntgc5', '1', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 13:55:42', '2026-03-19 13:55:42', '2026-03-18 13:55:42');
INSERT INTO `customer_sessions` VALUES ('53', '5r9famrf8rmimr8oaeuk90rrut', '14', NULL, '49.213.78.6', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '1', '2026-03-18 15:18:58', '2026-03-19 15:18:58', '2026-03-18 15:18:58');
INSERT INTO `customer_sessions` VALUES ('54', '60kn782rsalmm2uan9u69s6576', '33', NULL, '49.213.78.15', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '1', '2026-03-18 15:27:59', '2026-03-19 15:27:59', '2026-03-18 15:27:59');
INSERT INTO `customer_sessions` VALUES ('55', 'qo88hpqu1jn6ukh4idn22rnm5k', '1', NULL, '49.213.78.7', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '1', '2026-03-18 15:40:34', '2026-03-19 15:40:34', '2026-03-18 15:40:34');
INSERT INTO `customer_sessions` VALUES ('56', 'lhc4hnlhtj4njo8rajkfob13fe', '23', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 18:40:20', '2026-03-19 18:40:20', '2026-03-18 18:37:27');
INSERT INTO `customer_sessions` VALUES ('58', 'pm74d70a77lf10ftsvvaclcbj6', '1', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 20:32:45', '2026-03-19 20:32:45', '2026-03-18 20:28:18');
INSERT INTO `customer_sessions` VALUES ('60', '8463ecsgsn6jfumcfcvgrgg5r1', '1', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 20:47:18', '2026-03-19 20:47:18', '2026-03-18 20:40:32');
INSERT INTO `customer_sessions` VALUES ('63', 'uk02qvmp29eiou6ptu4q11tetp', '33', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 21:09:15', '2026-03-19 21:09:15', '2026-03-18 20:47:29');
INSERT INTO `customer_sessions` VALUES ('82', 'i3tmp76phhl363evit4sb3k7rf', '12', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 21:00:18', '2026-03-19 21:00:18', '2026-03-18 21:00:18');
INSERT INTO `customer_sessions` VALUES ('85', 'g5l72960c34cam1aprrlfumb8c', '33', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 21:07:31', '2026-03-19 21:07:31', '2026-03-18 21:07:31');
INSERT INTO `customer_sessions` VALUES ('90', 'q5d1glnhf1ieloqspmbq3rvrbu', '33', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 21:09:24', '2026-03-19 21:09:24', '2026-03-18 21:09:22');
INSERT INTO `customer_sessions` VALUES ('92', '8071c1c1ggj2eo34kfdsn99peu', '33', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 21:11:03', '2026-03-19 21:11:03', '2026-03-18 21:09:54');
INSERT INTO `customer_sessions` VALUES ('98', 'v695fdfttrp01mrj72juj609l6', '33', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 21:11:11', '2026-03-19 21:11:11', '2026-03-18 21:11:05');
INSERT INTO `customer_sessions` VALUES ('100', 'a4fkvidq7b3sdj8l0uohjsg9lh', '33', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 21:12:27', '2026-03-19 21:12:27', '2026-03-18 21:11:14');
INSERT INTO `customer_sessions` VALUES ('102', '0ubkdheauhsfmodstq0sljjrkl', '33', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 21:12:29', '2026-03-19 21:12:29', '2026-03-18 21:12:29');
INSERT INTO `customer_sessions` VALUES ('103', '7f8e842m28jru7n41hc6fqieef', '33', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 21:12:35', '2026-03-19 21:12:35', '2026-03-18 21:12:34');
INSERT INTO `customer_sessions` VALUES ('105', '5c0q088246gam1ur5t3iotrffc', '33', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 21:12:38', '2026-03-19 21:12:38', '2026-03-18 21:12:38');
INSERT INTO `customer_sessions` VALUES ('106', '6d4ot8kimtn18hh6jrq890fl1d', '33', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 21:15:31', '2026-03-19 21:15:31', '2026-03-18 21:12:49');
INSERT INTO `customer_sessions` VALUES ('109', 'st74j5ugj7pu5tlnmcnd8dap3g', '33', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 21:49:33', '2026-03-19 21:49:33', '2026-03-18 21:15:33');
INSERT INTO `customer_sessions` VALUES ('110', 'n719c05hheb4dmar281guesf5h', '32', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 21:16:02', '2026-03-19 21:16:02', '2026-03-18 21:16:02');
INSERT INTO `customer_sessions` VALUES ('111', 'rto0llb2l4g75oe8pcfe2f94p2', '32', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 21:16:39', '2026-03-19 21:16:39', '2026-03-18 21:16:39');
INSERT INTO `customer_sessions` VALUES ('113', 'k0cv16jtatu9qlqcr98m1cvshq', '32', NULL, '15.235.162.58', 'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php) _zbot', '1', '2026-03-18 21:26:00', '2026-03-19 21:26:00', '2026-03-18 21:26:00');
INSERT INTO `customer_sessions` VALUES ('114', '461cur6s6f5v7835g5jbi0nmvt', '23', NULL, '113.22.31.64', 'Mozilla/5.0 (Linux; Android 16; AMM-AN00 Build/HONORAMM-AN00;) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/145.0.7632.159 Mobile Safari/537.36 Zalo android/26021884 ZaloTheme/light ZaloLanguage/vi', '1', '2026-03-18 21:31:34', '2026-03-19 21:31:34', '2026-03-18 21:26:02');
INSERT INTO `customer_sessions` VALUES ('115', '22ctu30v3bhvthfmf0nqsv0gc8', '32', NULL, '113.22.31.64', 'WhatsApp/2', '1', '2026-03-18 21:26:12', '2026-03-19 21:26:12', '2026-03-18 21:26:12');
INSERT INTO `customer_sessions` VALUES ('116', 'f94ntj9v5qcqv3ajotidq4vo50', '32', NULL, '113.22.31.64', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '1', '2026-03-18 21:26:17', '2026-03-19 21:26:17', '2026-03-18 21:26:17');
INSERT INTO `customer_sessions` VALUES ('117', 'igc9tcuihie2r3dlc8o5nsrk8e', '32', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 21:28:50', '2026-03-19 21:28:50', '2026-03-18 21:28:50');
INSERT INTO `customer_sessions` VALUES ('119', 'uuii3gn25f8m4fo7ph2kcr7e1j', '32', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 21:29:02', '2026-03-19 21:29:02', '2026-03-18 21:29:02');
INSERT INTO `customer_sessions` VALUES ('120', 'jikrmig2rmdo8bkn25hnidh84n', '28', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 21:29:26', '2026-03-19 21:29:26', '2026-03-18 21:29:26');
INSERT INTO `customer_sessions` VALUES ('121', 'apefc3hgug46d73lmg3m962bnn', '31', NULL, '222.253.191.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '1', '2026-03-18 21:41:17', '2026-03-19 21:41:17', '2026-03-18 21:29:40');
INSERT INTO `customer_sessions` VALUES ('122', '5ivt3ple7c9csmc1ul7u5gtfn0', '23', NULL, '15.235.162.55', 'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php) _zbot', '1', '2026-03-18 21:31:32', '2026-03-19 21:31:32', '2026-03-18 21:31:32');
INSERT INTO `customer_sessions` VALUES ('124', '5c0vm0987j1nimgkd64p7ult2d', '33', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 21:35:22', '2026-03-19 21:35:22', '2026-03-18 21:34:33');
INSERT INTO `customer_sessions` VALUES ('127', 'sau2oo343dvmcpm9tokd3j37nl', '33', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 21:35:29', '2026-03-19 21:35:29', '2026-03-18 21:35:29');
INSERT INTO `customer_sessions` VALUES ('129', 'o6146sno1f1m2a7flnin5i2dek', '33', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 21:35:56', '2026-03-19 21:35:56', '2026-03-18 21:35:56');
INSERT INTO `customer_sessions` VALUES ('130', 'c062m1222c41g3cj4rr3uajhrq', '32', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 21:36:11', '2026-03-19 21:36:11', '2026-03-18 21:36:11');
INSERT INTO `customer_sessions` VALUES ('131', 'jmlredfqm7oh2t4v2990e8snm1', '31', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 21:37:18', '2026-03-19 21:37:18', '2026-03-18 21:37:18');
INSERT INTO `customer_sessions` VALUES ('133', 'v3ddn1927fn0u5v9fv2eh1abub', '31', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 21:38:24', '2026-03-19 21:38:24', '2026-03-18 21:38:24');
INSERT INTO `customer_sessions` VALUES ('134', 'jodv8plit6fgapq9s8t4ugpdrh', '31', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-18 21:39:29', '2026-03-19 21:39:29', '2026-03-18 21:39:29');
INSERT INTO `customer_sessions` VALUES ('139', 'lig7d9vr7cls9cpo3s7h2j5oju', '33', NULL, '42.116.204.229', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-19 12:23:37', '2026-03-20 12:23:37', '2026-03-19 07:28:55');
INSERT INTO `customer_sessions` VALUES ('140', 'qt7563085l4e0kcbqgjc2dinmu', '13', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148', '1', '2026-03-19 08:59:30', '2026-03-20 08:59:30', '2026-03-19 08:59:30');
INSERT INTO `customer_sessions` VALUES ('142', 's9tpdf1elnqdsseuk3n9ojimrg', '33', NULL, '42.116.204.229', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', '1', '2026-03-19 16:12:21', '2026-03-20 16:12:21', '2026-03-19 16:12:21');
INSERT INTO `customer_sessions` VALUES ('143', 'blvehfh78p1i0qdnukkaa9ls8e', '1', NULL, '118.70.230.232', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-19 16:14:42', '2026-03-20 16:14:42', '2026-03-19 16:14:42');
INSERT INTO `customer_sessions` VALUES ('144', 'nbf10strjn68rdvlrhu4hhmmsm', '33', NULL, '118.70.230.232', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-19 16:16:23', '2026-03-20 16:16:23', '2026-03-19 16:16:23');
INSERT INTO `customer_sessions` VALUES ('145', 'pptiaftue5ta9c3iinbka0aueo', '34', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-21 18:21:06', '2026-03-22 18:21:06', '2026-03-21 18:21:06');
INSERT INTO `customer_sessions` VALUES ('146', 'k4e0a17o472ifa0east921t8ll', '34', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-22 14:23:53', '2026-03-23 14:23:53', '2026-03-22 14:22:53');
INSERT INTO `customer_sessions` VALUES ('150', 'tvsb5q1a2derk6chvb5smdr048', '1', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-22 14:50:09', '2026-03-23 14:50:09', '2026-03-22 14:49:21');
INSERT INTO `customer_sessions` VALUES ('153', '94gi7n6a761crfotkc9rgj9r34', '1', NULL, '222.253.191.65', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '1', '2026-03-22 17:40:47', '2026-03-23 17:40:47', '2026-03-22 17:40:47');
INSERT INTO `customer_sessions` VALUES ('154', 'b8bc1m0ba65bgorompneicl2uk', '18', NULL, '58.187.186.14', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148', '1', '2026-03-23 08:11:18', '2026-03-24 08:11:18', '2026-03-23 08:11:18');
INSERT INTO `customer_sessions` VALUES ('155', '8uarer61g709s08iadt3n55dgc', '1', NULL, '103.249.23.60', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-23 08:17:45', '2026-03-24 08:17:45', '2026-03-23 08:17:45');
INSERT INTO `customer_sessions` VALUES ('156', '1kru07n9ogpd5jmuq38nlaai06', '1', NULL, '103.249.23.60', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_1) AppleWebKit/601.2.4 (KHTML, like Gecko) Version/9.0.1 Safari/601.2.4 facebookexternalhit/1.1 Facebot Twitterbot/1.0', '1', '2026-03-23 08:17:48', '2026-03-24 08:17:48', '2026-03-23 08:17:48');
INSERT INTO `customer_sessions` VALUES ('157', 'nm29c08dt66sdbtktetr9crjpl', '1', NULL, '222.253.191.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '1', '2026-03-23 08:51:31', '2026-03-24 08:51:31', '2026-03-23 08:51:31');
INSERT INTO `customer_sessions` VALUES ('158', 'kdm17orm8ml9ppdfo0mp4ht7tr', '1', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-23 08:51:57', '2026-03-24 08:51:57', '2026-03-23 08:51:57');
INSERT INTO `customer_sessions` VALUES ('159', 'ni3ln4beuaqbmkojfacsae2q9o', '1', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-23 08:53:55', '2026-03-24 08:53:55', '2026-03-23 08:53:55');
INSERT INTO `customer_sessions` VALUES ('160', 'i886a3d4h1kt29pcs3kpp54gsq', '1', NULL, '104.28.166.50', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-23 08:57:59', '2026-03-24 08:57:59', '2026-03-23 08:57:59');
INSERT INTO `customer_sessions` VALUES ('161', '22503c4pa15s1bm3kifmqqk109', '2', NULL, '104.28.166.50', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-23 08:58:12', '2026-03-24 08:58:12', '2026-03-23 08:58:12');
INSERT INTO `customer_sessions` VALUES ('162', 't9uq6u8ckmhkbt43ci15u13n01', '2', NULL, '104.28.157.200', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-23 09:01:50', '2026-03-24 09:01:50', '2026-03-23 09:01:50');
INSERT INTO `customer_sessions` VALUES ('163', 'sq6oupnkuc4qdniun8ug7ipm4a', '1', NULL, '104.28.157.200', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-23 09:02:04', '2026-03-24 09:02:04', '2026-03-23 09:02:04');
INSERT INTO `customer_sessions` VALUES ('164', 'qrhrld3uf57kr9pphk1qqf27i3', '3', NULL, '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '1', '2026-03-23 09:12:02', '2026-03-24 09:12:02', '2026-03-23 09:04:25');
INSERT INTO `customer_sessions` VALUES ('178', '0pvddaq7ek0ao10f2iub282mfe', '3', NULL, '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '1', '2026-03-23 09:15:33', '2026-03-24 09:15:33', '2026-03-23 09:12:15');
INSERT INTO `customer_sessions` VALUES ('185', '8tsqvuge9rmqes3pl8h4pp9kpi', '4', NULL, '104.28.163.50', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-23 09:16:36', '2026-03-24 09:16:36', '2026-03-23 09:16:05');
INSERT INTO `customer_sessions` VALUES ('187', '1i58o222kt5k6uh4912erjrdvv', '4', NULL, '104.28.163.50', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-23 09:16:48', '2026-03-24 09:16:48', '2026-03-23 09:16:48');
INSERT INTO `customer_sessions` VALUES ('188', 'lcn8rnakj4jblmq9j985i18c5a', '6', NULL, '104.28.163.50', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-23 09:20:10', '2026-03-24 09:20:10', '2026-03-23 09:19:41');
INSERT INTO `customer_sessions` VALUES ('190', '591go3rl0llqpk3o47ca6c6hao', '6', NULL, '104.28.163.50', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-23 09:20:18', '2026-03-24 09:20:18', '2026-03-23 09:20:18');
INSERT INTO `customer_sessions` VALUES ('191', 'mhjmkd3l8annl0nhithpps89p5', '6', NULL, '104.28.163.50', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-23 09:20:49', '2026-03-24 09:20:49', '2026-03-23 09:20:44');
INSERT INTO `customer_sessions` VALUES ('193', 'ibr5gvncf8fjf84fda864n62e6', '6', NULL, '104.28.163.50', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-23 09:21:22', '2026-03-24 09:21:22', '2026-03-23 09:21:05');
INSERT INTO `customer_sessions` VALUES ('195', 'rv0pqij33f6fi6k8l755phh52l', '6', NULL, '104.28.163.50', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-23 09:21:29', '2026-03-24 09:21:29', '2026-03-23 09:21:29');
INSERT INTO `customer_sessions` VALUES ('196', '1cf08836638m6qqq44q715qpvk', '5', NULL, '104.28.158.176', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-23 09:24:49', '2026-03-24 09:24:49', '2026-03-23 09:23:57');
INSERT INTO `customer_sessions` VALUES ('200', 'hbn19mpa5ng3vhh3kpnmu8qs18', '5', NULL, '104.28.158.176', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-23 09:24:55', '2026-03-24 09:24:55', '2026-03-23 09:24:55');
INSERT INTO `customer_sessions` VALUES ('201', '0dkq6nm62stkoi58u2748pfk0f', '4', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-23 09:26:19', '2026-03-24 09:26:19', '2026-03-23 09:25:49');
INSERT INTO `customer_sessions` VALUES ('206', 'tftqct3i2645urojju1bq5qo56', '4', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-23 09:26:53', '2026-03-24 09:26:53', '2026-03-23 09:26:53');
INSERT INTO `customer_sessions` VALUES ('207', 'hrj26u4o2kt03laqj4katvao7i', '1', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-23 09:31:34', '2026-03-24 09:31:34', '2026-03-23 09:31:34');
INSERT INTO `customer_sessions` VALUES ('208', 'u58tm0kjruj8t8s5dsmshn9785', '3', NULL, '104.28.163.97', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4 Mobile/15E148 Safari/604.1', '1', '2026-03-23 15:15:10', '2026-03-24 15:15:10', '2026-03-23 11:51:32');
INSERT INTO `customer_sessions` VALUES ('210', 'cvl7ib6llkg4honad2jaloe6ir', '3', NULL, '115.74.225.100', 'WhatsApp/2', '1', '2026-03-23 13:22:07', '2026-03-24 13:22:07', '2026-03-23 13:22:07');
INSERT INTO `customer_sessions` VALUES ('211', '6ucu82i8admnvfgnl5aul2nv35', '3', NULL, '49.213.78.6', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '1', '2026-03-23 15:00:40', '2026-03-24 15:00:40', '2026-03-23 15:00:40');
INSERT INTO `customer_sessions` VALUES ('213', '6dr2q9gt3hi485osimu2s8cv55', '3', NULL, '104.28.156.150', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4 Mobile/15E148 Safari/604.1', '1', '2026-03-24 04:05:20', '2026-03-25 04:05:20', '2026-03-24 04:05:20');
INSERT INTO `customer_sessions` VALUES ('214', 'knttbn4al523fgcgrj5op7ojs2', '33', NULL, '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '1', '2026-03-25 10:52:03', '2026-03-26 10:52:03', '2026-03-25 10:52:03');
INSERT INTO `customer_sessions` VALUES ('215', 'nhdl62277348pv04t45m0ln9mp', '19', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', '1', '2026-03-25 10:53:52', '2026-03-26 10:53:52', '2026-03-25 10:52:50');
INSERT INTO `customer_sessions` VALUES ('217', 'd0962r285pgn75sfedipsn1p45', '1', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-25 16:06:58', '2026-03-26 16:06:58', '2026-03-25 16:06:18');
INSERT INTO `customer_sessions` VALUES ('219', 'dcgj3bk549dq963q8k8o0617ng', '1', NULL, '125.235.188.211', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-26 10:15:15', '2026-03-27 10:15:15', '2026-03-26 10:15:15');
INSERT INTO `customer_sessions` VALUES ('220', 'psl6er9nfkrj5qq8ntsd8j4ejf', '34', NULL, '125.235.188.211', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-26 10:17:59', '2026-03-27 10:17:59', '2026-03-26 10:17:25');
INSERT INTO `customer_sessions` VALUES ('222', 'pgm9j1fqvimati2hcq67c8gsb7', '3', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-26 11:59:41', '2026-03-27 11:59:41', '2026-03-26 11:55:40');
INSERT INTO `customer_sessions` VALUES ('224', 'o2lpt30ku40blukpe494660pkm', '2', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-26 11:59:53', '2026-03-27 11:59:53', '2026-03-26 11:59:53');
INSERT INTO `customer_sessions` VALUES ('225', '239hmk4p6n9r8iat5hf992c08h', '3', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-26 12:05:58', '2026-03-27 12:05:58', '2026-03-26 12:05:58');
INSERT INTO `customer_sessions` VALUES ('226', 'v25dbr5g8mdv5vesspegdcqbrt', '4', NULL, '222.253.191.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-03-26 12:06:28', '2026-03-27 12:06:28', '2026-03-26 12:06:17');
INSERT INTO `customer_sessions` VALUES ('228', '0n6db5nub9v02s52ap6bg7anan', '1', NULL, '113.170.69.214', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '1', '2026-03-30 16:57:59', '2026-03-31 16:57:59', '2026-03-30 16:57:19');
INSERT INTO `customer_sessions` VALUES ('230', 'th36anls77180dcni8ciab65si', '1', NULL, '113.170.69.214', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '1', '2026-03-30 17:17:01', '2026-03-31 17:17:01', '2026-03-30 17:17:01');
INSERT INTO `customer_sessions` VALUES ('231', 'p8rvltqv37ij3f6krj7n1i37v9', '2', NULL, '113.170.69.214', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '1', '2026-03-30 17:26:43', '2026-03-31 17:26:43', '2026-03-30 17:21:58');
INSERT INTO `customer_sessions` VALUES ('233', '1dhrbqt7ubpn9gdjmuapu6vldm', '1', NULL, '113.170.69.214', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '1', '2026-03-30 18:19:06', '2026-03-31 18:19:06', '2026-03-30 18:09:47');
INSERT INTO `customer_sessions` VALUES ('238', 'ucvdm93rv24mnfs7me6dllqm8r', '15', NULL, '113.170.69.214', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '1', '2026-03-30 19:32:02', '2026-03-31 19:32:02', '2026-03-30 18:57:11');
INSERT INTO `customer_sessions` VALUES ('241', '0nndmj2il1b7ic0b2hacsfthae', '19', NULL, '113.170.69.214', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '1', '2026-03-30 20:29:38', '2026-03-31 20:29:38', '2026-03-30 19:34:10');
INSERT INTO `customer_sessions` VALUES ('254', '3h9h1qumnde3nrqt6sm2aqlmh0', '3', NULL, '113.170.69.214', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '1', '2026-03-30 20:44:42', '2026-03-31 20:44:42', '2026-03-30 20:42:37');
INSERT INTO `customer_sessions` VALUES ('262', 'f3tiks2su7nl7c7p08rsh6pof0', '7', NULL, '113.170.69.214', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '1', '2026-03-30 21:24:41', '2026-03-31 21:24:41', '2026-03-30 20:50:09');
INSERT INTO `customer_sessions` VALUES ('265', '1d47jdrpht5ichfhihup6a6cun', '9', NULL, '113.170.69.214', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '1', '2026-03-30 21:29:53', '2026-03-31 21:29:53', '2026-03-30 21:16:51');
INSERT INTO `customer_sessions` VALUES ('270', '7t1ufl33qouo68c72n4jv3jh6d', '3', NULL, '113.170.69.214', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '1', '2026-03-30 21:34:26', '2026-03-31 21:34:26', '2026-03-30 21:26:26');
INSERT INTO `customer_sessions` VALUES ('271', 'ek8o8a1pso9cng6u285p2440lj', '3', NULL, '113.170.69.214', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '1', '2026-03-30 21:27:40', '2026-03-31 21:27:40', '2026-03-30 21:27:40');
INSERT INTO `customer_sessions` VALUES ('274', 'og29kc9sgiucvhma8hgf2beubb', '3', NULL, '113.170.69.214', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '1', '2026-03-30 21:39:55', '2026-03-31 21:39:55', '2026-03-30 21:39:55');
INSERT INTO `customer_sessions` VALUES ('275', 'lcm3q1difvo62hnji73ps39o8p', '4', NULL, '113.170.69.214', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '1', '2026-03-30 21:47:35', '2026-03-31 21:47:35', '2026-03-30 21:40:04');
INSERT INTO `customer_sessions` VALUES ('280', '38rn71eunmof59d44kf8ejacbr', '10', NULL, '113.170.69.214', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '1', '2026-03-30 21:55:39', '2026-03-31 21:55:39', '2026-03-30 21:47:59');
INSERT INTO `customer_sessions` VALUES ('285', 'h06l9ddhd4fcviclcomel9c8nm', '20', NULL, '113.170.69.214', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '1', '2026-03-30 22:01:20', '2026-03-31 22:01:20', '2026-03-30 22:00:42');
INSERT INTO `customer_sessions` VALUES ('287', 'tqo2ps0k8fdrg7nak5078p7bhq', '2', NULL, '113.170.69.214', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '1', '2026-03-31 13:32:46', '2026-04-01 13:32:46', '2026-03-31 13:31:48');
INSERT INTO `customer_sessions` VALUES ('289', '0o0111pvp6psa0uc7rutlo2n2q', '4', NULL, '113.170.69.214', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '1', '2026-03-31 13:55:50', '2026-04-01 13:55:50', '2026-03-31 13:55:26');
INSERT INTO `customer_sessions` VALUES ('291', 'rv9ge8s8hlr4ff2rj98pht8bt1', '5', NULL, '113.170.69.214', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '1', '2026-03-31 14:01:47', '2026-04-01 14:01:47', '2026-03-31 14:01:27');
INSERT INTO `customer_sessions` VALUES ('293', 'kpfbkf89q24cl3h0fobdh27ir5', '2', NULL, '113.170.69.214', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '1', '2026-03-31 14:12:25', '2026-04-01 14:12:25', '2026-03-31 14:02:16');
INSERT INTO `customer_sessions` VALUES ('303', 'q5e08ksvddah3kk8932nbe6l7m', '3', NULL, '113.170.69.214', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '1', '2026-03-31 14:47:21', '2026-04-01 14:47:21', '2026-03-31 14:40:13');
INSERT INTO `customer_sessions` VALUES ('305', '88gqksjmuir75p44obcav50vus', '1', NULL, '103.249.23.54', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '1', '2026-04-01 19:15:30', '2026-04-02 19:15:30', '2026-04-01 14:15:10');
INSERT INTO `customer_sessions` VALUES ('307', '6ks70tar3o69b349t33tlehqp9', '3', NULL, '113.22.145.255', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '1', '2026-04-07 09:10:12', '2026-04-08 09:10:12', '2026-04-07 09:10:12');
INSERT INTO `customer_sessions` VALUES ('308', '9qd1if2e7t0irrcavgc3a209or', '34', NULL, '118.69.64.122', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '1', '2026-04-07 16:17:52', '2026-04-08 16:17:52', '2026-04-07 16:17:52');

DROP TABLE IF EXISTS `location_limits`;
CREATE TABLE `location_limits` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT 'Giới hạn QR Restaurant',
  `center_lat` decimal(10,8) NOT NULL COMMENT 'Vĩ độ trung tâm (Aurora Hotel)',
  `center_lng` decimal(11,8) NOT NULL COMMENT 'Kinh độ trung tâm',
  `radius_meters` int(10) unsigned NOT NULL DEFAULT 500 COMMENT 'Bán kính giới hạn (m)',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Bật/tắt giới hạn vị trí',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `location_limits` VALUES ('1', 'Giới hạn QR Restaurant', '10.95770000', '106.84480000', '500', '1', '2026-03-08 16:36:35', '2026-03-08 16:36:35');
INSERT INTO `location_limits` VALUES ('2', 'Giới hạn QR Restaurant', '10.95770000', '106.84480000', '500', '1', '2026-03-08 16:46:06', '2026-03-08 16:46:06');

DROP TABLE IF EXISTS `menu_categories`;
CREATE TABLE `menu_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT 'Tên danh mục: Khai vị, Chính, Tráng miệng...',
  `name_en` varchar(100) DEFAULT NULL COMMENT 'Tên tiếng Anh (tuỳ chọn)',
  `menu_type` enum('asia','europe','alacarte','set','other') DEFAULT 'asia',
  `icon` varchar(50) DEFAULT 'fa-utensils' COMMENT 'Font Awesome icon class',
  `sort_order` smallint(5) unsigned DEFAULT 0 COMMENT 'Thứ tự hiển thị',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `menu_categories` VALUES ('1', 'Khai Vị', 'Appetizers', 'asia', 'fa-leaf', '1', '1', '2026-03-07 18:08:27', '2026-03-07 18:08:27');
INSERT INTO `menu_categories` VALUES ('6', 'Gỏi - Nộm', 'Salads', 'alacarte', 'fa-leaf', '1', '1', '2026-03-07 18:45:33', '2026-03-17 08:55:26');
INSERT INTO `menu_categories` VALUES ('11', 'Hải Sản', 'Seafood', 'asia', 'fa-fish', '6', '1', '2026-03-07 18:45:33', '2026-03-07 18:45:33');
INSERT INTO `menu_categories` VALUES ('17', 'Cá & Hải Sản Âu', 'Fish & Seafood', 'europe', 'fa-fish', '6', '1', '2026-03-07 18:45:33', '2026-03-17 08:55:26');
INSERT INTO `menu_categories` VALUES ('20', 'Set 4 Người', 'Set for 4', 'alacarte', 'fa-users', '2', '1', '2026-03-07 18:45:33', '2026-03-07 18:45:33');

DROP TABLE IF EXISTS `menu_items`;
CREATE TABLE `menu_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL,
  `name` varchar(150) NOT NULL COMMENT 'Tên món',
  `name_en` varchar(150) DEFAULT NULL COMMENT 'Tên tiếng Anh (tuỳ chọn)',
  `description` text DEFAULT NULL COMMENT 'Mô tả món',
  `price` decimal(10,0) NOT NULL DEFAULT 0 COMMENT 'Giá (VND)',
  `image` varchar(255) DEFAULT NULL COMMENT 'Đường dẫn ảnh món',
  `is_available` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=còn hàng, 0=hết hàng',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=hiển thị, 0=ẩn',
  `service_type` enum('restaurant','room_service','both') NOT NULL DEFAULT 'both',
  `tags` set('bestseller','new','spicy','vegetarian','recommended') DEFAULT NULL,
  `note_options` text DEFAULT NULL,
  `note_options_en` text DEFAULT NULL,
  `sort_order` smallint(5) unsigned DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_items_category` (`category_id`),
  KEY `idx_items_available` (`is_available`,`is_active`),
  CONSTRAINT `fk_items_category` FOREIGN KEY (`category_id`) REFERENCES `menu_categories` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=155 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `menu_items` VALUES ('2', '1', 'Chả giò rế', 'Crispy Rolls', NULL, '75000', NULL, '0', '0', 'both', NULL, NULL, NULL, '2', '2026-03-07 18:08:27', '2026-04-01 13:53:41');
INSERT INTO `menu_items` VALUES ('55', '1', 'Gỏi cuốn tôm thịt', 'Fresh Spring Rolls', 'Cuốn tươi với tôm, thịt, rau sống, ăn kèm nước chấm', '85000', NULL, '0', '1', 'both', 'bestseller', 'Không rau thơm, Ít  cay, không tỏi', 'No herbs, mildly spicy, no garlic', '1', '2026-03-07 18:46:29', '2026-04-01 14:24:30');
INSERT INTO `menu_items` VALUES ('62', '6', 'Gỏi đu đủ bò khô', 'Papaya Salad with Dried Beef', 'Đu đủ giòn, bò khô, đậu phộng', '95000', NULL, '0', '0', 'both', NULL, NULL, NULL, '2', '2026-03-07 18:46:29', '2026-04-01 13:53:47');
INSERT INTO `menu_items` VALUES ('88', '11', 'Mực hấp gừng sả', 'Steamed Squid with Ginger', 'Mực tươi hấp gừng sả', '185000', NULL, '0', '0', 'both', NULL, NULL, NULL, '3', '2026-03-07 18:46:29', '2026-04-01 14:04:30');
INSERT INTO `menu_items` VALUES ('89', '11', 'Sò huyết nướng mỡ hành', 'Grilled Clams with Scallion', 'Sò huyết tươi nướng mỡ hành', '165000', NULL, '1', '1', 'both', NULL, NULL, NULL, '4', '2026-03-07 18:46:29', '2026-03-07 18:46:29');
INSERT INTO `menu_items` VALUES ('90', '11', 'Cua rang me', 'Tamarind Crab', 'Cua biển rang me', '450000', NULL, '1', '1', 'both', NULL, NULL, NULL, '5', '2026-03-07 18:46:29', '2026-03-07 18:46:29');
INSERT INTO `menu_items` VALUES ('149', '17', 'Tôm Hùm Nướng Bơ Tỏi', 'Grilled Lobster', 'Tôm hùm Canada nướng bơ tỏi', '850000', NULL, '1', '1', 'both', NULL, NULL, NULL, '3', '2026-03-07 18:47:06', '2026-03-07 18:47:06');
INSERT INTO `menu_items` VALUES ('150', '17', 'Nghêu Hấp Rượu Vang', 'Steamed Clams in Wine', 'Nghêu tươi hấp rượu vang trắng, tỏi', '185000', NULL, '1', '1', 'both', NULL, NULL, NULL, '4', '2026-03-07 18:47:06', '2026-03-07 18:47:06');

DROP TABLE IF EXISTS `menu_set_items`;
CREATE TABLE `menu_set_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `set_id` int(10) unsigned NOT NULL,
  `menu_item_id` int(10) unsigned NOT NULL,
  `quantity` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `is_required` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=bắt buộc, 0=tuỳ chọn',
  `sort_order` smallint(5) unsigned DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_set_items_set` (`set_id`),
  KEY `fk_set_items_menu` (`menu_item_id`),
  CONSTRAINT `fk_set_items_menu` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_set_items_set` FOREIGN KEY (`set_id`) REFERENCES `menu_sets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `menu_sets`;
CREATE TABLE `menu_sets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL COMMENT 'Tên set',
  `name_en` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,0) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` smallint(5) unsigned DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `menu_sets` VALUES ('15', 'Set Gia Đình 6 Người', 'Family Set for 6', 'Gỏi + 3 Món chính + Lẩu + Cơm + Tráng miệng + Nước', '1850000', NULL, '1', '9', '2026-03-07 18:47:15', '2026-03-07 18:47:15');
INSERT INTO `menu_sets` VALUES ('16', 'Set BBQ 2 Người', 'BBQ Set for 2', 'Ba chỉ bò + Sườn + Gà + Rau + Sốt + Cơm', '550000', NULL, '1', '10', '2026-03-07 18:47:15', '2026-03-07 18:47:15');

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `table_id` int(11) unsigned DEFAULT NULL COMMENT 'Bàn vật lý mà món này thuộc về (cho merged tables)',
  `menu_item_id` int(10) unsigned NOT NULL,
  `item_name` varchar(150) NOT NULL COMMENT 'Snapshot tên món tại thời điểm ghi',
  `item_price` decimal(10,0) NOT NULL COMMENT 'Snapshot giá tại thời điểm ghi',
  `quantity` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `note` varchar(255) DEFAULT NULL COMMENT 'Ghi chú: không hành, ít cay...',
  `split_from_item_id` int(11) unsigned DEFAULT NULL COMMENT 'ID của món gốc mà món này được tách từ đó',
  `is_split_item` tinyint(1) unsigned DEFAULT 0 COMMENT '1 = món này đã được tách từ bàn khác',
  `status` enum('draft','confirmed','cancelled') DEFAULT 'draft',
  `customer_id` varchar(64) DEFAULT NULL COMMENT 'Session ID của khách hàng (cho customer ordering)',
  `submitted_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian khách gửi món (chuyển từ draft sang pending)',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_order_items_order` (`order_id`),
  KEY `fk_order_items_menu` (`menu_item_id`),
  KEY `idx_order_items_table` (`table_id`),
  KEY `idx_split_tracking` (`is_split_item`,`split_from_item_id`),
  KEY `idx_table_status` (`table_id`,`status`),
  KEY `idx_customer_id` (`customer_id`),
  KEY `idx_submitted_at` (`submitted_at`),
  CONSTRAINT `fk_order_items_menu` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_order_items_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=287 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `order_items` VALUES ('251', '157', '1', '55', 'Gỏi cuốn tôm thịt', '85000', '2', '', NULL, '0', 'draft', NULL, NULL, '2026-03-30 19:52:04', '2026-03-30 19:52:04');
INSERT INTO `order_items` VALUES ('254', '157', '1', '55', 'Gỏi cuốn tôm thịt', '85000', '1', 'Không rau thơm / No herbs, Ít  cay / mildly spicy', NULL, '0', 'draft', NULL, NULL, '2026-03-30 21:20:04', '2026-03-30 21:20:04');
INSERT INTO `order_items` VALUES ('260', '157', '1', '55', 'Gỏi cuốn tôm thịt', '85000', '1', 'Không ớt', NULL, '0', 'draft', NULL, NULL, '2026-03-31 13:30:28', '2026-03-31 13:30:28');
INSERT INTO `order_items` VALUES ('283', '178', '1', '89', 'Sò huyết nướng mỡ hành', '165000', '1', '', NULL, '0', 'confirmed', NULL, NULL, '2026-04-01 14:18:45', '2026-04-01 14:18:48');
INSERT INTO `order_items` VALUES ('284', '178', '1', '90', 'Cua rang me', '450000', '1', '', NULL, '0', 'confirmed', NULL, NULL, '2026-04-01 14:18:45', '2026-04-01 14:18:48');
INSERT INTO `order_items` VALUES ('285', '179', '6', '89', 'Sò huyết nướng mỡ hành', '165000', '1', '', NULL, '0', 'confirmed', NULL, NULL, '2026-04-01 14:19:35', '2026-04-01 14:19:39');
INSERT INTO `order_items` VALUES ('286', '179', '6', '90', 'Cua rang me', '450000', '1', '', NULL, '0', 'confirmed', NULL, NULL, '2026-04-01 14:19:35', '2026-04-01 14:19:39');

DROP TABLE IF EXISTS `order_notifications`;
CREATE TABLE `order_notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned DEFAULT NULL,
  `table_id` int(10) unsigned NOT NULL,
  `notification_type` enum('new_order','order_item','support_request','payment_request','scan_qr') NOT NULL DEFAULT 'new_order',
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `read_by` int(10) unsigned DEFAULT NULL COMMENT 'Nhân viên đã đọc',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_notification_order` (`order_id`),
  KEY `idx_notification_table` (`table_id`),
  KEY `idx_notification_unread` (`is_read`),
  KEY `idx_notification_type` (`notification_type`),
  KEY `idx_notification_created` (`created_at`),
  CONSTRAINT `fk_notification_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_notification_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=298 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lưu trữ thông báo order cho waiter';

INSERT INTO `order_notifications` VALUES ('1', NULL, '1', 'scan_qr', 'Bàn A.01: Khách đang xem menu', 'Khách vừa quét mã QR tại bàn A.01', '1', '2026-03-18 09:43:59', '3', '2026-03-17 19:07:47');
INSERT INTO `order_notifications` VALUES ('2', NULL, '2', 'scan_qr', 'Bàn A.02: Khách đang xem menu', 'Khách vừa quét mã QR tại bàn A.02', '1', '2026-03-18 09:43:59', '3', '2026-03-17 19:08:13');
INSERT INTO `order_notifications` VALUES ('3', NULL, '28', 'scan_qr', 'Bàn Âu 02: Khách đang xem menu', 'Khách vừa quét mã QR tại bàn Âu 02', '1', '2026-03-18 09:43:59', '3', '2026-03-17 19:11:24');
INSERT INTO `order_notifications` VALUES ('4', NULL, '1', 'scan_qr', 'Bàn A.01: Khách đang xem menu', 'Khách vừa quét mã QR tại bàn A.01', '1', '2026-03-18 09:43:59', '3', '2026-03-17 19:21:08');
INSERT INTO `order_notifications` VALUES ('5', NULL, '1', 'support_request', 'Bàn 1: Cần hỗ trợ', 'Khách tại bàn 1 đang gọi nhân viên.', '1', '2026-03-18 09:43:59', '3', '2026-03-17 19:21:27');
INSERT INTO `order_notifications` VALUES ('6', NULL, '1', 'scan_qr', 'Bàn A.01: Khách đang xem menu', 'Khách vừa quét mã QR tại bàn A.01', '1', '2026-03-18 09:43:59', '3', '2026-03-17 19:21:56');
INSERT INTO `order_notifications` VALUES ('7', NULL, '1', 'support_request', 'Bàn 1: Cần hỗ trợ', 'Khách tại bàn 1 đang gọi nhân viên.', '1', '2026-03-18 09:43:59', '3', '2026-03-17 19:23:28');
INSERT INTO `order_notifications` VALUES ('8', NULL, '1', 'scan_qr', 'Bàn A.01: Khách đang xem menu', 'Khách vừa quét mã QR tại bàn A.01', '1', '2026-03-18 09:43:59', '3', '2026-03-17 19:25:38');
INSERT INTO `order_notifications` VALUES ('9', NULL, '12', 'scan_qr', 'Bàn B.06: Khách đang xem menu', 'Khách vừa quét mã QR tại bàn B.06', '1', '2026-03-18 09:43:59', '3', '2026-03-17 19:25:52');
INSERT INTO `order_notifications` VALUES ('15', NULL, '6', 'scan_qr', 'Bàn A.06: Khách đang xem menu', 'Khách vừa quét mã QR tại bàn A.06', '1', '2026-03-18 09:43:59', '3', '2026-03-17 19:33:19');
INSERT INTO `order_notifications` VALUES ('16', NULL, '6', 'scan_qr', 'Bàn A.06: Khách đang xem menu', 'Khách vừa quét mã QR tại bàn A.06', '1', '2026-03-18 09:43:59', '3', '2026-03-17 19:33:50');
INSERT INTO `order_notifications` VALUES ('17', NULL, '1', 'scan_qr', 'Bàn A.01: Khách đang xem menu', 'Khách vừa quét mã QR tại bàn A.01', '1', '2026-03-18 09:43:59', '3', '2026-03-17 19:35:08');
INSERT INTO `order_notifications` VALUES ('19', NULL, '28', 'scan_qr', 'Bàn Âu 02: Khách đang xem menu', 'Khách vừa quét mã QR tại bàn Âu 02', '1', '2026-03-18 09:43:59', '3', '2026-03-17 19:48:54');
INSERT INTO `order_notifications` VALUES ('20', NULL, '28', 'scan_qr', 'Bàn Âu 02: Khách đang xem menu', 'Khách vừa quét mã QR tại bàn Âu 02', '1', '2026-03-18 09:43:59', '3', '2026-03-17 19:49:00');
INSERT INTO `order_notifications` VALUES ('21', NULL, '28', 'scan_qr', 'Bàn Âu 02: Khách đang xem menu', 'Khách vừa quét mã QR tại bàn Âu 02', '1', '2026-03-18 09:43:59', '3', '2026-03-17 19:49:23');
INSERT INTO `order_notifications` VALUES ('22', NULL, '28', 'scan_qr', 'Bàn Âu 02: Khách đang xem menu', 'Khách vừa quét mã QR tại bàn Âu 02', '1', '2026-03-18 09:43:59', '3', '2026-03-17 19:58:35');
INSERT INTO `order_notifications` VALUES ('23', NULL, '28', 'scan_qr', 'Bàn Âu 02: Khách đang xem menu', 'Khách vừa quét mã QR tại bàn Âu 02', '1', '2026-03-18 09:43:59', '3', '2026-03-17 19:59:22');
INSERT INTO `order_notifications` VALUES ('47', NULL, '10', 'scan_qr', 'Bàn B.04: Khách đang xem menu', 'Khách vừa quét mã QR tại bàn B.04', '1', '2026-03-18 18:36:54', '3', '2026-03-18 11:00:45');
INSERT INTO `order_notifications` VALUES ('48', NULL, '10', 'support_request', 'Bàn 10: Cần hỗ trợ', 'Khách tại bàn 10 đang gọi nhân viên.', '1', '2026-03-18 18:36:54', '3', '2026-03-18 11:01:19');
INSERT INTO `order_notifications` VALUES ('49', NULL, '14', 'scan_qr', 'Bàn C.02: Khách đang xem menu', 'Khách vừa quét mã QR tại bàn C.02', '1', '2026-03-18 18:36:54', '3', '2026-03-18 11:14:45');
INSERT INTO `order_notifications` VALUES ('50', NULL, '14', 'scan_qr', 'Bàn C.02: Khách đang xem menu', 'Khách vừa quét mã QR tại bàn C.02', '1', '2026-03-18 18:36:54', '3', '2026-03-18 11:17:47');
INSERT INTO `order_notifications` VALUES ('51', NULL, '14', 'scan_qr', 'Bàn C.02: Khách đang xem menu', 'Khách vừa quét mã QR tại bàn C.02', '1', '2026-03-18 18:36:54', '3', '2026-03-18 13:09:49');
INSERT INTO `order_notifications` VALUES ('52', NULL, '18', 'scan_qr', 'Bàn C.06: Khách đang xem menu', 'Khách vừa quét mã QR tại bàn C.06', '1', '2026-03-18 18:36:54', '3', '2026-03-18 13:11:16');
INSERT INTO `order_notifications` VALUES ('53', NULL, '1', 'scan_qr', 'Bàn A.01: Khách đang xem menu', 'Khách vừa quét mã QR tại bàn A.01', '1', '2026-03-18 18:36:54', '3', '2026-03-18 13:13:08');
INSERT INTO `order_notifications` VALUES ('56', NULL, '2', 'scan_qr', 'Bàn A.02: Khách đang xem menu', 'Khách vừa quét mã QR tại bàn A.02', '1', '2026-03-18 18:36:54', '3', '2026-03-18 13:54:10');
INSERT INTO `order_notifications` VALUES ('57', NULL, '1', 'scan_qr', 'Bàn A.01: Khách đang xem menu', 'Khách vừa quét mã QR tại bàn A.01', '1', '2026-03-18 18:36:54', '3', '2026-03-18 13:55:42');
INSERT INTO `order_notifications` VALUES ('58', NULL, '1', 'scan_qr', 'Bàn A.01: Khách đang xem menu', 'Khách vừa quét mã QR tại bàn A.01', '1', '2026-03-18 18:36:54', '3', '2026-03-18 13:59:03');
INSERT INTO `order_notifications` VALUES ('60', NULL, '14', 'scan_qr', 'Bàn C.02: Khách đang xem menu', 'Khách vừa quét mã QR tại bàn C.02', '1', '2026-03-18 18:36:54', '3', '2026-03-18 15:18:58');
INSERT INTO `order_notifications` VALUES ('62', NULL, '1', 'scan_qr', 'Bàn A.01: Khách đang xem menu', 'Khách vừa quét mã QR tại bàn A.01', '1', '2026-03-18 18:36:54', '3', '2026-03-18 15:40:34');
INSERT INTO `order_notifications` VALUES ('63', NULL, '23', 'scan_qr', 'Bàn VIP 3.1: Khách đang xem menu', 'Khách vừa quét mã QR tại bàn VIP 3.1', '1', '2026-03-18 18:37:49', '3', '2026-03-18 18:37:27');
INSERT INTO `order_notifications` VALUES ('66', NULL, '1', 'scan_qr', 'Bàn A.01: Khách đang xem menu', 'Khách vừa quét mã QR tại bàn A.01', '1', '2026-03-18 20:28:35', '3', '2026-03-18 20:28:18');
INSERT INTO `order_notifications` VALUES ('204', '156', '19', 'scan_qr', 'Khách xem menu', 'Bàn VIP 1.1 vừa quét mã xem thực đơn.', '1', '2026-03-30 19:39:31', '3', '2026-03-30 19:34:10');
INSERT INTO `order_notifications` VALUES ('205', '156', '19', 'scan_qr', 'Khách xem menu', 'Bàn VIP 1.1 vừa quét mã xem thực đơn.', '1', '2026-03-30 19:39:31', '3', '2026-03-30 19:34:33');
INSERT INTO `order_notifications` VALUES ('206', '156', '19', 'order_item', 'Bàn VIP 1.1: Thêm món mới', 'Khách đã gửi thêm món qua QR.', '1', '2026-03-30 19:39:31', '3', '2026-03-30 19:34:43');
INSERT INTO `order_notifications` VALUES ('207', '156', '19', 'scan_qr', 'Khách xem menu', 'Bàn VIP 1.1 vừa quét mã xem thực đơn.', '1', '2026-03-30 19:39:31', '3', '2026-03-30 19:35:01');
INSERT INTO `order_notifications` VALUES ('208', '156', '19', 'support_request', 'Bàn 19: Cần hỗ trợ', 'Khách tại bàn 19 đang gọi nhân viên.', '1', '2026-03-30 19:39:31', '3', '2026-03-30 19:35:08');
INSERT INTO `order_notifications` VALUES ('209', '156', '19', 'support_request', 'Bàn 19: Cần hỗ trợ', 'Khách tại bàn 19 đang gọi nhân viên.', '1', '2026-03-30 19:39:31', '3', '2026-03-30 19:35:25');
INSERT INTO `order_notifications` VALUES ('210', '156', '19', 'order_item', 'Bàn VIP 1.1: Thêm món mới', 'Khách đã gửi thêm món qua QR.', '1', '2026-03-30 19:39:31', '3', '2026-03-30 19:36:27');
INSERT INTO `order_notifications` VALUES ('211', '156', '19', 'scan_qr', 'Khách xem menu', 'Bàn VIP 1.1 vừa quét mã xem thực đơn.', '1', '2026-03-30 19:39:31', '3', '2026-03-30 19:36:29');
INSERT INTO `order_notifications` VALUES ('212', '156', '19', 'scan_qr', 'Khách xem menu', 'Bàn VIP 1.1 vừa quét mã xem thực đơn.', '1', '2026-03-30 19:39:31', '3', '2026-03-30 19:39:06');
INSERT INTO `order_notifications` VALUES ('213', '156', '19', 'scan_qr', 'Khách xem menu', 'Bàn VIP 1.1 vừa quét mã xem thực đơn.', '1', '2026-03-30 19:39:31', '3', '2026-03-30 19:39:11');
INSERT INTO `order_notifications` VALUES ('214', '156', '19', 'order_item', '➕ GỌI THÊM: VIP 1.1', 'Khách tại VIP 1.1 vừa gọi thêm món mới.', '1', '2026-03-30 19:40:22', '3', '2026-03-30 19:39:47');
INSERT INTO `order_notifications` VALUES ('215', '156', '19', 'scan_qr', 'Khách xem menu', 'Bàn VIP 1.1 vừa quét mã xem thực đơn.', '1', '2026-03-30 19:45:50', '3', '2026-03-30 19:41:30');
INSERT INTO `order_notifications` VALUES ('216', '156', '19', 'order_item', '➕ GỌI THÊM: VIP 1.1', 'Khách tại VIP 1.1 vừa gọi thêm món mới.', '1', '2026-03-30 19:45:49', '3', '2026-03-30 19:41:37');
INSERT INTO `order_notifications` VALUES ('217', '156', '19', 'scan_qr', 'Khách xem menu', 'Bàn VIP 1.1 vừa quét mã xem thực đơn.', '1', '2026-03-30 19:45:48', '3', '2026-03-30 19:45:18');
INSERT INTO `order_notifications` VALUES ('218', '156', '19', 'scan_qr', 'Khách xem menu', 'Bàn VIP 1.1 vừa quét mã xem thực đơn.', '1', '2026-03-30 20:12:58', '3', '2026-03-30 19:50:48');
INSERT INTO `order_notifications` VALUES ('219', '156', '19', 'scan_qr', 'Khách xem menu', 'Bàn VIP 1.1 vừa quét mã xem thực đơn.', '1', '2026-03-30 20:12:57', '3', '2026-03-30 20:04:28');
INSERT INTO `order_notifications` VALUES ('220', '156', '19', 'scan_qr', 'Khách xem menu', 'Bàn VIP 1.1 vừa quét mã xem thực đơn.', '1', '2026-03-30 20:12:54', '3', '2026-03-30 20:10:44');
INSERT INTO `order_notifications` VALUES ('221', '156', '19', 'scan_qr', 'Khách xem menu', 'Bàn VIP 1.1 vừa quét mã xem thực đơn.', '1', '2026-03-30 20:12:51', '3', '2026-03-30 20:12:20');
INSERT INTO `order_notifications` VALUES ('222', '156', '19', 'scan_qr', 'Khách xem menu', 'Bàn VIP 1.1 vừa quét mã xem thực đơn.', '1', '2026-03-31 13:55:00', '3', '2026-03-30 20:29:38');
INSERT INTO `order_notifications` VALUES ('237', '163', '7', 'scan_qr', 'Khách xem menu', 'Bàn B.01 vừa quét mã xem thực đơn.', '1', '2026-03-31 13:54:58', '3', '2026-03-30 21:24:15');
INSERT INTO `order_notifications` VALUES ('238', '163', '7', 'scan_qr', 'Khách xem menu', 'Bàn B.01 vừa quét mã xem thực đơn.', '1', '2026-03-31 13:54:58', '3', '2026-03-30 21:24:23');
INSERT INTO `order_notifications` VALUES ('239', '163', '7', 'scan_qr', 'Khách xem menu', 'Bàn B.01 vừa quét mã xem thực đơn.', '1', '2026-03-31 13:54:58', '3', '2026-03-30 21:24:41');
INSERT INTO `order_notifications` VALUES ('294', '177', '1', 'scan_qr', 'Khách xem menu', 'Bàn A.01 vừa quét mã xem thực đơn.', '1', '2026-04-01 14:17:02', '3', '2026-04-01 14:15:10');
INSERT INTO `order_notifications` VALUES ('295', '180', '1', 'scan_qr', 'Khách xem menu', 'Bàn A.01 vừa quét mã xem thực đơn.', '1', '2026-04-07 09:08:01', '3', '2026-04-01 19:15:30');
INSERT INTO `order_notifications` VALUES ('296', '181', '3', 'scan_qr', 'Khách xem menu', 'Bàn A.03 vừa quét mã xem thực đơn.', '1', '2026-04-07 09:10:31', '1', '2026-04-07 09:10:12');
INSERT INTO `order_notifications` VALUES ('297', '182', '34', 'scan_qr', 'Khách xem menu', 'Bàn 701 vừa quét mã xem thực đơn.', '0', NULL, NULL, '2026-04-07 16:17:52');

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `table_id` int(10) unsigned NOT NULL,
  `waiter_id` int(10) unsigned DEFAULT NULL,
  `shift_id` int(10) unsigned DEFAULT NULL,
  `guest_count` tinyint(3) unsigned DEFAULT 1 COMMENT 'Số khách',
  `note` text DEFAULT NULL COMMENT 'Ghi chú cho cả order',
  `customer_notes` text DEFAULT NULL COMMENT 'Ghi chú từ khách hàng (lý do hủy, đặc biệt...)',
  `requires_confirmation` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Cần xác nhận từ nhân viên: 1=Có, 0=Không',
  `status` enum('open','closed') NOT NULL DEFAULT 'open' COMMENT 'open=đang phục vụ, closed=khách ra',
  `order_source` enum('waiter','customer_qr') NOT NULL DEFAULT 'waiter' COMMENT 'Nguồn tạo order: waiter (phục vụ) hoặc customer_qr (khách quét QR)',
  `is_realtime_hidden` tinyint(1) DEFAULT 0,
  `payment_method` varchar(50) DEFAULT 'cash',
  `payment_status` enum('pending','paid','canceled') DEFAULT 'pending',
  `opened_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Giờ mở bàn',
  `closed_at` timestamp NULL DEFAULT NULL COMMENT 'Giờ đóng bàn',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `session_id` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_orders_table` (`table_id`),
  KEY `idx_orders_waiter` (`waiter_id`),
  KEY `idx_orders_status` (`status`),
  KEY `idx_orders_opened` (`opened_at`),
  KEY `idx_order_source` (`order_source`),
  KEY `idx_orders_session` (`session_id`),
  CONSTRAINT `fk_orders_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_orders_waiter` FOREIGN KEY (`waiter_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=183 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `orders` VALUES ('156', '19', NULL, NULL, '1', 'Khách quét QR mở bàn', NULL, '1', 'closed', 'customer_qr', '1', 'cash', 'canceled', '2026-03-30 19:34:10', '2026-04-01 13:59:31', '2026-03-30 19:34:10', '2026-04-01 14:07:32', '499754bf90238de6f2ead2fa53be94b4');
INSERT INTO `orders` VALUES ('157', '1', '3', '1', '1', '', NULL, '1', 'closed', 'waiter', '1', 'cash', 'paid', '2026-03-30 19:51:57', '2026-04-01 13:58:14', '2026-03-30 19:51:57', '2026-04-01 14:07:31', NULL);
INSERT INTO `orders` VALUES ('161', '6', '3', '1', '3', '', NULL, '1', 'closed', 'waiter', '1', 'cash', 'canceled', '2026-03-30 21:08:51', '2026-04-01 13:59:14', '2026-03-30 21:08:51', '2026-04-01 14:07:30', NULL);
INSERT INTO `orders` VALUES ('163', '7', NULL, NULL, '1', 'Khách quét QR mở bàn', NULL, '1', 'closed', 'customer_qr', '1', 'cash', 'canceled', '2026-03-30 21:24:15', '2026-04-01 13:59:06', '2026-03-30 21:24:15', '2026-04-01 14:07:29', 'f21c8cb97942b488132de0911a44c83b');
INSERT INTO `orders` VALUES ('176', '93', '3', '1', '2', '', NULL, '1', 'closed', 'waiter', '1', 'cash', 'canceled', '2026-04-01 14:01:53', '2026-04-01 14:12:05', '2026-04-01 14:01:53', '2026-04-01 14:12:05', NULL);
INSERT INTO `orders` VALUES ('177', '1', NULL, NULL, '1', 'Khách quét QR mở bàn', NULL, '1', 'closed', 'customer_qr', '0', 'cash', 'canceled', '2026-04-01 14:15:10', '2026-04-01 14:16:58', '2026-04-01 14:15:10', '2026-04-01 14:16:58', '3a8ed76dbc292e63902c5fd15739ca41');
INSERT INTO `orders` VALUES ('178', '1', '3', '1', '1', '', NULL, '1', 'closed', 'waiter', '0', 'cash', 'paid', '2026-04-01 14:18:45', '2026-04-01 14:19:07', '2026-04-01 14:18:45', '2026-04-01 14:19:07', NULL);
INSERT INTO `orders` VALUES ('179', '6', '3', '1', '2', '', NULL, '1', 'closed', 'waiter', '0', 'cash', 'paid', '2026-04-01 14:19:27', '2026-04-01 14:21:57', '2026-04-01 14:19:27', '2026-04-01 14:21:57', NULL);
INSERT INTO `orders` VALUES ('180', '1', '3', '1', '2', '', NULL, '1', 'closed', 'customer_qr', '0', 'cash', 'canceled', '2026-04-01 14:23:51', '2026-04-07 16:11:03', '2026-04-01 14:23:51', '2026-04-07 16:11:03', '3a8ed76dbc292e63902c5fd15739ca41');
INSERT INTO `orders` VALUES ('181', '3', NULL, NULL, '1', 'Khách quét QR mở bàn', NULL, '1', 'closed', 'customer_qr', '0', 'cash', 'canceled', '2026-04-07 09:10:12', '2026-04-07 09:11:06', '2026-04-07 09:10:12', '2026-04-07 09:11:06', '4a31121867c453ac350d04e9dddddeaa');
INSERT INTO `orders` VALUES ('182', '34', NULL, NULL, '1', 'Khách quét QR mở bàn', NULL, '1', 'open', 'customer_qr', '0', 'cash', 'pending', '2026-04-07 16:17:52', NULL, '2026-04-07 16:17:52', '2026-04-07 16:17:52', '87bbd3a55599f403665adf8a3c44c812');

DROP TABLE IF EXISTS `qr_tables`;
CREATE TABLE `qr_tables` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `table_id` int(10) unsigned NOT NULL COMMENT 'Mã bàn (foreign key)',
  `qr_code` varchar(255) DEFAULT NULL COMMENT 'URL hoặc nội dung QR code',
  `qr_hash` varchar(64) NOT NULL COMMENT 'Mã hash duy nhất cho QR (dùng cho URL)',
  `generated_at` timestamp NULL DEFAULT current_timestamp() COMMENT 'Thời gian tạo QR',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=đ aktiv, 0=ẩn',
  `scan_count` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'Số lần quét QR code',
  `last_scanned_at` timestamp NULL DEFAULT NULL COMMENT 'Lần quét cuối cùng',
  `is_printed` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `table_id` (`table_id`),
  UNIQUE KEY `qr_hash` (`qr_hash`),
  KEY `idx_qr_active` (`is_active`),
  CONSTRAINT `fk_qr_tables_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=222 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `qr_tables` VALUES ('2', '2', '/qr/menu?table_id=2&token=5aea0ec36591ecddd837fa739b2a5786', '5aea0ec36591ecddd837fa739b2a5786', '2026-03-08 16:50:22', '2026-03-23 09:01:50', '1', '5', '2026-03-23 09:01:50', '0');
INSERT INTO `qr_tables` VALUES ('3', '3', '/qr/menu?table_id=3&token=42a2c52875c7bfc390916ca1c33a7157', '42a2c52875c7bfc390916ca1c33a7157', '2026-03-08 16:50:22', '2026-03-23 09:15:33', '1', '25', '2026-03-23 09:15:33', '0');
INSERT INTO `qr_tables` VALUES ('4', '4', '/qr/menu?table_id=4&token=59151733a4403e2ba90a3668b91ef209', '59151733a4403e2ba90a3668b91ef209', '2026-03-08 16:50:22', '2026-03-23 09:16:48', '1', '4', '2026-03-23 09:16:48', '0');
INSERT INTO `qr_tables` VALUES ('5', '5', '/qr/menu?table_id=5&token=618e597619b7339cb04747a43747b086', '618e597619b7339cb04747a43747b086', '2026-03-08 16:50:22', '2026-03-17 18:30:03', '1', '2', '2026-03-17 18:30:03', '0');
INSERT INTO `qr_tables` VALUES ('6', '6', '/qr/menu?table_id=6&token=4d594e96b4578dd6eb6a2772eeb342d4', '4d594e96b4578dd6eb6a2772eeb342d4', '2026-03-08 16:50:22', '2026-03-23 09:21:29', '1', '10', '2026-03-23 09:21:29', '0');
INSERT INTO `qr_tables` VALUES ('7', '7', '/qr/menu?table_id=7&token=9debf619d8155b5e4218cdd77c9caa19', '9debf619d8155b5e4218cdd77c9caa19', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('8', '8', '/qr/menu?table_id=8&token=1a19d6276459abb3b0c92c9d7dd7dc0f', '1a19d6276459abb3b0c92c9d7dd7dc0f', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('9', '9', '/qr/menu?table_id=9&token=399e1ce0c5dd5fcaeb4cc583e17b45c5', '399e1ce0c5dd5fcaeb4cc583e17b45c5', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('10', '10', '/qr/menu?table_id=10&token=fb7e119368932a90d53465b48456597e', 'fb7e119368932a90d53465b48456597e', '2026-03-08 16:50:22', '2026-03-18 11:00:45', '1', '1', '2026-03-18 11:00:45', '0');
INSERT INTO `qr_tables` VALUES ('11', '11', '/qr/menu?table_id=11&token=d6590664eb462875de436efba585885b', 'd6590664eb462875de436efba585885b', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('12', '12', '/qr/menu?table_id=12&token=7714f8eb8cd1d9f1f4119665b538b9ec', '7714f8eb8cd1d9f1f4119665b538b9ec', '2026-03-08 16:50:22', '2026-03-18 21:00:18', '1', '8', '2026-03-18 21:00:18', '0');
INSERT INTO `qr_tables` VALUES ('13', '13', '/qr/menu?table_id=13&token=77d2001b22efcd63714ee5ec39cd624f', '77d2001b22efcd63714ee5ec39cd624f', '2026-03-08 16:50:22', '2026-03-19 08:59:30', '1', '3', '2026-03-19 08:59:30', '0');
INSERT INTO `qr_tables` VALUES ('14', '14', '/qr/menu?table_id=14&token=4f49388e51b134fabd30a789026ef9d0', '4f49388e51b134fabd30a789026ef9d0', '2026-03-08 16:50:22', '2026-03-18 15:18:58', '1', '4', '2026-03-18 15:18:58', '0');
INSERT INTO `qr_tables` VALUES ('15', '15', '/qr/menu?table_id=15&token=e939bdc269e7ea98da7f779691f9c63f', 'e939bdc269e7ea98da7f779691f9c63f', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('16', '16', '/qr/menu?table_id=16&token=5271c6745193cbd78b6d60fff9fb2863', '5271c6745193cbd78b6d60fff9fb2863', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('17', '17', '/qr/menu?table_id=17&token=ced798a790cc9fc268d03bcb7d5ccb93', 'ced798a790cc9fc268d03bcb7d5ccb93', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('18', '18', '/qr/menu?table_id=18&token=fa8932bc456533b1d680f807d16f35e9', 'fa8932bc456533b1d680f807d16f35e9', '2026-03-08 16:50:22', '2026-03-23 08:11:18', '1', '2', '2026-03-23 08:11:18', '1');
INSERT INTO `qr_tables` VALUES ('19', '19', '/qr/menu?table_id=19&token=b756d7566f9984bbc5190a823b00497a', 'b756d7566f9984bbc5190a823b00497a', '2026-03-08 16:50:22', '2026-03-25 10:52:32', '1', '0', NULL, '1');
INSERT INTO `qr_tables` VALUES ('20', '20', '/qr/menu?table_id=20&token=21b46e1f5b1d62e2f92ff2f02b28cf20', '21b46e1f5b1d62e2f92ff2f02b28cf20', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('21', '21', '/qr/menu?table_id=21&token=8a76b7a961c5343e09d947e7d762e032', '8a76b7a961c5343e09d947e7d762e032', '2026-03-08 16:50:22', '2026-03-17 21:46:45', '1', '3', '2026-03-17 21:46:45', '0');
INSERT INTO `qr_tables` VALUES ('22', '22', '/qr/menu?table_id=22&token=bd35d8e8560defeda360b4d99677350e', 'bd35d8e8560defeda360b4d99677350e', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('23', '23', '/qr/menu?table_id=23&token=36fcd88597412647c56d588a4f136e49', '36fcd88597412647c56d588a4f136e49', '2026-03-08 16:50:22', '2026-03-18 21:31:34', '1', '5', '2026-03-18 21:31:34', '0');
INSERT INTO `qr_tables` VALUES ('25', '25', '/qr/menu?table_id=25&token=4feee1c893e3c3ae36a029a11fdcd143', '4feee1c893e3c3ae36a029a11fdcd143', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('26', '26', '/qr/menu?table_id=26&token=a13c5111c2fea2dbf170ad159d88b3d5', 'a13c5111c2fea2dbf170ad159d88b3d5', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('27', '27', '/qr/menu?table_id=27&token=ab4722e5df763a17d54a46afa3506d39', 'ab4722e5df763a17d54a46afa3506d39', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('28', '28', '/qr/menu?table_id=28&token=197c68ac3e576c4ca2ecc90ba0035749', '197c68ac3e576c4ca2ecc90ba0035749', '2026-03-08 16:50:22', '2026-03-18 21:29:40', '1', '10', '2026-03-18 21:29:40', '0');
INSERT INTO `qr_tables` VALUES ('29', '29', '/qr/menu?table_id=29&token=540c56c80e81ad174c96292e2a32e55b', '540c56c80e81ad174c96292e2a32e55b', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('30', '30', '/qr/menu?table_id=30&token=892c41525bae1635bc4dd6e00049db09', '892c41525bae1635bc4dd6e00049db09', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('31', '31', '/qr/menu?table_id=31&token=c4d60af58f19e189cfbae688a169a499', 'c4d60af58f19e189cfbae688a169a499', '2026-03-08 16:50:22', '2026-03-18 21:41:17', '1', '7', '2026-03-18 21:41:17', '0');
INSERT INTO `qr_tables` VALUES ('32', '32', '/qr/menu?table_id=32&token=3132152b936f32b9bd4833020db70d8e', '3132152b936f32b9bd4833020db70d8e', '2026-03-08 16:50:22', '2026-03-18 21:36:11', '1', '12', '2026-03-18 21:36:11', '0');
INSERT INTO `qr_tables` VALUES ('64', '1', '/qr/menu?table_id=1&token=c1674174442ac69294484eb54ffe1e2b', 'c1674174442ac69294484eb54ffe1e2b', '2026-03-17 17:59:01', '2026-03-23 09:02:04', '1', '74', '2026-03-23 09:02:04', '1');
INSERT INTO `qr_tables` VALUES ('99', '34', '/qr/menu?table_id=34&token=9853c359c274bc07', '9853c359c274bc07', '2026-03-21 18:15:32', '2026-03-22 14:23:53', '1', '5', '2026-03-22 14:23:53', '0');
INSERT INTO `qr_tables` VALUES ('100', '35', '/qr/menu?table_id=35&token=657bb64a5236cd07', '657bb64a5236cd07', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('101', '36', '/qr/menu?table_id=36&token=9e6e864e8dcd4d16', '9e6e864e8dcd4d16', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('102', '37', '/qr/menu?table_id=37&token=542e9fb5755eb267', '542e9fb5755eb267', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('103', '38', '/qr/menu?table_id=38&token=e1deba631cedf38e', 'e1deba631cedf38e', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('104', '39', '/qr/menu?table_id=39&token=d14c1f3cc89aac11', 'd14c1f3cc89aac11', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('105', '40', '/qr/menu?table_id=40&token=3bf496f44e08b889', '3bf496f44e08b889', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('106', '41', '/qr/menu?table_id=41&token=0d260ec51b770ce8', '0d260ec51b770ce8', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('107', '42', '/qr/menu?table_id=42&token=9001444b50b373db', '9001444b50b373db', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('108', '43', '/qr/menu?table_id=43&token=dbf00f10b62db39a', 'dbf00f10b62db39a', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('109', '44', '/qr/menu?table_id=44&token=cb05824f42f77532', 'cb05824f42f77532', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('110', '45', '/qr/menu?table_id=45&token=fdc33c6963b61a5f', 'fdc33c6963b61a5f', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('111', '46', '/qr/menu?table_id=46&token=ceddfbf3399ab9cb', 'ceddfbf3399ab9cb', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('112', '47', '/qr/menu?table_id=47&token=b9dc9f230ab371ca', 'b9dc9f230ab371ca', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('113', '48', '/qr/menu?table_id=48&token=92778081df46e36a', '92778081df46e36a', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('114', '49', '/qr/menu?table_id=49&token=4ed6c469b2367b6b', '4ed6c469b2367b6b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('115', '50', '/qr/menu?table_id=50&token=5caa7d2b02b578a3', '5caa7d2b02b578a3', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('116', '51', '/qr/menu?table_id=51&token=e92de0aa41aebbd1', 'e92de0aa41aebbd1', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('117', '52', '/qr/menu?table_id=52&token=1f3bd48265060d7f', '1f3bd48265060d7f', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('118', '53', '/qr/menu?table_id=53&token=95e7f49e988c9349', '95e7f49e988c9349', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('119', '54', '/qr/menu?table_id=54&token=074d0a40b61ad55f', '074d0a40b61ad55f', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('120', '55', '/qr/menu?table_id=55&token=4a40e346b77da1a1', '4a40e346b77da1a1', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('121', '56', '/qr/menu?table_id=56&token=c426d510ddc9d02e', 'c426d510ddc9d02e', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('122', '57', '/qr/menu?table_id=57&token=0e0fce6b5913b27d', '0e0fce6b5913b27d', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('123', '58', '/qr/menu?table_id=58&token=ca71a8d84a9f53a1', 'ca71a8d84a9f53a1', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('124', '59', '/qr/menu?table_id=59&token=2f722fa35a1b19a0', '2f722fa35a1b19a0', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('125', '60', '/qr/menu?table_id=60&token=8f4cdfd92d0da925', '8f4cdfd92d0da925', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('126', '61', '/qr/menu?table_id=61&token=c26b7a5d6e373c8d', 'c26b7a5d6e373c8d', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('127', '62', '/qr/menu?table_id=62&token=75f24d352b7930e0', '75f24d352b7930e0', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('128', '63', '/qr/menu?table_id=63&token=b233ed219adb5ff6', 'b233ed219adb5ff6', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('129', '64', '/qr/menu?table_id=64&token=faf4cc3087786929', 'faf4cc3087786929', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('130', '65', '/qr/menu?table_id=65&token=55ef6da9717fe36c', '55ef6da9717fe36c', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('131', '66', '/qr/menu?table_id=66&token=b5cbe2119ca3584b', 'b5cbe2119ca3584b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('132', '67', '/qr/menu?table_id=67&token=34864e0af2f15b4e', '34864e0af2f15b4e', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('133', '68', '/qr/menu?table_id=68&token=13b52b9cdc71396b', '13b52b9cdc71396b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('134', '69', '/qr/menu?table_id=69&token=3e9b4e4c66093170', '3e9b4e4c66093170', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('135', '70', '/qr/menu?table_id=70&token=c8fc938812da7595', 'c8fc938812da7595', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('136', '71', '/qr/menu?table_id=71&token=72ed0a67f3e418bc', '72ed0a67f3e418bc', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('137', '72', '/qr/menu?table_id=72&token=5f63dba64276ec9b', '5f63dba64276ec9b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('138', '73', '/qr/menu?table_id=73&token=18d2b01d34b02538', '18d2b01d34b02538', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('139', '74', '/qr/menu?table_id=74&token=c78d0727cc42e972', 'c78d0727cc42e972', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('140', '75', '/qr/menu?table_id=75&token=6c564c3fe3cb9817', '6c564c3fe3cb9817', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('141', '76', '/qr/menu?table_id=76&token=c33726dbdb0358a2', 'c33726dbdb0358a2', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('142', '77', '/qr/menu?table_id=77&token=96f602f4e71c331a', '96f602f4e71c331a', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('143', '78', '/qr/menu?table_id=78&token=572dab2f380d1ca7', '572dab2f380d1ca7', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('144', '79', '/qr/menu?table_id=79&token=c522360dfc9445d2', 'c522360dfc9445d2', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('145', '80', '/qr/menu?table_id=80&token=a88b48db14c892da', 'a88b48db14c892da', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('146', '81', '/qr/menu?table_id=81&token=4df8d4ba2e32312d', '4df8d4ba2e32312d', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('147', '82', '/qr/menu?table_id=82&token=cb3f6608ec4d5a72', 'cb3f6608ec4d5a72', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('148', '83', '/qr/menu?table_id=83&token=5aaedb0e390a7313', '5aaedb0e390a7313', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('149', '84', '/qr/menu?table_id=84&token=0ad57b083cac6260', '0ad57b083cac6260', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('150', '85', '/qr/menu?table_id=85&token=af6af4b4511a99e4', 'af6af4b4511a99e4', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('151', '86', '/qr/menu?table_id=86&token=b4420cde02c1b182', 'b4420cde02c1b182', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('152', '87', '/qr/menu?table_id=87&token=4ea6748a036a17a9', '4ea6748a036a17a9', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('153', '88', '/qr/menu?table_id=88&token=7eb45a95a506e6e2', '7eb45a95a506e6e2', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('154', '89', '/qr/menu?table_id=89&token=d63333a8c7b4ca3f', 'd63333a8c7b4ca3f', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('155', '90', '/qr/menu?table_id=90&token=4e2eb855787bcaf3', '4e2eb855787bcaf3', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('156', '91', '/qr/menu?table_id=91&token=2c90fda9e1180a1c', '2c90fda9e1180a1c', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('157', '92', '/qr/menu?table_id=92&token=6167dc28edb38a55', '6167dc28edb38a55', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('158', '93', '/qr/menu?table_id=93&token=1e93ecca46389d15', '1e93ecca46389d15', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('159', '94', '/qr/menu?table_id=94&token=2805dc1870802cfe', '2805dc1870802cfe', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('160', '95', '/qr/menu?table_id=95&token=97d9f38183834cd4', '97d9f38183834cd4', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('161', '96', '/qr/menu?table_id=96&token=14aacaae5e8a2d9a', '14aacaae5e8a2d9a', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('162', '97', '/qr/menu?table_id=97&token=4de1fb2e24430677', '4de1fb2e24430677', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('163', '98', '/qr/menu?table_id=98&token=b938fa1231a36d2d', 'b938fa1231a36d2d', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('164', '99', '/qr/menu?table_id=99&token=2b3b89ba01f3f085', '2b3b89ba01f3f085', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('165', '100', '/qr/menu?table_id=100&token=446bfe04c58be616', '446bfe04c58be616', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('166', '101', '/qr/menu?table_id=101&token=82c6002b648817c7', '82c6002b648817c7', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('167', '102', '/qr/menu?table_id=102&token=d6edbc3d315a0ef1', 'd6edbc3d315a0ef1', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('168', '103', '/qr/menu?table_id=103&token=74f7a42d4a33b9a7', '74f7a42d4a33b9a7', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('169', '104', '/qr/menu?table_id=104&token=e875bbb203cb84d7', 'e875bbb203cb84d7', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('170', '105', '/qr/menu?table_id=105&token=1f7c164a788f78dc', '1f7c164a788f78dc', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('171', '106', '/qr/menu?table_id=106&token=37b088c946864e19', '37b088c946864e19', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('172', '107', '/qr/menu?table_id=107&token=7c24fa1b0ceb862b', '7c24fa1b0ceb862b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('173', '108', '/qr/menu?table_id=108&token=2c7ae1c5c5a61adf', '2c7ae1c5c5a61adf', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('174', '109', '/qr/menu?table_id=109&token=bc03b90c607c4bc9', 'bc03b90c607c4bc9', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('175', '110', '/qr/menu?table_id=110&token=fc067eba34f4dba9', 'fc067eba34f4dba9', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('176', '111', '/qr/menu?table_id=111&token=f5926dec48bfbe9c', 'f5926dec48bfbe9c', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('177', '112', '/qr/menu?table_id=112&token=2383544b8b0258e7', '2383544b8b0258e7', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('178', '113', '/qr/menu?table_id=113&token=db8c74cc5321febd', 'db8c74cc5321febd', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('179', '114', '/qr/menu?table_id=114&token=03516ce24046399e', '03516ce24046399e', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('180', '115', '/qr/menu?table_id=115&token=56c2454f1b84c62c', '56c2454f1b84c62c', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('181', '116', '/qr/menu?table_id=116&token=da286833edbd8947', 'da286833edbd8947', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('182', '117', '/qr/menu?table_id=117&token=b99a416c123bbb03', 'b99a416c123bbb03', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('183', '118', '/qr/menu?table_id=118&token=4bc458bc62aa55d7', '4bc458bc62aa55d7', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('184', '119', '/qr/menu?table_id=119&token=ac330ef97f991520', 'ac330ef97f991520', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('185', '120', '/qr/menu?table_id=120&token=6d204a5f0433b0e8', '6d204a5f0433b0e8', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('186', '121', '/qr/menu?table_id=121&token=9e26e6b6c034b261', '9e26e6b6c034b261', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('187', '122', '/qr/menu?table_id=122&token=fcc56cc9ac7f6c8e', 'fcc56cc9ac7f6c8e', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('188', '123', '/qr/menu?table_id=123&token=d11f52647ab07b8b', 'd11f52647ab07b8b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('189', '124', '/qr/menu?table_id=124&token=38a0175b8c3cce4b', '38a0175b8c3cce4b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('190', '125', '/qr/menu?table_id=125&token=cad72d36fe9ca269', 'cad72d36fe9ca269', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('191', '126', '/qr/menu?table_id=126&token=83f76774214ee84e', '83f76774214ee84e', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('192', '127', '/qr/menu?table_id=127&token=9991fa34d9fb2d59', '9991fa34d9fb2d59', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('193', '128', '/qr/menu?table_id=128&token=86ae7a9242ada453', '86ae7a9242ada453', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('194', '129', '/qr/menu?table_id=129&token=23f166f0940f2ef6', '23f166f0940f2ef6', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('195', '130', '/qr/menu?table_id=130&token=b499b3b3782bd9db', 'b499b3b3782bd9db', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('196', '131', '/qr/menu?table_id=131&token=f85e5f115dd00f1b', 'f85e5f115dd00f1b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('197', '132', '/qr/menu?table_id=132&token=ec420c13716ca4fa', 'ec420c13716ca4fa', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('198', '133', '/qr/menu?table_id=133&token=6df5879b353e73ac', '6df5879b353e73ac', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('199', '134', '/qr/menu?table_id=134&token=cafdfa0c96dc556b', 'cafdfa0c96dc556b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('200', '135', '/qr/menu?table_id=135&token=3cc749ee1b83a365', '3cc749ee1b83a365', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('201', '136', '/qr/menu?table_id=136&token=d253f49e98fa7d34', 'd253f49e98fa7d34', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('202', '137', '/qr/menu?table_id=137&token=0c6cb912e525da5e', '0c6cb912e525da5e', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('203', '138', '/qr/menu?table_id=138&token=1968c9b0b8de1f5a', '1968c9b0b8de1f5a', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('204', '139', '/qr/menu?table_id=139&token=28952c5a3abedad9', '28952c5a3abedad9', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('205', '140', '/qr/menu?table_id=140&token=d2fbb6f6deef5f30', 'd2fbb6f6deef5f30', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('206', '141', '/qr/menu?table_id=141&token=f9f63ab0e0db6707', 'f9f63ab0e0db6707', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('207', '142', '/qr/menu?table_id=142&token=d644c2729c67fa8d', 'd644c2729c67fa8d', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('208', '143', '/qr/menu?table_id=143&token=fc38c53b4c412aad', 'fc38c53b4c412aad', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('209', '144', '/qr/menu?table_id=144&token=7a1bcb991914757a', '7a1bcb991914757a', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('210', '145', '/qr/menu?table_id=145&token=4cbb75dbb4fcbf09', '4cbb75dbb4fcbf09', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('211', '146', '/qr/menu?table_id=146&token=c7d6d2391254259c', 'c7d6d2391254259c', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('212', '147', '/qr/menu?table_id=147&token=4a84f03a016fe02d', '4a84f03a016fe02d', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('213', '148', '/qr/menu?table_id=148&token=09d06f72cd8bd631', '09d06f72cd8bd631', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('214', '149', '/qr/menu?table_id=149&token=36d48dbe9b2888a9', '36d48dbe9b2888a9', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('215', '150', '/qr/menu?table_id=150&token=c9c3520512677d78', 'c9c3520512677d78', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('216', '151', '/qr/menu?table_id=151&token=46d4fd06efb382d8', '46d4fd06efb382d8', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('217', '152', '/qr/menu?table_id=152&token=047cf64bdd192fca', '047cf64bdd192fca', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('218', '153', '/qr/menu?table_id=153&token=1ab3d2b7971a925e', '1ab3d2b7971a925e', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('219', '154', '/qr/menu?table_id=154&token=9ba8818156b831f5', '9ba8818156b831f5', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('220', '155', '/qr/menu?table_id=155&token=b0a92130b038239b', 'b0a92130b038239b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');

DROP TABLE IF EXISTS `realtime_notifications`;
CREATE TABLE `realtime_notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `channel` varchar(50) NOT NULL COMMENT 'Kênh: waiter_1, admin, table_5, all',
  `event_type` varchar(50) NOT NULL COMMENT 'Loại event: new_order, order_confirmed, table_occupied',
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Dữ liệu notification dạng JSON' CHECK (json_valid(`payload`)),
  `is_delivered` tinyint(1) NOT NULL DEFAULT 0,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NULL DEFAULT NULL COMMENT 'Hết hạn sau 24h',
  PRIMARY KEY (`id`),
  KEY `idx_channel` (`channel`),
  KEY `idx_event_type` (`event_type`),
  KEY `idx_delivered` (`is_delivered`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Real-time push notifications';


DROP TABLE IF EXISTS `shifts`;
CREATE TABLE `shifts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT 'Tên ca: Sáng, Chiều, Tối...',
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `shifts` VALUES ('3', 'Ca Sáng', '06:00:00', '14:00:00', '2026-04-07 09:07:03');
INSERT INTO `shifts` VALUES ('4', 'Ca Chiều', '14:00:00', '22:00:00', '2026-04-07 09:07:03');
INSERT INTO `shifts` VALUES ('5', 'Ca Tối', '22:00:00', '06:00:00', '2026-04-07 09:07:03');

DROP TABLE IF EXISTS `support_requests`;
CREATE TABLE `support_requests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `table_id` int(10) unsigned NOT NULL,
  `type` enum('support','payment','scan_qr','new_order') NOT NULL DEFAULT 'support' COMMENT 'Loại yêu cầu: support=hỗ trợ, payment=thanh toán, scan_qr=quét QR, new_order=order mới',
  `status` enum('pending','completed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_support_table` (`table_id`),
  CONSTRAINT `fk_support_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `support_requests` VALUES ('1', '1', '', 'pending', '2026-03-17 13:52:40', '2026-03-17 13:52:40');
INSERT INTO `support_requests` VALUES ('2', '21', '', 'pending', '2026-03-17 14:04:55', '2026-03-17 14:04:55');
INSERT INTO `support_requests` VALUES ('3', '1', 'scan_qr', 'pending', '2026-03-17 15:03:45', '2026-03-17 15:03:45');
INSERT INTO `support_requests` VALUES ('4', '3', 'scan_qr', 'pending', '2026-03-17 15:39:27', '2026-03-17 15:39:27');
INSERT INTO `support_requests` VALUES ('5', '1', 'scan_qr', 'pending', '2026-03-17 17:07:43', '2026-03-17 17:07:43');
INSERT INTO `support_requests` VALUES ('6', '1', 'scan_qr', 'pending', '2026-03-17 17:37:02', '2026-03-17 17:37:02');
INSERT INTO `support_requests` VALUES ('7', '1', 'scan_qr', 'pending', '2026-03-17 17:45:25', '2026-03-17 17:45:25');
INSERT INTO `support_requests` VALUES ('8', '3', 'scan_qr', 'pending', '2026-03-17 19:49:23', '2026-03-17 19:49:23');
INSERT INTO `support_requests` VALUES ('9', '1', 'scan_qr', 'pending', '2026-03-17 21:24:23', '2026-03-17 21:24:23');
INSERT INTO `support_requests` VALUES ('10', '21', 'scan_qr', 'pending', '2026-03-17 21:33:13', '2026-03-17 21:33:13');
INSERT INTO `support_requests` VALUES ('11', '3', 'scan_qr', 'pending', '2026-03-18 10:08:29', '2026-03-18 10:08:29');
INSERT INTO `support_requests` VALUES ('12', '19', 'scan_qr', 'pending', '2026-03-18 13:07:16', '2026-03-18 13:07:16');
INSERT INTO `support_requests` VALUES ('13', '1', 'scan_qr', 'pending', '2026-03-18 18:31:04', '2026-03-18 18:31:04');
INSERT INTO `support_requests` VALUES ('14', '1', 'scan_qr', 'pending', '2026-03-18 18:36:02', '2026-03-18 18:36:02');
INSERT INTO `support_requests` VALUES ('15', '2', 'scan_qr', 'pending', '2026-03-18 18:53:32', '2026-03-18 18:53:32');
INSERT INTO `support_requests` VALUES ('16', '31', 'scan_qr', 'pending', '2026-03-18 21:40:54', '2026-03-18 21:40:54');
INSERT INTO `support_requests` VALUES ('17', '38', 'scan_qr', 'pending', '2026-03-21 19:00:25', '2026-03-21 19:00:25');
INSERT INTO `support_requests` VALUES ('18', '36', 'scan_qr', 'pending', '2026-03-22 17:35:15', '2026-03-22 17:35:15');
INSERT INTO `support_requests` VALUES ('19', '126', 'scan_qr', 'pending', '2026-03-25 10:57:08', '2026-03-25 10:57:08');
INSERT INTO `support_requests` VALUES ('20', '5', 'scan_qr', 'pending', '2026-03-25 16:01:32', '2026-03-25 16:01:32');
INSERT INTO `support_requests` VALUES ('21', '19', 'scan_qr', 'pending', '2026-03-25 16:01:47', '2026-03-25 16:01:47');
INSERT INTO `support_requests` VALUES ('22', '1', 'scan_qr', 'pending', '2026-03-30 17:46:54', '2026-03-30 17:46:54');
INSERT INTO `support_requests` VALUES ('23', '1', 'scan_qr', 'pending', '2026-03-30 18:20:32', '2026-03-30 18:20:32');
INSERT INTO `support_requests` VALUES ('24', '13', 'scan_qr', 'pending', '2026-03-30 18:55:22', '2026-03-30 18:55:22');
INSERT INTO `support_requests` VALUES ('25', '1', 'scan_qr', 'pending', '2026-03-30 19:51:52', '2026-03-30 19:51:52');
INSERT INTO `support_requests` VALUES ('26', '1', 'scan_qr', 'pending', '2026-03-30 20:05:47', '2026-03-30 20:05:47');
INSERT INTO `support_requests` VALUES ('27', '1', 'scan_qr', 'pending', '2026-03-30 20:30:42', '2026-03-30 20:30:42');
INSERT INTO `support_requests` VALUES ('28', '7', 'scan_qr', 'pending', '2026-03-30 20:51:29', '2026-03-30 20:51:29');
INSERT INTO `support_requests` VALUES ('29', '1', 'scan_qr', 'pending', '2026-03-30 21:16:05', '2026-03-30 21:16:05');
INSERT INTO `support_requests` VALUES ('30', '1', 'scan_qr', 'pending', '2026-03-30 21:19:59', '2026-03-30 21:19:59');
INSERT INTO `support_requests` VALUES ('31', '1', 'scan_qr', 'pending', '2026-03-31 13:30:15', '2026-03-31 13:30:15');
INSERT INTO `support_requests` VALUES ('32', '2', 'scan_qr', 'pending', '2026-03-31 13:32:15', '2026-03-31 13:32:15');
INSERT INTO `support_requests` VALUES ('33', '2', 'scan_qr', 'pending', '2026-03-31 14:03:07', '2026-03-31 14:03:07');
INSERT INTO `support_requests` VALUES ('34', '93', 'scan_qr', 'pending', '2026-04-01 14:01:57', '2026-04-01 14:01:57');

DROP TABLE IF EXISTS `table_status_history`;
CREATE TABLE `table_status_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `table_id` int(10) unsigned NOT NULL,
  `previous_status` enum('available','occupied') NOT NULL,
  `current_status` enum('available','occupied') NOT NULL,
  `changed_by` int(10) unsigned DEFAULT NULL COMMENT 'User ID hoặc NULL nếu từ customer',
  `change_reason` varchar(100) DEFAULT NULL COMMENT 'Lý do: scan_qr, waiter_open, manual_close, auto_close',
  `order_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_table_history` (`table_id`),
  KEY `idx_table_status_time` (`created_at`),
  KEY `idx_table_change_reason` (`change_reason`),
  CONSTRAINT `fk_history_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lịch sử thay đổi trạng thái bàn';


DROP TABLE IF EXISTS `tables`;
CREATE TABLE `tables` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `type` enum('table','room') NOT NULL DEFAULT 'table',
  `name` varchar(50) NOT NULL COMMENT 'Tên bàn: Bàn 01, VIP 1...',
  `area` varchar(50) DEFAULT NULL COMMENT 'Khu vực: Trong, Ngoài, VIP...',
  `capacity` tinyint(3) unsigned NOT NULL DEFAULT 4 COMMENT 'Sức chứa (số ghế)',
  `status` enum('available','occupied') NOT NULL DEFAULT 'available',
  `position_x` smallint(5) unsigned DEFAULT 0 COMMENT 'Toạ độ X trên sơ đồ',
  `position_y` smallint(5) unsigned DEFAULT 0 COMMENT 'Toạ độ Y trên sơ đồ',
  `sort_order` smallint(5) unsigned DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_tables_parent` (`parent_id`),
  KEY `idx_parent_id` (`parent_id`),
  CONSTRAINT `fk_tables_parent` FOREIGN KEY (`parent_id`) REFERENCES `tables` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_tables_parent_new` FOREIGN KEY (`parent_id`) REFERENCES `tables` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tables` VALUES ('1', NULL, 'table', 'A.01', 'A1', '4', 'available', '0', '0', '1', '1', '2026-03-07 18:20:45', '2026-04-07 16:11:03');
INSERT INTO `tables` VALUES ('2', NULL, 'table', 'A.02', 'A1', '4', 'available', '0', '0', '2', '1', '2026-03-07 18:20:45', '2026-03-31 14:50:43');
INSERT INTO `tables` VALUES ('3', NULL, 'table', 'A.03', 'A1', '4', 'available', '0', '0', '3', '1', '2026-03-07 18:20:45', '2026-04-07 09:11:06');
INSERT INTO `tables` VALUES ('4', NULL, 'table', 'A.04', 'A1', '4', 'available', '0', '0', '4', '1', '2026-03-07 18:20:45', '2026-03-31 14:50:43');
INSERT INTO `tables` VALUES ('5', NULL, 'table', 'A.05', 'A1', '4', 'available', '0', '0', '5', '1', '2026-03-07 18:20:45', '2026-03-31 14:50:43');
INSERT INTO `tables` VALUES ('6', NULL, 'table', 'A.06', 'A1', '4', 'available', '0', '0', '6', '1', '2026-03-07 18:20:45', '2026-04-01 14:21:57');
INSERT INTO `tables` VALUES ('7', NULL, 'table', 'B.01', 'B1', '4', 'available', '0', '0', '7', '1', '2026-03-07 18:20:45', '2026-04-01 13:59:06');
INSERT INTO `tables` VALUES ('8', NULL, 'table', 'B.02', 'B1', '4', 'available', '0', '0', '8', '1', '2026-03-07 18:20:45', '2026-03-30 19:46:08');
INSERT INTO `tables` VALUES ('9', NULL, 'table', 'B.03', 'B1', '4', 'available', '0', '0', '9', '1', '2026-03-07 18:20:45', '2026-03-30 21:39:48');
INSERT INTO `tables` VALUES ('10', NULL, 'table', 'B.04', 'B1', '4', 'available', '0', '0', '10', '1', '2026-03-07 18:20:45', '2026-03-30 22:00:27');
INSERT INTO `tables` VALUES ('11', NULL, 'table', 'B.05', 'B1', '4', 'available', '0', '0', '11', '1', '2026-03-07 18:20:45', '2026-03-30 19:46:08');
INSERT INTO `tables` VALUES ('12', NULL, 'table', 'B.06', 'B1', '4', 'available', '0', '0', '12', '1', '2026-03-07 18:20:45', '2026-03-30 19:46:08');
INSERT INTO `tables` VALUES ('13', NULL, 'table', 'C.01', 'C1', '4', 'available', '0', '0', '13', '1', '2026-03-07 18:20:45', '2026-03-30 19:46:18');
INSERT INTO `tables` VALUES ('14', NULL, 'table', 'C.02', 'C1', '4', 'available', '0', '0', '14', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('15', NULL, 'table', 'C.03', 'C1', '4', 'available', '0', '0', '15', '1', '2026-03-07 18:20:45', '2026-03-30 19:46:34');
INSERT INTO `tables` VALUES ('16', NULL, 'table', 'C.04', 'C1', '4', 'available', '0', '0', '16', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('17', NULL, 'table', 'C.05', 'C1', '4', 'available', '0', '0', '17', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('18', NULL, 'table', 'C.06', 'C1', '4', 'available', '0', '0', '18', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('19', NULL, 'table', 'VIP 1.1', 'VIP 1', '8', 'available', '0', '0', '19', '1', '2026-03-07 18:20:45', '2026-04-01 13:59:31');
INSERT INTO `tables` VALUES ('20', NULL, 'table', 'VIP 1.2', 'VIP 1', '8', 'available', '0', '0', '20', '1', '2026-03-07 18:20:45', '2026-03-30 22:01:31');
INSERT INTO `tables` VALUES ('21', NULL, 'table', 'VIP 2.1', 'VIP 2', '8', 'available', '0', '0', '21', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('22', NULL, 'table', 'VIP 2.2', 'VIP 2', '8', 'available', '0', '0', '22', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('23', NULL, 'table', 'VIP 3.1', 'VIP 3', '8', 'available', '0', '0', '23', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('24', NULL, 'table', 'VIP 3.2', 'VIP 3', '8', 'available', '0', '0', '24', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('25', NULL, 'table', 'VIP 4.1', 'VIP 4', '8', 'available', '0', '0', '25', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('26', NULL, 'table', 'VIP 4.2', 'VIP 4', '8', 'available', '0', '0', '26', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('27', NULL, 'table', 'Âu 01', 'Âu', '4', 'available', '0', '0', '27', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('28', NULL, 'table', 'Âu 02', 'Âu', '4', 'available', '0', '0', '28', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('29', NULL, 'table', 'Âu 03', 'Âu', '4', 'available', '0', '0', '29', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('30', NULL, 'table', 'Âu 04', 'Âu', '4', 'available', '0', '0', '30', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('31', NULL, 'table', 'Âu 05', 'Âu', '4', 'available', '0', '0', '31', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('32', NULL, 'table', 'Âu 06', 'Âu', '4', 'available', '0', '0', '32', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('34', NULL, 'room', '701', 'Tầng 7', '3', 'occupied', '0', '0', '701', '1', '2026-03-21 18:15:32', '2026-04-07 16:17:52');
INSERT INTO `tables` VALUES ('35', NULL, 'room', '702', 'Tầng 7', '3', 'available', '0', '0', '702', '1', '2026-03-21 18:15:32', '2026-03-21 18:15:32');
INSERT INTO `tables` VALUES ('36', NULL, 'room', '703', 'Tầng 7', '3', 'available', '0', '0', '703', '1', '2026-03-21 18:15:32', '2026-03-23 14:48:57');
INSERT INTO `tables` VALUES ('37', NULL, 'room', '704', 'Tầng 7', '3', 'available', '0', '0', '704', '1', '2026-03-21 18:15:32', '2026-03-21 18:15:32');
INSERT INTO `tables` VALUES ('38', NULL, 'room', '705', 'Tầng 7', '3', 'available', '0', '0', '705', '1', '2026-03-21 18:15:32', '2026-03-21 19:00:33');
INSERT INTO `tables` VALUES ('39', NULL, 'room', '706', 'Tầng 7', '3', 'available', '0', '0', '706', '1', '2026-03-21 18:15:32', '2026-03-21 18:15:32');
INSERT INTO `tables` VALUES ('40', NULL, 'room', '707', 'Tầng 7', '3', 'available', '0', '0', '707', '1', '2026-03-21 18:15:32', '2026-03-21 18:15:32');
INSERT INTO `tables` VALUES ('41', NULL, 'room', '708', 'Tầng 7', '3', 'available', '0', '0', '708', '1', '2026-03-21 18:15:32', '2026-03-21 18:15:32');
INSERT INTO `tables` VALUES ('42', NULL, 'room', '709', 'Tầng 7', '3', 'available', '0', '0', '709', '1', '2026-03-21 18:15:32', '2026-03-21 18:15:32');
INSERT INTO `tables` VALUES ('43', NULL, 'room', '710', 'Tầng 7', '3', 'available', '0', '0', '710', '1', '2026-03-21 18:15:32', '2026-03-21 18:15:32');
INSERT INTO `tables` VALUES ('44', NULL, 'room', '711', 'Tầng 7', '3', 'available', '0', '0', '711', '1', '2026-03-21 18:15:32', '2026-03-21 18:15:32');
INSERT INTO `tables` VALUES ('45', NULL, 'room', '712', 'Tầng 7', '3', 'available', '0', '0', '712', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('46', NULL, 'room', '714', 'Tầng 7', '3', 'available', '0', '0', '714', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('47', NULL, 'room', '715', 'Tầng 7', '3', 'available', '0', '0', '715', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('48', NULL, 'room', '716', 'Tầng 7', '3', 'available', '0', '0', '716', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('49', NULL, 'room', '717', 'Tầng 7', '3', 'available', '0', '0', '717', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('50', NULL, 'room', '718', 'Tầng 7', '3', 'available', '0', '0', '718', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('51', NULL, 'room', '719', 'Tầng 7', '3', 'available', '0', '0', '719', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('52', NULL, 'room', '720', 'Tầng 7', '3', 'available', '0', '0', '720', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('53', NULL, 'room', '801', 'Tầng 8', '3', 'available', '0', '0', '801', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('54', NULL, 'room', '802', 'Tầng 8', '3', 'available', '0', '0', '802', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('55', NULL, 'room', '803', 'Tầng 8', '3', 'available', '0', '0', '803', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('56', NULL, 'room', '804', 'Tầng 8', '3', 'available', '0', '0', '804', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('57', NULL, 'room', '805', 'Tầng 8', '3', 'available', '0', '0', '805', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('58', NULL, 'room', '806', 'Tầng 8', '3', 'available', '0', '0', '806', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('59', NULL, 'room', '807', 'Tầng 8', '3', 'available', '0', '0', '807', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('60', NULL, 'room', '808', 'Tầng 8', '3', 'available', '0', '0', '808', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('61', NULL, 'room', '809', 'Tầng 8', '3', 'available', '0', '0', '809', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('62', NULL, 'room', '810', 'Tầng 8', '3', 'available', '0', '0', '810', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('63', NULL, 'room', '811', 'Tầng 8', '3', 'available', '0', '0', '811', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('64', NULL, 'room', '812', 'Tầng 8', '3', 'available', '0', '0', '812', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('65', NULL, 'room', '814', 'Tầng 8', '3', 'available', '0', '0', '814', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('66', NULL, 'room', '815', 'Tầng 8', '3', 'available', '0', '0', '815', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('67', NULL, 'room', '816', 'Tầng 8', '3', 'available', '0', '0', '816', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('68', NULL, 'room', '817', 'Tầng 8', '3', 'available', '0', '0', '817', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('69', NULL, 'room', '818', 'Tầng 8', '3', 'available', '0', '0', '818', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('70', NULL, 'room', '819', 'Tầng 8', '3', 'available', '0', '0', '819', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('71', NULL, 'room', '901', 'Tầng 9', '3', 'available', '0', '0', '901', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('72', NULL, 'room', '902', 'Tầng 9', '3', 'available', '0', '0', '902', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('73', NULL, 'room', '903', 'Tầng 9', '3', 'available', '0', '0', '903', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('74', NULL, 'room', '904', 'Tầng 9', '3', 'available', '0', '0', '904', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('75', NULL, 'room', '905', 'Tầng 9', '3', 'available', '0', '0', '905', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('76', NULL, 'room', '906', 'Tầng 9', '3', 'available', '0', '0', '906', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('77', NULL, 'room', '907', 'Tầng 9', '3', 'available', '0', '0', '907', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('78', NULL, 'room', '908', 'Tầng 9', '3', 'available', '0', '0', '908', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('79', NULL, 'room', '909', 'Tầng 9', '3', 'available', '0', '0', '909', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('80', NULL, 'room', '910', 'Tầng 9', '3', 'available', '0', '0', '910', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('81', NULL, 'room', '911', 'Tầng 9', '3', 'available', '0', '0', '911', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('82', NULL, 'room', '912', 'Tầng 9', '3', 'available', '0', '0', '912', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('83', NULL, 'room', '914', 'Tầng 9', '3', 'available', '0', '0', '914', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('84', NULL, 'room', '915', 'Tầng 9', '3', 'available', '0', '0', '915', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('85', NULL, 'room', '916', 'Tầng 9', '3', 'available', '0', '0', '916', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('86', NULL, 'room', '917', 'Tầng 9', '3', 'available', '0', '0', '917', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('87', NULL, 'room', '918', 'Tầng 9', '3', 'available', '0', '0', '918', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('88', NULL, 'room', '919', 'Tầng 9', '3', 'available', '0', '0', '919', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('89', NULL, 'room', '920', 'Tầng 9', '3', 'available', '0', '0', '920', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('90', NULL, 'room', '921', 'Tầng 9', '3', 'available', '0', '0', '921', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('91', NULL, 'room', '922', 'Tầng 9', '3', 'available', '0', '0', '922', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('92', NULL, 'room', '923', 'Tầng 9', '3', 'available', '0', '0', '923', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('93', NULL, 'room', '1001', 'Tầng 10', '3', 'available', '0', '0', '1001', '1', '2026-03-21 18:15:33', '2026-04-01 14:12:05');
INSERT INTO `tables` VALUES ('94', NULL, 'room', '1002', 'Tầng 10', '3', 'available', '0', '0', '1002', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('95', NULL, 'room', '1003', 'Tầng 10', '3', 'available', '0', '0', '1003', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('96', NULL, 'room', '1004', 'Tầng 10', '3', 'available', '0', '0', '1004', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('97', NULL, 'room', '1005', 'Tầng 10', '3', 'available', '0', '0', '1005', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('98', NULL, 'room', '1006', 'Tầng 10', '3', 'available', '0', '0', '1006', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('99', NULL, 'room', '1007', 'Tầng 10', '3', 'available', '0', '0', '1007', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('100', NULL, 'room', '1008', 'Tầng 10', '3', 'available', '0', '0', '1008', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('101', NULL, 'room', '1009', 'Tầng 10', '3', 'available', '0', '0', '1009', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('102', NULL, 'room', '1010', 'Tầng 10', '3', 'available', '0', '0', '1010', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('103', NULL, 'room', '1011', 'Tầng 10', '3', 'available', '0', '0', '1011', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('104', NULL, 'room', '1012', 'Tầng 10', '3', 'available', '0', '0', '1012', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('105', NULL, 'room', '1014', 'Tầng 10', '3', 'available', '0', '0', '1014', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('106', NULL, 'room', '1015', 'Tầng 10', '3', 'available', '0', '0', '1015', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('107', NULL, 'room', '1016', 'Tầng 10', '3', 'available', '0', '0', '1016', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('108', NULL, 'room', '1017', 'Tầng 10', '3', 'available', '0', '0', '1017', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('109', NULL, 'room', '1018', 'Tầng 10', '3', 'available', '0', '0', '1018', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('110', NULL, 'room', '1019', 'Tầng 10', '3', 'available', '0', '0', '1019', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('111', NULL, 'room', '1020', 'Tầng 10', '3', 'available', '0', '0', '1020', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('112', NULL, 'room', '1021', 'Tầng 10', '3', 'available', '0', '0', '1021', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('113', NULL, 'room', '1022', 'Tầng 10', '3', 'available', '0', '0', '1022', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('114', NULL, 'room', '1023', 'Tầng 10', '3', 'available', '0', '0', '1023', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('115', NULL, 'room', '1101', 'Tầng 11', '3', 'available', '0', '0', '1101', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('116', NULL, 'room', '1102', 'Tầng 11', '3', 'available', '0', '0', '1102', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('117', NULL, 'room', '1103', 'Tầng 11', '3', 'available', '0', '0', '1103', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('118', NULL, 'room', '1104', 'Tầng 11', '3', 'available', '0', '0', '1104', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('119', NULL, 'room', '1105', 'Tầng 11', '3', 'available', '0', '0', '1105', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('120', NULL, 'room', '1106', 'Tầng 11', '3', 'available', '0', '0', '1106', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('121', NULL, 'room', '1107', 'Tầng 11', '3', 'available', '0', '0', '1107', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('122', NULL, 'room', '1108', 'Tầng 11', '3', 'available', '0', '0', '1108', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('123', NULL, 'room', '1109', 'Tầng 11', '3', 'available', '0', '0', '1109', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('124', NULL, 'room', '1110', 'Tầng 11', '3', 'available', '0', '0', '1110', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('125', NULL, 'room', '1111', 'Tầng 11', '3', 'available', '0', '0', '1111', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('126', NULL, 'room', '1112', 'Tầng 11', '3', 'available', '0', '0', '1112', '1', '2026-03-21 18:15:33', '2026-03-26 10:14:55');
INSERT INTO `tables` VALUES ('127', NULL, 'room', '1114', 'Tầng 11', '3', 'available', '0', '0', '1114', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('128', NULL, 'room', '1115', 'Tầng 11', '3', 'available', '0', '0', '1115', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('129', NULL, 'room', '1116', 'Tầng 11', '3', 'available', '0', '0', '1116', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('130', NULL, 'room', '1117', 'Tầng 11', '3', 'available', '0', '0', '1117', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('131', NULL, 'room', '1118', 'Tầng 11', '3', 'available', '0', '0', '1118', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('132', NULL, 'room', '1119', 'Tầng 11', '3', 'available', '0', '0', '1119', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('133', NULL, 'room', '1120', 'Tầng 11', '3', 'available', '0', '0', '1120', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('134', NULL, 'room', '1121', 'Tầng 11', '3', 'available', '0', '0', '1121', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('135', NULL, 'room', '1122', 'Tầng 11', '3', 'available', '0', '0', '1122', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('136', NULL, 'room', '1123', 'Tầng 11', '3', 'available', '0', '0', '1123', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('137', NULL, 'room', '1201', 'Tầng 12', '3', 'available', '0', '0', '1201', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('138', NULL, 'room', '1202', 'Tầng 12', '3', 'available', '0', '0', '1202', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('139', NULL, 'room', '1203', 'Tầng 12', '3', 'available', '0', '0', '1203', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('140', NULL, 'room', '1204', 'Tầng 12', '3', 'available', '0', '0', '1204', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('141', NULL, 'room', '1205', 'Tầng 12', '3', 'available', '0', '0', '1205', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('142', NULL, 'room', '1206', 'Tầng 12', '3', 'available', '0', '0', '1206', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('143', NULL, 'room', '1207', 'Tầng 12', '3', 'available', '0', '0', '1207', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('144', NULL, 'room', '1208', 'Tầng 12', '3', 'available', '0', '0', '1208', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('145', NULL, 'room', '1209', 'Tầng 12', '3', 'available', '0', '0', '1209', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('146', NULL, 'room', '1210', 'Tầng 12', '3', 'available', '0', '0', '1210', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('147', NULL, 'room', '1211', 'Tầng 12', '3', 'available', '0', '0', '1211', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('148', NULL, 'room', '1212', 'Tầng 12', '3', 'available', '0', '0', '1212', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('149', NULL, 'room', '1214', 'Tầng 12', '3', 'available', '0', '0', '1214', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('150', NULL, 'room', '1215', 'Tầng 12', '3', 'available', '0', '0', '1215', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('151', NULL, 'room', '1216', 'Tầng 12', '3', 'available', '0', '0', '1216', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('152', NULL, 'room', '1217', 'Tầng 12', '3', 'available', '0', '0', '1217', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('153', NULL, 'room', '1218', 'Tầng 12', '3', 'available', '0', '0', '1218', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('154', NULL, 'room', '1219', 'Tầng 12', '3', 'available', '0', '0', '1219', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('155', NULL, 'room', '1220', 'Tầng 12', '3', 'available', '0', '0', '1220', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');

DROP TABLE IF EXISTS `user_shifts`;
CREATE TABLE `user_shifts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `shift_id` int(10) unsigned NOT NULL,
  `work_date` date NOT NULL COMMENT 'Ngày làm việc',
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_user_shifts_user` (`user_id`),
  KEY `fk_user_shifts_shift` (`shift_id`),
  CONSTRAINT `fk_user_shifts_shift` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_user_shifts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT 'Tên nhân viên',
  `username` varchar(50) NOT NULL COMMENT 'Tên đăng nhập',
  `pin` char(4) NOT NULL COMMENT 'PIN 4 số đăng nhập iPad',
  `role` enum('waiter','admin','it') NOT NULL DEFAULT 'waiter',
  `avatar` varchar(255) DEFAULT NULL COMMENT 'URL ảnh đại diện',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=hoạt động, 0=vô hiệu',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` VALUES ('1', 'Admin Nhà Hàng', 'admin', '0000', 'admin', NULL, '1', '2026-03-07 18:08:27', '2026-03-26 09:00:50');
INSERT INTO `users` VALUES ('2', 'IT System', 'it', '9999', 'it', NULL, '1', '2026-03-07 18:08:27', '2026-03-07 18:08:27');
INSERT INTO `users` VALUES ('3', 'Nhân Viên Nhà Hàng', 'waiter01', '1111', 'waiter', NULL, '1', '2026-03-07 18:08:27', '2026-03-26 09:00:09');
INSERT INTO `users` VALUES ('4', 'Nhân Viên Nhà Hàng', 'waiter02', '1111', 'waiter', NULL, '1', '2026-03-07 18:08:27', '2026-03-26 09:00:36');

DROP TABLE IF EXISTS `vw_location_limit`;
;

INSERT INTO `vw_location_limit` VALUES ('1', 'Giới hạn QR Restaurant', '10.95770000', '106.84480000', '500', '1', '2026-03-08 16:36:35', '2026-03-08 16:36:35');

SET FOREIGN_KEY_CHECKS = 1;
