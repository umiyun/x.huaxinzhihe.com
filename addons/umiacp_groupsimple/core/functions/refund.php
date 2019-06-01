<?php
/**
 * Created by IntelliJ IDEA.
 * User: 7³
 * Date: 2018/7/30 0030
 * Time: 12:18
 */

if (!defined('IN_IA')) {
    exit('Access Denied');
}


/**
 * 退款
 * @param $orderid  订单号
 * @param int $type 类型
 * @param int $money 金额
 * @return array|bool|mixed|string|void
 * @throws WxPayException
 */
if (!function_exists('youmi_refund')) {
    function youmi_refund($orderid, $money = 0)
    {
        global $_W;
        include_once YOUMI_CERT . 'WxPay.Api.php';

        $WxPayApi = new WxPayApi();
        $input = new WxPayRefund();

        $refund_order = pdo_get(YOUMI_NAME . '_' . 'order', ['uniacid' => $_W['uniacid'], 'id' => $orderid]);

        if ($refund_order['status'] == 5) {
            $result['err_code_des'] = '此订单已退款，请勿重复退款';
            $result['errno'] = 1;
            return $result;
        }
        $certfile = YOUMI_CERT  . '/apiclient_cert.pem.'. $_W['uniacid'];
        $keyfile = YOUMI_CERT . '/apiclient_key.pem.'. $_W['uniacid'];

        $setting = youmi_setting_get_list();

        $appid = trim($setting["wxapp_appid"]);
        $mchid = trim($setting["wxapp_mchid"]);
        $key = trim($setting["wxapp_signkey"]);

        if (!$money) {
            $fee = $refund_order['price'] * 100;
        } else {
            $fee = $money * 100;
        }
        $refund = floatval($refund_order['refund']) + $fee * 0.01;
        if ($refund > floatval($refund_order['price'])) {
            return array('errno' => 1, 'err_code_des' => '退款金额不能大于订单金额');
        }
        $refundid = $refund_order['transid'];
        $input->SetAppid($appid);
        $input->SetMch_id($mchid);
        $input->SetOp_user_id($mchid);
        $input->SetRefund_fee($fee);
        $input->SetTotal_fee($refund_order['price'] * 100);
        $input->SetTransaction_id($refundid);
        $input->SetOut_refund_no($refund_order['ordersn']);

        $result = $WxPayApi->refund($input, 6, $certfile, $keyfile, $key);

        if (($result['return_code'] == 'SUCCESS') && ($result['result_code'] == 'SUCCESS')) {

            $data['refund_time'] = TIMESTAMP;
            $data['refund_id'] = $result['refund_id'];
            $data['status'] = 5;
            pdo_update(YOUMI_NAME . '_' . 'order', $data, ['id' => $refund_order['id']]);


            return ['errno' => 0];
        } else if (($result['return_code'] == 'FAIL') || ($result['result_code'] == 'FAIL')) {
            $result['err_code_des'] = (empty($result['err_code_des']) ? $result['return_msg'] : $result['err_code_des']);
            $result['errno'] = 1;
            return $result;
        } else {
            $result['err_code_des'] = (empty($result['err_code_des']) ? $result['return_msg'] : $result['err_code_des']);
            $result['errno'] = 1;
            return $result;
        }
    }
}

