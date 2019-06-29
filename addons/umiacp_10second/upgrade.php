<?php
//自己删除上面的符号
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_activity` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家',
  `title` varchar(255) NOT NULL COMMENT '活动标题',
  `titleimg` varchar(255) DEFAULT NULL COMMENT '第一页标题',
  `title2` varchar(255) DEFAULT NULL COMMENT '第二页标题',
  `title3` varchar(255) DEFAULT NULL COMMENT '游戏页标题',
  `image` varchar(255) NOT NULL COMMENT '第一页主题',
  `bgimage` varchar(255) NOT NULL COMMENT '背景图',
  `bgimage2` varchar(255) DEFAULT NULL COMMENT '背景图',
  `bgimage3` varchar(255) DEFAULT NULL COMMENT '游戏页背景',
  `bgimage4` varchar(255) DEFAULT NULL COMMENT '排行榜背景图',
  `bgimage_my` varchar(255) DEFAULT NULL COMMENT '背景图',
  `music` varchar(255) NOT NULL COMMENT '背景音乐',
  `logo` varchar(255) NOT NULL COMMENT '第一页logo',
  `starttime` int(11) NOT NULL COMMENT '活动开始时间',
  `endtime` int(11) NOT NULL COMMENT '活动结束时间',
  `desc2` varchar(255) NOT NULL COMMENT '第二页说明',
  `desc3` varchar(255) DEFAULT NULL COMMENT '活动须知',
  `rule3` varchar(255) NOT NULL COMMENT '游戏页规则',
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
  `s_image` varchar(255) DEFAULT NULL COMMENT '分享图',
  `bgimage_share` varchar(255) DEFAULT NULL COMMENT '助力页背景图',
  `daynum` int(11) NOT NULL DEFAULT '0' COMMENT '每日初始次数',
  `allnum` int(11) NOT NULL DEFAULT '0' COMMENT '每日上限次数',
  `min_redpack` int(11) DEFAULT NULL COMMENT '起始金额',
  `max_redpack` int(11) DEFAULT NULL COMMENT '上限金额',
  `diff` decimal(10,4) NOT NULL DEFAULT '0.0000' COMMENT '差值',
  `act_name` varchar(255) DEFAULT NULL COMMENT '发送红包名称',
  `wishing` varchar(255) DEFAULT NULL COMMENT '发送红包祝福语',
  `send_name` varchar(255) DEFAULT NULL COMMENT '发送红包活动名称',
  `random` int(11) DEFAULT '0' COMMENT '中奖概率',
  `prize_status` int(11) NOT NULL DEFAULT '1' COMMENT '奖品模式  1红包 2奖品',
  `titlebgimg` varchar(255) DEFAULT NULL COMMENT '标题背景框',
  `share_img` varchar(255) DEFAULT NULL COMMENT '分享图',
  `share_title` varchar(255) DEFAULT NULL COMMENT '分享标题',
  `share_desc` varchar(255) DEFAULT NULL COMMENT '分享描述',
  `regional` int(11) NOT NULL DEFAULT '1' COMMENT '区域限制:1不限制;2限制;',
  `r_address` text COMMENT '活动区域限制地区',
  `ak` varchar(255) DEFAULT NULL COMMENT '百度密钥',
  `shop_code` varchar(255) DEFAULT NULL COMMENT '商家图片（二维码）',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `title` (`title`) USING BTREE,
  KEY `shop_id` (`shop_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=141 DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_10second_activity','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_10second_activity','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_10second_activity','shop_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家'");}
