-- SQL INSERT ROOMS AND QR CODES
SET @room_type = 'room';

-- Tầng 7
INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('701', 'Tầng 7', 3, @room_type, 701, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '1e5817c621acbe28', CONCAT('/qr/menu?table_id=', @last_id, '&token=1e5817c621acbe28'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('702', 'Tầng 7', 3, @room_type, 702, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '5f35fca9a1a1846c', CONCAT('/qr/menu?table_id=', @last_id, '&token=5f35fca9a1a1846c'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('703', 'Tầng 7', 3, @room_type, 703, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '5de87b4f728906be', CONCAT('/qr/menu?table_id=', @last_id, '&token=5de87b4f728906be'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('704', 'Tầng 7', 3, @room_type, 704, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '67db11abae42e250', CONCAT('/qr/menu?table_id=', @last_id, '&token=67db11abae42e250'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('705', 'Tầng 7', 3, @room_type, 705, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '9ef4b528f3754ced', CONCAT('/qr/menu?table_id=', @last_id, '&token=9ef4b528f3754ced'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('706', 'Tầng 7', 3, @room_type, 706, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '8d8ff0b67f6af403', CONCAT('/qr/menu?table_id=', @last_id, '&token=8d8ff0b67f6af403'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('707', 'Tầng 7', 3, @room_type, 707, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '86f0b8c0369df5e6', CONCAT('/qr/menu?table_id=', @last_id, '&token=86f0b8c0369df5e6'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('708', 'Tầng 7', 3, @room_type, 708, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'ebfca26f7c3a295a', CONCAT('/qr/menu?table_id=', @last_id, '&token=ebfca26f7c3a295a'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('709', 'Tầng 7', 3, @room_type, 709, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '6ce2cf88101c7db6', CONCAT('/qr/menu?table_id=', @last_id, '&token=6ce2cf88101c7db6'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('710', 'Tầng 7', 3, @room_type, 710, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '61292e3958ddfe70', CONCAT('/qr/menu?table_id=', @last_id, '&token=61292e3958ddfe70'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('711', 'Tầng 7', 3, @room_type, 711, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'f6661b88750da20c', CONCAT('/qr/menu?table_id=', @last_id, '&token=f6661b88750da20c'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('712', 'Tầng 7', 3, @room_type, 712, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '357506b82f3954ed', CONCAT('/qr/menu?table_id=', @last_id, '&token=357506b82f3954ed'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('714', 'Tầng 7', 3, @room_type, 714, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'cefa45a1752d79c1', CONCAT('/qr/menu?table_id=', @last_id, '&token=cefa45a1752d79c1'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('715', 'Tầng 7', 3, @room_type, 715, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '3078e6decc783820', CONCAT('/qr/menu?table_id=', @last_id, '&token=3078e6decc783820'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('716', 'Tầng 7', 3, @room_type, 716, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '9bdaae02d6c84e04', CONCAT('/qr/menu?table_id=', @last_id, '&token=9bdaae02d6c84e04'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('717', 'Tầng 7', 3, @room_type, 717, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '19273fc12c38cd20', CONCAT('/qr/menu?table_id=', @last_id, '&token=19273fc12c38cd20'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('718', 'Tầng 7', 3, @room_type, 718, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '311cef70f2334c47', CONCAT('/qr/menu?table_id=', @last_id, '&token=311cef70f2334c47'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('719', 'Tầng 7', 3, @room_type, 719, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '9871f28d534d62bd', CONCAT('/qr/menu?table_id=', @last_id, '&token=9871f28d534d62bd'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('720', 'Tầng 7', 3, @room_type, 720, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '8e6c0a4d6dd7e2f1', CONCAT('/qr/menu?table_id=', @last_id, '&token=8e6c0a4d6dd7e2f1'), 1, 0);

-- Tầng 8
INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('801', 'Tầng 8', 3, @room_type, 801, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '7111bb33301a4588', CONCAT('/qr/menu?table_id=', @last_id, '&token=7111bb33301a4588'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('802', 'Tầng 8', 3, @room_type, 802, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '0af4aac94d5068e3', CONCAT('/qr/menu?table_id=', @last_id, '&token=0af4aac94d5068e3'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('803', 'Tầng 8', 3, @room_type, 803, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '8efcbcccd3b5fbef', CONCAT('/qr/menu?table_id=', @last_id, '&token=8efcbcccd3b5fbef'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('804', 'Tầng 8', 3, @room_type, 804, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'fb17995710c83343', CONCAT('/qr/menu?table_id=', @last_id, '&token=fb17995710c83343'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('805', 'Tầng 8', 3, @room_type, 805, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '89522692edc66636', CONCAT('/qr/menu?table_id=', @last_id, '&token=89522692edc66636'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('806', 'Tầng 8', 3, @room_type, 806, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '0aaf0efb2d62911d', CONCAT('/qr/menu?table_id=', @last_id, '&token=0aaf0efb2d62911d'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('807', 'Tầng 8', 3, @room_type, 807, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '3b419b9fc4e25551', CONCAT('/qr/menu?table_id=', @last_id, '&token=3b419b9fc4e25551'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('808', 'Tầng 8', 3, @room_type, 808, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '4f8887c9586ed9be', CONCAT('/qr/menu?table_id=', @last_id, '&token=4f8887c9586ed9be'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('809', 'Tầng 8', 3, @room_type, 809, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'e99323696e678e60', CONCAT('/qr/menu?table_id=', @last_id, '&token=e99323696e678e60'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('810', 'Tầng 8', 3, @room_type, 810, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'c7af6c81b122a0d2', CONCAT('/qr/menu?table_id=', @last_id, '&token=c7af6c81b122a0d2'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('811', 'Tầng 8', 3, @room_type, 811, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '171e0bbc23f6101a', CONCAT('/qr/menu?table_id=', @last_id, '&token=171e0bbc23f6101a'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('812', 'Tầng 8', 3, @room_type, 812, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '7467c8b23891be3a', CONCAT('/qr/menu?table_id=', @last_id, '&token=7467c8b23891be3a'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('814', 'Tầng 8', 3, @room_type, 814, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '50c79dc5c38154e0', CONCAT('/qr/menu?table_id=', @last_id, '&token=50c79dc5c38154e0'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('815', 'Tầng 8', 3, @room_type, 815, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '917c8cf97dcc17f9', CONCAT('/qr/menu?table_id=', @last_id, '&token=917c8cf97dcc17f9'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('816', 'Tầng 8', 3, @room_type, 816, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'c769d01952537a23', CONCAT('/qr/menu?table_id=', @last_id, '&token=c769d01952537a23'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('817', 'Tầng 8', 3, @room_type, 817, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '17abfd2144144b66', CONCAT('/qr/menu?table_id=', @last_id, '&token=17abfd2144144b66'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('818', 'Tầng 8', 3, @room_type, 818, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'd4e70c71524d17e8', CONCAT('/qr/menu?table_id=', @last_id, '&token=d4e70c71524d17e8'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('819', 'Tầng 8', 3, @room_type, 819, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '46c92a0606d22572', CONCAT('/qr/menu?table_id=', @last_id, '&token=46c92a0606d22572'), 1, 0);

-- Tầng 9
INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('901', 'Tầng 9', 3, @room_type, 901, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '6ff6c88c1cc1720e', CONCAT('/qr/menu?table_id=', @last_id, '&token=6ff6c88c1cc1720e'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('902', 'Tầng 9', 3, @room_type, 902, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '1ed045a09b41a1ab', CONCAT('/qr/menu?table_id=', @last_id, '&token=1ed045a09b41a1ab'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('903', 'Tầng 9', 3, @room_type, 903, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '21c52bbea0bd9bbd', CONCAT('/qr/menu?table_id=', @last_id, '&token=21c52bbea0bd9bbd'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('904', 'Tầng 9', 3, @room_type, 904, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'be994adaa3e110c2', CONCAT('/qr/menu?table_id=', @last_id, '&token=be994adaa3e110c2'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('905', 'Tầng 9', 3, @room_type, 905, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '4fc0b1fe18d81c78', CONCAT('/qr/menu?table_id=', @last_id, '&token=4fc0b1fe18d81c78'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('906', 'Tầng 9', 3, @room_type, 906, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'c3638ef07d7bc32b', CONCAT('/qr/menu?table_id=', @last_id, '&token=c3638ef07d7bc32b'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('907', 'Tầng 9', 3, @room_type, 907, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '3809712994cd02ef', CONCAT('/qr/menu?table_id=', @last_id, '&token=3809712994cd02ef'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('908', 'Tầng 9', 3, @room_type, 908, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '7a74527b2009b5a5', CONCAT('/qr/menu?table_id=', @last_id, '&token=7a74527b2009b5a5'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('909', 'Tầng 9', 3, @room_type, 909, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'f51d7306e4dc925f', CONCAT('/qr/menu?table_id=', @last_id, '&token=f51d7306e4dc925f'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('910', 'Tầng 9', 3, @room_type, 910, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'b1a87e6d6764b941', CONCAT('/qr/menu?table_id=', @last_id, '&token=b1a87e6d6764b941'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('911', 'Tầng 9', 3, @room_type, 911, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '82e4d7906a47e552', CONCAT('/qr/menu?table_id=', @last_id, '&token=82e4d7906a47e552'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('912', 'Tầng 9', 3, @room_type, 912, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'd5fd4cd7eeeef75e', CONCAT('/qr/menu?table_id=', @last_id, '&token=d5fd4cd7eeeef75e'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('914', 'Tầng 9', 3, @room_type, 914, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'f981cce0db368d4a', CONCAT('/qr/menu?table_id=', @last_id, '&token=f981cce0db368d4a'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('915', 'Tầng 9', 3, @room_type, 915, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '985dd32e8e604f69', CONCAT('/qr/menu?table_id=', @last_id, '&token=985dd32e8e604f69'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('916', 'Tầng 9', 3, @room_type, 916, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'ea2a85504b95774c', CONCAT('/qr/menu?table_id=', @last_id, '&token=ea2a85504b95774c'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('917', 'Tầng 9', 3, @room_type, 917, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'b73b36e42a9383e2', CONCAT('/qr/menu?table_id=', @last_id, '&token=b73b36e42a9383e2'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('918', 'Tầng 9', 3, @room_type, 918, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '9b92fd6e9bb018c4', CONCAT('/qr/menu?table_id=', @last_id, '&token=9b92fd6e9bb018c4'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('919', 'Tầng 9', 3, @room_type, 919, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '2e0c849dc5f4f53b', CONCAT('/qr/menu?table_id=', @last_id, '&token=2e0c849dc5f4f53b'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('920', 'Tầng 9', 3, @room_type, 920, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '8ad797dd672ed009', CONCAT('/qr/menu?table_id=', @last_id, '&token=8ad797dd672ed009'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('921', 'Tầng 9', 3, @room_type, 921, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '089841227cb178db', CONCAT('/qr/menu?table_id=', @last_id, '&token=089841227cb178db'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('922', 'Tầng 9', 3, @room_type, 922, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '26fd125c7a3331ff', CONCAT('/qr/menu?table_id=', @last_id, '&token=26fd125c7a3331ff'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('923', 'Tầng 9', 3, @room_type, 923, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'd6371142860f6122', CONCAT('/qr/menu?table_id=', @last_id, '&token=d6371142860f6122'), 1, 0);

-- Tầng 10
INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1001', 'Tầng 10', 3, @room_type, 1001, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'a360f28effd85274', CONCAT('/qr/menu?table_id=', @last_id, '&token=a360f28effd85274'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1002', 'Tầng 10', 3, @room_type, 1002, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '4a439ea1b5b57cf3', CONCAT('/qr/menu?table_id=', @last_id, '&token=4a439ea1b5b57cf3'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1003', 'Tầng 10', 3, @room_type, 1003, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'e3b90f280f1b1b57', CONCAT('/qr/menu?table_id=', @last_id, '&token=e3b90f280f1b1b57'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1004', 'Tầng 10', 3, @room_type, 1004, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'a9e381d167cd5517', CONCAT('/qr/menu?table_id=', @last_id, '&token=a9e381d167cd5517'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1005', 'Tầng 10', 3, @room_type, 1005, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '5af9d55f5dcd7ebc', CONCAT('/qr/menu?table_id=', @last_id, '&token=5af9d55f5dcd7ebc'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1006', 'Tầng 10', 3, @room_type, 1006, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '9c19aa9156abc054', CONCAT('/qr/menu?table_id=', @last_id, '&token=9c19aa9156abc054'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1007', 'Tầng 10', 3, @room_type, 1007, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'ab52ff26a6135a02', CONCAT('/qr/menu?table_id=', @last_id, '&token=ab52ff26a6135a02'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1008', 'Tầng 10', 3, @room_type, 1008, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '51a60abb586ec804', CONCAT('/qr/menu?table_id=', @last_id, '&token=51a60abb586ec804'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1009', 'Tầng 10', 3, @room_type, 1009, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '359da1f2922add9b', CONCAT('/qr/menu?table_id=', @last_id, '&token=359da1f2922add9b'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1010', 'Tầng 10', 3, @room_type, 1010, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '034e773990726935', CONCAT('/qr/menu?table_id=', @last_id, '&token=034e773990726935'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1011', 'Tầng 10', 3, @room_type, 1011, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'b10d468dc981f060', CONCAT('/qr/menu?table_id=', @last_id, '&token=b10d468dc981f060'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1012', 'Tầng 10', 3, @room_type, 1012, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '7d58b9071d32e266', CONCAT('/qr/menu?table_id=', @last_id, '&token=7d58b9071d32e266'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1014', 'Tầng 10', 3, @room_type, 1014, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'f6585ef9901dda4b', CONCAT('/qr/menu?table_id=', @last_id, '&token=f6585ef9901dda4b'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1015', 'Tầng 10', 3, @room_type, 1015, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'aea6537ee1c2294e', CONCAT('/qr/menu?table_id=', @last_id, '&token=aea6537ee1c2294e'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1016', 'Tầng 10', 3, @room_type, 1016, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '655a2f809d63ea7c', CONCAT('/qr/menu?table_id=', @last_id, '&token=655a2f809d63ea7c'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1017', 'Tầng 10', 3, @room_type, 1017, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '1f98a8d7975d43f8', CONCAT('/qr/menu?table_id=', @last_id, '&token=1f98a8d7975d43f8'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1018', 'Tầng 10', 3, @room_type, 1018, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '2483e91d6c237f05', CONCAT('/qr/menu?table_id=', @last_id, '&token=2483e91d6c237f05'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1019', 'Tầng 10', 3, @room_type, 1019, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '4b2769ec186f67fb', CONCAT('/qr/menu?table_id=', @last_id, '&token=4b2769ec186f67fb'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1020', 'Tầng 10', 3, @room_type, 1020, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '8ca4ee11259979ac', CONCAT('/qr/menu?table_id=', @last_id, '&token=8ca4ee11259979ac'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1021', 'Tầng 10', 3, @room_type, 1021, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'ffe89a15d8a31a0a', CONCAT('/qr/menu?table_id=', @last_id, '&token=ffe89a15d8a31a0a'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1022', 'Tầng 10', 3, @room_type, 1022, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '48020fe7a6636aa6', CONCAT('/qr/menu?table_id=', @last_id, '&token=48020fe7a6636aa6'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1023', 'Tầng 10', 3, @room_type, 1023, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '8734823bcb35528f', CONCAT('/qr/menu?table_id=', @last_id, '&token=8734823bcb35528f'), 1, 0);

-- Tầng 11
INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1101', 'Tầng 11', 3, @room_type, 1101, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '02edc2cdd46e23e7', CONCAT('/qr/menu?table_id=', @last_id, '&token=02edc2cdd46e23e7'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1102', 'Tầng 11', 3, @room_type, 1102, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'd0dfdb5d9e115095', CONCAT('/qr/menu?table_id=', @last_id, '&token=d0dfdb5d9e115095'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1103', 'Tầng 11', 3, @room_type, 1103, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'a0137c7b91504f1e', CONCAT('/qr/menu?table_id=', @last_id, '&token=a0137c7b91504f1e'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1104', 'Tầng 11', 3, @room_type, 1104, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'a02d7469f6e0dd20', CONCAT('/qr/menu?table_id=', @last_id, '&token=a02d7469f6e0dd20'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1105', 'Tầng 11', 3, @room_type, 1105, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '649149ca345b4160', CONCAT('/qr/menu?table_id=', @last_id, '&token=649149ca345b4160'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1106', 'Tầng 11', 3, @room_type, 1106, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'f0465a11d6b4272e', CONCAT('/qr/menu?table_id=', @last_id, '&token=f0465a11d6b4272e'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1107', 'Tầng 11', 3, @room_type, 1107, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '1df608f4639b6ec0', CONCAT('/qr/menu?table_id=', @last_id, '&token=1df608f4639b6ec0'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1108', 'Tầng 11', 3, @room_type, 1108, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'dace4fa032fb04d6', CONCAT('/qr/menu?table_id=', @last_id, '&token=dace4fa032fb04d6'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1109', 'Tầng 11', 3, @room_type, 1109, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '0664ef3383a3e360', CONCAT('/qr/menu?table_id=', @last_id, '&token=0664ef3383a3e360'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1110', 'Tầng 11', 3, @room_type, 1110, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '5b3a83133b76c46a', CONCAT('/qr/menu?table_id=', @last_id, '&token=5b3a83133b76c46a'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1111', 'Tầng 11', 3, @room_type, 1111, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '52391608aea18a91', CONCAT('/qr/menu?table_id=', @last_id, '&token=52391608aea18a91'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1112', 'Tầng 11', 3, @room_type, 1112, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '4b02b5f702907076', CONCAT('/qr/menu?table_id=', @last_id, '&token=4b02b5f702907076'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1114', 'Tầng 11', 3, @room_type, 1114, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '5b02d423d3932f72', CONCAT('/qr/menu?table_id=', @last_id, '&token=5b02d423d3932f72'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1115', 'Tầng 11', 3, @room_type, 1115, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '15bcfd9fcd46aef6', CONCAT('/qr/menu?table_id=', @last_id, '&token=15bcfd9fcd46aef6'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1116', 'Tầng 11', 3, @room_type, 1116, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '6e9aa9be07f4c1a2', CONCAT('/qr/menu?table_id=', @last_id, '&token=6e9aa9be07f4c1a2'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1117', 'Tầng 11', 3, @room_type, 1117, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'ebb961ad40b4b458', CONCAT('/qr/menu?table_id=', @last_id, '&token=ebb961ad40b4b458'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1118', 'Tầng 11', 3, @room_type, 1118, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'f93defe35e2b5e2c', CONCAT('/qr/menu?table_id=', @last_id, '&token=f93defe35e2b5e2c'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1119', 'Tầng 11', 3, @room_type, 1119, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '0f9d317c176585de', CONCAT('/qr/menu?table_id=', @last_id, '&token=0f9d317c176585de'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1120', 'Tầng 11', 3, @room_type, 1120, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '87799a8c8530ab8a', CONCAT('/qr/menu?table_id=', @last_id, '&token=87799a8c8530ab8a'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1121', 'Tầng 11', 3, @room_type, 1121, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'ccc7cbdb8fb17a1d', CONCAT('/qr/menu?table_id=', @last_id, '&token=ccc7cbdb8fb17a1d'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1122', 'Tầng 11', 3, @room_type, 1122, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '1f5f5e064c5034b4', CONCAT('/qr/menu?table_id=', @last_id, '&token=1f5f5e064c5034b4'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1123', 'Tầng 11', 3, @room_type, 1123, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '61faeb1174de05c8', CONCAT('/qr/menu?table_id=', @last_id, '&token=61faeb1174de05c8'), 1, 0);

-- Tầng 12
INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1201', 'Tầng 12', 3, @room_type, 1201, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'b15b850ac8b8f12b', CONCAT('/qr/menu?table_id=', @last_id, '&token=b15b850ac8b8f12b'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1202', 'Tầng 12', 3, @room_type, 1202, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '78fe9bdc93f6719c', CONCAT('/qr/menu?table_id=', @last_id, '&token=78fe9bdc93f6719c'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1203', 'Tầng 12', 3, @room_type, 1203, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '274b95f25a16a80b', CONCAT('/qr/menu?table_id=', @last_id, '&token=274b95f25a16a80b'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1204', 'Tầng 12', 3, @room_type, 1204, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '1af9aa3abe11df6f', CONCAT('/qr/menu?table_id=', @last_id, '&token=1af9aa3abe11df6f'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1205', 'Tầng 12', 3, @room_type, 1205, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'b10f738ce6257fdb', CONCAT('/qr/menu?table_id=', @last_id, '&token=b10f738ce6257fdb'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1206', 'Tầng 12', 3, @room_type, 1206, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'a048f44f871ee2ff', CONCAT('/qr/menu?table_id=', @last_id, '&token=a048f44f871ee2ff'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1207', 'Tầng 12', 3, @room_type, 1207, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '93b504b82015bb52', CONCAT('/qr/menu?table_id=', @last_id, '&token=93b504b82015bb52'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1208', 'Tầng 12', 3, @room_type, 1208, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '80f671389a759040', CONCAT('/qr/menu?table_id=', @last_id, '&token=80f671389a759040'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1209', 'Tầng 12', 3, @room_type, 1209, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '0afc5abc4ce9ed80', CONCAT('/qr/menu?table_id=', @last_id, '&token=0afc5abc4ce9ed80'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1210', 'Tầng 12', 3, @room_type, 1210, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'cebfa40fde3d7b6c', CONCAT('/qr/menu?table_id=', @last_id, '&token=cebfa40fde3d7b6c'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1211', 'Tầng 12', 3, @room_type, 1211, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '37005f25d1110625', CONCAT('/qr/menu?table_id=', @last_id, '&token=37005f25d1110625'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1212', 'Tầng 12', 3, @room_type, 1212, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '70581305893b178f', CONCAT('/qr/menu?table_id=', @last_id, '&token=70581305893b178f'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1214', 'Tầng 12', 3, @room_type, 1214, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '12d88e7d24328753', CONCAT('/qr/menu?table_id=', @last_id, '&token=12d88e7d24328753'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1215', 'Tầng 12', 3, @room_type, 1215, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '0109ebc5b5e3faa3', CONCAT('/qr/menu?table_id=', @last_id, '&token=0109ebc5b5e3faa3'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1216', 'Tầng 12', 3, @room_type, 1216, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '2c40d8b36758f2c1', CONCAT('/qr/menu?table_id=', @last_id, '&token=2c40d8b36758f2c1'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1217', 'Tầng 12', 3, @room_type, 1217, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '924a996cdbdcfc20', CONCAT('/qr/menu?table_id=', @last_id, '&token=924a996cdbdcfc20'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1218', 'Tầng 12', 3, @room_type, 1218, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, 'f497d7398ec67814', CONCAT('/qr/menu?table_id=', @last_id, '&token=f497d7398ec67814'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1219', 'Tầng 12', 3, @room_type, 1219, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '65e769cf8788a618', CONCAT('/qr/menu?table_id=', @last_id, '&token=65e769cf8788a618'), 1, 0);

INSERT INTO tables (name, area, capacity, type, sort_order, is_active) VALUES ('1220', 'Tầng 12', 3, @room_type, 1220, 1);
SET @last_id = LAST_INSERT_ID();
INSERT INTO qr_tables (table_id, qr_hash, qr_code, is_active, is_printed) VALUES (@last_id, '1920651ef4efc275', CONCAT('/qr/menu?table_id=', @last_id, '&token=1920651ef4efc275'), 1, 0);

