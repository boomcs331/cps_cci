/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100432 (10.4.32-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : cps_cci

 Target Server Type    : MySQL
 Target Server Version : 100432 (10.4.32-MariaDB)
 File Encoding         : 65001

 Date: 30/08/2025 01:43:44
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for material_transactions
-- ----------------------------
DROP TABLE IF EXISTS `material_transactions`;
CREATE TABLE `material_transactions`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `transaction_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'เลขที่เอกสาร',
  `product_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_received` int NULL DEFAULT 0 COMMENT 'จำนวนที่รับเข้าจริง (หน่วยเต็ม)',
  `calculated_pieces` int NULL DEFAULT 0 COMMENT 'จำนวนชิ้นที่คำนวณได้ (unit_received / packing)',
  `unit` int NOT NULL COMMENT 'จำนวนสุดท้าย',
  `transaction_type` enum('IN','OUT') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ประเภทรายการ: IN=รับเข้า, OUT=จ่ายออก',
  `date_transaction` date NOT NULL COMMENT 'วันที่ทำรายการ',
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `create_date` timestamp NULL DEFAULT current_timestamp,
  `updated_date` timestamp NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  `date_revice` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_transaction_type`(`transaction_type` ASC) USING BTREE,
  INDEX `idx_date_transaction`(`date_transaction` ASC) USING BTREE,
  INDEX `idx_product_transaction`(`product_id` ASC, `transaction_type` ASC) USING BTREE,
  CONSTRAINT `fk_trans_material` FOREIGN KEY (`product_id`) REFERENCES `material` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 36 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
