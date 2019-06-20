<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

defined('IN_IA') or exit('Access Denied');

load()->model('mc');
$do = trim($_GPC['op']);
$dos = array('openid', 'userinfo', 'check');
$do = in_array($do, $dos) ? $do : 'openid';


if ($do == 'openid') {

    $code = $_GPC['code'];
    $openid = $_GPC['openid'];

    if (empty($openid) && !empty($_W['openid'])) {
        $openid = $_W['openid'];
    }

    $setting = youmi_setting_get_list();

    if (empty($setting['wxapp_appid']) || (empty($code) && empty($openid))) {
        exit('通信错误，请在微信中重新发起请求');
    }

    if (!empty($openid)) {
        $_SESSION['openid'] = $oauth['openid'];
        $umi_member = pdo_get(YOUMI_NAME . '_member', ['wxopenid' => $openid]);
        $fans = mc_fansinfo($openid);
        if (!empty($fans)) {
            youmi_result(0, '', array('sessionid' => $_W['session_id'], 'umi_member' => $umi_member, 'userinfo' => $fans));
        } else {
            youmi_result(1, 'openid不存在');
        }
    }
    $oauth = getOauthInfo($code);
    if (!empty($oauth) && !is_error($oauth)) {
        $_SESSION['openid'] = $oauth['openid'];
        $_SESSION['session_key'] = $oauth['session_key'];
        $fans = mc_fansinfo($oauth['openid']);
        $umi_mem