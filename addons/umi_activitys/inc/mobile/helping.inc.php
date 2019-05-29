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
$_W['page']['title'] = $setting['title'] ? $setting['title'] : '帮助中心';

$member = $this->member;



if ($op == 'display') {
    $_share['title'] = $setting['share_title'];
    $_share['imgUrl'] = tomedia($setting['share_image']);
    $_share['desc'] = $setting['share_desc'];
    $_share['link'] = $_W['siteroot'] . "app/" . $this->createMobileUrl('index', array('fopenid' => $openid));

    $cate_id = intval($_GPC['cate_id']);
    $helpcate = pdo_getall(YOUMI_NAME . '_helpcate', ['uniacid' => $uniacid, 'status' => 1]);
    if (!$cate_id) {
        $cate_id = $helpcate[0]['id'];
    }
    $help = pdo_getall(YOUMI_NAME . '_help', ['uniacid' => $uniacid, 'status' => 1, 'helpcate_id' => $cate_id]);
    foreach ($helpcate as $item) {
        if ($item['id'] == $cate_id) {
            $logo = tomedia($item['logo']);
        }
    }

    include $this->template('helping');
    exit();
}
if ($op == 'hDesc') {

    $id = intval($_GPC['id']);
    $help = pdo_get(YOUMI_NAME . '_help', ['uniacid' => $uniacid, 'status' => 1, 'id' => $id]);

    include $this->template('helping_desc');
    exit();
}