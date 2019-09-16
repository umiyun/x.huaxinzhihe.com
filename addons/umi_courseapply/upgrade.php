<?php

$sql_str = "CREATE TABLE IF NOT EXISTS  `ims_umi_courseapply_cate` (
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

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if (!pdo_fieldexists('umi_courseapply_cate','id')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cate') . " ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cate') . " MODIFY COLUMN 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT ");
}
if (!pdo_fieldexists('umi_courseapply_cate','uniacid')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cate') . " ADD   `uniacid` int(11) NOT NULL DEFAULT '0' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cate') . " MODIFY COLUMN   `uniacid` int(11) NOT NULL DEFAULT '0' ");
}
if (!pdo_fieldexists('umi_courseapply_cate','rank')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cate') . " ADD   `rank` int(11) NOT NULL DEFAULT '0' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cate') . " MODIFY COLUMN   `rank` int(11) NOT NULL DEFAULT '0' ");
}
if (!pdo_fieldexists('umi_courseapply_cate','cate_id')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cate') . " ADD   `cate_id` int(11) NOT NULL DEFAULT '0' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cate') . " MODIFY COLUMN   `cate_id` int(11) NOT NULL DEFAULT '0' ");
}
if (!pdo_fieldexists('umi_courseapply_cate','title')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cate') . " ADD   `title` varchar(255) NOT NULL COMMENT '分类名' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cate') . " MODIFY COLUMN   `title` varchar(255) NOT NULL COMMENT '分类名' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_cate','logo')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cate') . " ADD   `logo` varchar(255) NOT NULL COMMENT '分类logo' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cate') . " MODIFY COLUMN   `logo` varchar(255) NOT NULL COMMENT '分类logo' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_cate','status')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cate') . " ADD   `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态:1,启用;2,禁用;' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cate') . " MODIFY COLUMN   `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态:1,启用;2,禁用;' ");
}
if (!pdo_fieldexists('umi_courseapply_cate','createtime')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cate') . " ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cate') . " MODIFY COLUMN   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间' ");
}



$sql_str = "CREATE TABLE IF NOT EXISTS  `ims_umi_courseapply_cut` (
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

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if (!pdo_fieldexists('umi_courseapply_cut','id')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cut') . " ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cut') . " MODIFY COLUMN 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT ");
}
if (!pdo_fieldexists('umi_courseapply_cut','uniacid')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cut') . " ADD   `uniacid` int(11) NOT NULL DEFAULT '0' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cut') . " MODIFY COLUMN   `uniacid` int(11) NOT NULL DEFAULT '0' ");
}
if (!pdo_fieldexists('umi_courseapply_cut','order_id')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cut') . " ADD   `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cut') . " MODIFY COLUMN   `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单' ");
}
if (!pdo_fieldexists('umi_courseapply_cut','mid')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cut') . " ADD   `mid` int(11) NOT NULL DEFAULT '0' COMMENT '砍价人' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cut') . " MODIFY COLUMN   `mid` int(11) NOT NULL DEFAULT '0' COMMENT '砍价人' ");
}
if (!pdo_fieldexists('umi_courseapply_cut','nickname')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cut') . " ADD   `nickname` varchar(255) NOT NULL COMMENT '昵称' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cut') . " MODIFY COLUMN   `nickname` varchar(255) NOT NULL COMMENT '昵称' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_cut','avatar')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cut') . " ADD   `avatar` varchar(255) NOT NULL COMMENT '头像' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cut') . " MODIFY COLUMN   `avatar` varchar(255) NOT NULL COMMENT '头像' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_cut','oprice')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cut') . " ADD   `oprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cut') . " MODIFY COLUMN   `oprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价' ");
}
if (!pdo_fieldexists('umi_courseapply_cut','nprice')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cut') . " ADD   `nprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '现价' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cut') . " MODIFY COLUMN   `nprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '现价' ");
}
if (!pdo_fieldexists('umi_courseapply_cut','price')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cut') . " ADD   `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '砍价' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cut') . " MODIFY COLUMN   `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '砍价' ");
}
if (!pdo_fieldexists('umi_courseapply_cut','status')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cut') . " ADD   `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cut') . " MODIFY COLUMN   `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:' ");
}
if (!pdo_fieldexists('umi_courseapply_cut','createtime')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cut') . " ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '发起时间' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_cut') . " MODIFY COLUMN   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '发起时间' ");
}




