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
$_W['page']['title'] = $setting['title'] ? $setting['title'] : '我的活动';

$member = $this->member;

youmi_puv('index');

if ($op == 'display') {

    $activity = pdo_fetchall('select * from ' . tablename(YOUMI_NAME . '_activity') . ' where uniacid = ' . $uniacid . ' and id in (select activity_id from ' . tablename(YOUMI_NAME . '_cut') . ' where uniacid = ' . $uniacid . ' and mid = ' . $this->mid . ' group by activity_id )');
    foreach ($activity as &$item) {
        $item['image'] = tomedia($item['image']);
        unset($item);
    }

    $_share['title'] = $setting['share_title'];
    $_share['imgUrl'] = tomedia($setting['share_image']);
    $_share['desc'] = $setting['share_desc'];
    if(empty($setting['cannon_fodder'])){
        $_share['link'] = $_W['siteroot'] . "app/" . $this->createMobileUrl('index', array('fopenid' => $openid));
    }else{
        $_share['link'] = $setting['cannon_fodder'] . "app/" . $this->createMobileUrl('index', array('fopenid' => $openid));
    }
    include $this->template('activitys');
    exit();
}
