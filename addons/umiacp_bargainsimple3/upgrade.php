<?php
//自己删除上面的符号
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_bargainsimple3_activity` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `image` varchar(255) NOT NULL COMMENT '顶部主题',
  `bgimage` varchar(255) NOT NULL COMMENT '背景图',
  `music` varchar(255) NOT NULL COMMENT '背景音乐',
  `cutnum` int(11) NOT NULL COMMENT '最多可参与几种商品砍价',
  `starttime` int(11) NOT NULL COMMENT '活动开始时间',
  `endtime` int(11) NOT NULL COMMENT '活动结束时间',
  `preferential_title` varchar(255) NOT NULL COMMENT '活动优惠标题',
  `preferential_val` text COMMENT '活动优惠内容',
  `desc_title` varchar(255) NOT NULL COMMENT '活动描述标题',
  `desc_imgs` text COMMENT '活动描述图集',
  `desc_val` text COMMENT '活动描述内容',
  `rule_title` varchar(255) NOT NULL COMMENT '活动规则标题',
  `rule_val` text COMMENT '活动规则内容',
  `shop_title` varchar(255) NOT NULL COMMENT '商家介绍标题',
  `shop_val` text COMMENT '商家介绍内容',
  `shop_imgs` text COMMENT '商家介绍图集',
  `receive_time` varchar(255) NOT NULL COMMENT '领取时间',
  `receive_address` varchar(255) NOT NULL COMMENT '领取地址',
  `receive_mobile` varchar(255) NOT NULL COMMENT '联系电话',
  `shop_name` varchar(255) NOT NULL COMMENT '商家姓名',
  `shop_mobile` varchar(255) NOT NULL COMMENT '商家热线',
  `shop_province` varchar(255) NOT NULL COMMENT '省',
  `shop_city` varchar(255) NOT NULL COMMENT '市',
  `shop_district` varchar(255) NOT NULL COMMENT '区',
  `shop_address` varchar(255) NOT NULL COMMENT '商家详细地址',
  `userinfo` text COMMENT '用户信息收集',
  `pv` int(11) NOT NULL DEFAULT '0' COMMENT '查看人数',
  `share` int(11) NOT NULL DEFAULT '0' COMMENT '分享人数',
  `participate` int(11) NOT NULL DEFAULT '0' COMMENT '参与人数',
  `success` int(11) NOT NULL DEFAULT '0' COMMENT '成功人数',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,上架;2,下架;3,未开始;',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `effects_imgs` text COMMENT '特效图集',
  `titlebgimg` varchar(255) DEFAULT NULL COMMENT '标题背景框',
  `share_img` varchar(255) DEFAULT NULL COMMENT '分享图',
  `share_title` varchar(255) DEFAULT NULL COMMENT '分享标题',
  `share_desc` varchar(255) DEFAULT NULL COMMENT '分享描述',
  `regional` int(11) NOT NULL DEFAULT '1' COMMENT '区域限制:1不限制;2限制;',
  `r_address` text COMMENT '活动区域限制地区',
  `ak` varchar(255) DEFAULT NULL COMMENT '百度密钥',
  `shop_code` varchar(255) DEFAULT NULL COMMENT '商家图片（二维码）',
  `cutting_pay` tinyint(4) NOT NULL DEFAULT '1' COMMENT '允许砍价中付款:1.允许;2.不允许',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `title` (`title`) USING BTREE,
  KEY `shop_id` (`shop_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=207 DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_bargainsimple3_activity','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','shop_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','title')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `title` varchar(255) NOT NULL COMMENT '标题'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','image')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `image` varchar(255) NOT NULL COMMENT '顶部主题'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','bgimage')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `bgimage` varchar(255) NOT NULL COMMENT '背景图'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','music')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `music` varchar(255) NOT NULL COMMENT '背景音乐'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','cutnum')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `cutnum` int(11) NOT NULL COMMENT '最多可参与几种商品砍价'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','starttime')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `starttime` int(11) NOT NULL COMMENT '活动开始时间'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','endtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `endtime` int(11) NOT NULL COMMENT '活动结束时间'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','preferential_title')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `preferential_title` varchar(255) NOT NULL COMMENT '活动优惠标题'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','preferential_val')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `preferential_val` text COMMENT '活动优惠内容'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','desc_title')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `desc_title` varchar(255) NOT NULL COMMENT '活动描述标题'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','desc_imgs')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `desc_imgs` text COMMENT '活动描述图集'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','desc_val')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `desc_val` text COMMENT '活动描述内容'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','rule_title')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `rule_title` varchar(255) NOT NULL COMMENT '活动规则标题'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','rule_val')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `rule_val` text COMMENT '活动规则内容'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','shop_title')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `shop_title` varchar(255) NOT NULL COMMENT '商家介绍标题'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','shop_val')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `shop_val` text COMMENT '商家介绍内容'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','shop_imgs')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `shop_imgs` text COMMENT '商家介绍图集'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','receive_time')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `receive_time` varchar(255) NOT NULL COMMENT '领取时间'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','receive_address')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `receive_address` varchar(255) NOT NULL COMMENT '领取地址'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','receive_mobile')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `receive_mobile` varchar(255) NOT NULL COMMENT '联系电话'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','shop_name')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `shop_name` varchar(255) NOT NULL COMMENT '商家姓名'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','shop_mobile')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `shop_mobile` varchar(255) NOT NULL COMMENT '商家热线'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','shop_province')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `shop_province` varchar(255) NOT NULL COMMENT '省'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','shop_city')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `shop_city` varchar(255) NOT NULL COMMENT '市'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','shop_district')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `shop_district` varchar(255) NOT NULL COMMENT '区'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','shop_address')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `shop_address` varchar(255) NOT NULL COMMENT '商家详细地址'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','userinfo')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `userinfo` text COMMENT '用户信息收集'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','pv')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `pv` int(11) NOT NULL DEFAULT '0' COMMENT '查看人数'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','share')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `share` int(11) NOT NULL DEFAULT '0' COMMENT '分享人数'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','participate')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `participate` int(11) NOT NULL DEFAULT '0' COMMENT '参与人数'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','success')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `success` int(11) NOT NULL DEFAULT '0' COMMENT '成功人数'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,上架;2,下架;3,未开始;'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','effects_imgs')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `effects_imgs` text COMMENT '特效图集'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','titlebgimg')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `titlebgimg` varchar(255) DEFAULT NULL COMMENT '标题背景框'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','share_img')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `share_img` varchar(255) DEFAULT NULL COMMENT '分享图'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','share_title')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `share_title` varchar(255) DEFAULT NULL COMMENT '分享标题'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','share_desc')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `share_desc` varchar(255) DEFAULT NULL COMMENT '分享描述'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','regional')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `regional` int(11) NOT NULL DEFAULT '1' COMMENT '区域限制:1不限制;2限制;'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','r_address')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `r_address` text COMMENT '活动区域限制地区'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','ak')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `ak` varchar(255) DEFAULT NULL COMMENT '百度密钥'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','shop_code')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `shop_code` varchar(255) DEFAULT NULL COMMENT '商家图片（二维码）'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','cutting_pay')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   `cutting_pay` tinyint(4) NOT NULL DEFAULT '1' COMMENT '允许砍价中付款:1.允许;2.不允许'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   PRIMARY KEY (`id`) USING BTREE");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   KEY `uniacid` (`uniacid`) USING BTREE");}
