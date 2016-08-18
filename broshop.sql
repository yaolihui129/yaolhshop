/*
Navicat MySQL Data Transfer

Source Server         : 43.61:3307
Source Server Version : 50532
Source Host           : localhost:3307
Source Database       : broshop

Target Server Type    : MYSQL
Target Server Version : 50532
File Encoding         : 65001

Date: 2016-08-18 11:27:28
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `bro_ad`
-- ----------------------------
DROP TABLE IF EXISTS `bro_ad`;
CREATE TABLE `bro_ad` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '广告编号',
  `logo` varchar(100) NOT NULL DEFAULT '' COMMENT '广告图片',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '广告链接',
  `position` tinyint(10) unsigned NOT NULL DEFAULT '1' COMMENT '广告位置',
  `ord` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '广告顺序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bro_ad
-- ----------------------------

-- ----------------------------
-- Table structure for `bro_admin`
-- ----------------------------
DROP TABLE IF EXISTS `bro_admin`;
CREATE TABLE `bro_admin` (
  `id` smallint(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理编号',
  `name` varchar(20) NOT NULL COMMENT '管理名',
  `pw` varchar(32) NOT NULL COMMENT '管理密码',
  `atime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理注册时间',
  `ltime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理上次登录时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bro_admin
-- ----------------------------
INSERT INTO `bro_admin` VALUES ('1', 'admin', '240e287225c3352c38a0af10a55c3b09', '1469526021', '1471488692');

-- ----------------------------
-- Table structure for `bro_article`
-- ----------------------------
DROP TABLE IF EXISTS `bro_article`;
CREATE TABLE `bro_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章编号',
  `name` varchar(100) NOT NULL COMMENT '文章标题',
  `content` text NOT NULL COMMENT '文章内容',
  `atime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章添加时间',
  `clicknum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章点击数',
  `cid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '文章分类编号',
  PRIMARY KEY (`id`),
  KEY `article_cid` (`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bro_article
-- ----------------------------
INSERT INTO `bro_article` VALUES ('1', '请问请问', '<p>\r\n	去委屈委屈我</p>\r\n', '1471489591', '0', '1');

-- ----------------------------
-- Table structure for `bro_ask`
-- ----------------------------
DROP TABLE IF EXISTS `bro_ask`;
CREATE TABLE `bro_ask` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '咨询编号',
  `asktext` text NOT NULL COMMENT '咨询内容',
  `atime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '咨询时间',
  `replytext` text NOT NULL COMMENT '回复内容',
  `replytime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '回复时间',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '咨询情况',
  `pid` int(10) unsigned NOT NULL COMMENT '产品编号',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户编号',
  `uname` varchar(20) NOT NULL COMMENT '咨询用户名',
  `uip` char(15) NOT NULL DEFAULT '0.0.0.0' COMMENT '咨询用户IP',
  PRIMARY KEY (`id`),
  KEY `ask_pid` (`pid`),
  KEY `ask_uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bro_ask
-- ----------------------------
INSERT INTO `bro_ask` VALUES ('1', '你过订两台的话是不是可以便宜一些？', '1471489112', '如果定10台以上可以按批发价走', '1471489151', '1', '1', '1', 'yaolh', '192.168.84.11');

-- ----------------------------
-- Table structure for `bro_cart`
-- ----------------------------
DROP TABLE IF EXISTS `bro_cart`;
CREATE TABLE `bro_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '购物编号',
  `atime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '购物时间',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '产品编号',
  `pnum` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '产品数量',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户编号',
  PRIMARY KEY (`id`),
  KEY `cart_pid` (`pid`),
  KEY `cart_uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bro_cart
-- ----------------------------

-- ----------------------------
-- Table structure for `bro_category`
-- ----------------------------
DROP TABLE IF EXISTS `bro_category`;
CREATE TABLE `bro_category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类编号',
  `pid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '父级分类编号',
  `catname` varchar(30) NOT NULL COMMENT '分类名称',
  `ord` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '分类排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bro_category
-- ----------------------------
INSERT INTO `bro_category` VALUES ('1', '0', '电脑', '0');
INSERT INTO `bro_category` VALUES ('2', '0', '手机/数码', '1');
INSERT INTO `bro_category` VALUES ('3', '1', '电脑整机', '1');
INSERT INTO `bro_category` VALUES ('4', '1', '电脑配件', '2');
INSERT INTO `bro_category` VALUES ('5', '2', '手机', '1');
INSERT INTO `bro_category` VALUES ('6', '2', '对讲机', '3');
INSERT INTO `bro_category` VALUES ('7', '2', '手机配件', '2');
INSERT INTO `bro_category` VALUES ('8', '2', '摄影影像', '4');
INSERT INTO `bro_category` VALUES ('9', '2', '数码配件', '5');
INSERT INTO `bro_category` VALUES ('10', '2', '影音娱乐', '6');
INSERT INTO `bro_category` VALUES ('11', '2', '智能设备', '7');
INSERT INTO `bro_category` VALUES ('12', '1', '外设产品', '4');
INSERT INTO `bro_category` VALUES ('13', '1', '游戏设备', '3');
INSERT INTO `bro_category` VALUES ('14', '1', '网络产品', '5');
INSERT INTO `bro_category` VALUES ('15', '1', '办公设备', '6');
INSERT INTO `bro_category` VALUES ('16', '1', '文具耗材', '7');
INSERT INTO `bro_category` VALUES ('17', '3', '笔记本', '0');
INSERT INTO `bro_category` VALUES ('18', '3', '台式机', '1');
INSERT INTO `bro_category` VALUES ('19', '3', '一体机', '2');
INSERT INTO `bro_category` VALUES ('20', '3', '平板电脑', '3');
INSERT INTO `bro_category` VALUES ('21', '3', '服务器/工作站', '4');

-- ----------------------------
-- Table structure for `bro_class`
-- ----------------------------
DROP TABLE IF EXISTS `bro_class`;
CREATE TABLE `bro_class` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类编号',
  `catname` varchar(30) NOT NULL COMMENT '分类名称',
  `ord` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '分类排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bro_class
-- ----------------------------
INSERT INTO `bro_class` VALUES ('1', '1231', '1');

-- ----------------------------
-- Table structure for `bro_collect`
-- ----------------------------
DROP TABLE IF EXISTS `bro_collect`;
CREATE TABLE `bro_collect` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '收藏编号',
  `atime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏时间',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏的商品编号',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏的用户编号',
  PRIMARY KEY (`id`),
  KEY `collect_pid` (`pid`),
  KEY `collect_uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bro_collect
-- ----------------------------
INSERT INTO `bro_collect` VALUES ('1', '1471489066', '1', '1');

-- ----------------------------
-- Table structure for `bro_comment`
-- ----------------------------
DROP TABLE IF EXISTS `bro_comment`;
CREATE TABLE `bro_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '留言id',
  `content` text NOT NULL COMMENT '留言内容',
  `atime` int(10) NOT NULL DEFAULT '0' COMMENT '留言时间',
  `pid` int(10) unsigned NOT NULL COMMENT '留言产品编号',
  `uid` int(10) unsigned NOT NULL COMMENT '接受方用户id',
  `uname` varchar(20) NOT NULL COMMENT '留言用户名',
  `uip` char(15) NOT NULL DEFAULT '0.0.0.0' COMMENT '留言用户ip',
  PRIMARY KEY (`id`),
  KEY `comment_pid` (`pid`),
  KEY `comment_uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bro_comment
-- ----------------------------
INSERT INTO `bro_comment` VALUES ('1', '很好', '1471489468', '1', '1', 'yaolh', '192.168.84.11');

-- ----------------------------
-- Table structure for `bro_iplog`
-- ----------------------------
DROP TABLE IF EXISTS `bro_iplog`;
CREATE TABLE `bro_iplog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ip记录id',
  `ip` char(15) NOT NULL COMMENT 'ip记录ip',
  `atime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ip记录时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=142 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bro_iplog
-- ----------------------------
INSERT INTO `bro_iplog` VALUES ('1', '192.168.84.26', '1469526262');
INSERT INTO `bro_iplog` VALUES ('2', '192.168.84.26', '1469526283');
INSERT INTO `bro_iplog` VALUES ('3', '192.168.84.26', '1469526285');
INSERT INTO `bro_iplog` VALUES ('4', '192.168.84.26', '1469526286');
INSERT INTO `bro_iplog` VALUES ('5', '192.168.84.26', '1469526287');
INSERT INTO `bro_iplog` VALUES ('6', '192.168.84.26', '1469526288');
INSERT INTO `bro_iplog` VALUES ('7', '192.168.84.26', '1469526289');
INSERT INTO `bro_iplog` VALUES ('8', '192.168.84.26', '1469526289');
INSERT INTO `bro_iplog` VALUES ('9', '192.168.84.26', '1469526294');
INSERT INTO `bro_iplog` VALUES ('10', '192.168.84.26', '1469526296');
INSERT INTO `bro_iplog` VALUES ('11', '192.168.84.14', '1471402398');
INSERT INTO `bro_iplog` VALUES ('12', '192.168.84.14', '1471402436');
INSERT INTO `bro_iplog` VALUES ('13', '192.168.84.11', '1471482522');
INSERT INTO `bro_iplog` VALUES ('14', '192.168.84.11', '1471483912');
INSERT INTO `bro_iplog` VALUES ('15', '192.168.84.11', '1471483953');
INSERT INTO `bro_iplog` VALUES ('16', '192.168.84.11', '1471483961');
INSERT INTO `bro_iplog` VALUES ('17', '192.168.84.11', '1471483981');
INSERT INTO `bro_iplog` VALUES ('18', '192.168.84.11', '1471483982');
INSERT INTO `bro_iplog` VALUES ('19', '192.168.84.11', '1471483982');
INSERT INTO `bro_iplog` VALUES ('20', '192.168.84.11', '1471483988');
INSERT INTO `bro_iplog` VALUES ('21', '192.168.84.11', '1471483994');
INSERT INTO `bro_iplog` VALUES ('22', '192.168.84.11', '1471484044');
INSERT INTO `bro_iplog` VALUES ('23', '192.168.84.11', '1471484044');
INSERT INTO `bro_iplog` VALUES ('24', '192.168.84.11', '1471484047');
INSERT INTO `bro_iplog` VALUES ('25', '192.168.84.11', '1471484052');
INSERT INTO `bro_iplog` VALUES ('26', '192.168.84.11', '1471484054');
INSERT INTO `bro_iplog` VALUES ('27', '192.168.84.11', '1471484055');
INSERT INTO `bro_iplog` VALUES ('28', '192.168.84.11', '1471484057');
INSERT INTO `bro_iplog` VALUES ('29', '192.168.84.11', '1471484062');
INSERT INTO `bro_iplog` VALUES ('30', '192.168.84.11', '1471484075');
INSERT INTO `bro_iplog` VALUES ('31', '192.168.84.11', '1471484077');
INSERT INTO `bro_iplog` VALUES ('32', '192.168.84.11', '1471484078');
INSERT INTO `bro_iplog` VALUES ('33', '192.168.84.11', '1471484079');
INSERT INTO `bro_iplog` VALUES ('34', '192.168.84.11', '1471484081');
INSERT INTO `bro_iplog` VALUES ('35', '192.168.84.11', '1471484082');
INSERT INTO `bro_iplog` VALUES ('36', '192.168.84.11', '1471484084');
INSERT INTO `bro_iplog` VALUES ('37', '192.168.84.11', '1471484086');
INSERT INTO `bro_iplog` VALUES ('38', '192.168.84.11', '1471484088');
INSERT INTO `bro_iplog` VALUES ('39', '192.168.84.11', '1471484090');
INSERT INTO `bro_iplog` VALUES ('40', '192.168.84.11', '1471484092');
INSERT INTO `bro_iplog` VALUES ('41', '192.168.84.11', '1471484095');
INSERT INTO `bro_iplog` VALUES ('42', '192.168.84.11', '1471484103');
INSERT INTO `bro_iplog` VALUES ('43', '192.168.84.11', '1471484157');
INSERT INTO `bro_iplog` VALUES ('44', '192.168.84.11', '1471486384');
INSERT INTO `bro_iplog` VALUES ('45', '192.168.84.11', '1471486717');
INSERT INTO `bro_iplog` VALUES ('46', '192.168.84.11', '1471487348');
INSERT INTO `bro_iplog` VALUES ('47', '192.168.84.11', '1471487353');
INSERT INTO `bro_iplog` VALUES ('48', '192.168.84.11', '1471487364');
INSERT INTO `bro_iplog` VALUES ('49', '192.168.84.11', '1471487367');
INSERT INTO `bro_iplog` VALUES ('50', '192.168.84.11', '1471487368');
INSERT INTO `bro_iplog` VALUES ('51', '192.168.84.11', '1471487371');
INSERT INTO `bro_iplog` VALUES ('52', '192.168.84.11', '1471487807');
INSERT INTO `bro_iplog` VALUES ('53', '192.168.84.11', '1471488137');
INSERT INTO `bro_iplog` VALUES ('54', '192.168.84.11', '1471488202');
INSERT INTO `bro_iplog` VALUES ('55', '192.168.84.11', '1471488468');
INSERT INTO `bro_iplog` VALUES ('56', '192.168.84.11', '1471488474');
INSERT INTO `bro_iplog` VALUES ('57', '192.168.84.11', '1471488480');
INSERT INTO `bro_iplog` VALUES ('58', '192.168.84.11', '1471488571');
INSERT INTO `bro_iplog` VALUES ('59', '192.168.84.11', '1471488574');
INSERT INTO `bro_iplog` VALUES ('60', '192.168.84.11', '1471488578');
INSERT INTO `bro_iplog` VALUES ('61', '192.168.84.11', '1471488580');
INSERT INTO `bro_iplog` VALUES ('62', '192.168.84.11', '1471488598');
INSERT INTO `bro_iplog` VALUES ('63', '192.168.84.11', '1471488598');
INSERT INTO `bro_iplog` VALUES ('64', '192.168.84.11', '1471488608');
INSERT INTO `bro_iplog` VALUES ('65', '192.168.84.11', '1471488622');
INSERT INTO `bro_iplog` VALUES ('66', '192.168.84.11', '1471488622');
INSERT INTO `bro_iplog` VALUES ('67', '192.168.84.11', '1471488626');
INSERT INTO `bro_iplog` VALUES ('68', '192.168.84.11', '1471488640');
INSERT INTO `bro_iplog` VALUES ('69', '192.168.84.11', '1471488642');
INSERT INTO `bro_iplog` VALUES ('70', '192.168.84.11', '1471488643');
INSERT INTO `bro_iplog` VALUES ('71', '192.168.84.11', '1471488757');
INSERT INTO `bro_iplog` VALUES ('72', '192.168.84.11', '1471488762');
INSERT INTO `bro_iplog` VALUES ('73', '192.168.84.11', '1471488779');
INSERT INTO `bro_iplog` VALUES ('74', '192.168.84.11', '1471488783');
INSERT INTO `bro_iplog` VALUES ('75', '192.168.84.11', '1471488788');
INSERT INTO `bro_iplog` VALUES ('76', '192.168.84.11', '1471488872');
INSERT INTO `bro_iplog` VALUES ('77', '192.168.84.11', '1471488873');
INSERT INTO `bro_iplog` VALUES ('78', '192.168.84.11', '1471488878');
INSERT INTO `bro_iplog` VALUES ('79', '192.168.84.11', '1471488880');
INSERT INTO `bro_iplog` VALUES ('80', '192.168.84.11', '1471488915');
INSERT INTO `bro_iplog` VALUES ('81', '192.168.84.11', '1471488964');
INSERT INTO `bro_iplog` VALUES ('82', '192.168.84.11', '1471488965');
INSERT INTO `bro_iplog` VALUES ('83', '192.168.84.11', '1471488973');
INSERT INTO `bro_iplog` VALUES ('84', '192.168.84.11', '1471488997');
INSERT INTO `bro_iplog` VALUES ('85', '192.168.84.11', '1471489000');
INSERT INTO `bro_iplog` VALUES ('86', '192.168.84.11', '1471489001');
INSERT INTO `bro_iplog` VALUES ('87', '192.168.84.11', '1471489004');
INSERT INTO `bro_iplog` VALUES ('88', '192.168.84.11', '1471489007');
INSERT INTO `bro_iplog` VALUES ('89', '192.168.84.11', '1471489009');
INSERT INTO `bro_iplog` VALUES ('90', '192.168.84.11', '1471489024');
INSERT INTO `bro_iplog` VALUES ('91', '192.168.84.11', '1471489032');
INSERT INTO `bro_iplog` VALUES ('92', '192.168.84.11', '1471489060');
INSERT INTO `bro_iplog` VALUES ('93', '192.168.84.11', '1471489062');
INSERT INTO `bro_iplog` VALUES ('94', '192.168.84.11', '1471489063');
INSERT INTO `bro_iplog` VALUES ('95', '192.168.84.11', '1471489063');
INSERT INTO `bro_iplog` VALUES ('96', '192.168.84.11', '1471489065');
INSERT INTO `bro_iplog` VALUES ('97', '192.168.84.11', '1471489066');
INSERT INTO `bro_iplog` VALUES ('98', '192.168.84.11', '1471489071');
INSERT INTO `bro_iplog` VALUES ('99', '192.168.84.11', '1471489073');
INSERT INTO `bro_iplog` VALUES ('100', '192.168.84.11', '1471489076');
INSERT INTO `bro_iplog` VALUES ('101', '192.168.84.11', '1471489078');
INSERT INTO `bro_iplog` VALUES ('102', '192.168.84.11', '1471489080');
INSERT INTO `bro_iplog` VALUES ('103', '192.168.84.11', '1471489112');
INSERT INTO `bro_iplog` VALUES ('104', '192.168.84.11', '1471489233');
INSERT INTO `bro_iplog` VALUES ('105', '192.168.84.11', '1471489252');
INSERT INTO `bro_iplog` VALUES ('106', '192.168.84.11', '1471489256');
INSERT INTO `bro_iplog` VALUES ('107', '192.168.84.11', '1471489262');
INSERT INTO `bro_iplog` VALUES ('108', '192.168.84.11', '1471489270');
INSERT INTO `bro_iplog` VALUES ('109', '192.168.84.11', '1471489304');
INSERT INTO `bro_iplog` VALUES ('110', '192.168.84.11', '1471489305');
INSERT INTO `bro_iplog` VALUES ('111', '192.168.84.11', '1471489308');
INSERT INTO `bro_iplog` VALUES ('112', '192.168.84.11', '1471489313');
INSERT INTO `bro_iplog` VALUES ('113', '192.168.84.11', '1471489315');
INSERT INTO `bro_iplog` VALUES ('114', '192.168.84.11', '1471489318');
INSERT INTO `bro_iplog` VALUES ('115', '192.168.84.11', '1471489356');
INSERT INTO `bro_iplog` VALUES ('116', '192.168.84.11', '1471489381');
INSERT INTO `bro_iplog` VALUES ('117', '192.168.84.11', '1471489452');
INSERT INTO `bro_iplog` VALUES ('118', '192.168.84.11', '1471489459');
INSERT INTO `bro_iplog` VALUES ('119', '192.168.84.11', '1471489459');
INSERT INTO `bro_iplog` VALUES ('120', '192.168.84.11', '1471489468');
INSERT INTO `bro_iplog` VALUES ('121', '192.168.84.11', '1471489473');
INSERT INTO `bro_iplog` VALUES ('122', '192.168.84.11', '1471489610');
INSERT INTO `bro_iplog` VALUES ('123', '192.168.84.11', '1471489620');
INSERT INTO `bro_iplog` VALUES ('124', '192.168.84.11', '1471489624');
INSERT INTO `bro_iplog` VALUES ('125', '192.168.84.11', '1471489629');
INSERT INTO `bro_iplog` VALUES ('126', '192.168.84.11', '1471489737');
INSERT INTO `bro_iplog` VALUES ('127', '192.168.84.11', '1471489743');
INSERT INTO `bro_iplog` VALUES ('128', '192.168.84.11', '1471489760');
INSERT INTO `bro_iplog` VALUES ('129', '192.168.84.11', '1471489791');
INSERT INTO `bro_iplog` VALUES ('130', '192.168.84.11', '1471489805');
INSERT INTO `bro_iplog` VALUES ('131', '192.168.84.11', '1471489829');
INSERT INTO `bro_iplog` VALUES ('132', '192.168.84.11', '1471489857');
INSERT INTO `bro_iplog` VALUES ('133', '192.168.84.11', '1471489866');
INSERT INTO `bro_iplog` VALUES ('134', '192.168.84.11', '1471489871');
INSERT INTO `bro_iplog` VALUES ('135', '192.168.84.11', '1471489871');
INSERT INTO `bro_iplog` VALUES ('136', '192.168.84.11', '1471489877');
INSERT INTO `bro_iplog` VALUES ('137', '192.168.84.11', '1471489883');
INSERT INTO `bro_iplog` VALUES ('138', '192.168.84.11', '1471490054');
INSERT INTO `bro_iplog` VALUES ('139', '192.168.84.11', '1471490057');
INSERT INTO `bro_iplog` VALUES ('140', '192.168.84.11', '1471490059');
INSERT INTO `bro_iplog` VALUES ('141', '192.168.84.11', '1471490061');

-- ----------------------------
-- Table structure for `bro_link`
-- ----------------------------
DROP TABLE IF EXISTS `bro_link`;
CREATE TABLE `bro_link` (
  `id` smallint(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '友情链接id',
  `name` varchar(50) NOT NULL COMMENT '友情链接名称',
  `url` varchar(100) NOT NULL COMMENT '友情链接url',
  `ord` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '友情链接排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bro_link
-- ----------------------------

-- ----------------------------
-- Table structure for `bro_order`
-- ----------------------------
DROP TABLE IF EXISTS `bro_order`;
CREATE TABLE `bro_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单id',
  `money` decimal(10,1) unsigned NOT NULL DEFAULT '0.0' COMMENT '订单金额',
  `productmoney` decimal(10,1) unsigned NOT NULL DEFAULT '0.0' COMMENT '商品总额',
  `state` smallint(6) NOT NULL DEFAULT '1' COMMENT '订单状态',
  `payway` smallint(6) NOT NULL DEFAULT '1' COMMENT '支付方式',
  `content` varchar(255) NOT NULL COMMENT '订单留言',
  `atime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下单时间',
  `ptime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '付款时间',
  `stime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发货时间',
  `wlname` varchar(20) NOT NULL COMMENT '物流名称',
  `wlid` varchar(20) NOT NULL COMMENT '运单编号',
  `wlmoney` decimal(5,1) NOT NULL COMMENT '物流运费',
  `uid` int(10) unsigned NOT NULL COMMENT '用户编号',
  `uname` varchar(20) NOT NULL COMMENT '用户名',
  `utname` varchar(10) NOT NULL COMMENT '收货人名',
  `uphone` char(11) NOT NULL COMMENT '收货手机',
  `utel` varchar(20) NOT NULL COMMENT '收货人固定电话',
  `uaddress` varchar(255) NOT NULL COMMENT '用户地址',
  PRIMARY KEY (`id`),
  KEY `order_uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=1608180002 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bro_order
-- ----------------------------
INSERT INTO `bro_order` VALUES ('1608180001', '5288.0', '5288.0', '4', '3', '尽快发货', '1471488598', '1471488742', '1471488709', '顺丰快递', '1231312314', '0.0', '1', 'admin', '腰立辉', '18801043607', '', '北京市北京市北京市海淀区西外大街168号');

-- ----------------------------
-- Table structure for `bro_orderdata`
-- ----------------------------
DROP TABLE IF EXISTS `bro_orderdata`;
CREATE TABLE `bro_orderdata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单数据id',
  `oid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单id',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `pname` varchar(50) NOT NULL COMMENT '商品名称',
  `plogo` varchar(200) NOT NULL COMMENT '商品logo',
  `pmoney` decimal(10,1) NOT NULL DEFAULT '0.0' COMMENT '商品价格',
  `pnum` smallint(5) unsigned NOT NULL COMMENT '商品数量',
  PRIMARY KEY (`id`),
  KEY `orderdata_oid` (`oid`),
  KEY `orderdata_pid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bro_orderdata
-- ----------------------------
INSERT INTO `bro_orderdata` VALUES ('1', '1608180001', '1', '（Apple） MacBook Air 11.6英寸笔记本电脑 i5 4', '2016-08/20160818104743879q.jpg', '5288.0', '1');

-- ----------------------------
-- Table structure for `bro_page`
-- ----------------------------
DROP TABLE IF EXISTS `bro_page`;
CREATE TABLE `bro_page` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '单页id',
  `name` varchar(20) NOT NULL COMMENT '单页名称',
  `content` text NOT NULL COMMENT '单页内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bro_page
-- ----------------------------
INSERT INTO `bro_page` VALUES ('12', '订单查询', '');
INSERT INTO `bro_page` VALUES ('13', '退换货流程', '');
INSERT INTO `bro_page` VALUES ('14', '退换货条款', '');
INSERT INTO `bro_page` VALUES ('15', '用户协议', '');
INSERT INTO `bro_page` VALUES ('16', '公司简介', '');
INSERT INTO `bro_page` VALUES ('17', '联系我们', '<p>\r\n	1</p>\r\n');
INSERT INTO `bro_page` VALUES ('18', '诚聘英才', '');
INSERT INTO `bro_page` VALUES ('8', '支付方式', '');
INSERT INTO `bro_page` VALUES ('9', '常见问题', '');
INSERT INTO `bro_page` VALUES ('10', '配送时间及运费', '');
INSERT INTO `bro_page` VALUES ('11', '验货与签收', '');
INSERT INTO `bro_page` VALUES ('7', '购物指南', '');

-- ----------------------------
-- Table structure for `bro_payway`
-- ----------------------------
DROP TABLE IF EXISTS `bro_payway`;
CREATE TABLE `bro_payway` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '支付id',
  `name` varchar(10) NOT NULL COMMENT '支付方式名称',
  `mark` tinyint(3) NOT NULL COMMENT '支付方式标记',
  `logo` varchar(100) NOT NULL COMMENT '支付方式的logo',
  `model` text NOT NULL COMMENT '支付模型',
  `config` text NOT NULL COMMENT '支付配置',
  `ptext` varchar(255) NOT NULL COMMENT '支付描述',
  `ord` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '显示顺序',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '启用1/停用0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bro_payway
-- ----------------------------
INSERT INTO `bro_payway` VALUES ('2', '银行转账/汇款', '2', 'include/plugin/payway/bank/logo.gif', 'a:1:{s:9:\"bank_text\";a:2:{s:4:\"name\";s:12:\"收款信息\";s:9:\"form_type\";s:8:\"textarea\";}}', 'a:1:{s:9:&quot;bank_text&quot;;s:0:&quot;&quot;;}', '通过线下汇款方式支付，汇款帐号：建设银行 00000025400000xxxx 陈某某', '4', '0');
INSERT INTO `bro_payway` VALUES ('3', '货到付款', '3', 'include/plugin/payway/cod/logo.gif', '', '', '送货上门后再收款，支持现金、POS机刷卡、支票支付', '1', '1');

-- ----------------------------
-- Table structure for `bro_product`
-- ----------------------------
DROP TABLE IF EXISTS `bro_product`;
CREATE TABLE `bro_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品id',
  `name` varchar(50) NOT NULL COMMENT '商品名称',
  `content` text NOT NULL COMMENT '商品描述',
  `logo` varchar(200) NOT NULL COMMENT '商品logo',
  `money` decimal(10,1) unsigned NOT NULL DEFAULT '0.0' COMMENT '商品商城价',
  `smoney` decimal(10,1) unsigned NOT NULL DEFAULT '0.0' COMMENT '商品市场价',
  `wlmoney` decimal(5,1) unsigned NOT NULL DEFAULT '0.0' COMMENT '商品物流价',
  `mark` varchar(20) NOT NULL COMMENT '商品货号',
  `weight` decimal(7,2) NOT NULL COMMENT '商品尺寸',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '商品状态',
  `atime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品发布时间',
  `num` smallint(5) unsigned NOT NULL COMMENT '商品库存数',
  `sellnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品销售数',
  `clicknum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品点击数',
  `collectnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品收藏数',
  `asknum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品咨询数',
  `commentnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品评价数',
  `istuijian` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '商品推荐',
  `istj` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '特价商品',
  `cid` smallint(5) unsigned NOT NULL COMMENT '商品分类id',
  PRIMARY KEY (`id`),
  KEY `product_cid` (`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bro_product
-- ----------------------------
INSERT INTO `bro_product` VALUES ('1', '（Apple） MacBook Air 11.6英寸笔记本电脑 i5 4', '<ul class=&quot;p-parameter-list&quot; id=&quot;parameter2&quot; style=&quot;margin: 0px 0px 20px; padding: 0px; list-style: none; overflow: hidden; color: rgb(102, 102, 102); font-family: &#039;microsoft yahei&#039;; font-size: 12px; line-height: 18px; white-space: normal; background-color: rgb(255, 255, 255);&quot;>\r\n	<li style=&quot;margin: 0px; padding: 0px; width: 320px; float: left; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; line-height: 26px;&quot; title=&quot;全球购 亚太版 苹果（Apple） MacBook Air 11.6英寸笔记本电脑 i5  4G 128Gssd/VM2&quot;>\r\n		商品名称：全球购 亚太版 苹果（Apple） MacBook Air 11.6英寸笔记本电脑 i5 4G 128Gssd/VM2</li>\r\n	<li style=&quot;margin: 0px; padding: 0px; width: 320px; float: left; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; line-height: 26px;&quot; title=&quot;1967011635&quot;>\r\n		商品编号：1967011635</li>\r\n	<li style=&quot;margin: 0px; padding: 0px; width: 320px; float: left; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; line-height: 26px;&quot; title=&quot;GIT通讯数码旗舰店&quot;>\r\n		店铺：eshaop电子商城</li>\r\n	<li style=&quot;margin: 0px; padding: 0px; width: 320px; float: left; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; line-height: 26px;&quot; title=&quot;1.08kg&quot;>\r\n		商品毛重：1.08kg</li>\r\n	<li style=&quot;margin: 0px; padding: 0px; width: 320px; float: left; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; line-height: 26px;&quot; title=&quot;MAC&quot;>\r\n		系统：MAC</li>\r\n	<li style=&quot;margin: 0px; padding: 0px; width: 320px; float: left; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; line-height: 26px;&quot; title=&quot;10.0mm以下&quot;>\r\n		厚度：10.0mm以下</li>\r\n	<li style=&quot;margin: 0px; padding: 0px; width: 320px; float: left; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; line-height: 26px;&quot; title=&quot;其他&quot;>\r\n		内存容量：其他</li>\r\n	<li style=&quot;margin: 0px; padding: 0px; width: 320px; float: left; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; line-height: 26px;&quot; title=&quot;其他&quot;>\r\n		分辨率：其他</li>\r\n	<li style=&quot;margin: 0px; padding: 0px; width: 320px; float: left; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; line-height: 26px;&quot; title=&quot;其他&quot;>\r\n		显卡型号：其他</li>\r\n	<li style=&quot;margin: 0px; padding: 0px; width: 320px; float: left; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; line-height: 26px;&quot; title=&quot;7-9小时&quot;>\r\n		待机时长：7-9小时</li>\r\n	<li style=&quot;margin: 0px; padding: 0px; width: 320px; float: left; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; line-height: 26px;&quot; title=&quot;Intel 其他&quot;>\r\n		处理器：Intel 其他</li>\r\n	<li style=&quot;margin: 0px; padding: 0px; width: 320px; float: left; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; line-height: 26px;&quot; title=&quot;其他&quot;>\r\n		特性：其他</li>\r\n	<li style=&quot;margin: 0px; padding: 0px; width: 320px; float: left; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; line-height: 26px;&quot; title=&quot;集成显卡&quot;>\r\n		显卡类别：集成显卡</li>\r\n	<li style=&quot;margin: 0px; padding: 0px; width: 320px; float: left; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; line-height: 26px;&quot; title=&quot;小于1KG&quot;>\r\n		裸机重量：小于1KG</li>\r\n	<li style=&quot;margin: 0px; padding: 0px; width: 320px; float: left; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; line-height: 26px;&quot; title=&quot;其他&quot;>\r\n		硬盘容量：其他</li>\r\n	<li style=&quot;margin: 0px; padding: 0px; width: 320px; float: left; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; line-height: 26px;&quot; title=&quot;其他&quot;>\r\n		显存容量：其他</li>\r\n	<li style=&quot;margin: 0px; padding: 0px; width: 320px; float: left; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; line-height: 26px;&quot; title=&quot;超薄本&quot;>\r\n		分类：超薄本</li>\r\n	<li style=&quot;margin: 0px; padding: 0px; width: 320px; float: left; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; line-height: 26px;&quot; title=&quot;其他&quot;>\r\n		屏幕尺寸：其他</li>\r\n	<li>\r\n		&nbsp;</li>\r\n</ul>\r\n', '2016-08/20160818104743879q.jpg', '5288.0', '6288.0', '0.0', '1967011635', '0.00', '1', '1471488274', '4', '1', '8', '1', '1', '1', '1', '0', '17');

-- ----------------------------
-- Table structure for `bro_setting`
-- ----------------------------
DROP TABLE IF EXISTS `bro_setting`;
CREATE TABLE `bro_setting` (
  `id` smallint(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '基本信息id',
  `skey` varchar(50) NOT NULL COMMENT '基本设置下标',
  `svalue` text NOT NULL COMMENT '基本设置的值',
  PRIMARY KEY (`id`),
  KEY `setting_skey` (`skey`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bro_setting
-- ----------------------------
INSERT INTO `bro_setting` VALUES ('1', 'web_title', '欢迎使用eShop商城系统');
INSERT INTO `bro_setting` VALUES ('2', 'web_keywords', '源代码,itxdl,php,shop,brophp,php商城系统,b2c商城系统,php商城源码,b2c商城源码,开源免费网上商城系统');
INSERT INTO `bro_setting` VALUES ('3', 'web_description', '教学演示系统！');
INSERT INTO `bro_setting` VALUES ('4', 'web_copyright', 'yaolh');
INSERT INTO `bro_setting` VALUES ('5', 'web_tpl', 'ceil');
INSERT INTO `bro_setting` VALUES ('6', 'web_phone', '18801043607');
INSERT INTO `bro_setting` VALUES ('7', 'web_icp', '');
INSERT INTO `bro_setting` VALUES ('8', 'web_weibo', 'http://weibo.com/yaolihui129');
INSERT INTO `bro_setting` VALUES ('9', 'web_tongji', '');
INSERT INTO `bro_setting` VALUES ('10', 'web_logo', '2016-08/20160818093133374d.jpg');
INSERT INTO `bro_setting` VALUES ('11', 'web_qq', '83000892,12210557');

-- ----------------------------
-- Table structure for `bro_user`
-- ----------------------------
DROP TABLE IF EXISTS `bro_user`;
CREATE TABLE `bro_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `name` varchar(20) NOT NULL COMMENT '用户名',
  `pw` varchar(32) NOT NULL COMMENT '登录密码',
  `tname` varchar(10) NOT NULL COMMENT '直实姓名',
  `phone` char(11) NOT NULL COMMENT '用户手机',
  `tel` varchar(20) NOT NULL COMMENT '固定电话',
  `qq` varchar(10) NOT NULL COMMENT '用户QQ',
  `email` varchar(30) NOT NULL COMMENT '用户email',
  `atime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户注册时间',
  `ltime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户上次登录时间',
  `address` varchar(255) NOT NULL COMMENT '用户地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bro_user
-- ----------------------------
INSERT INTO `bro_user` VALUES ('1', 'yaolh', '81b9d9c3039a8be1da48788aac000b8c', '腰立辉', '18801043607', '', '', 'yaolh@yiche.com', '1471483982', '1471489459', '北京市海淀区西外大街168号');
