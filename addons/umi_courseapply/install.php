<?php
$sql_str="
 CREATE TABLE IF NOT EXISTS  `ims_umi_courseapply_cate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `rank` int(11) NOT NULL DEFAULT '0',
  `cate_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL COMMENT '分类名',
  `logo` varchar(255) NOT NULL COMMENT '分类logo',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态:1,启用;2,禁用;',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `title` (`title`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品分类';


 CREATE TABLE IF NOT EXISTS  `ims_umi_courseapply_cut` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '砍价人',
  `nickname` varchar(255) NOT NULL COMMENT '昵称',
  `avatar` varchar(255) NOT NULL COMMENT '头像',
  `oprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价',
  `nprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '现价',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '砍价',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '发起时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `mid` (`mid`),
  KEY `status` (`status`),
  KEY `activity_id` (`order_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=192 DEFAULT CHARSET=utf8;


 CREATE TABLE IF NOT EXISTS  `ims_umi_courseapply_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '门店',
  `cate_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类',
  `title` varchar(255) NOT NULL COMMENT '商品名',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `valid_day` int(11) NOT NULL DEFAULT '0' COMMENT '有效期',
  `image` varchar(255) NOT NULL COMMENT '主图',
  `images` text NOT NULL COMMENT '图集',
  `starttime` int(11) NOT NULL DEFAULT '0' COMMENT '开售时间',
  `endtime` int(11) NOT NULL DEFAULT '0' COMMENT '停售时间',
  `descs` varchar(255) NOT NULL COMMENT '简述',
  `description` text COMMENT '详情',
  `precautions` text COMMENT '注意事项',
  `is_recommend` tinyint(3) NOT NULL DEFAULT '2' COMMENT '推荐:1,是;2,否;',
  `success` int(11) NOT NULL DEFAULT '0' COMMENT '已售',
  `stock` int(11) NOT NULL DEFAULT '1' COMMENT '库存',
  `status` tinyint(3) NOT NULL DEFAULT '2' COMMENT '状态:1,上架;2,下架;3,售罄;',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `shop_id` (`cate_id`,`shop_id`,`store_id`) USING BTREE,
  KEY `is_recommend` (`is_recommend`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `starttime` (`starttime`,`endtime`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品';


 CREATE TABLE IF NOT EXISTS  `ims_umi_courseapply_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `user_type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '类型:1,后台;2,公众号;3,小程序;',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '用户mid',
  `username` varchar(50) NOT NULL COMMENT '操作员昵称',
  `op` varchar(255) NOT NULL COMMENT '操作位置',
  `content` varchar(255) NOT NULL COMMENT '具体操作',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态:1,启用;2,禁用;',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '操作时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `type` (`user_type`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `mid` (`mid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户操作日志';


 CREATE TABLE IF NOT EXISTS  `ims_umi_courseapply_member` (
  `mid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `openid` varchar(255) NOT NULL COMMENT '用户',
  `wxopenid` varchar(255) NOT NULL COMMENT '小程序',
  `fmid` tinyint(11) NOT NULL DEFAULT '0' COMMENT '推荐人',
  `realname` varchar(255) NOT NULL COMMENT '姓名',
  `mobile` varchar(255) NOT NULL COMMENT '手机号',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `salt` varchar(255) NOT NULL COMMENT '秘钥',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态:1,正常;2,黑名单;',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间',
  PRIMARY KEY (`mid`),
  KEY `uniacid` (`uniacid`),
  KEY `openid` (`openid`,`wxopenid`),
  KEY `mobile` (`mobile`),
  KEY `status` (`status`),
  KEY `wxopenid` (`wxopenid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='用户基本信息';


 CREATE TABLE IF NOT EXISTS  `ims_umi_courseapply_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `openid` varchar(255) NOT NULL COMMENT '用户',
  `temp_id` varchar(255) NOT NULL COMMENT '模板id',
  `send` text COMMENT '模板内容',
  `url` varchar(255) NOT NULL COMMENT '链接',
  `result` text COMMENT '返回',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,待发;2,发松成功;3,发送失败;',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


 CREATE TABLE IF NOT EXISTS  `ims_umi_courseapply_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '用户',
  `openid` varchar(255) NOT NULL,
  `prize_id` int(11) NOT NULL DEFAULT '0' COMMENT '票',
  `title` varchar(255) NOT NULL COMMENT '票',
  `nickname` varchar(255) NOT NULL COMMENT '昵称',
  `avatar` varchar(255) NOT NULL COMMENT '头像',
  `realname` varchar(255) NOT NULL COMMENT '姓名',
  `mobile` varchar(255) NOT NULL COMMENT '电话',
  `ordersn` varchar(255) NOT NULL COMMENT '订单号',
  `tid` varchar(255) NOT NULL COMMENT '商户订单号',
  `times` int(11) NOT NULL DEFAULT '0' COMMENT '砍价次数',
  `oprice` decimal(10,2) NOT NULL COMMENT '原价',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `pay_time` int(11) NOT NULL DEFAULT '0' COMMENT '砍价完成时间',
  `transid` varchar(255) NOT NULL COMMENT '微信支付单号',
  `saler_time` int(11) NOT NULL COMMENT '核销时间',
  `saler_id` int(11) NOT NULL COMMENT '核销员',
  `status` tinyint(3) NOT NULL COMMENT '状态:1,待完成;2,已完成;3,已核销;4,已取消;5,已退款;6,砍价失败;7,退款失败;',
  `createtime` int(11) NOT NULL COMMENT '下单时间',
  `number` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uniacid` (`uniacid`),
  KEY `mid` (`mid`),
  KEY `tid` (`tid`,`ordersn`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='商品订单';


 CREATE TABLE IF NOT EXISTS  `ims_umi_courseapply_prize` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `title_img` varchar(255) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL COMMENT '说明',
  `num` int(11) NOT NULL DEFAULT '-1' COMMENT '总数量',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:0,未启用;1,启用;',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `starttime` int(11) DEFAULT NULL,
  `endtime` int(11) DEFAULT NULL,
  `weeks` varchar(255) DEFAULT NULL COMMENT '第几周',
  `day` varchar(255) DEFAULT NULL COMMENT '日期 例 9月1日',
  `ampm` varchar(255) DEFAULT NULL COMMENT '上午下午',
  `times` varchar(255) DEFAULT NULL COMMENT '几点开始',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='课程';


 CREATE TABLE IF NOT EXISTS  `ims_umi_courseapply_puv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `page` varchar(255) NOT NULL COMMENT '页面',
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品',
  `pv` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '总浏览人次',
  `uv` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '总浏览人数',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态:1,有效;2,无效;',
  `createtime` int(11) NOT NULL COMMENT '总访问时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `page` (`page`),
  KEY `goods_id` (`goods_id`),
  KEY `status` (`status`),
  KEY `createtime` (`createtime`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='页面浏览puv';


 CREATE TABLE IF NOT EXISTS  `ims_umi_courseapply_puv_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '访问商品',
  `page` varchar(50) NOT NULL COMMENT '访问页面',
  `input` text COMMENT '用户输入',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态:1,有效;2,无效;',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '访问时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `openid` (`openid`) USING BTREE,
  KEY `page` (`page`) USING BTREE,
  KEY `goods_id` (`goods_id`),
  KEY `status` (`status`),
  KEY `createtime` (`createtime`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=509 DEFAULT CHARSET=utf8 COMMENT='浏览页面详情';


 CREATE TABLE IF NOT EXISTS  `ims_umi_courseapply_saler` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '用户',
  `nickname` varchar(255) NOT NULL COMMENT '昵称',
  `avatar` varchar(255) NOT NULL COMMENT '头像',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,启用;2,禁用;',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `mid` (`mid`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=194 DEFAULT CHARSET=utf8;


 CREATE TABLE IF NOT EXISTS  `ims_umi_courseapply_setting` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `skey` varchar(255) NOT NULL COMMENT '键',
  `svalue` text COMMENT '值',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态:1,启用;2,禁用;',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `skey` (`skey`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='系统基本配置';

";$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);