if(!pdo_fieldexists('umiacp_bargainsimple3_activity','title')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_activity')." ADD   KEY `title` (`title`) USING BTREE");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_bargainsimple3_cate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `rank` int(11) NOT NULL DEFAULT '0',
  `cate_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL COMMENT '分类名',
  `logo` varchar(255) NOT NULL COMMENT '分类logo',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `title` (`title`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_bargainsimple3_cate','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cate')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cate','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cate')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cate','rank')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cate')." ADD   `rank` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cate','cate_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cate')." ADD   `cate_id` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cate','title')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cate')." ADD   `title` varchar(255) NOT NULL COMMENT '分类名'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cate','logo')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cate')." ADD   `logo` varchar(255) NOT NULL COMMENT '分类logo'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cate','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cate')." ADD   `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cate','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cate')." ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cate','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cate')." ADD   PRIMARY KEY (`id`) USING BTREE");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cate','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cate')." ADD   KEY `uniacid` (`uniacid`) USING BTREE");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_bargainsimple3_complain` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动',
  `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家',
  `reason` varchar(255) NOT NULL DEFAULT '0' COMMENT '投诉原因',
  `desc` varchar(255) NOT NULL COMMENT '投诉描述',
  `mobile` varchar(255) NOT NULL COMMENT '手机号',
  `userinfo` varchar(255) DEFAULT NULL COMMENT '具体用户信息',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '发起时间',
  `title` varchar(255) DEFAULT NULL COMMENT '活动标题',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `realname` (`desc`,`mobile`) USING BTREE,
  KEY `activity_id` (`activity_id`,`shop_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=156 DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_bargainsimple3_complain','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_complain')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_bargainsimple3_complain','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_complain')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_complain','activity_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_complain')." ADD   `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_complain','shop_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_complain')." ADD   `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_complain','reason')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_complain')." ADD   `reason` varchar(255) NOT NULL DEFAULT '0' COMMENT '投诉原因'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_complain','desc')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_complain')." ADD   `desc` varchar(255) NOT NULL COMMENT '投诉描述'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_complain','mobile')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_complain')." ADD   `mobile` varchar(255) NOT NULL COMMENT '手机号'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_complain','userinfo')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_complain')." ADD   `userinfo` varchar(255) DEFAULT NULL COMMENT '具体用户信息'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_complain','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_complain')." ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '发起时间'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_complain','title')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_complain')." ADD   `title` varchar(255) DEFAULT NULL COMMENT '活动标题'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_complain','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_complain')." ADD   PRIMARY KEY (`id`)");}
