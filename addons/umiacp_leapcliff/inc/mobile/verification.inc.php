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
$_W['page']['title'] = $setting['title'] ? $setting['title'] : '核销';

$member = $this->member;


if ($op == 'display') {

    include $this->template('verification');
    exit();
}