$sql_str = "CREATE TABLE IF NOT EXISTS  `ims_umi_courseapply_goods` (
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

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if (!pdo_fieldexists('umi_courseapply_goods','id')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " MODIFY COLUMN 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT ");
}
if (!pdo_fieldexists('umi_courseapply_goods','uniacid')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " ADD   `uniacid` int(11) NOT NULL DEFAULT '0' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " MODIFY COLUMN   `uniacid` int(11) NOT NULL DEFAULT '0' ");
}
if (!pdo_fieldexists('umi_courseapply_goods','shop_id')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " ADD   `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " MODIFY COLUMN   `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '商家' ");
}
if (!pdo_fieldexists('umi_courseapply_goods','store_id')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " ADD   `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '门店' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " MODIFY COLUMN   `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '门店' ");
}
if (!pdo_fieldexists('umi_courseapply_goods','cate_id')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " ADD   `cate_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " MODIFY COLUMN   `cate_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类' ");
}
if (!pdo_fieldexists('umi_courseapply_goods','title')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " ADD   `title` varchar(255) NOT NULL COMMENT '商品名' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " MODIFY COLUMN   `title` varchar(255) NOT NULL COMMENT '商品名' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_goods','price')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " ADD   `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " MODIFY COLUMN   `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格' ");
}
if (!pdo_fieldexists('umi_courseapply_goods','valid_day')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " ADD   `valid_day` int(11) NOT NULL DEFAULT '0' COMMENT '有效期' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " MODIFY COLUMN   `valid_day` int(11) NOT NULL DEFAULT '0' COMMENT '有效期' ");
}
if (!pdo_fieldexists('umi_courseapply_goods','image')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " ADD   `image` varchar(255) NOT NULL COMMENT '主图' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " MODIFY COLUMN   `image` varchar(255) NOT NULL COMMENT '主图' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_goods','images')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " ADD   `images` text NOT NULL COMMENT '图集' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " MODIFY COLUMN   `images` text NOT NULL COMMENT '图集' ");
}
if (!pdo_fieldexists('umi_courseapply_goods','starttime')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " ADD   `starttime` int(11) NOT NULL DEFAULT '0' COMMENT '开售时间' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " MODIFY COLUMN   `starttime` int(11) NOT NULL DEFAULT '0' COMMENT '开售时间' ");
}
if (!pdo_fieldexists('umi_courseapply_goods','endtime')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " ADD   `endtime` int(11) NOT NULL DEFAULT '0' COMMENT '停售时间' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " MODIFY COLUMN   `endtime` int(11) NOT NULL DEFAULT '0' COMMENT '停售时间' ");
}
if (!pdo_fieldexists('umi_courseapply_goods','descs')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " ADD   `descs` varchar(255) NOT NULL COMMENT '简述' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " MODIFY COLUMN   `descs` varchar(255) NOT NULL COMMENT '简述' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_goods','description')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " ADD   `description` text COMMENT '详情' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " MODIFY COLUMN   `description` text COMMENT '详情' ");
}
if (!pdo_fieldexists('umi_courseapply_goods','precautions')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " ADD   `precautions` text COMMENT '注意事项' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " MODIFY COLUMN   `precautions` text COMMENT '注意事项' ");
}
if (!pdo_fieldexists('umi_courseapply_goods','is_recommend')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " ADD   `is_recommend` tinyint(3) NOT NULL DEFAULT '2' COMMENT '推荐:1,是;2,否;' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " MODIFY COLUMN   `is_recommend` tinyint(3) NOT NULL DEFAULT '2' COMMENT '推荐:1,是;2,否;' ");
}
if (!pdo_fieldexists('umi_courseapply_goods','success')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " ADD   `success` int(11) NOT NULL DEFAULT '0' COMMENT '已售' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " MODIFY COLUMN   `success` int(11) NOT NULL DEFAULT '0' COMMENT '已售' ");
}
if (!pdo_fieldexists('umi_courseapply_goods','stock')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " ADD   `stock` int(11) NOT NULL DEFAULT '1' COMMENT '库存' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " MODIFY COLUMN   `stock` int(11) NOT NULL DEFAULT '1' COMMENT '库存' ");
}
if (!pdo_fieldexists('umi_courseapply_goods','status')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " ADD   `status` tinyint(3) NOT NULL DEFAULT '2' COMMENT '状态:1,上架;2,下架;3,售罄;' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " MODIFY COLUMN   `status` tinyint(3) NOT NULL DEFAULT '2' COMMENT '状态:1,上架;2,下架;3,售罄;' ");
}
if (!pdo_fieldexists('umi_courseapply_goods','createtime')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_goods') . " MODIFY COLUMN   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间' ");
}





