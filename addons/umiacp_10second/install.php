<?php
$sql_str="
 CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_activity` (
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


 CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_activity_prize` (
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


 CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_boost` (
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


 CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_cate` (
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


 CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_complain` (
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


 CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_cut` (
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


 CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_goods` (
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


 CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_log` (
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


 CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_member` (
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


 CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_message` (
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


 CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_order` (
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


 CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_prize_log` (
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


 CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_puv` (
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


 CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_puv_record` (
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


 CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_record` (
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


 CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_recording` (
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


 CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_reward` (
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


 CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_saler` (
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


 CREATE TABLE IF NOT EXISTS  `ims_umiacp_10second_setting` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `skey` varchar(255) NOT NULL COMMENT '键',
  `svalue` text COMMENT '值',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `skey` (`skey`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

";$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);