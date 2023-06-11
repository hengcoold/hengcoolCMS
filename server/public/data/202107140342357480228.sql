-- SONGBO SQL Backup
-- Time:2021-07-14 03:42:35
-- 备份数据库 

--
-- 表的结构 `lv_auth_admins`
-- 
DROP TABLE IF EXISTS `lv_auth_admins`;
CREATE TABLE `lv_auth_admins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `phone` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '账号',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密码',
  `group_id` int(11) DEFAULT NULL COMMENT '权限组ID',
  `project_id` int(11) DEFAULT NULL COMMENT '项目ID',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态:0=禁用,1=启用',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `lv_auth_admins_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='管理员表';

-- 
-- 导出`lv_auth_admins`表中的数据 `lv_auth_admins`
--
INSERT INTO `lv_auth_admins` VALUES (1,'','','admin','$2y$10$gddj.QV7l7OP3I2MpgM9COcCKCBM8SMPq.xe/JrqkOXY3DlnozTP.',1,1,1,'2021-07-10 18:13:03','2021-07-12 08:34:07');
INSERT INTO `lv_auth_admins` VALUES (2,'宋博','18092444781','songbo','$2y$10$NhdagpIFbxK2zAVFeCFEa.wUrtKv.2o4aG4ZZ5W3yYB9epkx/Xm9y',2,1,1,'2021-07-12 10:25:12','2021-07-13 17:56:02');