$sql_str = "CREATE TABLE IF NOT EXISTS  `ims_umi_courseapply_log` (
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

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if (!pdo_fieldexists('umi_courseapply_log','id')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_log') . " ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_log') . " MODIFY COLUMN 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT ");
}
if (!pdo_fieldexists('umi_courseapply_log','uniacid')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_log') . " ADD   `uniacid` int(11) NOT NULL DEFAULT '0' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_log') . " MODIFY COLUMN   `uniacid` int(11) NOT NULL DEFAULT '0' ");
}
if (!pdo_fieldexists('umi_courseapply_log','user_type')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_log') . " ADD   `user_type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '类型:1,后台;2,公众号;3,小程序;' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_log') . " MODIFY COLUMN   `user_type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '类型:1,后台;2,公众号;3,小程序;' ");
}
if (!pdo_fieldexists('umi_courseapply_log','mid')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_log') . " ADD   `mid` int(11) NOT NULL DEFAULT '0' COMMENT '用户mid' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_log') . " MODIFY COLUMN   `mid` int(11) NOT NULL DEFAULT '0' COMMENT '用户mid' ");
}
if (!pdo_fieldexists('umi_courseapply_log','username')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_log') . " ADD   `username` varchar(50) NOT NULL COMMENT '操作员昵称' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_log') . " MODIFY COLUMN   `username` varchar(50) NOT NULL COMMENT '操作员昵称' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_log','op')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_log') . " ADD   `op` varchar(255) NOT NULL COMMENT '操作位置' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_log') . " MODIFY COLUMN   `op` varchar(255) NOT NULL COMMENT '操作位置' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_log','content')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_log') . " ADD   `content` varchar(255) NOT NULL COMMENT '具体操作' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_log') . " MODIFY COLUMN   `content` varchar(255) NOT NULL COMMENT '具体操作' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_log','status')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_log') . " ADD   `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态:1,启用;2,禁用;' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_log') . " MODIFY COLUMN   `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态:1,启用;2,禁用;' ");
}
if (!pdo_fieldexists('umi_courseapply_log','createtime')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_log') . " ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '操作时间' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_log') . " MODIFY COLUMN   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '操作时间' ");
}