if(!pdo_fieldexists('umiacp_bargainsimple3_complain','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_complain')." ADD   KEY `uniacid` (`uniacid`)");}
if(!pdo_fieldexists('umiacp_bargainsimple3_complain','realname')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_complain')." ADD   KEY `realname` (`desc`,`mobile`) USING BTREE");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_bargainsimple3_cut` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '砍价人',
  `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动',
  `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家',
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品',
  `oprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价',
  `nprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '现价',
  `rprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '底价',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '砍价',
  `times` int(11) NOT NULL DEFAULT '0' COMMENT '剩余砍价次数',
  `realname` varchar(255) NOT NULL COMMENT '姓名',
  `mobile` varchar(255) NOT NULL COMMENT '手机号',
  `userinfo` text COMMENT '具体用户信息',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,砍价中;2,砍到底;3,已购买;',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '发起时间',
  `lottery_time` int(11) NOT NULL DEFAULT '0' COMMENT '核销时间',
  `avatar` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `mid` (`mid`),
  KEY `status` (`status`),
  KEY `realname` (`realname`,`mobile`) USING BTREE,
  KEY `activity_id` (`activity_id`,`goods_id`,`shop_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=483 DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_bargainsimple3_cut','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cut')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cut','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cut')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cut','mid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cut')." ADD   `mid` int(11) NOT NULL DEFAULT '0' COMMENT '砍价人'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cut','activity_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cut')." ADD   `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cut','shop_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cut')." ADD   `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cut','goods_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cut')." ADD   `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cut','oprice')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cut')." ADD   `oprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cut','nprice')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cut')." ADD   `nprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '现价'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cut','rprice')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cut')." ADD   `rprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '底价'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cut','price')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cut')." ADD   `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '砍价'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cut','times')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cut')." ADD   `times` int(11) NOT NULL DEFAULT '0' COMMENT '剩余砍价次数'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cut','realname')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cut')." ADD   `realname` varchar(255) NOT NULL COMMENT '姓名'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cut','mobile')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cut')." ADD   `mobile` varchar(255) NOT NULL COMMENT '手机号'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cut','userinfo')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cut')." ADD   `userinfo` text COMMENT '具体用户信息'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cut','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cut')." ADD   `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,砍价中;2,砍到底;3,已购买;'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cut','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cut')." ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '发起时间'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cut','lottery_time')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cut')." ADD   `lottery_time` int(11) NOT NULL DEFAULT '0' COMMENT '核销时间'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cut','avatar')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cut')." ADD   `avatar` varchar(255) NOT NULL");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cut','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cut')." ADD   PRIMARY KEY (`id`)");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cut','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cut')." ADD   KEY `uniacid` (`uniacid`)");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cut','mid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cut')." ADD   KEY `mid` (`mid`)");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cut','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cut')." ADD   KEY `status` (`status`)");}
if(!pdo_fieldexists('umiacp_bargainsimple3_cut','realname')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_cut')." ADD   KEY `realname` (`realname`,`mobile`) USING BTREE");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_bargainsimple3_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '用户',
  `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动',
  `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家',
  `title` varchar(255) NOT NULL COMMENT '商品名',
  `image` varchar(255) NOT NULL COMMENT '商品主图',
  `oprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价',
  `gnum` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  `rprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '底价',
  `times` int(11) NOT NULL DEFAULT '0' COMMENT '砍价次数',
  `desc` varchar(255) NOT NULL COMMENT '商品描述',
  `participate` int(11) NOT NULL DEFAULT '0' COMMENT '参与人数',
  `success` int(11) NOT NULL DEFAULT '0' COMMENT '购买成功',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,启用;0,禁用;',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '下单时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `mid` (`mid`),
  KEY `status` (`status`),
  KEY `activity_id` (`activity_id`,`shop_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=206 DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_bargainsimple3_goods','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_goods')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_bargainsimple3_goods','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_goods')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_goods','mid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_goods')." ADD   `mid` int(11) NOT NULL DEFAULT '0' COMMENT '用户'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_goods','activity_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_goods')." ADD   `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_goods','shop_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_goods')." ADD   `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_goods','title')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_goods')." ADD   `title` varchar(255) NOT NULL COMMENT '商品名'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_goods','image')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_goods')." ADD   `image` varchar(255) NOT NULL COMMENT '商品主图'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_goods','oprice')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_goods')." ADD   `oprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_goods','gnum')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_goods')." ADD   `gnum` int(11) NOT NULL DEFAULT '0' COMMENT '数量'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_goods','rprice')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_goods')." ADD   `rprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '底价'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_goods','times')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_goods')." ADD   `times` int(11) NOT NULL DEFAULT '0' COMMENT '砍价次数'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_goods','desc')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_goods')." ADD   `desc` varchar(255) NOT NULL COMMENT '商品描述'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_goods','participate')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_goods')." ADD   `participate` int(11) NOT NULL DEFAULT '0' COMMENT '参与人数'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_goods','success')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_goods')." ADD   `success` int(11) NOT NULL DEFAULT '0' COMMENT '购买成功'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_goods','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_goods')." ADD   `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,启用;0,禁用;'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_goods','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_goods')." ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '下单时间'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_goods','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_goods')." ADD   PRIMARY KEY (`id`)");}