if(!pdo_fieldexists('umiacp_10second_activity','title')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `title` varchar(255) NOT NULL COMMENT '活动标题'");}
if(!pdo_fieldexists('umiacp_10second_activity','titleimg')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `titleimg` varchar(255) DEFAULT NULL COMMENT '第一页标题'");}
if(!pdo_fieldexists('umiacp_10second_activity','title2')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `title2` varchar(255) DEFAULT NULL COMMENT '第二页标题'");}
if(!pdo_fieldexists('umiacp_10second_activity','title3')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `title3` varchar(255) DEFAULT NULL COMMENT '游戏页标题'");}
if(!pdo_fieldexists('umiacp_10second_activity','image')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `image` varchar(255) NOT NULL COMMENT '第一页主题'");}
if(!pdo_fieldexists('umiacp_10second_activity','bgimage')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `bgimage` varchar(255) NOT NULL COMMENT '背景图'");}
if(!pdo_fieldexists('umiacp_10second_activity','bgimage2')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `bgimage2` varchar(255) DEFAULT NULL COMMENT '背景图'");}
if(!pdo_fieldexists('umiacp_10second_activity','bgimage3')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `bgimage3` varchar(255) DEFAULT NULL COMMENT '游戏页背景'");}
if(!pdo_fieldexists('umiacp_10second_activity','bgimage4')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `bgimage4` varchar(255) DEFAULT NULL COMMENT '排行榜背景图'");}
if(!pdo_fieldexists('umiacp_10second_activity','bgimage_my')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `bgimage_my` varchar(255) DEFAULT NULL COMMENT '背景图'");}
if(!pdo_fieldexists('umiacp_10second_activity','music')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `music` varchar(255) NOT NULL COMMENT '背景音乐'");}
if(!pdo_fieldexists('umiacp_10second_activity','logo')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `logo` varchar(255) NOT NULL COMMENT '第一页logo'");}
if(!pdo_fieldexists('umiacp_10second_activity','starttime')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `starttime` int(11) NOT NULL COMMENT '活动开始时间'");}
if(!pdo_fieldexists('umiacp_10second_activity','endtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `endtime` int(11) NOT NULL COMMENT '活动结束时间'");}
if(!pdo_fieldexists('umiacp_10second_activity','desc2')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `desc2` varchar(255) NOT NULL COMMENT '第二页说明'");}
if(!pdo_fieldexists('umiacp_10second_activity','desc3')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `desc3` varchar(255) DEFAULT NULL COMMENT '活动须知'");}
if(!pdo_fieldexists('umiacp_10second_activity','rule3')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `rule3` varchar(255) NOT NULL COMMENT '游戏页规则'");}
if(!pdo_fieldexists('umiacp_10second_activity','shop_name')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `shop_name` varchar(255) NOT NULL COMMENT '商家姓名'");}
if(!pdo_fieldexists('umiacp_10second_activity','shop_mobile')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `shop_mobile` varchar(255) NOT NULL COMMENT '商家热线'");}
if(!pdo_fieldexists('umiacp_10second_activity','shop_province')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `shop_province` varchar(255) NOT NULL COMMENT '省'");}
if(!pdo_fieldexists('umiacp_10second_activity','shop_city')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `shop_city` varchar(255) NOT NULL COMMENT '市'");}
if(!pdo_fieldexists('umiacp_10second_activity','shop_district')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `shop_district` varchar(255) NOT NULL COMMENT '区'");}
if(!pdo_fieldexists('umiacp_10second_activity','shop_address')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `shop_address` varchar(255) NOT NULL COMMENT '商家详细地址'");}
if(!pdo_fieldexists('umiacp_10second_activity','userinfo')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `userinfo` text COMMENT '用户信息收集'");}
if(!pdo_fieldexists('umiacp_10second_activity','pv')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `pv` int(11) NOT NULL DEFAULT '0' COMMENT '查看人数'");}
if(!pdo_fieldexists('umiacp_10second_activity','share')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `share` int(11) NOT NULL DEFAULT '0' COMMENT '分享人数'");}
if(!pdo_fieldexists('umiacp_10second_activity','participate')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `participate` int(11) NOT NULL DEFAULT '0' COMMENT '参与人数'");}
if(!pdo_fieldexists('umiacp_10second_activity','success')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `success` int(11) NOT NULL DEFAULT '0' COMMENT '成功人数'");}
if(!pdo_fieldexists('umiacp_10second_activity','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,上架;2,下架;3,未开始;'");}
if(!pdo_fieldexists('umiacp_10second_activity','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间'");}
if(!pdo_fieldexists('umiacp_10second_activity','s_image')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `s_image` varchar(255) DEFAULT NULL COMMENT '分享图'");}
if(!pdo_fieldexists('umiacp_10second_activity','bgimage_share')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `bgimage_share` varchar(255) DEFAULT NULL COMMENT '助力页背景图'");}
if(!pdo_fieldexists('umiacp_10second_activity','daynum')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `daynum` int(11) NOT NULL DEFAULT '0' COMMENT '每日初始次数'");}
if(!pdo_fieldexists('umiacp_10second_activity','allnum')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `allnum` int(11) NOT NULL DEFAULT '0' COMMENT '每日上限次数'");}
if(!pdo_fieldexists('umiacp_10second_activity','min_redpack')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `min_redpack` int(11) DEFAULT NULL COMMENT '起始金额'");}
if(!pdo_fieldexists('umiacp_10second_activity','max_redpack')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `max_redpack` int(11) DEFAULT NULL COMMENT '上限金额'");}
if(!pdo_fieldexists('umiacp_10second_activity','diff')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `diff` decimal(10,4) NOT NULL DEFAULT '0.0000' COMMENT '差值'");}
if(!pdo_fieldexists('umiacp_10second_activity','act_name')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `act_name` varchar(255) DEFAULT NULL COMMENT '发送红包名称'");}
if(!pdo_fieldexists('umiacp_10second_activity','wishing')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `wishing` varchar(255) DEFAULT NULL COMMENT '发送红包祝福语'");}
if(!pdo_fieldexists('umiacp_10second_activity','send_name')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `send_name` varchar(255) DEFAULT NULL COMMENT '发送红包活动名称'");}
if(!pdo_fieldexists('umiacp_10second_activity','random')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `random` int(11) DEFAULT '0' COMMENT '中奖概率'");}
if(!pdo_fieldexists('umiacp_10second_activity','prize_status')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `prize_status` int(11) NOT NULL DEFAULT '1' COMMENT '奖品模式  1红包 2奖品'");}
if(!pdo_fieldexists('umiacp_10second_activity','titlebgimg')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `titlebgimg` varchar(255) DEFAULT NULL COMMENT '标题背景框'");}
if(!pdo_fieldexists('umiacp_10second_activity','share_img')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `share_img` varchar(255) DEFAULT NULL COMMENT '分享图'");}
if(!pdo_fieldexists('umiacp_10second_activity','share_title')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `share_title` varchar(255) DEFAULT NULL COMMENT '分享标题'");}
if(!pdo_fieldexists('umiacp_10second_activity','share_desc')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `share_desc` varchar(255) DEFAULT NULL COMMENT '分享描述'");}
if(!pdo_fieldexists('umiacp_10second_activity','regional')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `regional` int(11) NOT NULL DEFAULT '1' COMMENT '区域限制:1不限制;2限制;'");}
if(!pdo_fieldexists('umiacp_10second_activity','r_address')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `r_address` text COMMENT '活动区域限制地区'");}
if(!pdo_fieldexists('umiacp_10second_activity','ak')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `ak` varchar(255) DEFAULT NULL COMMENT '百度密钥'");}
if(!pdo_fieldexists('umiacp_10second_activity','shop_code')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   `shop_code` varchar(255) DEFAULT NULL COMMENT '商家图片（二维码）'");}
if(!pdo_fieldexists('umiacp_10second_activity','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   PRIMARY KEY (`id`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_activity','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   KEY `uniacid` (`uniacid`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_activity','title')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity')." ADD   KEY `title` (`title`) USING BTREE");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_activity_prize` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `activity_id` int(11) NOT NULL COMMENT '活动',
  `title` varchar(255) NOT NULL COMMENT '奖品',
  `image` varchar(255) NOT NULL COMMENT '图片',
  `odds` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '中奖概率',
  `num` int(11) NOT NULL DEFAULT '-1' COMMENT '奖品数量',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:0,未中奖;1,中奖;',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `shop_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `activity_id` (`activity_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_10second_activity_prize','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity_prize')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_10second_activity_prize','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity_prize')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_10second_activity_prize','activity_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity_prize')." ADD   `activity_id` int(11) NOT NULL COMMENT '活动'");}
if(!pdo_fieldexists('umiacp_10second_activity_prize','title')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity_prize')." ADD   `title` varchar(255) NOT NULL COMMENT '奖品'");}
if(!pdo_fieldexists('umiacp_10second_activity_prize','image')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity_prize')." ADD   `image` varchar(255) NOT NULL COMMENT '图片'");}
if(!pdo_fieldexists('umiacp_10second_activity_prize','odds')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity_prize')." ADD   `odds` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '中奖概率'");}
if(!pdo_fieldexists('umiacp_10second_activity_prize','num')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity_prize')." ADD   `num` int(11) NOT NULL DEFAULT '-1' COMMENT '奖品数量'");}
if(!pdo_fieldexists('umiacp_10second_activity_prize','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity_prize')." ADD   `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:0,未中奖;1,中奖;'");}
if(!pdo_fieldexists('umiacp_10second_activity_prize','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity_prize')." ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间'");}
if(!pdo_fieldexists('umiacp_10second_activity_prize','shop_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity_prize')." ADD   `shop_id` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_10second_activity_prize','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity_prize')." ADD   PRIMARY KEY (`id`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_activity_prize','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity_prize')." ADD   KEY `uniacid` (`uniacid`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_activity_prize','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_activity_prize')." ADD   KEY `status` (`status`) USING BTREE");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_boost` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `openid` varchar(255) NOT NULL COMMENT '助力人',
  `fopenid` varchar(255) NOT NULL COMMENT '被助力人',
  `day` int(11) NOT NULL,
  `activetime` int(11) NOT NULL COMMENT '活动时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  `createtime` int(3) NOT NULL DEFAULT '0' COMMENT '助力时间',
  `activity_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `openid` (`openid`) USING BTREE,
  KEY `fopenid` (`fopenid`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `activetime` (`activetime`) USING BTREE,
  KEY `day` (`day`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_10second_boost','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_boost')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_10second_boost','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_boost')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_10second_boost','openid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_boost')." ADD   `openid` varchar(255) NOT NULL COMMENT '助力人'");}
if(!pdo_fieldexists('umiacp_10second_boost','fopenid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_boost')." ADD   `fopenid` varchar(255) NOT NULL COMMENT '被助力人'");}
if(!pdo_fieldexists('umiacp_10second_boost','day')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_boost')." ADD   `day` int(11) NOT NULL");}
if(!pdo_fieldexists('umiacp_10second_boost','activetime')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_boost')." ADD   `activetime` int(11) NOT NULL COMMENT '活动时间'");}
if(!pdo_fieldexists('umiacp_10second_boost','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_boost')." ADD   `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态'");}
if(!pdo_fieldexists('umiacp_10second_boost','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_boost')." ADD   `createtime` int(3) NOT NULL DEFAULT '0' COMMENT '助力时间'");}
if(!pdo_fieldexists('umiacp_10second_boost','activity_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_boost')." ADD   `activity_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('umiacp_10second_boost','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_boost')." ADD   PRIMARY KEY (`id`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_boost','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_boost')." ADD   KEY `uniacid` (`uniacid`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_boost','openid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_boost')." ADD   KEY `openid` (`openid`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_boost','fopenid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_boost')." ADD   KEY `fopenid` (`fopenid`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_boost','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_boost')." ADD   KEY `status` (`status`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_boost','activetime')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_boost')." ADD   KEY `activetime` (`activetime`) USING BTREE");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_cate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
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

if(!pdo_fieldexists('umiacp_10second_cate','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cate')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_10second_cate','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cate')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_10second_cate','title')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cate')." ADD   `title` varchar(255) NOT NULL COMMENT '分类名'");}
if(!pdo_fieldexists('umiacp_10second_cate','logo')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cate')." ADD   `logo` varchar(255) NOT NULL COMMENT '分类logo'");}
if(!pdo_fieldexists('umiacp_10second_cate','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cate')." ADD   `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态'");}
if(!pdo_fieldexists('umiacp_10second_cate','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cate')." ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间'");}
if(!pdo_fieldexists('umiacp_10second_cate','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cate')." ADD   PRIMARY KEY (`id`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_cate','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cate')." ADD   KEY `uniacid` (`uniacid`) USING BTREE");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_complain` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动',
  `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家',
  `reason` varchar(255) NOT NULL DEFAULT '0' COMMENT '投诉原因',
  `desc` varchar(255) NOT NULL COMMENT '投诉描述',
  `mobile` varchar(255) NOT NULL COMMENT '手机号',
  `userinfo` varchar(255) DEFAULT NULL COMMENT '具体用户信息',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '投诉时间',
  `title` varchar(255) DEFAULT NULL COMMENT '活动标题',
  `status` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `realname` (`desc`,`mobile`) USING BTREE,
  KEY `activity_id` (`activity_id`,`shop_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=155 DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_10second_complain','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_complain')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_10second_complain','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_complain')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_10second_complain','activity_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_complain')." ADD   `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动'");}
if(!pdo_fieldexists('umiacp_10second_complain','shop_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_complain')." ADD   `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家'");}
if(!pdo_fieldexists('umiacp_10second_complain','reason')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_complain')." ADD   `reason` varchar(255) NOT NULL DEFAULT '0' COMMENT '投诉原因'");}
if(!pdo_fieldexists('umiacp_10second_complain','desc')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_complain')." ADD   `desc` varchar(255) NOT NULL COMMENT '投诉描述'");}
if(!pdo_fieldexists('umiacp_10second_complain','mobile')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_complain')." ADD   `mobile` varchar(255) NOT NULL COMMENT '手机号'");}
if(!pdo_fieldexists('umiacp_10second_complain','userinfo')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_complain')." ADD   `userinfo` varchar(255) DEFAULT NULL COMMENT '具体用户信息'");}
if(!pdo_fieldexists('umiacp_10second_complain','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_complain')." ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '投诉时间'");}
if(!pdo_fieldexists('umiacp_10second_complain','title')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_complain')." ADD   `title` varchar(255) DEFAULT NULL COMMENT '活动标题'");}
if(!pdo_fieldexists('umiacp_10second_complain','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_complain')." ADD   `status` int(11) DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_10second_complain','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_complain')." ADD   PRIMARY KEY (`id`)");}
if(!pdo_fieldexists('umiacp_10second_complain','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_complain')." ADD   KEY `uniacid` (`uniacid`)");}
if(!pdo_fieldexists('umiacp_10second_complain','realname')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_complain')." ADD   KEY `realname` (`desc`,`mobile`) USING BTREE");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_cut` (
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
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,已报名;2,已支付;3,已购买;4已取消',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '发起时间',
  `lottery_time` int(11) NOT NULL DEFAULT '0' COMMENT '核销时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `mid` (`mid`),
  KEY `status` (`status`),
  KEY `realname` (`realname`,`mobile`) USING BTREE,
  KEY `activity_id` (`activity_id`,`goods_id`,`shop_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=165 DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_10second_cut','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cut')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_10second_cut','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cut')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_10second_cut','mid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cut')." ADD   `mid` int(11) NOT NULL DEFAULT '0' COMMENT '砍价人'");}
if(!pdo_fieldexists('umiacp_10second_cut','activity_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cut')." ADD   `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动'");}
if(!pdo_fieldexists('umiacp_10second_cut','shop_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cut')." ADD   `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家'");}
if(!pdo_fieldexists('umiacp_10second_cut','goods_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cut')." ADD   `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品'");}
if(!pdo_fieldexists('umiacp_10second_cut','oprice')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cut')." ADD   `oprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价'");}
if(!pdo_fieldexists('umiacp_10second_cut','nprice')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cut')." ADD   `nprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '现价'");}
if(!pdo_fieldexists('umiacp_10second_cut','rprice')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cut')." ADD   `rprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '底价'");}
if(!pdo_fieldexists('umiacp_10second_cut','price')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cut')." ADD   `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '砍价'");}
if(!pdo_fieldexists('umiacp_10second_cut','times')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cut')." ADD   `times` int(11) NOT NULL DEFAULT '0' COMMENT '剩余砍价次数'");}
if(!pdo_fieldexists('umiacp_10second_cut','realname')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cut')." ADD   `realname` varchar(255) NOT NULL COMMENT '姓名'");}
if(!pdo_fieldexists('umiacp_10second_cut','mobile')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cut')." ADD   `mobile` varchar(255) NOT NULL COMMENT '手机号'");}
if(!pdo_fieldexists('umiacp_10second_cut','userinfo')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cut')." ADD   `userinfo` text COMMENT '具体用户信息'");}
if(!pdo_fieldexists('umiacp_10second_cut','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cut')." ADD   `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,已报名;2,已支付;3,已购买;4已取消'");}
if(!pdo_fieldexists('umiacp_10second_cut','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cut')." ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '发起时间'");}
if(!pdo_fieldexists('umiacp_10second_cut','lottery_time')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cut')." ADD   `lottery_time` int(11) NOT NULL DEFAULT '0' COMMENT '核销时间'");}
if(!pdo_fieldexists('umiacp_10second_cut','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cut')." ADD   PRIMARY KEY (`id`)");}
if(!pdo_fieldexists('umiacp_10second_cut','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cut')." ADD   KEY `uniacid` (`uniacid`)");}
if(!pdo_fieldexists('umiacp_10second_cut','mid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cut')." ADD   KEY `mid` (`mid`)");}
if(!pdo_fieldexists('umiacp_10second_cut','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cut')." ADD   KEY `status` (`status`)");}
if(!pdo_fieldexists('umiacp_10second_cut','realname')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_cut')." ADD   KEY `realname` (`realname`,`mobile`) USING BTREE");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_goods` (
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
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_10second_goods','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_goods')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_10second_goods','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_goods')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_10second_goods','mid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_goods')." ADD   `mid` int(11) NOT NULL DEFAULT '0' COMMENT '用户'");}
if(!pdo_fieldexists('umiacp_10second_goods','activity_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_goods')." ADD   `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动'");}
if(!pdo_fieldexists('umiacp_10second_goods','shop_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_goods')." ADD   `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家'");}
if(!pdo_fieldexists('umiacp_10second_goods','title')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_goods')." ADD   `title` varchar(255) NOT NULL COMMENT '商品名'");}
if(!pdo_fieldexists('umiacp_10second_goods','image')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_goods')." ADD   `image` varchar(255) NOT NULL COMMENT '商品主图'");}
if(!pdo_fieldexists('umiacp_10second_goods','oprice')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_goods')." ADD   `oprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价'");}
if(!pdo_fieldexists('umiacp_10second_goods','gnum')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_goods')." ADD   `gnum` int(11) NOT NULL DEFAULT '0' COMMENT '数量'");}
if(!pdo_fieldexists('umiacp_10second_goods','rprice')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_goods')." ADD   `rprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '底价'");}
if(!pdo_fieldexists('umiacp_10second_goods','times')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_goods')." ADD   `times` int(11) NOT NULL DEFAULT '0' COMMENT '砍价次数'");}
if(!pdo_fieldexists('umiacp_10second_goods','desc')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_goods')." ADD   `desc` varchar(255) NOT NULL COMMENT '商品描述'");}
if(!pdo_fieldexists('umiacp_10second_goods','participate')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_goods')." ADD   `participate` int(11) NOT NULL DEFAULT '0' COMMENT '参与人数'");}
if(!pdo_fieldexists('umiacp_10second_goods','success')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_goods')." ADD   `success` int(11) NOT NULL DEFAULT '0' COMMENT '购买成功'");}
if(!pdo_fieldexists('umiacp_10second_goods','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_goods')." ADD   `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,启用;0,禁用;'");}
if(!pdo_fieldexists('umiacp_10second_goods','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_goods')." ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '下单时间'");}
if(!pdo_fieldexists('umiacp_10second_goods','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_goods')." ADD   PRIMARY KEY (`id`)");}
if(!pdo_fieldexists('umiacp_10second_goods','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_goods')." ADD   KEY `uniacid` (`uniacid`)");}
if(!pdo_fieldexists('umiacp_10second_goods','mid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_goods')." ADD   KEY `mid` (`mid`)");}
if(!pdo_fieldexists('umiacp_10second_goods','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_goods')." ADD   KEY `status` (`status`)");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `user_type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '类型 1 后台 2 公众号 3 小程序 ',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户uid',
  `openid` varchar(50) NOT NULL COMMENT '用户openid',
  `username` varchar(50) NOT NULL COMMENT '操作员昵称',
  `op` varchar(255) NOT NULL COMMENT '操作位置',
  `content` text COMMENT '具体操作',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '操作时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `type` (`user_type`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_10second_log','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_log')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_10second_log','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_log')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_10second_log','user_type')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_log')." ADD   `user_type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '类型 1 后台 2 公众号 3 小程序 '");}
if(!pdo_fieldexists('umiacp_10second_log','uid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_log')." ADD   `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户uid'");}
if(!pdo_fieldexists('umiacp_10second_log','openid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_log')." ADD   `openid` varchar(50) NOT NULL COMMENT '用户openid'");}
if(!pdo_fieldexists('umiacp_10second_log','username')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_log')." ADD   `username` varchar(50) NOT NULL COMMENT '操作员昵称'");}
if(!pdo_fieldexists('umiacp_10second_log','op')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_log')." ADD   `op` varchar(255) NOT NULL COMMENT '操作位置'");}
if(!pdo_fieldexists('umiacp_10second_log','content')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_log')." ADD   `content` text COMMENT '具体操作'");}
if(!pdo_fieldexists('umiacp_10second_log','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_log')." ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '操作时间'");}
if(!pdo_fieldexists('umiacp_10second_log','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_log')." ADD   PRIMARY KEY (`id`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_log','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_log')." ADD   KEY `uniacid` (`uniacid`) USING BTREE");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_member` (
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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_10second_member','mid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_member')." ADD 
  `mid` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_10second_member','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_member')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_10second_member','openid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_member')." ADD   `openid` varchar(255) NOT NULL COMMENT '用户'");}
if(!pdo_fieldexists('umiacp_10second_member','wxopenid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_member')." ADD   `wxopenid` varchar(255) NOT NULL COMMENT '小程序'");}
if(!pdo_fieldexists('umiacp_10second_member','fmid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_member')." ADD   `fmid` tinyint(11) NOT NULL DEFAULT '0' COMMENT '推荐人'");}
if(!pdo_fieldexists('umiacp_10second_member','realname')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_member')." ADD   `realname` varchar(255) NOT NULL COMMENT '姓名'");}
if(!pdo_fieldexists('umiacp_10second_member','mobile')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_member')." ADD   `mobile` varchar(255) NOT NULL COMMENT '手机号'");}
if(!pdo_fieldexists('umiacp_10second_member','password')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_member')." ADD   `password` varchar(255) NOT NULL COMMENT '密码'");}
if(!pdo_fieldexists('umiacp_10second_member','salt')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_member')." ADD   `salt` varchar(255) NOT NULL COMMENT '秘钥'");}
if(!pdo_fieldexists('umiacp_10second_member','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_member')." ADD   `status` tinyint(3) NOT NULL COMMENT '状态:1,;2,;'");}
if(!pdo_fieldexists('umiacp_10second_member','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_member')." ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间'");}
if(!pdo_fieldexists('umiacp_10second_member','mid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_member')." ADD   PRIMARY KEY (`mid`)");}
if(!pdo_fieldexists('umiacp_10second_member','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_member')." ADD   KEY `uniacid` (`uniacid`)");}
if(!pdo_fieldexists('umiacp_10second_member','openid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_member')." ADD   KEY `openid` (`openid`,`wxopenid`)");}
if(!pdo_fieldexists('umiacp_10second_member','mobile')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_member')." ADD   KEY `mobile` (`mobile`)");}
if(!pdo_fieldexists('umiacp_10second_member','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_member')." ADD   KEY `status` (`status`)");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `openid` varchar(255) NOT NULL,
  `temp_id` varchar(255) NOT NULL,
  `send` text,
  `url` varchar(255) NOT NULL,
  `result` text,
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,未发;2,已发;',
  `createtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_10second_message','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_message')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_10second_message','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_message')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_10second_message','openid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_message')." ADD   `openid` varchar(255) NOT NULL");}
if(!pdo_fieldexists('umiacp_10second_message','temp_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_message')." ADD   `temp_id` varchar(255) NOT NULL");}
if(!pdo_fieldexists('umiacp_10second_message','send')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_message')." ADD   `send` text");}
if(!pdo_fieldexists('umiacp_10second_message','url')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_message')." ADD   `url` varchar(255) NOT NULL");}
if(!pdo_fieldexists('umiacp_10second_message','result')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_message')." ADD   `result` text");}
if(!pdo_fieldexists('umiacp_10second_message','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_message')." ADD   `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,未发;2,已发;'");}
if(!pdo_fieldexists('umiacp_10second_message','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_message')." ADD   `createtime` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_10second_message','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_message')." ADD   PRIMARY KEY (`id`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_message','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_message')." ADD   KEY `uniacid` (`uniacid`) USING BTREE");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `openid` varchar(255) NOT NULL COMMENT '用户',
  `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动',
  `title` varchar(255) NOT NULL COMMENT '商品名',
  `ordersn` varchar(255) NOT NULL COMMENT '订单号',
  `tid` varchar(255) NOT NULL COMMENT '商户订单号',
  `price` decimal(10,2) NOT NULL COMMENT '金额',
  `pay_time` int(11) NOT NULL COMMENT '支付时间',
  `transid` varchar(255) NOT NULL COMMENT '微信支付单号',
  `status` tinyint(3) NOT NULL COMMENT '状态',
  `createtime` int(11) NOT NULL COMMENT '下单时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `openid` (`openid`) USING BTREE,
  KEY `tid` (`tid`) USING BTREE,
  KEY `ordersn` (`ordersn`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_10second_order','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_order')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_10second_order','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_order')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_10second_order','openid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_order')." ADD   `openid` varchar(255) NOT NULL COMMENT '用户'");}
if(!pdo_fieldexists('umiacp_10second_order','activity_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_order')." ADD   `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动'");}
if(!pdo_fieldexists('umiacp_10second_order','title')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_order')." ADD   `title` varchar(255) NOT NULL COMMENT '商品名'");}
if(!pdo_fieldexists('umiacp_10second_order','ordersn')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_order')." ADD   `ordersn` varchar(255) NOT NULL COMMENT '订单号'");}
if(!pdo_fieldexists('umiacp_10second_order','tid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_order')." ADD   `tid` varchar(255) NOT NULL COMMENT '商户订单号'");}
if(!pdo_fieldexists('umiacp_10second_order','price')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_order')." ADD   `price` decimal(10,2) NOT NULL COMMENT '金额'");}
if(!pdo_fieldexists('umiacp_10second_order','pay_time')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_order')." ADD   `pay_time` int(11) NOT NULL COMMENT '支付时间'");}
if(!pdo_fieldexists('umiacp_10second_order','transid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_order')." ADD   `transid` varchar(255) NOT NULL COMMENT '微信支付单号'");}
if(!pdo_fieldexists('umiacp_10second_order','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_order')." ADD   `status` tinyint(3) NOT NULL COMMENT '状态'");}
if(!pdo_fieldexists('umiacp_10second_order','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_order')." ADD   `createtime` int(11) NOT NULL COMMENT '下单时间'");}
if(!pdo_fieldexists('umiacp_10second_order','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_order')." ADD   PRIMARY KEY (`id`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_order','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_order')." ADD   KEY `uniacid` (`uniacid`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_order','openid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_order')." ADD   KEY `openid` (`openid`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_order','tid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_order')." ADD   KEY `tid` (`tid`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_order','ordersn')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_order')." ADD   KEY `ordersn` (`ordersn`) USING BTREE");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_prize_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `openid` varchar(255) NOT NULL COMMENT '用户',
  `realname` varchar(255) NOT NULL COMMENT '姓名',
  `mobile` varchar(255) NOT NULL COMMENT '电话',
  `activity_id` int(11) NOT NULL COMMENT '活动',
  `prize_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL COMMENT '奖品',
  `image` varchar(255) NOT NULL COMMENT '图片',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:0,未中奖;1,已中奖;2,已领奖',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '中奖时间',
  `mid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `openid` (`openid`) USING BTREE,
  KEY `activity_id` (`activity_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=158 DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_10second_prize_log','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_prize_log')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_10second_prize_log','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_prize_log')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_10second_prize_log','openid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_prize_log')." ADD   `openid` varchar(255) NOT NULL COMMENT '用户'");}
if(!pdo_fieldexists('umiacp_10second_prize_log','realname')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_prize_log')." ADD   `realname` varchar(255) NOT NULL COMMENT '姓名'");}
if(!pdo_fieldexists('umiacp_10second_prize_log','mobile')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_prize_log')." ADD   `mobile` varchar(255) NOT NULL COMMENT '电话'");}
if(!pdo_fieldexists('umiacp_10second_prize_log','activity_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_prize_log')." ADD   `activity_id` int(11) NOT NULL COMMENT '活动'");}
if(!pdo_fieldexists('umiacp_10second_prize_log','prize_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_prize_log')." ADD   `prize_id` int(11) NOT NULL");}
if(!pdo_fieldexists('umiacp_10second_prize_log','title')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_prize_log')." ADD   `title` varchar(255) NOT NULL COMMENT '奖品'");}
if(!pdo_fieldexists('umiacp_10second_prize_log','image')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_prize_log')." ADD   `image` varchar(255) NOT NULL COMMENT '图片'");}
if(!pdo_fieldexists('umiacp_10second_prize_log','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_prize_log')." ADD   `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:0,未中奖;1,已中奖;2,已领奖'");}
if(!pdo_fieldexists('umiacp_10second_prize_log','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_prize_log')." ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '中奖时间'");}
if(!pdo_fieldexists('umiacp_10second_prize_log','mid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_prize_log')." ADD   `mid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('umiacp_10second_prize_log','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_prize_log')." ADD   PRIMARY KEY (`id`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_prize_log','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_prize_log')." ADD   KEY `uniacid` (`uniacid`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_prize_log','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_prize_log')." ADD   KEY `status` (`status`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_prize_log','openid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_prize_log')." ADD   KEY `openid` (`openid`) USING BTREE");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_puv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `page` varchar(255) NOT NULL COMMENT '页面',
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品',
  `pv` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '总浏览人次',
  `uv` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '总浏览人数',
  `createtime` int(11) NOT NULL COMMENT '总访问时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `page` (`page`) USING BTREE,
  KEY `goods_id` (`goods_id`) USING BTREE,
  KEY `createtime` (`createtime`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_10second_puv','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_puv')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_10second_puv','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_puv')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_10second_puv','page')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_puv')." ADD   `page` varchar(255) NOT NULL COMMENT '页面'");}
if(!pdo_fieldexists('umiacp_10second_puv','goods_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_puv')." ADD   `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品'");}
if(!pdo_fieldexists('umiacp_10second_puv','pv')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_puv')." ADD   `pv` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '总浏览人次'");}
if(!pdo_fieldexists('umiacp_10second_puv','uv')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_puv')." ADD   `uv` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '总浏览人数'");}
if(!pdo_fieldexists('umiacp_10second_puv','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_puv')." ADD   `createtime` int(11) NOT NULL COMMENT '总访问时间'");}
if(!pdo_fieldexists('umiacp_10second_puv','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_puv')." ADD   PRIMARY KEY (`id`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_puv','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_puv')." ADD   KEY `uniacid` (`uniacid`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_puv','page')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_puv')." ADD   KEY `page` (`page`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_puv','goods_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_puv')." ADD   KEY `goods_id` (`goods_id`) USING BTREE");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_puv_record` (
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
  KEY `goods_id` (`goods_id`) USING BTREE,
  KEY `createtime` (`createtime`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3196 DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_10second_puv_record','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_puv_record')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_10second_puv_record','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_puv_record')." ADD   `uniacid` int(11) NOT NULL");}
if(!pdo_fieldexists('umiacp_10second_puv_record','openid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_puv_record')." ADD   `openid` varchar(50) NOT NULL");}
if(!pdo_fieldexists('umiacp_10second_puv_record','goods_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_puv_record')." ADD   `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '访问商品'");}
if(!pdo_fieldexists('umiacp_10second_puv_record','page')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_puv_record')." ADD   `page` varchar(50) NOT NULL COMMENT '访问页面'");}
if(!pdo_fieldexists('umiacp_10second_puv_record','input')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_puv_record')." ADD   `input` text COMMENT '用户输入'");}
if(!pdo_fieldexists('umiacp_10second_puv_record','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_puv_record')." ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '访问时间'");}
if(!pdo_fieldexists('umiacp_10second_puv_record','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_puv_record')." ADD   PRIMARY KEY (`id`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_puv_record','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_puv_record')." ADD   KEY `uniacid` (`uniacid`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_puv_record','openid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_puv_record')." ADD   KEY `openid` (`openid`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_puv_record','page')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_puv_record')." ADD   KEY `page` (`page`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_puv_record','goods_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_puv_record')." ADD   KEY `goods_id` (`goods_id`) USING BTREE");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_record` (
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
) ENGINE=InnoDB AUTO_INCREMENT=144 DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_10second_record','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_record')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_10second_record','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_record')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_10second_record','shop_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_record')." ADD   `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家'");}
if(!pdo_fieldexists('umiacp_10second_record','activity_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_record')." ADD   `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动'");}
if(!pdo_fieldexists('umiacp_10second_record','goods_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_record')." ADD   `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品'");}
if(!pdo_fieldexists('umiacp_10second_record','cut_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_record')." ADD   `cut_id` int(11) NOT NULL DEFAULT '0' COMMENT '砍价'");}
if(!pdo_fieldexists('umiacp_10second_record','fmid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_record')." ADD   `fmid` int(11) NOT NULL DEFAULT '0' COMMENT '发起人'");}
if(!pdo_fieldexists('umiacp_10second_record','mid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_record')." ADD   `mid` int(11) NOT NULL DEFAULT '0' COMMENT '砍价人'");}
if(!pdo_fieldexists('umiacp_10second_record','oprice')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_record')." ADD   `oprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价'");}
if(!pdo_fieldexists('umiacp_10second_record','nprice')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_record')." ADD   `nprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '现价'");}
if(!pdo_fieldexists('umiacp_10second_record','price')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_record')." ADD   `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '砍价'");}
if(!pdo_fieldexists('umiacp_10second_record','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_record')." ADD   `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,砍价中;2,砍到底;'");}
if(!pdo_fieldexists('umiacp_10second_record','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_record')." ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '砍价时间'");}
if(!pdo_fieldexists('umiacp_10second_record','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_record')." ADD   PRIMARY KEY (`id`)");}
if(!pdo_fieldexists('umiacp_10second_record','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_record')." ADD   KEY `uniacid` (`uniacid`)");}
if(!pdo_fieldexists('umiacp_10second_record','mid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_record')." ADD   KEY `mid` (`mid`)");}
if(!pdo_fieldexists('umiacp_10second_record','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_record')." ADD   KEY `status` (`status`)");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_recording` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `openid` varchar(255) NOT NULL COMMENT '用户',
  `sj` decimal(10,4) NOT NULL COMMENT '时间',
  `diff` decimal(10,4) NOT NULL COMMENT '差值',
  `day` int(3) NOT NULL DEFAULT '0' COMMENT '第几天',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,未中奖;2,中奖;',
  `createtime` int(3) NOT NULL DEFAULT '0' COMMENT '参与时间',
  `activity_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `lkopenid` (`openid`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=221 DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_10second_recording','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_recording')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_10second_recording','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_recording')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_10second_recording','openid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_recording')." ADD   `openid` varchar(255) NOT NULL COMMENT '用户'");}
if(!pdo_fieldexists('umiacp_10second_recording','sj')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_recording')." ADD   `sj` decimal(10,4) NOT NULL COMMENT '时间'");}
if(!pdo_fieldexists('umiacp_10second_recording','diff')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_recording')." ADD   `diff` decimal(10,4) NOT NULL COMMENT '差值'");}
if(!pdo_fieldexists('umiacp_10second_recording','day')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_recording')." ADD   `day` int(3) NOT NULL DEFAULT '0' COMMENT '第几天'");}
if(!pdo_fieldexists('umiacp_10second_recording','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_recording')." ADD   `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,未中奖;2,中奖;'");}
if(!pdo_fieldexists('umiacp_10second_recording','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_recording')." ADD   `createtime` int(3) NOT NULL DEFAULT '0' COMMENT '参与时间'");}
if(!pdo_fieldexists('umiacp_10second_recording','activity_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_recording')." ADD   `activity_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('umiacp_10second_recording','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_recording')." ADD   PRIMARY KEY (`id`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_recording','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_recording')." ADD   KEY `uniacid` (`uniacid`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_recording','lkopenid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_recording')." ADD   KEY `lkopenid` (`openid`) USING BTREE");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_reward` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `fopenid` varchar(255) DEFAULT NULL,
  `openid` varchar(255) NOT NULL COMMENT '用户',
  `cut_id` int(11) DEFAULT NULL,
  `reward_type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '奖励类型:1,随机红包;2,入场卷;3,未中奖;',
  `reward_name` varchar(255) NOT NULL COMMENT '奖励名称',
  `reward_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '红包奖励金额',
  `tid` varchar(255) NOT NULL,
  `result` text,
  `boost` int(11) NOT NULL DEFAULT '0' COMMENT '助力人数',
  `use` int(11) NOT NULL DEFAULT '3' COMMENT '可玩游戏次数',
  `used` int(11) NOT NULL DEFAULT '0' COMMENT '已游戏次数',
  `good` decimal(10,4) NOT NULL DEFAULT '0.0000' COMMENT '最好成绩',
  `diff` decimal(10,4) NOT NULL DEFAULT '-1.0000' COMMENT '最好差值',
  `day` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,红包待拆;2,入场卷;3,红包已发;4,红包发放失败;5,未中奖;',
  `pay_time` int(11) NOT NULL DEFAULT '0' COMMENT '领奖时间',
  `createtime` int(3) NOT NULL DEFAULT '0' COMMENT '参与时间',
  `activity_id` int(11) DEFAULT NULL,
  `nickname` varchar(100) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `mid` int(11) NOT NULL DEFAULT '0',
  `send_name` varchar(255) DEFAULT NULL COMMENT '活动名称',
  `wishing` varchar(255) DEFAULT NULL COMMENT '祝福语',
  `act_name` varchar(255) DEFAULT NULL COMMENT '活动名称',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `lkopenid` (`openid`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `day` (`day`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_10second_reward','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_10second_reward','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_10second_reward','fopenid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   `fopenid` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('umiacp_10second_reward','openid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   `openid` varchar(255) NOT NULL COMMENT '用户'");}
if(!pdo_fieldexists('umiacp_10second_reward','cut_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   `cut_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('umiacp_10second_reward','reward_type')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   `reward_type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '奖励类型:1,随机红包;2,入场卷;3,未中奖;'");}
if(!pdo_fieldexists('umiacp_10second_reward','reward_name')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   `reward_name` varchar(255) NOT NULL COMMENT '奖励名称'");}
if(!pdo_fieldexists('umiacp_10second_reward','reward_money')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   `reward_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '红包奖励金额'");}
if(!pdo_fieldexists('umiacp_10second_reward','tid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   `tid` varchar(255) NOT NULL");}
if(!pdo_fieldexists('umiacp_10second_reward','result')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   `result` text");}
if(!pdo_fieldexists('umiacp_10second_reward','boost')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   `boost` int(11) NOT NULL DEFAULT '0' COMMENT '助力人数'");}
if(!pdo_fieldexists('umiacp_10second_reward','use')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   `use` int(11) NOT NULL DEFAULT '3' COMMENT '可玩游戏次数'");}
if(!pdo_fieldexists('umiacp_10second_reward','used')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   `used` int(11) NOT NULL DEFAULT '0' COMMENT '已游戏次数'");}
if(!pdo_fieldexists('umiacp_10second_reward','good')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   `good` decimal(10,4) NOT NULL DEFAULT '0.0000' COMMENT '最好成绩'");}
if(!pdo_fieldexists('umiacp_10second_reward','diff')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   `diff` decimal(10,4) NOT NULL DEFAULT '-1.0000' COMMENT '最好差值'");}
if(!pdo_fieldexists('umiacp_10second_reward','day')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   `day` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_10second_reward','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,红包待拆;2,入场卷;3,红包已发;4,红包发放失败;5,未中奖;'");}
if(!pdo_fieldexists('umiacp_10second_reward','pay_time')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   `pay_time` int(11) NOT NULL DEFAULT '0' COMMENT '领奖时间'");}
if(!pdo_fieldexists('umiacp_10second_reward','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   `createtime` int(3) NOT NULL DEFAULT '0' COMMENT '参与时间'");}
if(!pdo_fieldexists('umiacp_10second_reward','activity_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   `activity_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('umiacp_10second_reward','nickname')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   `nickname` varchar(100) DEFAULT NULL");}
if(!pdo_fieldexists('umiacp_10second_reward','avatar')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   `avatar` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('umiacp_10second_reward','mid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   `mid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_10second_reward','send_name')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   `send_name` varchar(255) DEFAULT NULL COMMENT '活动名称'");}
if(!pdo_fieldexists('umiacp_10second_reward','wishing')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   `wishing` varchar(255) DEFAULT NULL COMMENT '祝福语'");}
if(!pdo_fieldexists('umiacp_10second_reward','act_name')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   `act_name` varchar(255) DEFAULT NULL COMMENT '活动名称'");}
if(!pdo_fieldexists('umiacp_10second_reward','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   PRIMARY KEY (`id`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_reward','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   KEY `uniacid` (`uniacid`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_reward','lkopenid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   KEY `lkopenid` (`openid`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_reward','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_reward')." ADD   KEY `status` (`status`) USING BTREE");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_saler` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '用户',
  `openid` varchar(255) NOT NULL,
  `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家',
  `realname` varchar(255) NOT NULL COMMENT '姓名',
  `mobile` varchar(255) NOT NULL COMMENT '电话',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,启用;0,禁用;',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '子模块活动',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `mid` (`mid`),
  KEY `status` (`status`),
  KEY `shop_id` (`shop_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_10second_saler','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_saler')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_10second_saler','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_saler')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_10second_saler','mid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_saler')." ADD   `mid` int(11) NOT NULL DEFAULT '0' COMMENT '用户'");}
if(!pdo_fieldexists('umiacp_10second_saler','openid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_saler')." ADD   `openid` varchar(255) NOT NULL");}
if(!pdo_fieldexists('umiacp_10second_saler','shop_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_saler')." ADD   `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家'");}
if(!pdo_fieldexists('umiacp_10second_saler','realname')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_saler')." ADD   `realname` varchar(255) NOT NULL COMMENT '姓名'");}
if(!pdo_fieldexists('umiacp_10second_saler','mobile')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_saler')." ADD   `mobile` varchar(255) NOT NULL COMMENT '电话'");}
if(!pdo_fieldexists('umiacp_10second_saler','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_saler')." ADD   `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,启用;0,禁用;'");}
if(!pdo_fieldexists('umiacp_10second_saler','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_saler')." ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间'");}
if(!pdo_fieldexists('umiacp_10second_saler','activity_id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_saler')." ADD   `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '子模块活动'");}
if(!pdo_fieldexists('umiacp_10second_saler','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_saler')." ADD   PRIMARY KEY (`id`)");}
if(!pdo_fieldexists('umiacp_10second_saler','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_saler')." ADD   KEY `uniacid` (`uniacid`)");}
if(!pdo_fieldexists('umiacp_10second_saler','mid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_saler')." ADD   KEY `mid` (`mid`)");}
if(!pdo_fieldexists('umiacp_10second_saler','status')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_saler')." ADD   KEY `status` (`status`)");}
$sql_str="CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_setting` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `skey` varchar(255) NOT NULL COMMENT '键',
  `svalue` text COMMENT '值',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `skey` (`skey`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if(!pdo_fieldexists('umiacp_10second_setting','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_setting')." ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('umiacp_10second_setting','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_setting')." ADD   `uniacid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('umiacp_10second_setting','skey')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_setting')." ADD   `skey` varchar(255) NOT NULL COMMENT '键'");}
if(!pdo_fieldexists('umiacp_10second_setting','svalue')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_setting')." ADD   `svalue` text COMMENT '值'");}
if(!pdo_fieldexists('umiacp_10second_setting','createtime')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_setting')." ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间'");}
if(!pdo_fieldexists('umiacp_10second_setting','id')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_setting')." ADD   PRIMARY KEY (`id`) USING BTREE");}
if(!pdo_fieldexists('umiacp_10second_setting','uniacid')) {pdo_query("ALTER TABLE ".tablename('umiacp_10second_setting')." ADD   KEY `uniacid` (`uniacid`) USING BTREE");}