$sql_str = "CREATE TABLE IF NOT EXISTS  `ims_umi_courseapply_member` (
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

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if (!pdo_fieldexists('umi_courseapply_member','mid')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_member') . " ADD 
  `mid` int(11) unsigned NOT NULL AUTO_INCREMENT ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_member') . " MODIFY COLUMN 
  `mid` int(11) unsigned NOT NULL AUTO_INCREMENT ");
}
if (!pdo_fieldexists('umi_courseapply_member','uniacid')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_member') . " ADD   `uniacid` int(11) NOT NULL DEFAULT '0' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_member') . " MODIFY COLUMN   `uniacid` int(11) NOT NULL DEFAULT '0' ");
}
if (!pdo_fieldexists('umi_courseapply_member','openid')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_member') . " ADD   `openid` varchar(255) NOT NULL COMMENT '用户' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_member') . " MODIFY COLUMN   `openid` varchar(255) NOT NULL COMMENT '用户' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_member','wxopenid')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_member') . " ADD   `wxopenid` varchar(255) NOT NULL COMMENT '小程序' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_member') . " MODIFY COLUMN   `wxopenid` varchar(255) NOT NULL COMMENT '小程序' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_member','fmid')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_member') . " ADD   `fmid` tinyint(11) NOT NULL DEFAULT '0' COMMENT '推荐人' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_member') . " MODIFY COLUMN   `fmid` tinyint(11) NOT NULL DEFAULT '0' COMMENT '推荐人' ");
}
if (!pdo_fieldexists('umi_courseapply_member','realname')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_member') . " ADD   `realname` varchar(255) NOT NULL COMMENT '姓名' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_member') . " MODIFY COLUMN   `realname` varchar(255) NOT NULL COMMENT '姓名' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_member','mobile')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_member') . " ADD   `mobile` varchar(255) NOT NULL COMMENT '手机号' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_member') . " MODIFY COLUMN   `mobile` varchar(255) NOT NULL COMMENT '手机号' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_member','password')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_member') . " ADD   `password` varchar(255) NOT NULL COMMENT '密码' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_member') . " MODIFY COLUMN   `password` varchar(255) NOT NULL COMMENT '密码' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_member','salt')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_member') . " ADD   `salt` varchar(255) NOT NULL COMMENT '秘钥' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_member') . " MODIFY COLUMN   `salt` varchar(255) NOT NULL COMMENT '秘钥' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_member','status')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_member') . " ADD   `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态:1,正常;2,黑名单;' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_member') . " MODIFY COLUMN   `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态:1,正常;2,黑名单;' ");
}
if (!pdo_fieldexists('umi_courseapply_member','createtime')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_member') . " ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_member') . " MODIFY COLUMN   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间' ");
}





$sql_str = "CREATE TABLE IF NOT EXISTS  `ims_umi_courseapply_message` (
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

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if (!pdo_fieldexists('umi_courseapply_message','id')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_message') . " ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_message') . " MODIFY COLUMN 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT ");
}
if (!pdo_fieldexists('umi_courseapply_message','uniacid')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_message') . " ADD   `uniacid` int(11) NOT NULL DEFAULT '0' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_message') . " MODIFY COLUMN   `uniacid` int(11) NOT NULL DEFAULT '0' ");
}
if (!pdo_fieldexists('umi_courseapply_message','openid')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_message') . " ADD   `openid` varchar(255) NOT NULL COMMENT '用户' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_message') . " MODIFY COLUMN   `openid` varchar(255) NOT NULL COMMENT '用户' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_message','temp_id')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_message') . " ADD   `temp_id` varchar(255) NOT NULL COMMENT '模板id' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_message') . " MODIFY COLUMN   `temp_id` varchar(255) NOT NULL COMMENT '模板id' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_message','send')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_message') . " ADD   `send` text COMMENT '模板内容' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_message') . " MODIFY COLUMN   `send` text COMMENT '模板内容' ");
}
if (!pdo_fieldexists('umi_courseapply_message','url')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_message') . " ADD   `url` varchar(255) NOT NULL COMMENT '链接' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_message') . " MODIFY COLUMN   `url` varchar(255) NOT NULL COMMENT '链接' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_message','result')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_message') . " ADD   `result` text COMMENT '返回' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_message') . " MODIFY COLUMN   `result` text COMMENT '返回' ");
}
if (!pdo_fieldexists('umi_courseapply_message','status')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_message') . " ADD   `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,待发;2,发松成功;3,发送失败;' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_message') . " MODIFY COLUMN   `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,待发;2,发松成功;3,发送失败;' ");
}
if (!pdo_fieldexists('umi_courseapply_message','createtime')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_message') . " ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_message') . " MODIFY COLUMN   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间' ");
}


