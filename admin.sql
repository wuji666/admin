/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50642
 Source Host           : 127.0.0.1:3306
 Source Schema         : admin

 Target Server Type    : MySQL
 Target Server Version : 50642
 File Encoding         : 65001

 Date: 01/03/2020 01:59:46
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tb_admin
-- ----------------------------
DROP TABLE IF EXISTS `tb_admin`;
CREATE TABLE `tb_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '角色id',
  `username` varchar(255) NOT NULL COMMENT '用户名',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `salt` varchar(255) NOT NULL COMMENT '盐',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='管理员表';

-- ----------------------------
-- Records of tb_admin
-- ----------------------------
BEGIN;
INSERT INTO `tb_admin` VALUES (1, 0, 'admin', '2abd9755021e2ae4a9597fdb6f183b0a', 'OstSqp7JM5', 0, 0);
INSERT INTO `tb_admin` VALUES (2, 1, 'test', '2abd9755021e2ae4a9597fdb6f183b0a', 'OstSqp7JM5', 0, 1581948632);
COMMIT;

-- ----------------------------
-- Table structure for tb_menu
-- ----------------------------
DROP TABLE IF EXISTS `tb_menu`;
CREATE TABLE `tb_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '菜单路径',
  `title` varchar(255) NOT NULL COMMENT '菜单名称',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1=>显示，2=>不显示',
  `p_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级id',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COMMENT='菜单表';

-- ----------------------------
-- Records of tb_menu
-- ----------------------------
BEGIN;
INSERT INTO `tb_menu` VALUES (1, '#', '系统管理', 1, 0, 0, 0, 0);
INSERT INTO `tb_menu` VALUES (2, 'admin/menu/index', '菜单管理', 1, 1, 0, 0, 0);
INSERT INTO `tb_menu` VALUES (3, 'admin/menu/form', '编辑和添加', 0, 2, 0, 0, 0);
INSERT INTO `tb_menu` VALUES (4, 'admin/menu/save', '保存', 0, 2, 0, 0, 0);
INSERT INTO `tb_menu` VALUES (5, 'admin/menu/del', '删除', 0, 2, 0, 0, 0);
INSERT INTO `tb_menu` VALUES (6, '#', '用户管理', 1, 0, 0, 1581844588, 1581844588);
INSERT INTO `tb_menu` VALUES (49, 'admin/role/index', '角色管理', 1, 1, 0, 1581926140, 1581926140);
INSERT INTO `tb_menu` VALUES (50, 'admin/role/save', '编辑和添加', 0, 49, 0, 1581926200, 1581926228);
INSERT INTO `tb_menu` VALUES (51, 'admin/role/save', '保存', 0, 49, 0, 1581926245, 1581926245);
INSERT INTO `tb_menu` VALUES (52, 'admin/role/del', '删除', 0, 49, 0, 1581926256, 1581926256);
INSERT INTO `tb_menu` VALUES (53, 'admin/admin/index', '管理员管理', 1, 1, 0, 1581926858, 1581926858);
INSERT INTO `tb_menu` VALUES (54, 'admin/index/index', '网站信息', 1, 0, 999, 0, 0);
INSERT INTO `tb_menu` VALUES (56, 'admin/role/index', '列表', 0, 49, 0, 1582005720, 1582005720);
INSERT INTO `tb_menu` VALUES (57, 'admin/menu/index', '列表', 0, 2, 0, 1582005742, 1582005742);
INSERT INTO `tb_menu` VALUES (58, 'admin/admin/index', '列表', 0, 53, 0, 1582011246, 1582011246);
INSERT INTO `tb_menu` VALUES (59, 'admin/admin/form', '编辑和添加', 0, 53, 0, 1582011268, 1582011268);
INSERT INTO `tb_menu` VALUES (60, 'admin/admin/save', '保存', 0, 53, 0, 1582011281, 1582011281);
INSERT INTO `tb_menu` VALUES (61, 'admin/admin/del', '删除', 0, 53, 0, 1582011294, 1582011294);
COMMIT;

-- ----------------------------
-- Table structure for tb_role
-- ----------------------------
DROP TABLE IF EXISTS `tb_role`;
CREATE TABLE `tb_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '角色名称',
  `role` text COMMENT '角色权限',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='角色表';

-- ----------------------------
-- Records of tb_role
-- ----------------------------
BEGIN;
INSERT INTO `tb_role` VALUES (1, '管理员', '6,54,1,53,49,56,52,2,57', 1581927783, 1582006157);
INSERT INTO `tb_role` VALUES (2, '客服', '1,53,49,52,51,50,2,3,4,5', 1581927783, 1581945524);
COMMIT;

-- ----------------------------
-- Table structure for tb_user
-- ----------------------------
DROP TABLE IF EXISTS `tb_user`;
CREATE TABLE `tb_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户表';

SET FOREIGN_KEY_CHECKS = 1;
