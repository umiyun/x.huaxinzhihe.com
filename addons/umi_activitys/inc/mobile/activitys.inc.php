<?php
/**
 * Created by IntelliJ IDEA.
 * User: 7³
 * Date: 2018/7/30 0030
 * Time: 12:18
 */

global $_W, $_GPC;

checkauth();

$uniacid = intval($_W['uniacid']);
$openid = trim($_W['openid']);

$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'display';

$setting = youmi_setting_get_list();
$_W['page']['title'] = $setting['title'] ? $setting['title'] : '活动宝';
$setting['notice'] = htmlspecialchars_decode($setting['notice']);
$member = $this->member;


if ($op == 'display') {
    $_share['title'] = $setting['share_title'];
    $_share['imgUrl'] = tomedia($setting['share_image']);
    $_share['desc'] = $setting['share_desc'];
    $_share['link'] = $_W['siteroot'] . "app/" . $this->createMobileUrl('index', array('fopenid' => $openid));

//    分类    推荐写死   类型  行业 节日  品牌  顺序对应1234
    $cate1 = pdo_getall(YOUMI_NAME . '_' . 'cate', ['uniacid' => $uniacid, 'status' => 1, 'type' => 1],array() , '' , 'sort DESC,createtime DESC');
    $cate2 = pdo_getall(YOUMI_NAME . '_' . 'cate', ['uniacid' => $uniacid, 'status' => 1, 'type' => 2],array() , '' , 'sort DESC,createtime DESC');
    $cate3 = pdo_getall(YOUMI_NAME . '_' . 'cate', ['uniacid' => $uniacid, 'status' => 1, 'type' => 3],array() , '' , 'sort DESC,createtime DESC');
    $cate4 = pdo_getall(YOUMI_NAME . '_' . 'cate', ['uniacid' => $uniacid, 'status' => 1, 'type' => 4],array() , '' , 'sort DESC,createtime DESC');

    //案例    默认推荐
    $case = pdo_getall(YOUMI_NAME . '_' . 'case', ['uniacid' => $uniacid, 'status' => 1],array() , '' , 'sort DESC,createtime DESC');

    foreach ($case as &$item) {
        $item['logo'] = tomedia($item['logo']);
        unset($item);
    }
    foreach ($cate1 as &$item) {
        $item['logo'] = tomedia($item['logo']);
        unset($item);
    }
    foreach ($cate2 as &$item) {
        $item['logo'] = tomedia($item['logo']);
        unset($item);
    }
    foreach ($cate3 as &$item) {
        $item['logo'] = tomedia($item['logo']);
        unset($item);
    }
    foreach ($cate4 as &$item) {
        $item['logo'] = tomedia($item['logo']);
        unset($item);
    }

    $temp = $setting['template'];
    if ($temp == 1) {

        include $this->template('activitys');

    } else {

        include $this->template('activitys2');
    }

    exit();
}

if ($op == 'case_list') {

//    type  分类类型    cate_id  分类id
    $type = intval($_GPC['type']);
    $cate_id = intval($_GPC['cate_id']);
    $cate_id1 = intval($_GPC['cate_id1']);
    $cate_id2 = intval($_GPC['cate_id2']);
    $cate_id3 = intval($_GPC['cate_id3']);
    $cate_id4 = intval($_GPC['cate_id4']);
    $where['uniacid'] = $uniacid;

    $where['status >'] = -1;


//    $case = pdo_getall(YOUMI_NAME . '_' . 'case', $where,array(),'',);
    $condition = '';
    $pindex = max(1, intval($_GPC['page']));
    $psize = !empty($_GPC['psize']) ? $_GPC['psize'] : 12;
    $paras[':uniacid'] = $uniacid;

    switch ($type) {
        case 0 :
            $condition .= ' and status > -1 ';
            break;
        case 1 :
            $condition .= ' and cate_id1 =' . $cate_id;
            break;
        case 2 :
            $condition .= ' and cate_id2 =' . $cate_id;
            break;
        case 3 :
            $condition .= ' and cate_id3 =' . $cate_id;
            break;
        case 4 :
            $condition .= ' and cate_id4 =' . $cate_id;
            break;
    }
    if ($cate_id1 > 0) {
        $condition .= ' and cate_id1 =' . $cate_id1;
    }
    if ($cate_id2 > 0) {
        $condition .= ' and cate_id2 =' . $cate_id2;
    }
    if ($cate_id3 > 0) {
        $condition .= ' and cate_id3 =' . $cate_id3;
    }
    if ($cate_id4 > 0) {
        $condition .= ' and cate_id4 =' . $cate_id4;
    }

    $keyword = trim($_GPC['keyword']);
    if ($keyword) {
        $condition .= " and title like '%{$keyword}%' ";
    }

    $orderby = ' order by sort desc,createtime desc';

    $case = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'case') . ' where uniacid = :uniacid ' . $condition . $orderby . ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);
    foreach ($case as &$item) {
        $item['logo'] = tomedia($item['logo']);
        unset($item);
    }

    youmi_result(0, '案例列表', $case);

}