$sql_str = "CREATE TABLE IF NOT EXISTS  `ims_umi_courseapply_order` (
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

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if (!pdo_fieldexists('umi_courseapply_order','id')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " MODIFY COLUMN 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT ");
}
if (!pdo_fieldexists('umi_courseapply_order','uniacid')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " ADD   `uniacid` int(11) NOT NULL DEFAULT '0' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " MODIFY COLUMN   `uniacid` int(11) NOT NULL DEFAULT '0' ");
}
if (!pdo_fieldexists('umi_courseapply_order','mid')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " ADD   `mid` int(11) NOT NULL DEFAULT '0' COMMENT '用户' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " MODIFY COLUMN   `mid` int(11) NOT NULL DEFAULT '0' COMMENT '用户' ");
}
if (!pdo_fieldexists('umi_courseapply_order','openid')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " ADD   `openid` varchar(255) NOT NULL DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " MODIFY COLUMN   `openid` varchar(255) NOT NULL DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_order','prize_id')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " ADD   `prize_id` int(11) NOT NULL DEFAULT '0' COMMENT '票' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " MODIFY COLUMN   `prize_id` int(11) NOT NULL DEFAULT '0' COMMENT '票' ");
}
if (!pdo_fieldexists('umi_courseapply_order','title')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " ADD   `title` varchar(255) NOT NULL COMMENT '票' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " MODIFY COLUMN   `title` varchar(255) NOT NULL COMMENT '票' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_order','nickname')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " ADD   `nickname` varchar(255) NOT NULL COMMENT '昵称' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " MODIFY COLUMN   `nickname` varchar(255) NOT NULL COMMENT '昵称' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_order','avatar')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " ADD   `avatar` varchar(255) NOT NULL COMMENT '头像' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " MODIFY COLUMN   `avatar` varchar(255) NOT NULL COMMENT '头像' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_order','realname')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " ADD   `realname` varchar(255) NOT NULL COMMENT '姓名' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " MODIFY COLUMN   `realname` varchar(255) NOT NULL COMMENT '姓名' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_order','mobile')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " ADD   `mobile` varchar(255) NOT NULL COMMENT '电话' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " MODIFY COLUMN   `mobile` varchar(255) NOT NULL COMMENT '电话' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_order','ordersn')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " ADD   `ordersn` varchar(255) NOT NULL COMMENT '订单号' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " MODIFY COLUMN   `ordersn` varchar(255) NOT NULL COMMENT '订单号' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_order','tid')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " ADD   `tid` varchar(255) NOT NULL COMMENT '商户订单号' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " MODIFY COLUMN   `tid` varchar(255) NOT NULL COMMENT '商户订单号' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_order','times')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " ADD   `times` int(11) NOT NULL DEFAULT '0' COMMENT '砍价次数' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " MODIFY COLUMN   `times` int(11) NOT NULL DEFAULT '0' COMMENT '砍价次数' ");
}
if (!pdo_fieldexists('umi_courseapply_order','oprice')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " ADD   `oprice` decimal(10,2) NOT NULL COMMENT '原价' DEFAULT 0  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " MODIFY COLUMN   `oprice` decimal(10,2) NOT NULL COMMENT '原价' DEFAULT 0  ");
}
if (!pdo_fieldexists('umi_courseapply_order','price')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " ADD   `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '金额' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " MODIFY COLUMN   `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '金额' ");
}
if (!pdo_fieldexists('umi_courseapply_order','pay_time')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " ADD   `pay_time` int(11) NOT NULL DEFAULT '0' COMMENT '砍价完成时间' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " MODIFY COLUMN   `pay_time` int(11) NOT NULL DEFAULT '0' COMMENT '砍价完成时间' ");
}
if (!pdo_fieldexists('umi_courseapply_order','transid')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " ADD   `transid` varchar(255) NOT NULL COMMENT '微信支付单号' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " MODIFY COLUMN   `transid` varchar(255) NOT NULL COMMENT '微信支付单号' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_order','saler_time')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " ADD   `saler_time` int(11) NOT NULL COMMENT '核销时间' DEFAULT 0  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " MODIFY COLUMN   `saler_time` int(11) NOT NULL COMMENT '核销时间' DEFAULT 0  ");
}
if (!pdo_fieldexists('umi_courseapply_order','saler_id')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " ADD   `saler_id` int(11) NOT NULL COMMENT '核销员' DEFAULT 0  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " MODIFY COLUMN   `saler_id` int(11) NOT NULL COMMENT '核销员' DEFAULT 0  ");
}
if (!pdo_fieldexists('umi_courseapply_order','status')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " ADD   `status` tinyint(3) NOT NULL COMMENT '状态:1,待完成;2,已完成;3,已核销;4,已取消;5,已退款;6,砍价失败;7,退款失败;' DEFAULT 0  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " MODIFY COLUMN   `status` tinyint(3) NOT NULL COMMENT '状态:1,待完成;2,已完成;3,已核销;4,已取消;5,已退款;6,砍价失败;7,退款失败;' DEFAULT 0  ");
}
if (!pdo_fieldexists('umi_courseapply_order','createtime')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " ADD   `createtime` int(11) NOT NULL COMMENT '下单时间' DEFAULT 0  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " MODIFY COLUMN   `createtime` int(11) NOT NULL COMMENT '下单时间' DEFAULT 0  ");
}
if (!pdo_fieldexists('umi_courseapply_order','number')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " ADD   `number` int(11) DEFAULT 0 ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_order') . " MODIFY COLUMN   `number` int(11) DEFAULT 0 ");
}




$sql_str = "CREATE TABLE IF NOT EXISTS  `ims_umi_courseapply_prize` (
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

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if (!pdo_fieldexists('umi_courseapply_prize','id')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " MODIFY COLUMN 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT ");
}
if (!pdo_fieldexists('umi_courseapply_prize','uniacid')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " ADD   `uniacid` int(11) NOT NULL DEFAULT '0' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " MODIFY COLUMN   `uniacid` int(11) NOT NULL DEFAULT '0' ");
}
if (!pdo_fieldexists('umi_courseapply_prize','title')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " ADD   `title` varchar(255) NOT NULL COMMENT '标题' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " MODIFY COLUMN   `title` varchar(255) NOT NULL COMMENT '标题' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_prize','title_img')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " ADD   `title_img` varchar(255) DEFAULT '' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " MODIFY COLUMN   `title_img` varchar(255) DEFAULT '' ");
}
if (!pdo_fieldexists('umi_courseapply_prize','desc')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " ADD   `desc` varchar(255) DEFAULT '' COMMENT '说明' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " MODIFY COLUMN   `desc` varchar(255) DEFAULT '' COMMENT '说明' ");
}
if (!pdo_fieldexists('umi_courseapply_prize','num')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " ADD   `num` int(11) NOT NULL DEFAULT '-1' COMMENT '总数量' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " MODIFY COLUMN   `num` int(11) NOT NULL DEFAULT '-1' COMMENT '总数量' ");
}
if (!pdo_fieldexists('umi_courseapply_prize','status')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " ADD   `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:0,未启用;1,启用;' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " MODIFY COLUMN   `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:0,未启用;1,启用;' ");
}
if (!pdo_fieldexists('umi_courseapply_prize','createtime')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " MODIFY COLUMN   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间' ");
}
if (!pdo_fieldexists('umi_courseapply_prize','starttime')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " ADD   `starttime` int(11) DEFAULT 0 ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " MODIFY COLUMN   `starttime` int(11) DEFAULT 0 ");
}
if (!pdo_fieldexists('umi_courseapply_prize','endtime')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " ADD   `endtime` int(11) DEFAULT 0 ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " MODIFY COLUMN   `endtime` int(11) DEFAULT 0 ");
}
if (!pdo_fieldexists('umi_courseapply_prize','weeks')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " ADD   `weeks` varchar(255) DEFAULT '' COMMENT '第几周' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " MODIFY COLUMN   `weeks` varchar(255) DEFAULT '' COMMENT '第几周' ");
}
if (!pdo_fieldexists('umi_courseapply_prize','day')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " ADD   `day` varchar(255) DEFAULT '' COMMENT '日期 例 9月1日' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " MODIFY COLUMN   `day` varchar(255) DEFAULT '' COMMENT '日期 例 9月1日' ");
}
if (!pdo_fieldexists('umi_courseapply_prize','ampm')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " ADD   `ampm` varchar(255) DEFAULT '' COMMENT '上午下午' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " MODIFY COLUMN   `ampm` varchar(255) DEFAULT '' COMMENT '上午下午' ");
}
if (!pdo_fieldexists('umi_courseapply_prize','times')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " ADD   `times` varchar(255) DEFAULT '' COMMENT '几点开始' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " MODIFY COLUMN   `times` varchar(255) DEFAULT '' COMMENT '几点开始' ");
}
if (!pdo_fieldexists('umi_courseapply_prize','sort')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " ADD   `sort` int(11) DEFAULT '0' COMMENT '排序' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_prize') . " MODIFY COLUMN   `sort` int(11) DEFAULT '0' COMMENT '排序' ");
}


