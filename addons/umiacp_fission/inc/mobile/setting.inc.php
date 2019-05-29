<?php
/**
 * Created by IntelliJ IDEA.
 * User: 7³
 * Date: 2018/7/30 0030
 * Time: 12:18
 */

global $_W, $_GPC;

$uniacid = intval($this->uniacid);

$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'display';

$setting = youmi_setting_get_list();

if ($op == 'display') {


    $account_api = WeAccount::create();
    $jssdk = $account_api->getJssdkConfig();
    $data['jssdk'] = $jssdk;
    unset($setting['wxapp_appid']);
    unset($setting['wxapp_mchid']);
    unset($setting['wxapp_secret']);
    unset($setting['wxapp_signkey']);
    $data['setting'] = $setting;

    youmi_result(0, '模块通用配置', $data);

}

if ($op == 'wxapp') {
    unset($setting['wxapp_appid']);
    unset($setting['wxapp_mchid']);
    unset($setting['wxapp_secret']);
    unset($setting['wxapp_signkey']);
    $data['setting'] = $setting;
    youmi_result(0, '模块通用配置', $data);

}
