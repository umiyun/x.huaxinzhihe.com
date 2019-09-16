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
if(!function_exists('youmi_settlement_log')) {
    function youmi_settlement_log($order, $type, $money, $detail)
    {

        $account = pdo_get(YOUMI_NAME . '_' . 'account', ['uniacid' => $order['uniacid'], 'shop_id' => $order['shop_id']]);
        if (!$account) {
            $account['uniacid'] = $order['uniacid'];
            $account['shop_id'] = $order['shop_id'];
            $account['unsettle'] = 0;
            $account['settled'] = 0;
            $account['apply'] = 0;
            $account['withdraw'] = 0;
            $account['status'] = 2;
            $account['createtime'] = TIMESTAMP;
            $res = pdo_insert(YOUMI_NAME . '_' . 'account', $account);
            $account['id'] = pdo_insertid();
        }

        $data['uniacid'] = $order['uniacid'];
        $data['shop_id'] = $order['shop_id'];
        $data['order_id'] = $order['id'];
        $data['type'] = $type;
        $data['money'] = $money;
        $data['detail'] = is_array($detail) ? serialize($detail) : $detail;
        $data['createtime'] = TIMESTAMP;
        $res = pdo_insert(YOUMI_NAME . '_' . 'account_record', $data);
        $id = pdo_insertid();

        if ($order['goods_type'] == 2) {
            if ($type == 2) {
                pdo_update(YOUMI_NAME . '_' . 'account', ['unsettle +=' => $money], ['shop_id' => $order['shop_id']]);
            }
            if ($type == 3) {
                if (floatval($account['unsettle']) <= 0) {
                    return ['errno' => 1, 'message' => '余额不足'];
                }
                pdo_update(YOUMI_NAME . '_' . 'account', ['unsettle -=' => $money], ['shop_id' => $order['shop_id']]);
            }

            if ($type == 4) {
                pdo_update(YOUMI_NAME . '_' . 'account', ['unsettle -=' => $money, 'settled +=' => $money], ['shop_id' => $order['shop_id']]);
            }

        }
        if ($type == 5) {
            if ($type == 3 && $account['settled'] <= $money) {
                return ['errno' => 1, 'message' => '可提现余额不足'];
            }
            pdo_update(YOUMI_NAME . '_' . 'account', ['settled -=' => $money, 'apply +=' => $money, 'status' => 1], ['shop_id' => $order['shop_id']]);
        }
        if ($type == 6) {
            pdo_update(YOUMI_NAME . '_' . 'account', ['apply -=' => $money, 'withdraw +=' => $money, 'status' => 2], ['shop_id' => $order['shop_id']]);
        }
        return ['errno' => 0, 'id' => $id];
    }
}