$sql_str = "CREATE TABLE IF NOT EXISTS  `ims_umi_courseapply_puv` (
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

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if (!pdo_fieldexists('umi_courseapply_puv','id')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv') . " ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv') . " MODIFY COLUMN 
  `id` int(11) NOT NULL AUTO_INCREMENT ");
}
if (!pdo_fieldexists('umi_courseapply_puv','uniacid')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv') . " ADD   `uniacid` int(11) NOT NULL DEFAULT '0' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv') . " MODIFY COLUMN   `uniacid` int(11) NOT NULL DEFAULT '0' ");
}
if (!pdo_fieldexists('umi_courseapply_puv','page')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv') . " ADD   `page` varchar(255) NOT NULL COMMENT '页面' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv') . " MODIFY COLUMN   `page` varchar(255) NOT NULL COMMENT '页面' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_puv','goods_id')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv') . " ADD   `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv') . " MODIFY COLUMN   `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品' ");
}
if (!pdo_fieldexists('umi_courseapply_puv','pv')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv') . " ADD   `pv` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '总浏览人次' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv') . " MODIFY COLUMN   `pv` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '总浏览人次' ");
}
if (!pdo_fieldexists('umi_courseapply_puv','uv')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv') . " ADD   `uv` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '总浏览人数' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv') . " MODIFY COLUMN   `uv` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '总浏览人数' ");
}
if (!pdo_fieldexists('umi_courseapply_puv','status')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv') . " ADD   `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态:1,有效;2,无效;' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv') . " MODIFY COLUMN   `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态:1,有效;2,无效;' ");
}
if (!pdo_fieldexists('umi_courseapply_puv','createtime')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv') . " ADD   `createtime` int(11) NOT NULL COMMENT '总访问时间' DEFAULT 0  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv') . " MODIFY COLUMN   `createtime` int(11) NOT NULL COMMENT '总访问时间' DEFAULT 0  ");
}





