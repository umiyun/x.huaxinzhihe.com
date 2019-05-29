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

/**
 * 每天凌晨0点重置商品数据  跑1小时
 * do : task   op : reset_goods
 */
if ($op == 'reset_goods') {
    $referrer = trim($_GPC['referrer']);
//    if (!$referrer) {
//        youmi_result(1, '非法访问');
//    }

    $paras[':uniacid'] = $this->uniacid;
    $new = strtotime(date('Y-m-d'));
    $paras[':new'] = $new;

    $goods = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'goods') . ' where uniacid = :uniacid and ((goods_type = 1 and batch_number <> \'\' ) or endtime < :new) and id not in (select goods_id from ' . tablename(YOUMI_NAME . '_' . 'goods_log') . ' where uniacid = :uniacid and createtime = :new) limit 50 ', $paras);
    $res = 0;

    if ($goods) {
        foreach ($goods as $good) {

            if ($good['goods_type'] == 1) {
                $update['temp_price'] = $good['price'];
                $update['batch_number'] = '';
                $update['batch_status'] = 2;
                $update['pv'] = 0;
                $update['stock'] = 1;
                if ($good['batch_number']) {
                    pdo_update(YOUMI_NAME . '_' . 'batch', [
                        'status' => 3,
                        'updatetime' => TIMESTAMP,
                    ], ['goods_id' => $good['id'], 'batch_number' => $good['batch_number']]);
                }
            }
            if ($good['endtime'] < TIMESTAMP) {
                $update['status'] = 2;
            }
            $res += pdo_update(YOUMI_NAME . '_' . 'goods', $update, ['id' => $good['id']]);
            pdo_insert(YOUMI_NAME . '_' . 'goods_log', [
                'uniacid' => $uniacid,
                'goods_id' => $good['id'],
                'status' => 1,
                'createtime' => strtotime(date('Y-m-d'))
            ]);

            unset($good);
            unset($update);
        }
    }
    pdo_delete(YOUMI_NAME . '_' . 'goods_log', ['uniacid' => $uniacid, 'createtime' => strtotime(date('Y-m-d') . ' -3 day')]);
    youmi_result(0, '成功处理' . $res . '条商品！');

}

/**
 * 定时更新订单
 * do : task   op : reset_order
 */
if ($op == 'reset_order') {
    $referrer = trim($_GPC['referrer']);
//    if (!$referrer) {
//        youmi_result(1, '非法访问');
//    }

    $paras[':uniacid'] = $this->uniacid;
    $new = strtotime(date('Y-m-d'));

    $orders = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'order') . ' where uniacid = :uniacid and status = 1 limit 20 ', $paras);
    if ($orders) {
        $res = 0;

        $setting = youmi_setting_get_list();

        $appid = $setting["wxapp_appid"];
        $mch_id = $setting["wxapp_mchid"];
        $key = $setting["wxapp_signkey"];

        foreach ($orders as $order) {

            $weixinpay = new WeixinPay($appid, $openid, $mch_id, $key, $order['tid'], $order['title'], $order['price']);
            $return = $weixinpay->orderquery();
            if ($return['return_code'] == 'SUCCESS' && $return['result_code'] == 'SUCCESS') {
                if ($return['trade_state'] == 'SUCCESS' && floatval($return['total_fee']) / 100 == $order['price']) {
                    $res += pdo_update(YOUMI_NAME . '_' . 'order', ['status' => 2, 'pay_time' => TIMESTAMP, 'transid' => $return['transaction_id']], ['id' => $order['id']]);
                    pdo_update(YOUMI_NAME . '_' . 'goods', ['stock -=' => $order['num']], ['id' => $order['goods_id']]);
                    if ($order['goods_type'] == 1) {
//                pdo_update(YOUMI_NAME . '_' . 'goods', ['batch_number' => '', 'batch_status' => 2], ['id' => $order['goods_id']]);
                        pdo_update(YOUMI_NAME . '_' . 'batch', ['status' => 2, 'updatetime' => TIMESTAMP], ['batch_number' => $order['batch_number']]);
                    }
                    $order['status'] = 2;
                }
            }

            if ($order['goods_type'] == 1 && $order['status'] == 1 && $order['createtime'] + 10 * 60 < TIMESTAMP) {
                $res += pdo_update(YOUMI_NAME . '_' . 'order', ['status' => 4], ['id' => $order['id']]);
            }

            unset($order);
        }
    }
    youmi_result(0, '成功处理' . $res . '条订单！');

}


/**
 * 定时结算订单
 * do : task   op : settle_order
 */
if ($op == 'settle_order') {
    $referrer = trim($_GPC['referrer']);
//    if (!$referrer) {
//        youmi_result(1, '非法访问');
//    }

    $paras[':uniacid'] = $this->uniacid;
    $setting = youmi_setting_get_list();
    $setting['billing_cycle'] = intval($setting['billing_cycle']) ? intval($setting['billing_cycle']) : 3;
    $paras[':new'] = time() - $setting['billing_cycle'] * 24 * 60 * 60;

    $orders = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'order') . ' where uniacid = :uniacid and status = 3 and is_settle = 0 and salertime <= :new limit 20 ', $paras);
    if ($orders) {
        $res = 0;

        foreach ($orders as $order) {
            $res = youmi_settlement_log($order, 4, $order['price'], '结算订单：订单ID：' . $order['id'] . '，结算时间：' . date('Y-m-d H:i:s'));
            if ($res['errno'] === 0) {
                pdo_update(YOUMI_NAME . '_' . 'order', ['is_settle' => 1], ['id' => $order['id']]);
            }
            unset($order);
        }
    }
    youmi_result(0, '成功处理' . $res . '条订单！');

}


