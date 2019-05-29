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
$_W['page']['title'] = $setting['title'] ? $setting['title'] : '套餐订购';

$member = $this->member;



if ($op == 'display') {
    $_share['title'] = $setting['share_title'];
    $_share['imgUrl'] = tomedia($setting['share_image']);
    $_share['desc'] = $setting['share_desc'];
    $_share['link'] = $_W['siteroot'] . "app/" . $this->createMobileUrl('index', array('fopenid' => $openid));

    $meal = pdo_getall(YOUMI_NAME . '_meal', ['uniacid' => $uniacid, 'status >' => -1]);
    foreach ($meal as &$item) {
        $item['average'] = $item['price'] / ($item['buy'] + $item['gift']);
        $item['average'] = number_format($item['average'], 0);
        unset($item);
    }

    include $this->template('buying');
    exit();
}