$sql_str = "CREATE TABLE IF NOT EXISTS  `ims_umi_courseapply_puv_record` (
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

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if (!pdo_fieldexists('umi_courseapply_puv_record','id')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv_record') . " ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv_record') . " MODIFY COLUMN 
  `id` int(11) NOT NULL AUTO_INCREMENT ");
}
if (!pdo_fieldexists('umi_courseapply_puv_record','uniacid')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv_record') . " ADD   `uniacid` int(11) NOT NULL DEFAULT 0  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv_record') . " MODIFY COLUMN   `uniacid` int(11) NOT NULL DEFAULT 0  ");
}
if (!pdo_fieldexists('umi_courseapply_puv_record','openid')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv_record') . " ADD   `openid` varchar(50) NOT NULL DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv_record') . " MODIFY COLUMN   `openid` varchar(50) NOT NULL DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_puv_record','goods_id')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv_record') . " ADD   `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '访问商品' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv_record') . " MODIFY COLUMN   `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '访问商品' ");
}
if (!pdo_fieldexists('umi_courseapply_puv_record','page')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv_record') . " ADD   `page` varchar(50) NOT NULL COMMENT '访问页面' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv_record') . " MODIFY COLUMN   `page` varchar(50) NOT NULL COMMENT '访问页面' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_puv_record','input')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv_record') . " ADD   `input` text COMMENT '用户输入' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv_record') . " MODIFY COLUMN   `input` text COMMENT '用户输入' ");
}
if (!pdo_fieldexists('umi_courseapply_puv_record','status')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv_record') . " ADD   `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态:1,有效;2,无效;' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv_record') . " MODIFY COLUMN   `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态:1,有效;2,无效;' ");
}
if (!pdo_fieldexists('umi_courseapply_puv_record','createtime')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv_record') . " ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '访问时间' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_puv_record') . " MODIFY COLUMN   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '访问时间' ");
}






