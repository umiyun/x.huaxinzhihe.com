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
    $openid="onsDWtxg9p4dd4uQHFc579UTJCtE";
    $url='www.baidu.com';
    $args=[
        'keyword1'=>'测试名称',
        'keyword2'=>'A39393939331',
        'keyword3'=>'预订时间 '.date('Y-m-d H:i:s'),
    ];
    $result =sendOrderMsg($openid,$args,$setting,$url);
}

function sendOrderMsg($openid,$args,$setting,$url){
    $data = array(
        'first' => array(
            'value' => $setting['order_first'],
            'color' => '#ff510'
        ),
        'keyword1' => array(
            'value' => $args['keyword1'],
            'color' => '#ff510'
        ),
        'keyword2' => array(
            'value' => $args['keyword2'],
            'color' => '#ff510'
        ),
        'keyword3' => array(
            'value' => $args['keyword3'],
            'color' => '#ff510'
        ),
        'remark' => array(
            'value' => $setting['order_remark'],
            'color' => '#ff510'
        ),
    );

    $account_api = WeAccount::create();
    return $account_api->sendTplNotice($openid, $setting['order_id'], $data,$url);
}