if(!pdo_fieldexists('umiacp_bargainsimple3_goods','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_goods')." ADD   KEY `uniacid` (`uniacid`)");}
if(!pdo_fieldexists('umiacp_bargainsimple3_goods','mid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_goods')." ADD   KEY `mid` (`mid`)");}
if(!pdo_fieldexists('umiacp_bargainsimple3_goods','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_goods')." ADD   KEY `status` (`status`)");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_bargainsimple3_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `user_type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '类型:1,后台;2,公众号;3,小程序;',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户uid',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '用户mid',
  `username` varchar(50) NOT NULL COMMENT '操作员昵称',
  `op` varchar(255) NOT NULL COMMENT '操作位置',
  `content` varchar(255) NOT NULL COMMENT '具体操作',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '操作时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `type` (`user_type`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `mid` (`mid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_bargainsimple3_log','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_log')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_bargainsimple3_log','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_log')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_log','user_type')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_log')." ADD   `user_type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '类型:1,后台;2,公众号;3,小程序;'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_log','uid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_log')." ADD   `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户uid'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_log','mid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_log')." ADD   `mid` int(11) NOT NULL DEFAULT '0' COMMENT '用户mid'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_log','username')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_log')." ADD   `username` varchar(50) NOT NULL COMMENT '操作员昵称'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_log','op')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_log')." ADD   `op` varchar(255) NOT NULL COMMENT '操作位置'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_log','content')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_log')." ADD   `content` varchar(255) NOT NULL COMMENT '具体操作'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_log','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_log')." ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '操作时间'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_log','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_log')." ADD   PRIMARY KEY (`id`) USING BTREE");}
if(!pdo_fieldexists('umiacp_bargainsimple3_log','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_log')." ADD   KEY `uniacid` (`uniacid`) USING BTREE");}
if(!pdo_fieldexists('umiacp_bargainsimple3_log','type')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_log')." ADD   KEY `type` (`user_type`) USING BTREE");}
if(!pdo_fieldexists('umiacp_bargainsimple3_log','uid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_log')." ADD   KEY `uid` (`uid`) USING BTREE");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_bargainsimple3_member` (
  `mid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `openid` varchar(255) NOT NULL COMMENT '用户',
  `wxopenid` varchar(255) NOT NULL COMMENT '小程序',
  `fmid` tinyint(11) NOT NULL DEFAULT '0' COMMENT '推荐人',
  `realname` varchar(255) NOT NULL COMMENT '姓名',
  `mobile` varchar(255) NOT NULL COMMENT '手机号',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `salt` varchar(255) NOT NULL COMMENT '秘钥',
  `status` tinyint(3) NOT NULL COMMENT '状态:1,;2,;',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间',
  PRIMARY KEY (`mid`),
  KEY `uniacid` (`uniacid`),
  KEY `openid` (`openid`,`wxopenid`),
  KEY `mobile` (`mobile`),
  KEY `status` (`status`),
  KEY `wxopenid` (`wxopenid`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_bargainsimple3_member','mid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_member')." ADD 
  `mid` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_bargainsimple3_member','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_member')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_member','openid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_member')." ADD   `openid` varchar(255) NOT NULL COMMENT '用户'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_member','wxopenid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_member')." ADD   `wxopenid` varchar(255) NOT NULL COMMENT '小程序'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_member','fmid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_member')." ADD   `fmid` tinyint(11) NOT NULL DEFAULT '0' COMMENT '推荐人'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_member','realname')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_member')." ADD   `realname` varchar(255) NOT NULL COMMENT '姓名'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_member','mobile')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_member')." ADD   `mobile` varchar(255) NOT NULL COMMENT '手机号'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_member','password')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_member')." ADD   `password` varchar(255) NOT NULL COMMENT '密码'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_member','salt')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_member')." ADD   `salt` varchar(255) NOT NULL COMMENT '秘钥'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_member','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_member')." ADD   `status` tinyint(3) NOT NULL COMMENT '状态:1,;2,;'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_member','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_member')." ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_member','mid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_member')." ADD   PRIMARY KEY (`mid`)");}
if(!pdo_fieldexists('umiacp_bargainsimple3_member','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_member')." ADD   KEY `uniacid` (`uniacid`)");}
if(!pdo_fieldexists('umiacp_bargainsimple3_member','openid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_member')." ADD   KEY `openid` (`openid`,`wxopenid`)");}
if(!pdo_fieldexists('umiacp_bargainsimple3_member','mobile')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_member')." ADD   KEY `mobile` (`mobile`)");}
if(!pdo_fieldexists('umiacp_bargainsimple3_member','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_member')." ADD   KEY `status` (`status`)");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_bargainsimple3_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '用户',
  `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家',
  `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动',
  `goods_id` int(11) NOT NULL COMMENT '商品',
  `cut_id` int(11) NOT NULL DEFAULT '0' COMMENT '砍价',
  `title` varchar(255) NOT NULL COMMENT '商品名',
  `ordersn` varchar(255) NOT NULL COMMENT '订单号',
  `tid` varchar(255) NOT NULL COMMENT '商户订单号',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `pay_time` int(11) NOT NULL DEFAULT '0' COMMENT '支付时间',
  `transid` varchar(255) NOT NULL COMMENT '微信支付单号',
  `saler_id` int(11) NOT NULL DEFAULT '0' COMMENT '核销员',
  `saler_openid` varchar(255) NOT NULL,
  `salertime` int(11) NOT NULL DEFAULT '0' COMMENT '核销时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,待支付;2,已支付;3,已核销;4,已取消;5,申请退款;6,已退款;',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '下单时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `tid` (`tid`),
  KEY `ordersn` (`ordersn`),
  KEY `status` (`status`),
  KEY `mid` (`mid`,`shop_id`,`activity_id`,`goods_id`,`cut_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=151 DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_bargainsimple3_order','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_order')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_bargainsimple3_order','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_order')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_order','mid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_order')." ADD   `mid` int(11) NOT NULL DEFAULT '0' COMMENT '用户'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_order','shop_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_order')." ADD   `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_order','activity_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_order')." ADD   `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_order','goods_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_order')." ADD   `goods_id` int(11) NOT NULL COMMENT '商品'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_order','cut_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_order')." ADD   `cut_id` int(11) NOT NULL DEFAULT '0' COMMENT '砍价'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_order','title')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_order')." ADD   `title` varchar(255) NOT NULL COMMENT '商品名'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_order','ordersn')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_order')." ADD   `ordersn` varchar(255) NOT NULL COMMENT '订单号'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_order','tid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_order')." ADD   `tid` varchar(255) NOT NULL COMMENT '商户订单号'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_order','price')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_order')." ADD   `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '金额'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_order','pay_time')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_order')." ADD   `pay_time` int(11) NOT NULL DEFAULT '0' COMMENT '支付时间'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_order','transid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_order')." ADD   `transid` varchar(255) NOT NULL COMMENT '微信支付单号'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_order','saler_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_order')." ADD   `saler_id` int(11) NOT NULL DEFAULT '0' COMMENT '核销员'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_order','saler_openid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_order')." ADD   `saler_openid` varchar(255) NOT NULL");}
