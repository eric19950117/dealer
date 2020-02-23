/*
 Navicat Premium Data Transfer

 Source Server         : localhost-mysql
 Source Server Type    : MySQL
 Source Server Version : 80017
 Source Host           : localhost:3306
 Source Schema         : laravel

 Target Server Type    : MySQL
 Target Server Version : 80017
 File Encoding         : 65001

 Date: 17/01/2020 15:22:55
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_groups
-- ----------------------------
DROP TABLE IF EXISTS `admin_groups`;
CREATE TABLE `admin_groups`  (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '名稱',
  `permission` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '權限',
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `deleted_at` datetime(0) NULL DEFAULT NULL,
  `created_id` int(10) NULL DEFAULT NULL,
  `updated_id` int(10) NULL DEFAULT NULL,
  `deleted_id` int(10) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_groups
-- ----------------------------
INSERT INTO `admin_groups` VALUES (1, '管理員', '[1A],[1B],[1C],[1D],[2A],[2B],[2C],[2D],[3A],[3B],[3C],[3D],', '2020-01-13 17:57:26', '2020-01-16 09:55:24', NULL, 1, 2, NULL);

-- ----------------------------
-- Table structure for admins
-- ----------------------------
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '帳號',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '密碼',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'IMB會員' COMMENT '姓名',
  `admin_group_id` int(10) NULL DEFAULT NULL COMMENT '群組ID(關連admin_groups.id)',
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '狀態(1啟用，0停用)',
  `remember_token` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '？？？',
  `block_at` timestamp(0) NULL DEFAULT NULL COMMENT '？？？',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `created_id` int(10) NULL DEFAULT NULL,
  `updated_id` int(10) NULL DEFAULT NULL,
  `deleted_id` int(10) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admins
-- ----------------------------
INSERT INTO `admins` VALUES (1, 'ben@3t9s.com', '$2y$10$udqGTsAnCcGzOO.uvYROh.AANdNI1cEgd.qKA0ZMyz5FmVF5Ym2b6', 'Likol', 1, 1, NULL, NULL, '2016-06-05 14:21:12', '2020-01-17 07:17:39', NULL, NULL, 2, NULL);
INSERT INTO `admins` VALUES (2, 'dj0935@hotmail.com', '$2y$10$udqGTsAnCcGzOO.uvYROh.AANdNI1cEgd.qKA0ZMyz5FmVF5Ym2b6', 'Zen', 1, 1, NULL, NULL, '2020-01-09 16:11:59', '2020-01-14 05:20:08', NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for sidebars
-- ----------------------------
DROP TABLE IF EXISTS `sidebars`;
CREATE TABLE `sidebars`  (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `sidebar_id` int(10) NOT NULL DEFAULT 0 COMMENT '子項目ID(關連sidebars.id)',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '名稱',
  `icon` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'ICON',
  `url` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '連結',
  `sort` int(10) NOT NULL DEFAULT 0 COMMENT '排序',
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `deleted_at` datetime(0) NULL DEFAULT NULL,
  `created_id` int(10) NULL DEFAULT NULL,
  `updated_id` int(10) NULL DEFAULT NULL,
  `deleted_id` int(10) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sidebars
-- ----------------------------
INSERT INTO `sidebars` VALUES (1, 0, '首頁', 'icon-home', '/backend/', 1, '2020-01-13 11:59:21', '2020-01-13 11:59:25', NULL, 1, 1, NULL);
INSERT INTO `sidebars` VALUES (2, 0, '後台會員管理', 'icon-user', '/backend/admin/', 2, '2020-01-13 12:00:02', '2020-01-13 12:00:06', NULL, 1, 1, NULL);
INSERT INTO `sidebars` VALUES (3, 0, '後台群組管理', 'icon-users', '/backend/admingroup/', 3, '2020-01-14 15:26:13', '2020-01-14 15:26:15', NULL, 1, 1, NULL);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '流水號',
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '帳號',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '密碼',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'IMB會員' COMMENT '姓名',
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT '狀態(0:停用，1:啟用)',
  `remember_token` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '？？？',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `block_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'ben@3t9s.com', '$2y$10$udqGTsAnCcGzOO.uvYROh.AANdNI1cEgd.qKA0ZMyz5FmVF5Ym2b6', 'Likol', 1, NULL, '2016-06-05 14:21:12', '2020-01-07 13:39:47', NULL, NULL);
INSERT INTO `users` VALUES (2, 'dj0935@hotmail.com', '$2y$10$udqGTsAnCcGzOO.uvYROh.AANdNI1cEgd.qKA0ZMyz5FmVF5Ym2b6', 'Zen', 1, NULL, '2020-01-09 16:11:59', '2020-01-09 16:12:02', NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
