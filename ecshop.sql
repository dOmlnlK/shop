/*
Navicat MySQL Data Transfer

Source Server         : ll
Source Server Version : 50640
Source Host           : localhost:3306
Source Database       : ecshop

Target Server Type    : MYSQL
Target Server Version : 50640
File Encoding         : 65001

Date: 2019-02-21 14:32:53
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ec_admin`
-- ----------------------------
DROP TABLE IF EXISTS `ec_admin`;
CREATE TABLE `ec_admin` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `username` varchar(30) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='管理员';

-- ----------------------------
-- Records of ec_admin
-- ----------------------------
INSERT INTO `ec_admin` VALUES ('1', 'root', '202cb962ac59075b964b07152d234b70');
INSERT INTO `ec_admin` VALUES ('4', 'test', '698d51a19d8a121ce581499d7b701668');
INSERT INTO `ec_admin` VALUES ('5', '', '');

-- ----------------------------
-- Table structure for `ec_admin_role`
-- ----------------------------
DROP TABLE IF EXISTS `ec_admin_role`;
CREATE TABLE `ec_admin_role` (
  `admin_id` mediumint(8) unsigned NOT NULL COMMENT '管理员id',
  `role_id` mediumint(8) unsigned NOT NULL COMMENT '角色id',
  KEY `admin_id` (`admin_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员角色';

-- ----------------------------
-- Records of ec_admin_role
-- ----------------------------
INSERT INTO `ec_admin_role` VALUES ('4', '1');

-- ----------------------------
-- Table structure for `ec_attribute`
-- ----------------------------
DROP TABLE IF EXISTS `ec_attribute`;
CREATE TABLE `ec_attribute` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `attr_name` varchar(30) NOT NULL COMMENT '属性名称',
  `attr_type` enum('唯一','可选') NOT NULL COMMENT '属性类型',
  `attr_option_values` varchar(300) NOT NULL DEFAULT '' COMMENT '属性可选值',
  `type_id` mediumint(8) unsigned NOT NULL COMMENT '所属类型Id',
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='属性表';

-- ----------------------------
-- Records of ec_attribute
-- ----------------------------
INSERT INTO `ec_attribute` VALUES ('8', '颜色', '可选', '黑色,白色', '3');
INSERT INTO `ec_attribute` VALUES ('9', '内存', '可选', '32G,64G,128G', '3');
INSERT INTO `ec_attribute` VALUES ('10', 'cpu', '唯一', '', '3');
INSERT INTO `ec_attribute` VALUES ('11', '颜色', '可选', '黑色,蓝色,紫色', '4');
INSERT INTO `ec_attribute` VALUES ('12', '材质', '唯一', '', '4');
INSERT INTO `ec_attribute` VALUES ('13', '包装', '可选', '大包,中包,小包', '5');
INSERT INTO `ec_attribute` VALUES ('14', '材质', '唯一', '塑料,金属', '3');
INSERT INTO `ec_attribute` VALUES ('15', '摄像头', '唯一', '', '3');
INSERT INTO `ec_attribute` VALUES ('16', '尺寸', '可选', 'S,M,L,XL,XXL', '4');
INSERT INTO `ec_attribute` VALUES ('17', '款式', '唯一', '', '4');

-- ----------------------------
-- Table structure for `ec_brand`
-- ----------------------------
DROP TABLE IF EXISTS `ec_brand`;
CREATE TABLE `ec_brand` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `brand_name` varchar(30) NOT NULL COMMENT '品牌名称',
  `site_url` varchar(150) NOT NULL DEFAULT '' COMMENT '官方网址',
  `logo` varchar(150) NOT NULL DEFAULT '' COMMENT '品牌Logo图片',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='品牌';

-- ----------------------------
-- Records of ec_brand
-- ----------------------------
INSERT INTO `ec_brand` VALUES ('1', 'gucci', 'www.gucci.com', 'Brand/2019-02-12/5c626922b3336.jpg');
INSERT INTO `ec_brand` VALUES ('2', 'LV', 'www.lv.com', 'Brand/2019-02-12/5c626929acf64.jpg');
INSERT INTO `ec_brand` VALUES ('3', 'adidas', 'www.adidas.com', 'Brand/2019-02-21/5c6e41e2702de.png');
INSERT INTO `ec_brand` VALUES ('4', 'fender', 'www.fender.com', 'Brand/2019-02-21/5c6e4218d4fbe.png');

-- ----------------------------
-- Table structure for `ec_cart`
-- ----------------------------
DROP TABLE IF EXISTS `ec_cart`;
CREATE TABLE `ec_cart` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品Id',
  `goods_attr_id` varchar(150) NOT NULL DEFAULT '' COMMENT '商品属性Id',
  `goods_number` mediumint(8) unsigned NOT NULL COMMENT '购买的数量',
  `member_id` mediumint(8) unsigned NOT NULL COMMENT '会员Id',
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='购物车';

-- ----------------------------
-- Records of ec_cart
-- ----------------------------

-- ----------------------------
-- Table structure for `ec_category`
-- ----------------------------
DROP TABLE IF EXISTS `ec_category`;
CREATE TABLE `ec_category` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `cat_name` varchar(30) NOT NULL COMMENT '分类名称',
  `parent_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类的Id,0:顶级分类',
  `is_floor` enum('是','否') NOT NULL DEFAULT '否' COMMENT '是否推荐楼层',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COMMENT='分类';

-- ----------------------------
-- Records of ec_category
-- ----------------------------
INSERT INTO `ec_category` VALUES ('4', '家居、家具、家装、厨具', '0', '否');
INSERT INTO `ec_category` VALUES ('5', '男装、女装、内衣、珠宝', '0', '否');
INSERT INTO `ec_category` VALUES ('6', '个护化妆', '0', '否');
INSERT INTO `ec_category` VALUES ('8', '运动户外', '0', '否');
INSERT INTO `ec_category` VALUES ('9', '汽车、汽车用品', '0', '否');
INSERT INTO `ec_category` VALUES ('10', '母婴、玩具乐器', '0', '否');
INSERT INTO `ec_category` VALUES ('11', '食品、酒类、生鲜、特产', '0', '否');
INSERT INTO `ec_category` VALUES ('12', '营养保健', '0', '否');
INSERT INTO `ec_category` VALUES ('13', '图书、音像、电子书', '0', '否');
INSERT INTO `ec_category` VALUES ('14', '彩票、旅行、充值、票务', '0', '否');
INSERT INTO `ec_category` VALUES ('15', '理财、众筹、白条、保险', '0', '否');
INSERT INTO `ec_category` VALUES ('20', '五金家装', '1', '否');
INSERT INTO `ec_category` VALUES ('21', 'iphone', '2', '否');
INSERT INTO `ec_category` VALUES ('23', '大家电', '27', '否');
INSERT INTO `ec_category` VALUES ('24', '手机', '27', '是');
INSERT INTO `ec_category` VALUES ('25', '数码', '27', '是');
INSERT INTO `ec_category` VALUES ('26', 'PSP游戏机', '25', '否');
INSERT INTO `ec_category` VALUES ('27', '家用电器', '0', '是');
INSERT INTO `ec_category` VALUES ('28', '羽绒服', '5', '否');
INSERT INTO `ec_category` VALUES ('29', '鞋子', '5', '是');
INSERT INTO `ec_category` VALUES ('30', '电子产品', '27', '否');
INSERT INTO `ec_category` VALUES ('31', '短袖衣服', '5', '否');
INSERT INTO `ec_category` VALUES ('32', '床', '4', '否');
INSERT INTO `ec_category` VALUES ('33', '衣柜', '4', '否');
INSERT INTO `ec_category` VALUES ('34', '电视', '23', '是');
INSERT INTO `ec_category` VALUES ('35', '项链', '5', '否');
INSERT INTO `ec_category` VALUES ('36', '面膜', '6', '否');
INSERT INTO `ec_category` VALUES ('37', '登山鞋', '8', '否');
INSERT INTO `ec_category` VALUES ('38', '汽车坐垫', '9', '否');
INSERT INTO `ec_category` VALUES ('39', '脑白金', '12', '否');
INSERT INTO `ec_category` VALUES ('40', '漫画书', '13', '否');
INSERT INTO `ec_category` VALUES ('41', '辣条', '11', '否');
INSERT INTO `ec_category` VALUES ('42', '编程', '13', '否');

-- ----------------------------
-- Table structure for `ec_comment`
-- ----------------------------
DROP TABLE IF EXISTS `ec_comment`;
CREATE TABLE `ec_comment` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品Id',
  `member_id` mediumint(8) unsigned NOT NULL COMMENT '会员Id',
  `content` varchar(200) NOT NULL COMMENT '内容',
  `addtime` datetime NOT NULL COMMENT '发表时间',
  `star` tinyint(3) unsigned NOT NULL COMMENT '分值',
  `click_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '有用的数字',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COMMENT='评论';

-- ----------------------------
-- Records of ec_comment
-- ----------------------------
INSERT INTO `ec_comment` VALUES ('1', '17', '4', '我哭了 你呢', '2019-02-14 17:27:51', '5', '0');
INSERT INTO `ec_comment` VALUES ('2', '17', '4', '去去去去', '2019-02-14 18:54:46', '4', '0');
INSERT INTO `ec_comment` VALUES ('3', '17', '4', '去去去去', '2019-02-14 18:54:51', '4', '0');
INSERT INTO `ec_comment` VALUES ('4', '17', '4', 'www', '2019-02-14 18:56:43', '4', '0');
INSERT INTO `ec_comment` VALUES ('5', '17', '4', '呜呜呜', '2019-02-14 18:57:08', '5', '0');
INSERT INTO `ec_comment` VALUES ('6', '17', '4', '还可以', '2019-02-14 18:58:27', '3', '0');
INSERT INTO `ec_comment` VALUES ('7', '17', '4', '呵呵', '2019-02-14 18:58:40', '4', '0');
INSERT INTO `ec_comment` VALUES ('8', '17', '4', '啊啊啊', '2019-02-14 18:58:55', '2', '0');
INSERT INTO `ec_comment` VALUES ('9', '17', '4', '测', '2019-02-14 19:01:44', '3', '0');
INSERT INTO `ec_comment` VALUES ('10', '17', '4', '噩噩噩噩噩', '2019-02-14 19:01:53', '4', '0');
INSERT INTO `ec_comment` VALUES ('11', '17', '4', '呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜呜', '2019-02-14 19:02:01', '5', '0');
INSERT INTO `ec_comment` VALUES ('12', '17', '4', '完美啊', '2019-02-14 19:03:04', '3', '0');
INSERT INTO `ec_comment` VALUES ('13', '17', '4', '还可以', '2019-02-14 19:03:13', '1', '0');
INSERT INTO `ec_comment` VALUES ('14', '17', '4', '呵呵呵', '2019-02-14 19:03:31', '4', '0');
INSERT INTO `ec_comment` VALUES ('15', '17', '4', '11', '2019-02-14 19:40:11', '5', '0');
INSERT INTO `ec_comment` VALUES ('16', '3', '4', '嗯嗯呐', '2019-02-14 19:41:54', '5', '0');
INSERT INTO `ec_comment` VALUES ('17', '2', '4', 'ooo', '2019-02-15 12:26:23', '1', '0');
INSERT INTO `ec_comment` VALUES ('18', '17', '4', 'test', '2019-02-15 13:45:47', '2', '0');
INSERT INTO `ec_comment` VALUES ('19', '17', '4', 'test2', '2019-02-15 13:46:36', '4', '0');
INSERT INTO `ec_comment` VALUES ('20', '17', '4', '♪(´ε｀)', '2019-02-15 13:48:21', '5', '0');
INSERT INTO `ec_comment` VALUES ('21', '17', '4', 'test3', '2019-02-15 13:49:15', '2', '0');
INSERT INTO `ec_comment` VALUES ('22', '17', '4', '切切切', '2019-02-15 13:51:21', '4', '0');
INSERT INTO `ec_comment` VALUES ('23', '17', '4', 'qqq', '2019-02-15 13:52:51', '4', '0');
INSERT INTO `ec_comment` VALUES ('24', '17', '4', 'qqq', '2019-02-15 14:00:38', '5', '0');
INSERT INTO `ec_comment` VALUES ('25', '17', '4', 'nnn', '2019-02-15 14:00:54', '1', '0');
INSERT INTO `ec_comment` VALUES ('26', '17', '4', 'qqq', '2019-02-15 15:19:10', '5', '0');
INSERT INTO `ec_comment` VALUES ('27', '17', '4', 'qqq', '2019-02-15 15:21:00', '3', '0');
INSERT INTO `ec_comment` VALUES ('28', '17', '4', '新评论', '2019-02-15 15:22:13', '1', '0');

-- ----------------------------
-- Table structure for `ec_comment_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ec_comment_reply`;
CREATE TABLE `ec_comment_reply` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `comment_id` mediumint(8) unsigned NOT NULL COMMENT '评论Id',
  `member_id` mediumint(8) unsigned NOT NULL COMMENT '会员Id',
  `content` varchar(200) NOT NULL COMMENT '内容',
  `addtime` datetime NOT NULL COMMENT '发表时间',
  PRIMARY KEY (`id`),
  KEY `comment_id` (`comment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='评论回复';

-- ----------------------------
-- Records of ec_comment_reply
-- ----------------------------
INSERT INTO `ec_comment_reply` VALUES ('1', '28', '4', '亲亲亲亲亲', '2019-02-15 15:39:23');
INSERT INTO `ec_comment_reply` VALUES ('2', '28', '4', 'qqq', '2019-02-15 15:41:34');
INSERT INTO `ec_comment_reply` VALUES ('3', '28', '4', 'rrrrr', '2019-02-15 16:12:15');
INSERT INTO `ec_comment_reply` VALUES ('4', '27', '4', 'rrrr', '2019-02-15 16:12:38');
INSERT INTO `ec_comment_reply` VALUES ('5', '17', '4', 'kkk', '2019-02-15 16:13:09');
INSERT INTO `ec_comment_reply` VALUES ('6', '17', '4', 'qqq', '2019-02-15 16:13:12');

-- ----------------------------
-- Table structure for `ec_goods`
-- ----------------------------
DROP TABLE IF EXISTS `ec_goods`;
CREATE TABLE `ec_goods` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `goods_name` varchar(150) NOT NULL COMMENT '商品名称',
  `market_price` decimal(10,2) NOT NULL COMMENT '市场价格',
  `shop_price` decimal(10,2) NOT NULL COMMENT '本店价格',
  `goods_desc` longtext COMMENT '商品描述',
  `is_on_sale` enum('是','否') NOT NULL DEFAULT '是' COMMENT '是否上架',
  `is_delete` enum('是','否') NOT NULL DEFAULT '否' COMMENT '是否放到回收站',
  `addtime` datetime NOT NULL COMMENT '添加时间',
  `logo` varchar(150) NOT NULL DEFAULT '' COMMENT '原图',
  `sm_logo` varchar(150) NOT NULL DEFAULT '' COMMENT '小图',
  `mid_logo` varchar(150) NOT NULL DEFAULT '' COMMENT '中图',
  `big_logo` varchar(150) NOT NULL DEFAULT '' COMMENT '大图',
  `mbig_logo` varchar(150) NOT NULL DEFAULT '' COMMENT '更大图',
  `brand_id` int(10) unsigned NOT NULL COMMENT '品牌id',
  `cat_id` int(10) unsigned NOT NULL COMMENT '主分类id',
  `type_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '类型Id',
  `promote_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '促销价格',
  `promote_start_date` datetime NOT NULL COMMENT '促销开始时间',
  `promote_end_date` datetime NOT NULL COMMENT '促销结束时间',
  `is_new` enum('是','否') NOT NULL DEFAULT '否' COMMENT '是否新品',
  `is_hot` enum('是','否') NOT NULL DEFAULT '否' COMMENT '是否热卖',
  `is_best` enum('是','否') NOT NULL DEFAULT '否' COMMENT '是否精品',
  `is_floor` enum('是','否') NOT NULL DEFAULT '否' COMMENT '是否推荐楼层',
  `sort_num` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '权重',
  PRIMARY KEY (`id`),
  KEY `shop_price` (`shop_price`),
  KEY `addtime` (`addtime`),
  KEY `is_on_sale` (`is_on_sale`),
  KEY `cat_id` (`cat_id`),
  KEY `promote_price` (`promote_price`),
  KEY `promote_start_date` (`promote_start_date`),
  KEY `promote_end_date` (`promote_end_date`),
  KEY `is_new` (`is_new`),
  KEY `is_hot` (`is_hot`),
  KEY `is_best` (`is_best`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='商品';

-- ----------------------------
-- Records of ec_goods
-- ----------------------------
INSERT INTO `ec_goods` VALUES ('3', 'php编程', '44.00', '66.00', '<p>从入门到放弃</p>', '是', '否', '2019-02-03 21:12:03', 'Goods/2019-02-12/5c62b25f5fc84.jpg', 'Goods/2019-02-12/thumb_3_5c62b25f5fc84.jpg', 'Goods/2019-02-12/thumb_2_5c62b25f5fc84.jpg', 'Goods/2019-02-12/thumb_1_5c62b25f5fc84.jpg', 'Goods/2019-02-12/thumb_0_5c62b25f5fc84.jpg', '1', '42', '0', '0.00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '否', '否', '否', '是', '100');
INSERT INTO `ec_goods` VALUES ('6', '书籍', '122.00', '222.00', '<p><img src=\"http://img.baidu.com/hi/jx2/j_0001.gif\" alt=\"j_0001.gif\" /></p>', '是', '否', '2019-02-04 13:09:54', 'Goods/2019-02-12/5c62b23febf51.jpg', 'Goods/2019-02-12/thumb_3_5c62b23febf51.jpg', 'Goods/2019-02-12/thumb_2_5c62b23febf51.jpg', 'Goods/2019-02-12/thumb_1_5c62b23febf51.jpg', 'Goods/2019-02-12/thumb_0_5c62b23febf51.jpg', '2', '4', '0', '0.00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '否', '否', '否', '否', '100');
INSERT INTO `ec_goods` VALUES ('7', '手袋', '33333.00', '22222.00', '<p>好东西</p>', '是', '否', '2019-02-04 15:00:47', 'Goods/2019-02-12/5c62b2259d966.jpg', 'Goods/2019-02-12/thumb_3_5c62b2259d966.jpg', 'Goods/2019-02-12/thumb_2_5c62b2259d966.jpg', 'Goods/2019-02-12/thumb_1_5c62b2259d966.jpg', 'Goods/2019-02-12/thumb_0_5c62b2259d966.jpg', '1', '5', '0', '0.00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '否', '否', '是', '否', '100');
INSERT INTO `ec_goods` VALUES ('8', '皮带', '111.00', '99.00', '<p>切切切</p>', '是', '否', '2019-02-04 15:47:31', 'Goods/2019-02-12/5c62b1efafb8b.jpg', 'Goods/2019-02-12/thumb_3_5c62b1efafb8b.jpg', 'Goods/2019-02-12/thumb_2_5c62b1efafb8b.jpg', 'Goods/2019-02-12/thumb_1_5c62b1efafb8b.jpg', 'Goods/2019-02-12/thumb_0_5c62b1efafb8b.jpg', '1', '4', '0', '0.00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '否', '否', '否', '否', '100');
INSERT INTO `ec_goods` VALUES ('12', '尼采i8', '999.00', '888.00', '', '是', '否', '2019-02-06 11:57:00', 'Goods/2019-02-12/5c62b1ba9d1dc.jpg', 'Goods/2019-02-12/thumb_3_5c62b1ba9d1dc.jpg', 'Goods/2019-02-12/thumb_2_5c62b1ba9d1dc.jpg', 'Goods/2019-02-12/thumb_1_5c62b1ba9d1dc.jpg', 'Goods/2019-02-12/thumb_0_5c62b1ba9d1dc.jpg', '1', '24', '0', '0.00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '否', '否', '否', '否', '100');
INSERT INTO `ec_goods` VALUES ('14', '冰箱', '1999.00', '1888.00', '', '是', '否', '2019-02-06 23:12:50', 'Goods/2019-02-12/5c62b19747f12.jpg', 'Goods/2019-02-12/thumb_3_5c62b19747f12.jpg', 'Goods/2019-02-12/thumb_2_5c62b19747f12.jpg', 'Goods/2019-02-12/thumb_1_5c62b19747f12.jpg', 'Goods/2019-02-12/thumb_0_5c62b19747f12.jpg', '0', '4', '0', '0.00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '否', '否', '否', '否', '100');
INSERT INTO `ec_goods` VALUES ('15', '红米pad', '777.00', '666.00', '', '是', '否', '2019-02-06 23:16:20', 'Goods/2019-02-12/5c62b1747536a.jpg', 'Goods/2019-02-12/thumb_3_5c62b1747536a.jpg', 'Goods/2019-02-12/thumb_2_5c62b1747536a.jpg', 'Goods/2019-02-12/thumb_1_5c62b1747536a.jpg', 'Goods/2019-02-12/thumb_0_5c62b1747536a.jpg', '2', '23', '0', '0.00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '否', '否', '否', '否', '100');
INSERT INTO `ec_goods` VALUES ('16', '微波炉', '555.00', '444.00', '', '是', '否', '2019-02-06 23:20:52', 'Goods/2019-02-12/5c62b15359c5b.jpg', 'Goods/2019-02-12/thumb_3_5c62b15359c5b.jpg', 'Goods/2019-02-12/thumb_2_5c62b15359c5b.jpg', 'Goods/2019-02-12/thumb_1_5c62b15359c5b.jpg', 'Goods/2019-02-12/thumb_0_5c62b15359c5b.jpg', '0', '4', '0', '0.00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '否', '是', '否', '否', '100');
INSERT INTO `ec_goods` VALUES ('17', 'T恤', '1499.00', '1299.00', '<p>自杀空间男主dominik同款T恤<img src=\"http://img.baidu.com/hi/jx2/j_0002.gif\" alt=\"j_0002.gif\" /><img src=\"http://img.baidu.com/hi/jx2/j_0013.gif\" alt=\"j_0013.gif\" /><img src=\"http://img.baidu.com/hi/jx2/j_0012.gif\" alt=\"j_0012.gif\" /></p><p><img src=\"http://www.shop.com/Public/umeditor1_2_2-utf8-php/php/upload/20190212/15499683921797.jpg\" alt=\"15499683921797.jpg\" /></p>', '是', '否', '2019-02-11 19:54:30', 'Goods/2019-02-12/5c62a67697151.jpg', 'Goods/2019-02-12/thumb_3_5c62a67697151.jpg', 'Goods/2019-02-12/thumb_2_5c62a67697151.jpg', 'Goods/2019-02-12/thumb_1_5c62a67697151.jpg', 'Goods/2019-02-12/thumb_0_5c62a67697151.jpg', '1', '31', '4', '999.00', '2019-02-11 19:54:00', '2019-03-01 00:00:00', '是', '是', '是', '是', '10');
INSERT INTO `ec_goods` VALUES ('18', '电吉他', '3555.00', '3333.00', '', '是', '否', '2019-02-11 22:30:43', 'Goods/2019-02-12/5c628bdcf3a26.jpg', 'Goods/2019-02-12/thumb_3_5c628bdcf3a26.jpg', 'Goods/2019-02-12/thumb_2_5c628bdcf3a26.jpg', 'Goods/2019-02-12/thumb_1_5c628bdcf3a26.jpg', 'Goods/2019-02-12/thumb_0_5c628bdcf3a26.jpg', '3', '4', '3', '2999.00', '2019-02-11 22:30:00', '2019-02-28 00:00:00', '是', '否', '否', '是', '1');
INSERT INTO `ec_goods` VALUES ('19', 'SK2', '999.00', '888.00', '<p>好东西</p>', '是', '否', '2019-02-21 14:19:03', 'Goods/2019-02-21/5c6e42d71e33f.jpg', 'Goods/2019-02-21/sm_5c6e42d71e33f.jpg', 'Goods/2019-02-21/mid_5c6e42d71e33f.jpg', 'Goods/2019-02-21/big_5c6e42d71e33f.jpg', 'Goods/2019-02-21/mbig_5c6e42d71e33f.jpg', '2', '6', '0', '666.00', '2019-02-24 00:00:00', '2019-02-28 00:00:00', '是', '是', '是', '是', '100');

-- ----------------------------
-- Table structure for `ec_goods_attr`
-- ----------------------------
DROP TABLE IF EXISTS `ec_goods_attr`;
CREATE TABLE `ec_goods_attr` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `attr_value` varchar(150) NOT NULL DEFAULT '' COMMENT '属性值',
  `attr_id` mediumint(8) unsigned NOT NULL COMMENT '属性Id',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品Id',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`),
  KEY `attr_id` (`attr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='商品属性';

-- ----------------------------
-- Records of ec_goods_attr
-- ----------------------------
INSERT INTO `ec_goods_attr` VALUES ('1', '黑色', '11', '17');
INSERT INTO `ec_goods_attr` VALUES ('2', '蓝色', '11', '17');
INSERT INTO `ec_goods_attr` VALUES ('3', '紫色', '11', '17');
INSERT INTO `ec_goods_attr` VALUES ('4', '棉', '12', '17');
INSERT INTO `ec_goods_attr` VALUES ('5', 'S', '16', '17');
INSERT INTO `ec_goods_attr` VALUES ('6', 'M', '16', '17');
INSERT INTO `ec_goods_attr` VALUES ('7', 'L', '16', '17');
INSERT INTO `ec_goods_attr` VALUES ('8', '2019', '17', '17');

-- ----------------------------
-- Table structure for `ec_goods_cat`
-- ----------------------------
DROP TABLE IF EXISTS `ec_goods_cat`;
CREATE TABLE `ec_goods_cat` (
  `cat_id` mediumint(8) unsigned NOT NULL COMMENT '分类id',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品Id',
  KEY `goods_id` (`goods_id`),
  KEY `cat_id` (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品扩展分类';

-- ----------------------------
-- Records of ec_goods_cat
-- ----------------------------
INSERT INTO `ec_goods_cat` VALUES ('23', '16');
INSERT INTO `ec_goods_cat` VALUES ('24', '15');
INSERT INTO `ec_goods_cat` VALUES ('23', '14');
INSERT INTO `ec_goods_cat` VALUES ('27', '18');

-- ----------------------------
-- Table structure for `ec_goods_number`
-- ----------------------------
DROP TABLE IF EXISTS `ec_goods_number`;
CREATE TABLE `ec_goods_number` (
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品Id',
  `goods_number` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '库存量',
  `goods_attr_id` varchar(150) NOT NULL COMMENT '商品属性表的ID,如果有多个，就用程序拼成字符串存到这个字段中',
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='库存量';

-- ----------------------------
-- Records of ec_goods_number
-- ----------------------------
INSERT INTO `ec_goods_number` VALUES ('16', '6', '');
INSERT INTO `ec_goods_number` VALUES ('17', '208', '1,7');

-- ----------------------------
-- Table structure for `ec_impression`
-- ----------------------------
DROP TABLE IF EXISTS `ec_impression`;
CREATE TABLE `ec_impression` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品Id',
  `yx_name` varchar(30) NOT NULL COMMENT '印象名称',
  `yx_count` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '印象的次数',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='印象';

-- ----------------------------
-- Records of ec_impression
-- ----------------------------
INSERT INTO `ec_impression` VALUES ('1', '17', '质量好', '2');
INSERT INTO `ec_impression` VALUES ('2', '17', '掉色', '4');
INSERT INTO `ec_impression` VALUES ('3', '17', '质量好，偏大', '2');
INSERT INTO `ec_impression` VALUES ('4', '17', '质量不行', '1');
INSERT INTO `ec_impression` VALUES ('5', '17', '偏大', '1');
INSERT INTO `ec_impression` VALUES ('6', '17', '质量还行', '1');

-- ----------------------------
-- Table structure for `ec_member`
-- ----------------------------
DROP TABLE IF EXISTS `ec_member`;
CREATE TABLE `ec_member` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `username` varchar(30) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `avatar` varchar(150) NOT NULL DEFAULT '' COMMENT '头像',
  `jifen` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='会员';

-- ----------------------------
-- Records of ec_member
-- ----------------------------
INSERT INTO `ec_member` VALUES ('4', 'dick', '96e79218965eb72c92a549dd5a330112', '', '0');

-- ----------------------------
-- Table structure for `ec_member_level`
-- ----------------------------
DROP TABLE IF EXISTS `ec_member_level`;
CREATE TABLE `ec_member_level` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `level_name` varchar(30) NOT NULL COMMENT '级别名称',
  `jifen_bottom` mediumint(8) unsigned NOT NULL COMMENT '积分下限',
  `jifen_top` mediumint(8) unsigned NOT NULL COMMENT '积分上限',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='会员级别';

-- ----------------------------
-- Records of ec_member_level
-- ----------------------------
INSERT INTO `ec_member_level` VALUES ('1', '注册会员', '0', '5000');
INSERT INTO `ec_member_level` VALUES ('2', '初级会员', '5001', '10000');
INSERT INTO `ec_member_level` VALUES ('3', '中级会员', '10001', '15000');
INSERT INTO `ec_member_level` VALUES ('4', '高级会员', '15001', '20000');

-- ----------------------------
-- Table structure for `ec_member_price`
-- ----------------------------
DROP TABLE IF EXISTS `ec_member_price`;
CREATE TABLE `ec_member_price` (
  `price` decimal(10,2) NOT NULL COMMENT '会员价格',
  `level_id` mediumint(8) unsigned NOT NULL COMMENT '级别Id',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品Id',
  KEY `level_id` (`level_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员价格';

-- ----------------------------
-- Records of ec_member_price
-- ----------------------------
INSERT INTO `ec_member_price` VALUES ('111.00', '1', '17');
INSERT INTO `ec_member_price` VALUES ('1000.00', '2', '17');
INSERT INTO `ec_member_price` VALUES ('888.00', '3', '17');
INSERT INTO `ec_member_price` VALUES ('799.00', '4', '17');

-- ----------------------------
-- Table structure for `ec_order`
-- ----------------------------
DROP TABLE IF EXISTS `ec_order`;
CREATE TABLE `ec_order` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `member_id` mediumint(8) unsigned NOT NULL COMMENT '会员Id',
  `addtime` int(10) unsigned NOT NULL COMMENT '下单时间',
  `pay_status` enum('是','否') NOT NULL DEFAULT '否' COMMENT '支付状态',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支付时间',
  `total_price` decimal(10,2) NOT NULL COMMENT '定单总价',
  `shr_name` varchar(30) NOT NULL COMMENT '收货人姓名',
  `shr_tel` varchar(30) NOT NULL COMMENT '收货人电话',
  `shr_province` varchar(30) NOT NULL COMMENT '收货人省',
  `shr_city` varchar(30) NOT NULL COMMENT '收货人城市',
  `shr_area` varchar(30) NOT NULL COMMENT '收货人地区',
  `shr_address` varchar(30) NOT NULL COMMENT '收货人详细地址',
  `post_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '发货状态,0:未发货,1:已发货2:已收到货',
  `post_number` varchar(30) NOT NULL DEFAULT '' COMMENT '快递号',
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`),
  KEY `addtime` (`addtime`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='定单基本信息';

-- ----------------------------
-- Records of ec_order
-- ----------------------------
INSERT INTO `ec_order` VALUES ('1', '4', '1550065329', '否', '0', '444.00', '李白', '10086', '北京', '朝阳区', '西二旗', '三环路', '0', '');
INSERT INTO `ec_order` VALUES ('2', '4', '1550065473', '否', '0', '111.00', '李白', '10086', '北京', '朝阳区', '西二旗', '三环路', '0', '');
INSERT INTO `ec_order` VALUES ('3', '4', '1550065773', '否', '0', '111.00', '李白', '10086', '北京', '朝阳区', '西二旗', '三环路', '0', '');
INSERT INTO `ec_order` VALUES ('5', '4', '1550066114', '否', '0', '111.00', '李白', '10086', '北京', '朝阳区', '西二旗', '三环路', '0', '');
INSERT INTO `ec_order` VALUES ('6', '4', '1550066569', '否', '0', '555.00', '李白', '10086', '北京', '东城区', '三环以内', '三环路', '0', '');
INSERT INTO `ec_order` VALUES ('7', '4', '1550119917', '否', '0', '111.00', '李白', '10086', '上海', '东城区', '西三旗', '三环路', '0', '');

-- ----------------------------
-- Table structure for `ec_order_goods`
-- ----------------------------
DROP TABLE IF EXISTS `ec_order_goods`;
CREATE TABLE `ec_order_goods` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `order_id` mediumint(8) unsigned NOT NULL COMMENT '定单Id',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品Id',
  `goods_attr_id` varchar(150) NOT NULL DEFAULT '' COMMENT '商品属性id',
  `goods_number` mediumint(8) unsigned NOT NULL COMMENT '购买的数量',
  `price` decimal(10,2) NOT NULL COMMENT '购买的价格',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='定单商品表';

-- ----------------------------
-- Records of ec_order_goods
-- ----------------------------
INSERT INTO `ec_order_goods` VALUES ('1', '1', '16', '', '8', '444.00');
INSERT INTO `ec_order_goods` VALUES ('2', '2', '17', '1,7', '3', '111.00');
INSERT INTO `ec_order_goods` VALUES ('3', '3', '17', '1,7', '5', '111.00');
INSERT INTO `ec_order_goods` VALUES ('5', '5', '17', '1,7', '4', '111.00');
INSERT INTO `ec_order_goods` VALUES ('6', '6', '17', '1,7', '1', '111.00');
INSERT INTO `ec_order_goods` VALUES ('7', '6', '16', '', '1', '444.00');
INSERT INTO `ec_order_goods` VALUES ('8', '7', '17', '1,7', '1', '111.00');

-- ----------------------------
-- Table structure for `ec_privilege`
-- ----------------------------
DROP TABLE IF EXISTS `ec_privilege`;
CREATE TABLE `ec_privilege` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `pri_name` varchar(30) NOT NULL COMMENT '权限名称',
  `module_name` varchar(30) NOT NULL DEFAULT '' COMMENT '模块名称',
  `controller_name` varchar(30) NOT NULL DEFAULT '' COMMENT '控制器名称',
  `action_name` varchar(30) NOT NULL DEFAULT '' COMMENT '方法名称',
  `parent_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '上级权限Id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COMMENT='权限';

-- ----------------------------
-- Records of ec_privilege
-- ----------------------------
INSERT INTO `ec_privilege` VALUES ('1', '商品模块', '', '', '', '0');
INSERT INTO `ec_privilege` VALUES ('2', '商品列表', 'Admin', 'Goods', 'lst', '1');
INSERT INTO `ec_privilege` VALUES ('3', '添加商品', 'Admin', 'Goods', 'add', '2');
INSERT INTO `ec_privilege` VALUES ('4', '修改商品', 'Admin', 'Goods', 'edit', '2');
INSERT INTO `ec_privilege` VALUES ('5', '删除商品', 'Admin', 'Goods', 'delete', '2');
INSERT INTO `ec_privilege` VALUES ('6', '分类列表', 'Admin', 'Category', 'lst', '1');
INSERT INTO `ec_privilege` VALUES ('7', '添加分类', 'Admin', 'Category', 'add', '6');
INSERT INTO `ec_privilege` VALUES ('8', '修改分类', 'Admin', 'Category', 'edit', '6');
INSERT INTO `ec_privilege` VALUES ('9', '删除分类', 'Admin', 'Category', 'delete', '6');
INSERT INTO `ec_privilege` VALUES ('10', 'RBAC', '', '', '', '0');
INSERT INTO `ec_privilege` VALUES ('11', '权限列表', 'Admin', 'Privilege', 'lst', '10');
INSERT INTO `ec_privilege` VALUES ('12', '添加权限', 'Privilege', 'Admin', 'add', '11');
INSERT INTO `ec_privilege` VALUES ('13', '修改权限', 'Admin', 'Privilege', 'edit', '11');
INSERT INTO `ec_privilege` VALUES ('14', '删除权限', 'Admin', 'Privilege', 'delete', '11');
INSERT INTO `ec_privilege` VALUES ('15', '角色列表', 'Admin', 'Role', 'lst', '10');
INSERT INTO `ec_privilege` VALUES ('16', '添加角色', 'Admin', 'Role', 'add', '15');
INSERT INTO `ec_privilege` VALUES ('17', '修改角色', 'Admin', 'Role', 'edit', '15');
INSERT INTO `ec_privilege` VALUES ('18', '删除角色', 'Admin', 'Role', 'delete', '15');
INSERT INTO `ec_privilege` VALUES ('19', '管理员列表', 'Admin', 'Admin', 'lst', '10');
INSERT INTO `ec_privilege` VALUES ('20', '添加管理员', 'Admin', 'Admin', 'add', '19');
INSERT INTO `ec_privilege` VALUES ('21', '修改管理员', 'Admin', 'Admin', 'edit', '19');
INSERT INTO `ec_privilege` VALUES ('22', '删除管理员', 'Admin', 'Admin', 'delete', '19');
INSERT INTO `ec_privilege` VALUES ('23', '类型列表', 'Admin', 'Type', 'lst', '1');
INSERT INTO `ec_privilege` VALUES ('24', '添加类型', 'Admin', 'Type', 'add', '23');
INSERT INTO `ec_privilege` VALUES ('25', '修改类型', 'Admin', 'Type', 'edit', '23');
INSERT INTO `ec_privilege` VALUES ('26', '删除类型', 'Admin', 'Type', 'delete', '23');
INSERT INTO `ec_privilege` VALUES ('27', '属性列表', 'Admin', 'Attribute', 'lst', '23');
INSERT INTO `ec_privilege` VALUES ('28', '添加属性', 'Admin', 'Attribute', 'add', '27');
INSERT INTO `ec_privilege` VALUES ('29', '修改属性', 'Admin', 'Attribute', 'edit', '27');
INSERT INTO `ec_privilege` VALUES ('30', '删除属性', 'Admin', 'Attribute', 'delete', '27');
INSERT INTO `ec_privilege` VALUES ('31', 'ajax删除商品属性', 'Admin', 'Goods', 'ajaxDelGoodsAttr', '4');
INSERT INTO `ec_privilege` VALUES ('32', 'ajax删除商品相册图片', 'Admin', 'Goods', 'ajaxDelImage', '4');
INSERT INTO `ec_privilege` VALUES ('33', '会员管理', '', '', '', '0');
INSERT INTO `ec_privilege` VALUES ('34', '会员级别列表', 'Admin', 'MemberLevel', 'lst', '33');
INSERT INTO `ec_privilege` VALUES ('35', '添加会员级别', 'Admin', 'MemberLevel', 'add', '34');
INSERT INTO `ec_privilege` VALUES ('36', '修改会员级别', 'Admin', 'MemberLevel', 'edit', '34');
INSERT INTO `ec_privilege` VALUES ('37', '删除会员级别', 'Admin', 'MemberLevel', 'delete', '34');
INSERT INTO `ec_privilege` VALUES ('38', '品牌列表', 'Admin', 'Brand', 'lst', '1');
INSERT INTO `ec_privilege` VALUES ('39', '添加品牌', 'Admin', 'Brand', 'add', '38');
INSERT INTO `ec_privilege` VALUES ('40', '修改品牌', 'Admin', 'Brand', 'edit', '38');
INSERT INTO `ec_privilege` VALUES ('41', '删除品牌', 'Admin', 'Brand', 'delete', '38');

-- ----------------------------
-- Table structure for `ec_role`
-- ----------------------------
DROP TABLE IF EXISTS `ec_role`;
CREATE TABLE `ec_role` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `role_name` varchar(30) NOT NULL COMMENT '角色名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='角色';

-- ----------------------------
-- Records of ec_role
-- ----------------------------
INSERT INTO `ec_role` VALUES ('1', '商品模块管理员');
INSERT INTO `ec_role` VALUES ('2', '超级管理员');

-- ----------------------------
-- Table structure for `ec_role_pri`
-- ----------------------------
DROP TABLE IF EXISTS `ec_role_pri`;
CREATE TABLE `ec_role_pri` (
  `pri_id` mediumint(8) unsigned NOT NULL COMMENT '权限id',
  `role_id` mediumint(8) unsigned NOT NULL COMMENT '角色id',
  KEY `pri_id` (`pri_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色权限';

-- ----------------------------
-- Records of ec_role_pri
-- ----------------------------
INSERT INTO `ec_role_pri` VALUES ('10', '2');
INSERT INTO `ec_role_pri` VALUES ('11', '2');
INSERT INTO `ec_role_pri` VALUES ('12', '2');
INSERT INTO `ec_role_pri` VALUES ('13', '2');
INSERT INTO `ec_role_pri` VALUES ('14', '2');
INSERT INTO `ec_role_pri` VALUES ('15', '2');
INSERT INTO `ec_role_pri` VALUES ('16', '2');
INSERT INTO `ec_role_pri` VALUES ('17', '2');
INSERT INTO `ec_role_pri` VALUES ('18', '2');
INSERT INTO `ec_role_pri` VALUES ('19', '2');
INSERT INTO `ec_role_pri` VALUES ('20', '2');
INSERT INTO `ec_role_pri` VALUES ('21', '2');
INSERT INTO `ec_role_pri` VALUES ('22', '2');
INSERT INTO `ec_role_pri` VALUES ('1', '1');
INSERT INTO `ec_role_pri` VALUES ('2', '1');
INSERT INTO `ec_role_pri` VALUES ('3', '1');
INSERT INTO `ec_role_pri` VALUES ('4', '1');
INSERT INTO `ec_role_pri` VALUES ('31', '1');
INSERT INTO `ec_role_pri` VALUES ('32', '1');
INSERT INTO `ec_role_pri` VALUES ('5', '1');
INSERT INTO `ec_role_pri` VALUES ('6', '1');
INSERT INTO `ec_role_pri` VALUES ('7', '1');
INSERT INTO `ec_role_pri` VALUES ('8', '1');
INSERT INTO `ec_role_pri` VALUES ('9', '1');
INSERT INTO `ec_role_pri` VALUES ('23', '1');
INSERT INTO `ec_role_pri` VALUES ('24', '1');
INSERT INTO `ec_role_pri` VALUES ('25', '1');
INSERT INTO `ec_role_pri` VALUES ('26', '1');
INSERT INTO `ec_role_pri` VALUES ('27', '1');
INSERT INTO `ec_role_pri` VALUES ('28', '1');
INSERT INTO `ec_role_pri` VALUES ('29', '1');
INSERT INTO `ec_role_pri` VALUES ('30', '1');
INSERT INTO `ec_role_pri` VALUES ('38', '1');
INSERT INTO `ec_role_pri` VALUES ('39', '1');
INSERT INTO `ec_role_pri` VALUES ('40', '1');
INSERT INTO `ec_role_pri` VALUES ('41', '1');

-- ----------------------------
-- Table structure for `ec_type`
-- ----------------------------
DROP TABLE IF EXISTS `ec_type`;
CREATE TABLE `ec_type` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `type_name` varchar(30) NOT NULL COMMENT '类型名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='类型';

-- ----------------------------
-- Records of ec_type
-- ----------------------------
INSERT INTO `ec_type` VALUES ('3', '手机');
INSERT INTO `ec_type` VALUES ('4', '服装');
INSERT INTO `ec_type` VALUES ('5', '零食');
