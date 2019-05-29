<?php
/**
 * Created by IntelliJ IDEA.
 * User: 7?
 * Date: 2018/7/30 0030
 * Time: 12:18
 */

if (!defined('IN_IA')) {
    exit('Access Denied');
}

/**
 * 结算日志
 */
if (!function_exists('youmi_settlement_log')) {
    function youmi_settlement_log($order, $type, $money, $detail)
    {

        if (!$order['shop_id']) {
            return ['errno' => 0, 'id' => 0];
        }
        $account = pdo_get(UMI_NAME . '_' . 'account', ['uniacid' => $order['uniacid'], 'shop_id' => $order['shop_id']]);
        if (!$account) {
            $account['uniacid'] = $order['uniacid'];
            $account['shop_id'] = $order['shop_id'];
            $account['unsettle'] = 0;
            $account['settled'] = 0;
            $account['apply'] = 0;
            $account['withdraw'] = 0;
            $account['status'] = 2;
            $account['createtime'] = TIMESTAMP;
            $res = pdo_insert(UMI_NAME . '_' . 'account', $account);
            $account['id'] = pdo_insertid();
        }

        $data['uniacid'] = $order['uniacid'];
        $data['shop_id'] = $order['shop_id'];
        $data['order_id'] = $order['id'];
        $data['type'] = $type;
        $data['money'] = $money;
        $data['detail'] = is_array($detail) ? serialize($detail) : $detail;
        $data['createtime'] = TIMESTAMP;
        $res = pdo_insert(UMI_NAME . '_' . 'account_record', $data);
        $id = pdo_insertid();

        switch ($type) {
            case 1 :
                pdo_update(UMI_NAME . '_' . 'account', ['unsettle +=' => $money], ['shop_id' => $order['shop_id']]);
                break;
            case 2 :
                pdo_update(UMI_NAME . '_' . 'account', ['unsettle -=' => $money, 'settled +=' => $money], ['shop_id' => $order['shop_id']]);
                break;
            case 3 :
                pdo_update(UMI_NAME . '_' . 'account', ['settled -=' => $money, 'apply +=' => $money, 'status' => 1], ['shop_id' => $order['shop_id']]);
                break;
            case 4 :
                pdo_update(UMI_NAME . '_' . 'account', ['apply -=' => $money, 'withdraw +=' => $money, 'status' => 2], ['shop_id' => $order['shop_id']]);
                break;
        }
//        pdo_debug();
        return ['errno' => 0, 'id' => $id];
    }
}

