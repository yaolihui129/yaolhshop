-- --------------------------------------------------
-- 文件名: shop.sql 
-- BroShop 系统安装使用的SQL查询所在的文件
-- 作者： 高洛峰
-- --------------------------------------------------


--
-- Database: `broshop`
--

-- --------------------------------------------------------

--
-- 表的结构 `bro_ad`
--
DROP TABLE IF EXISTS bro_ad;;
CREATE TABLE `bro_ad` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '广告编号',
  `logo` varchar(100) NOT NULL DEFAULT '' COMMENT '广告图片',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '广告链接',
  `position` tinyint(10) unsigned NOT NULL DEFAULT '1' COMMENT '广告位置',
  `ord` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '广告顺序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;;

-- --------------------------------------------------------

--
-- 表的结构 `bro_admin`
--
DROP TABLE IF EXISTS bro_admin;;
CREATE TABLE `bro_admin` (
  `id` smallint(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理编号',
  `name` varchar(20) NOT NULL COMMENT '管理名',
  `pw` varchar(32) NOT NULL COMMENT '管理密码',
  `atime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理注册时间',
  `ltime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理上次登录时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;;

-- --------------------------------------------------------

--
-- 表的结构 `bro_article`
--
DROP TABLE IF EXISTS bro_article;;
CREATE TABLE `bro_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章编号',
  `name` varchar(100) NOT NULL COMMENT '文章标题',
  `content` text NOT NULL COMMENT '文章内容',
  `atime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章添加时间',
  `clicknum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章点击数',
  `cid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '文章分类编号',
  PRIMARY KEY (`id`),
  KEY `article_cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;;

-- --------------------------------------------------------

--
-- 表的结构 `bro_ask`
--
DROP TABLE IF EXISTS bro_ask;;
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;;

-- --------------------------------------------------------

--
-- 表的结构 `bro_cart`
--
DROP TABLE IF EXISTS bro_cart;;
CREATE TABLE `bro_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '购物编号',
  `atime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '购物时间',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '产品编号',
  `pnum` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '产品数量',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户编号',
  PRIMARY KEY (`id`),
  KEY `cart_pid` (`pid`),
  KEY `cart_uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;;

-- --------------------------------------------------------

--
-- 表的结构 `bro_category`
--
DROP TABLE IF EXISTS bro_category;;
CREATE TABLE `bro_category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类编号',
  `pid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '父级分类编号',
  `catname` varchar(30) NOT NULL COMMENT '分类名称',
  `ord` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '分类排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;;

-- --------------------------------------------------------

--
-- 表的结构 `bro_class`
--
DROP TABLE IF EXISTS bro_class;;
CREATE TABLE `bro_class` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类编号',
  `catname` varchar(30) NOT NULL COMMENT '分类名称',
  `ord` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '分类排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;;

-- --------------------------------------------------------

--
-- 表的结构 `bro_collect`
--
DROP TABLE IF EXISTS bro_collect;;
CREATE TABLE `bro_collect` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '收藏编号',
  `atime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏时间',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏的商品编号',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏的用户编号',
  PRIMARY KEY (`id`),
  KEY `collect_pid` (`pid`),
  KEY `collect_uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;;

-- --------------------------------------------------------

--
-- 表的结构 `bro_comment`
--
DROP TABLE IF EXISTS bro_comment;;
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;;

-- --------------------------------------------------------

--
-- 表的结构 `bro_iplog`
--
DROP TABLE IF EXISTS bro_iplog;;
CREATE TABLE `bro_iplog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ip记录id',
  `ip` char(15) NOT NULL COMMENT 'ip记录ip',
  `atime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ip记录时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;;


-- --------------------------------------------------------

--
-- 表的结构 `bro_link`
--
DROP TABLE IF EXISTS bro_link;;
CREATE TABLE `bro_link` (
  `id` smallint(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '友情链接id',
  `name` varchar(50) NOT NULL COMMENT '友情链接名称',
  `url` varchar(100) NOT NULL COMMENT '友情链接url',
  `ord` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '友情链接排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;;

-- --------------------------------------------------------

--
-- 表的结构 `bro_order`
--
DROP TABLE IF EXISTS bro_order;;
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;;

-- --------------------------------------------------------

--
-- 表的结构 `bro_orderdata`
--
DROP TABLE IF EXISTS bro_orderdata;;
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;;

-- --------------------------------------------------------

--
-- 表的结构 `bro_page`
--
DROP TABLE IF EXISTS bro_page;;
CREATE TABLE `bro_page` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '单页id',
  `name` varchar(20) NOT NULL COMMENT '单页名称',
  `content` text NOT NULL COMMENT '单页内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;;

--
-- 转存表中的数据 `bro_page`
--

INSERT INTO `bro_page` (`id`, `name`, `content`) VALUES
(12, '订单查询', ''),
(13, '退换货流程', ''),
(14, '退换货条款', ''),
(15, '用户协议', ''),
(16, '公司简介', ''),
(17, '联系我们', ''),
(18, '诚聘英才', ''),
(8, '支付方式', ''),
(9, '常见问题', ''),
(10, '配送时间及运费', ''),
(11, '验货与签收', ''),
(7, '购物指南', '');;

-- --------------------------------------------------------

--
-- 表的结构 `bro_payway`
--
DROP TABLE IF EXISTS bro_payway;;
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;;

--
-- 转存表中的数据 `bro_payway`
--

INSERT INTO `bro_payway` (`id`, `name`, `mark`, `logo`, `model`, `config`, `ptext`, `ord`, `state`) VALUES
(2, '银行转账/汇款', 2, 'include/plugin/payway/bank/logo.gif', 'a:1:{s:9:"bank_text";a:2:{s:4:"name";s:12:"收款信息";s:9:"form_type";s:8:"textarea";}}', '[dsdsdsdss]', '通过线下汇款方式支付，汇款帐号：建设银行 00000025400000xxxx 陈某某', 4, 1),
(3, '货到付款', 3, 'include/plugin/payway/cod/logo.gif', '', '', '送货上门后再收款，支持现金、POS机刷卡、支票支付', 1, 1);;
-- --------------------------------------------------------

--
-- 表的结构 `bro_product`
--
DROP TABLE IF EXISTS bro_product;;
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;;

-- --------------------------------------------------------

--
-- 表的结构 `bro_setting`
--
DROP TABLE IF EXISTS bro_setting;;
CREATE TABLE `bro_setting` (
  `id` smallint(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '基本信息id',
  `skey` varchar(50) NOT NULL COMMENT '基本设置下标',
  `svalue` text NOT NULL COMMENT '基本设置的值',
  PRIMARY KEY (`id`),
  KEY `setting_skey` (`skey`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12;;

--
-- 转存表中的数据 `bro_setting`
--

INSERT INTO `bro_setting` (`id`, `skey`, `svalue`) VALUES
(1, 'web_title', '欢迎使用BroShop商城系统'),
(2, 'web_keywords', '源代码,itxdl,php,shop,brophp,php商城系统,b2c商城系统,php商城源码,b2c商城源码,开源免费网上商城系统'),
(3, 'web_description', '教学演示系统！'),
(4, 'web_copyright', '易第优教育 2014-2017'),
(5, 'web_tpl', 'default'),
(6, 'web_phone', '400-700-1307'),
(7, 'web_icp', '京ICP备11018177号 京公网安备11011402000177'),
(8, 'web_weibo', 'http://weibo.com/gaoluofeng'),
(9, 'web_tongji', '<script src="http://s17.cnzz.com/stat.php?id=2414116&web_id=2414116&show=pic" language="JavaScript"></script>'),
(10, 'web_logo', '2015-10/20151029090103982j.png'),
(11, 'web_qq', '779050720,46458494,3154661953');;

-- --------------------------------------------------------

--
-- 表的结构 `bro_user`
--
DROP TABLE IF EXISTS bro_user;;
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;;
