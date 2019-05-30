<?php
/**
 * Created by IntelliJ IDEA.
 * User: 7³
 * Date: 2018/7/30 0030
 * Time: 12:18
 */

global $_W, $_GPC;
$_W['page']['title'] = '系统设置';

$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'display';

$setting = youmi_setting_get_list();

if ($op == 'display') {
    if (checksubmit()) {

        $set = $_GPC['set'];
        foreach ($set as$skey => &$item) {
            $item = array_filter($item);
            if (!$item) {
                continue;
            }
            youmi_setting_save($skey, $item);
            unset($item);
        }

        itoast('温馨提示：修改成功', $this->createwebUrl('template'),'success');
    }
    include $this->template('template');
}

if ($op=='aa'){
    //发送订单通知

    $data = array(
        'first' => array(
            'value' => $setting['order_first'],
            'color' => '#ff510'
        ),
        'keyword1' => array(
            'value' => $setting['order_keyword1'],
            'color' => '#ff510'
        ),
        'keyword2' => array(
            'value' => $setting['order_keyword2'],
            'color' => '#ff510'
        ),
        'keyword3' => array(
            'value' => $setting['order_keyword3'],
            'color' => '#ff510'
        ),
        'remark' => array(
            'value' => $setting['order_remark'],
            'color' => '#ff510'
        ),
    );
    $openid="onsDWtxg9p4dd4uQHFc579UTJCtE";
    $url='www.baidu.com';
    $account_api = WeAccount::create();
    $result = $account_api->sendTplNotice($openid, $setting['order_id'], $data,$url);
}