$sql_str = "CREATE TABLE IF NOT EXISTS  `ims_umi_courseapply_saler` (
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

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if (!pdo_fieldexists('umi_courseapply_saler','id')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_saler') . " ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_saler') . " MODIFY COLUMN 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT ");
}
if (!pdo_fieldexists('umi_courseapply_saler','uniacid')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_saler') . " ADD   `uniacid` int(11) NOT NULL DEFAULT '0' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_saler') . " MODIFY COLUMN   `uniacid` int(11) NOT NULL DEFAULT '0' ");
}
if (!pdo_fieldexists('umi_courseapply_saler','mid')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_saler') . " ADD   `mid` int(11) NOT NULL DEFAULT '0' COMMENT '用户' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_saler') . " MODIFY COLUMN   `mid` int(11) NOT NULL DEFAULT '0' COMMENT '用户' ");
}
if (!pdo_fieldexists('umi_courseapply_saler','nickname')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_saler') . " ADD   `nickname` varchar(255) NOT NULL COMMENT '昵称' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_saler') . " MODIFY COLUMN   `nickname` varchar(255) NOT NULL COMMENT '昵称' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_saler','avatar')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_saler') . " ADD   `avatar` varchar(255) NOT NULL COMMENT '头像' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_saler') . " MODIFY COLUMN   `avatar` varchar(255) NOT NULL COMMENT '头像' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_saler','status')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_saler') . " ADD   `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,启用;2,禁用;' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_saler') . " MODIFY COLUMN   `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1,启用;2,禁用;' ");
}
if (!pdo_fieldexists('umi_courseapply_saler','createtime')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_saler') . " ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_saler') . " MODIFY COLUMN   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间' ");
}



$sql_str = "CREATE TABLE IF NOT EXISTS  `ims_umi_courseapply_setting` (
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

";
$sql_str=str_replace('ims_', $GLOBALS['_W']['config']['db']['tablepre'], $sql_str);
pdo_query($sql_str);

if (!pdo_fieldexists('umi_courseapply_setting','id')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_setting') . " ADD 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_setting') . " MODIFY COLUMN 
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT ");
}
if (!pdo_fieldexists('umi_courseapply_setting','uniacid')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_setting') . " ADD   `uniacid` int(11) NOT NULL DEFAULT '0' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_setting') . " MODIFY COLUMN   `uniacid` int(11) NOT NULL DEFAULT '0' ");
}
if (!pdo_fieldexists('umi_courseapply_setting','skey')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_setting') . " ADD   `skey` varchar(255) NOT NULL COMMENT '键' DEFAULT ''  ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_setting') . " MODIFY COLUMN   `skey` varchar(255) NOT NULL COMMENT '键' DEFAULT ''  ");
}
if (!pdo_fieldexists('umi_courseapply_setting','svalue')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_setting') . " ADD   `svalue` text COMMENT '值' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_setting') . " MODIFY COLUMN   `svalue` text COMMENT '值' ");
}
if (!pdo_fieldexists('umi_courseapply_setting','status')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_setting') . " ADD   `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态:1,启用;2,禁用;' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_setting') . " MODIFY COLUMN   `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态:1,启用;2,禁用;' ");
}
if (!pdo_fieldexists('umi_courseapply_setting','createtime')) {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_setting') . " ADD   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间' ");
}else {
    pdo_query("ALTER TABLE " . tablename('umi_courseapply_setting') . " MODIFY COLUMN   `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间' ");
}