if ($op == 'activitys_my') {
    if (empty($_GPC['status'])) {
        $_GPC['status'] = 1;
    }
    $list = pdo_getall(YOUMI_NAME . '_activity', ['mid' => $this->mid, 'status' => intval($_GPC['status'])], array(), '', 'createtime DESC');
    include $this->template('activitys_my');
    exit();

}

if ($op == 'activitys_detail') {

    $activity_id = intval($_GPC['activity_id']);
    $item = pdo_get(YOUMI_NAME . '_activity', ['id' => $activity_id]);
    $activity = pdo_get($item['module'] . '_activity', ['id' => $item['activity_id']]);

    $master=pdo_get(YOUMI_NAME . '_master', ['openid'=>$openid,'status'=>1]);
    if ($item['mid'] != $this->mid) {
        if(!$master) {
            message('暂无权限操作');
        }
    }
    if ($item['module'] == 'umiacp_eggfreny' || $item['module'] == 'umiacp_speeddial' || $item['module'] == 'umiacp_roulette' || $item['module'] == 'umiacp_leapcliff' || $item['module'] == 'umiacp_10second') {
        include $this->template('activitys_detail_prize');
    } else {
        include $this->template('activitys_detail');
    }
    exit();

}
if ($op == 'un_status') {
    $activity_id = intval($_GPC['activity_id']);
    $item = pdo_get(YOUMI_NAME . '_activity', ['id' => $activity_id]);
    $activity = pdo_get($item['module'] . '_activity', ['id' => $item['activity_id']]);
    pdo_update(YOUMI_NAME . '_activity', array('status' => 2), ['id' => $activity_id]);
    pdo_update($item['module'] . '_activity', array('status' => 2), ['id' => $item['activity_id']]);
    youmi_result(0, '已下架');
}
if ($op == 'up_status') {
    $activity_id = intval($_GPC['activity_id']);
    $item = pdo_get(YOUMI_NAME . '_activity', ['id' => $activity_id]);
    $shop = pdo_get(YOUMI_NAME . '_shop', ['id' => $item['shop_id']]);
    if ($shop['endtime'] < time() && $shop['times'] > $setting['vip_times']) {
        youmi_result(1, '体验浏览量已用完，请购买VIP套餐');
    }
    $activity = pdo_get($item['module'] . '_activity', ['id' => $item['activity_id']]);
    pdo_update(YOUMI_NAME . '_activity', array('status' => 1), ['id' => $activity_id]);
    pdo_update($item['module'] . '_activity', array('status' => 1), ['id' => $item['activity_id']]);
    youmi_result(0, '已上架');
}
if ($op == 'del_status') {
    $activity_id = intval($_GPC['activity_id']);
    $item = pdo_get(YOUMI_NAME . '_activity', ['id' => $activity_id]);
    pdo_update(YOUMI_NAME . '_activity', array('status' => 4), ['id' => $activity_id]);
    pdo_update($item['module'] . '_activity', array('status' => 4), ['id' => $item['activity_id']]);
    youmi_result(0, '已删除');
}

