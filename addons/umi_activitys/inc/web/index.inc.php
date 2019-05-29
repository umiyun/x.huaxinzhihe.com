<?php

global $_W, $_GPC;

$uniacid = intval($_W['uniacid']);
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

if ($op == 'display') {
    //当天开始时间
    $start_time = mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));
    //当天结束时间
    $end_time = mktime(23,59,59,date("m",time()),date("d",time()),date("Y",time()));


    $total_today=pdo_fetchcolumn('select count(*) from '. tablename(YOUMI_NAME . '_' . 'activity') .' where uniacid = :uniacid and createtime >= ' . $start_time.' and createtime <= '.$end_time,array(':uniacid'=>$uniacid));
    $total_pv_today=pdo_fetchcolumn('select sum(pv) from '. tablename(YOUMI_NAME . '_' . 'activity') .' where uniacid = :uniacid and createtime >= ' . $start_time.' and createtime <= '.$end_time,array(':uniacid'=>$uniacid));
    $total_participate_today=pdo_fetchcolumn('select sum(participate) from '. tablename(YOUMI_NAME . '_' . 'activity') .' where uniacid = :uniacid and createtime >= ' . $start_time.' and createtime <= '.$end_time,array(':uniacid'=>$uniacid));


//    活动总数
    $total=pdo_fetchcolumn('select count(*) from '. tablename(YOUMI_NAME . '_' . 'activity') .' where uniacid = :uniacid ',array(':uniacid'=>$uniacid));
//    浏览总量
    $total_pv=pdo_fetchcolumn('select sum(pv) from '. tablename(YOUMI_NAME . '_' . 'activity') .' where uniacid = :uniacid ',array(':uniacid'=>$uniacid));
//    报名总数
    $total_participate=pdo_fetchcolumn('select sum(participate) from '. tablename(YOUMI_NAME . '_' . 'activity') .' where uniacid = :uniacid ',array(':uniacid'=>$uniacid));

//商家数量
    $shop = pdo_fetchcolumn('SELECT count(*) FROM ' . tablename(YOUMI_NAME . '_' . 'shop') . ' where uniacid = :uniacid ',array(':uniacid'=>$uniacid));

//    今日打款金额
    $apply = pdo_fetchcolumn('SELECT sum(apply) FROM ' . tablename(YOUMI_NAME . '_' . 'withdraw') . ' where uniacid = :uniacid and createtime >=' . $start_time,array(':uniacid'=>$uniacid));

    include $this->template('index');
} 