if(!pdo_fieldexists('umiacp_bargainsimple3_order','salertime')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_order')." ADD   `salertime` int(11) NOT NULL DEFAULT '0' COMMENT '核销时间'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_order','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_order')." ADD   `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,待支付;2,已支付;3,已核销;4,已取消;5,申请退款;6,已退款;'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_order','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_order')." ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '下单时间'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_order','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_order')." ADD   PRIMARY KEY (`id`)");}
if(!pdo_fieldexists('umiacp_bargainsimple3_order','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_order')." ADD   KEY `uniacid` (`uniacid`)");}
if(!pdo_fieldexists('umiacp_bargainsimple3_order','tid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_order')." ADD   KEY `tid` (`tid`)");}
if(!pdo_fieldexists('umiacp_bargainsimple3_order','ordersn')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_order')." ADD   KEY `ordersn` (`ordersn`)");}
if(!pdo_fieldexists('umiacp_bargainsimple3_order','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_order')." ADD   KEY `status` (`status`)");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_bargainsimple3_puv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `page` varchar(255) NOT NULL COMMENT '页面',
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品',
  `pv` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '总浏览人次',
  `uv` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '总浏览人数',
  `createtime` int(11) NOT NULL COMMENT '总访问时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `page` (`page`),
  KEY `goods_id` (`goods_id`),
  KEY `createtime` (`createtime`)
) ENGINE=InnoDB AUTO_INCREMENT=216 DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_bargainsimple3_puv','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_puv')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_bargainsimple3_puv','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_puv')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_puv','page')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_puv')." ADD   `page` varchar(255) NOT NULL COMMENT '页面'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_puv','goods_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_puv')." ADD   `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_puv','pv')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_puv')." ADD   `pv` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '总浏览人次'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_puv','uv')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_puv')." ADD   `uv` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '总浏览人数'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_puv','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_puv')." ADD   `createtime` int(11) NOT NULL COMMENT '总访问时间'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_puv','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_puv')." ADD   PRIMARY KEY (`id`) USING BTREE");}
if(!pdo_fieldexists('umiacp_bargainsimple3_puv','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_puv')." ADD   KEY `uniacid` (`uniacid`) USING BTREE");}
if(!pdo_fieldexists('umiacp_bargainsimple3_puv','page')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_puv')." ADD   KEY `page` (`page`)");}
if(!pdo_fieldexists('umiacp_bargainsimple3_puv','goods_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_puv')." ADD   KEY `goods_id` (`goods_id`)");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_bargainsimple3_puv_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '访问商品',
  `page` varchar(50) NOT NULL COMMENT '访问页面',
  `input` text COMMENT '用户输入',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '访问时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `openid` (`openid`) USING BTREE,
  KEY `page` (`page`) USING BTREE,
  KEY `goods_id` (`goods_id`),
  KEY `createtime` (`createtime`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6024 DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_bargainsimple3_puv_record','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_puv_record')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_bargainsimple3_puv_record','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_puv_record')." ADD   `uniacid` int(11) NOT NULL");}
if(!pdo_fieldexists('umiacp_bargainsimple3_puv_record','openid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_puv_record')." ADD   `openid` varchar(50) NOT NULL");}
if(!pdo_fieldexists('umiacp_bargainsimple3_puv_record','goods_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_puv_record')." ADD   `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '访问商品'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_puv_record','page')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_puv_record')." ADD   `page` varchar(50) NOT NULL COMMENT '访问页面'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_puv_record','input')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_puv_record')." ADD   `input` text COMMENT '用户输入'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_puv_record','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_puv_record')." ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '访问时间'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_puv_record','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_puv_record')." ADD   PRIMARY KEY (`id`) USING BTREE");}
if(!pdo_fieldexists('umiacp_bargainsimple3_puv_record','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_puv_record')." ADD   KEY `uniacid` (`uniacid`) USING BTREE");}
if(!pdo_fieldexists('umiacp_bargainsimple3_puv_record','openid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_puv_record')." ADD   KEY `openid` (`openid`) USING BTREE");}
if(!pdo_fieldexists('umiacp_bargainsimple3_puv_record','page')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_puv_record')." ADD   KEY `page` (`page`) USING BTREE");}
if(!pdo_fieldexists('umiacp_bargainsimple3_puv_record','goods_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_puv_record')." ADD   KEY `goods_id` (`goods_id`)");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_bargainsimple3_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家',
  `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动',
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品',
  `cut_id` int(11) NOT NULL DEFAULT '0' COMMENT '砍价',
  `fmid` int(11) NOT NULL DEFAULT '0' COMMENT '发起人',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '砍价人',
  `oprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价',
  `nprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '现价',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '砍价',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,砍价中;2,砍到底;',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '砍价时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `mid` (`mid`),
  KEY `status` (`status`),
  KEY `activity_id` (`activity_id`,`goods_id`,`cut_id`,`shop_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=525 DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_bargainsimple3_record','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_record')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_bargainsimple3_record','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_record')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_record','shop_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_record')." ADD   `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_record','activity_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_record')." ADD   `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_record','goods_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_record')." ADD   `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_record','cut_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_record')." ADD   `cut_id` int(11) NOT NULL DEFAULT '0' COMMENT '砍价'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_record','fmid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_record')." ADD   `fmid` int(11) NOT NULL DEFAULT '0' COMMENT '发起人'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_record','mid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_record')." ADD   `mid` int(11) NOT NULL DEFAULT '0' COMMENT '砍价人'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_record','oprice')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_record')." ADD   `oprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_record','nprice')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_record')." ADD   `nprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '现价'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_record','price')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_record')." ADD   `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '砍价'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_record','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_record')." ADD   `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,砍价中;2,砍到底;'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_record','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_record')." ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '砍价时间'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_record','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_record')." ADD   PRIMARY KEY (`id`)");}
if(!pdo_fieldexists('umiacp_bargainsimple3_record','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_record')." ADD   KEY `uniacid` (`uniacid`)");}
if(!pdo_fieldexists('umiacp_bargainsimple3_record','mid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_record')." ADD   KEY `mid` (`mid`)");}
if(!pdo_fieldexists('umiacp_bargainsimple3_record','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_record')." ADD   KEY `status` (`status`)");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_bargainsimple3_saler` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '用户',
  `openid` varchar(255) NOT NULL,
  `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家',
  `realname` varchar(255) NOT NULL COMMENT '姓名',
  `mobile` varchar(255) NOT NULL COMMENT '电话',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,启用;0,禁用;',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `activity_id` int(11) NOT NULL COMMENT '活动id',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `mid` (`mid`),
  KEY `status` (`status`),
  KEY `shop_id` (`shop_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_bargainsimple3_saler','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_saler')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_bargainsimple3_saler','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_saler')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_saler','mid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_saler')." ADD   `mid` int(11) NOT NULL DEFAULT '0' COMMENT '用户'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_saler','openid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_saler')." ADD   `openid` varchar(255) NOT NULL");}
if(!pdo_fieldexists('umiacp_bargainsimple3_saler','shop_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_saler')." ADD   `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_saler','realname')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_saler')." ADD   `realname` varchar(255) NOT NULL COMMENT '姓名'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_saler','mobile')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_saler')." ADD   `mobile` varchar(255) NOT NULL COMMENT '电话'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_saler','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_saler')." ADD   `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,启用;0,禁用;'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_saler','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_saler')." ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_saler','activity_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_saler')." ADD   `activity_id` int(11) NOT NULL COMMENT '活动id'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_saler','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_saler')." ADD   PRIMARY KEY (`id`)");}
if(!pdo_fieldexists('umiacp_bargainsimple3_saler','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_saler')." ADD   KEY `uniacid` (`uniacid`)");}
if(!pdo_fieldexists('umiacp_bargainsimple3_saler','mid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_saler')." ADD   KEY `mid` (`mid`)");}
if(!pdo_fieldexists('umiacp_bargainsimple3_saler','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_saler')." ADD   KEY `status` (`status`)");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_bargainsimple3_setting` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `skey` varchar(255) NOT NULL COMMENT '键',
  `svalue` text COMMENT '值',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `skey` (`skey`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_bargainsimple3_setting','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_setting')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_bargainsimple3_setting','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_setting')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_setting','skey')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_setting')." ADD   `skey` varchar(255) NOT NULL COMMENT '键'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_setting','svalue')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_setting')." ADD   `svalue` text COMMENT '值'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_setting','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_setting')." ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间'");}
if(!pdo_fieldexists('umiacp_bargainsimple3_setting','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_setting')." ADD   PRIMARY KEY (`id`) USING BTREE");}
if(!pdo_fieldexists('umiacp_bargainsimple3_setting','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_bargainsimple3_setting')." ADD   KEY `uniacid` (`uniacid`) USING BTREE");}
