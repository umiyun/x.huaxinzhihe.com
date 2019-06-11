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

require IA_ROOT . '/addons/umi_kouling/core/defines.php';

class Umi_koulingModuleSite extends WeModuleSite
{
    private $uid;
    private $username;
    private $member;
    private $openid;
    private $user_type;

    public function __construct()
    {
        global $_GPC, $_W;
        $this->uniacid = intval($_W['uniacid']);
        if ($_W['user']) {
            $this->uid = $_W['uid'];
            $this->username = trim($_W['username']);
            $this->user_type = 1;
        } else {
            $this->openid = $_W['openid'] ? $_W['openid'] : ($_W['fans']['openid'] ? $_W['fans']['openid'] : $_GPC['openid']);
            $this->member = $this->getMemberInfo($this->openid);
            $this->uid = $this->member['uid'];
            $this->username = $this->member['nickname'];
            $this->user_type = 2;
        }

    }

    /**
     * 获取会员信息
     * @param $openid
     * @return bool|string
     */
    public function getMemberInfo($openid, $user_type = null)
    {
        $uniacid = $this->uniacid;
        if (!$user_type) {
            $user_type = $this->user_type;
        }
        if ($user_type != 1) {
            if (empty($openid)) {
                $openid = $this->openid;
            }
            $member = pdo_fetch('SELECT em.*,m.credit1,m.credit2,m.avatar,m.gender FROM ' . tablename('mc_mapping_fans') . ' AS em left join ' . tablename('mc_members') . ' AS m on em.uid = m.uid where em.uniacid = :uniacid and em.openid = :openid ', array(':uniacid' => $uniacid, ':openid' => $openid));
        } else {
            $member = pdo_fetch('SELECT em.*,m.credit1,m.credit2,m.avatar,m.gender FROM ' . tablename('mc_mapping_fans') . ' AS em left join ' . tablename('mc_members') . ' AS m on em.uid = m.uid where em.uniacid = :uniacid and em.uid = :openid ', array(':uniacid' => $uniacid, ':openid' => $openid));
        }
        if (empty($member)) {
            return '';
        }

        $member['avatar'] = str_replace('132132', '132', $member['avatar']);
        $member['tag'] = iunserializer(base64_decode($member['tag']));
        return $member;
    }

    /**
     * 获取会员信息
     * @param $openid
     * @return bool|string
     */
    public function getMemberKeyword($keyword)
    {
        $uniacid = $this->uniacid;
        $con = '';
        $pamas['uniacid'] = $uniacid;
        $con .= " and (m.nickname like '%{$keyword}%' or m.mobile like '%{$keyword}%' )";

        $member = pdo_fetchall('SELECT em.*,m.credit1,m.credit2,m.avatar,m.gender FROM ' . tablename('mc_mapping_fans') . ' AS em left join ' . tablename('mc_members') . ' AS m on em.uid = m.uid where em.uniacid = :uniacid ' . $con, $pamas);
        foreach ($member as &$item) {
            $item['avatar'] = str_replace('132132', '132', $item['avatar']);
            $item['tag'] = iunserializer(base64_decode($item['tag']));
            unset($item);
        }

        return $member;
    }
    
    /**
     * 统计访问量
     * @param $uniacid
     * @param $openid
     * @param string $page
     */
    public function umi_kouling_puvindex($page = 'index', $openid, $uniacid)
    {
        global $_W;
        if (!$uniacid) {
            $uniacid = intval($this->uniacid);
        }
        if (!$openid) {
            $openid = $this->openid;
        }
        $new = strtotime(date('Y-m-d'));
        $pu = pdo_fetch("select * from " . tablename(MC_NAME . '_' . 'puv') . " where uniacid = {$uniacid} and createtime = {$new} limit 1");
        if (empty($pu)) {
            pdo_insert(MC_NAME . '_' . 'puv', array('uniacid' => $uniacid, 'pv' => 0, 'uv' => 0, 'createtime' => $new));
            $pu = pdo_fetch("select * from " . tablename(MC_NAME . '_' . 'puv') . " where uniacid = {$uniacid} and createtime = {$new} limit 1");
        }
        $myp = pdo_fetch("select openid from " . tablename(MC_NAME . '_' . 'puv_record') . " where uniacid = {$uniacid} and openid = '{$openid}' and createtime >= {$new} ");
        pdo_insert(MC_NAME . '_' . 'puv_record', array('uniacid' => $uniacid, 'openid' => $openid, 'page' => $page, 'createtime' => TIMESTAMP));
        if (!empty($myp)) {
            pdo_update(MC_NAME . '_' . 'puv', array('pv' => $pu['pv'] + 1), array('id' => $pu['id']));
        } else {
            pdo_update(MC_NAME . '_' . 'puv', array('pv' => $pu['pv'] + 1, 'uv' => $pu['uv'] + 1), array('id' => $pu['id']));
        }

    }

     /**
     * 操作日志
     * @param $op
     * @param $content
     * @param null $user_type
     * @return mixed
     */
    public function umi_kouling_log($op, $content, $user_type = null)
    {
        if (!$user_type) {
            $user_type = $this->user_type;
        }
        $data['uniacid'] = $this->uniacid;
        $data['user_type'] = $user_type;
        $data['uid'] = $this->uid;
        $data['openid'] = $this->openid;
        $data['username'] = $this->username;
        $data['op'] = $op;
        $data['content'] = $content;
        $data['createtime'] = TIMESTAMP;
        $res = pdo_insert(MC_NAME . '_' . 'log', $data);
        $id = pdo_insertid();
        return $id;
    }

    /**
     * 日志
     * @param $path
     * @param $args
     * @param $uniacid
     */
    public function internal_log($path, $args, $uniacid)
    {

        global $_W;

        if (!$uniacid) {
            $uniacid = $_W['uniacid'];
        }
        //TODO 创建日志
        $path = IA_ROOT . '/addons/' . MC_NAME . '/data/log/' . $uniacid . '/' . $path . '/';
        //首先判断目录存在否
        if (!is_dir($path)) {
            //第3个参数“true”意思是能创建多级目录，iconv防止中文目录乱码
            $res = mkdir(iconv('UTF-8', 'GBK', $path), 0777, true);
        }
        $date = date('Y-m-d', TIMESTAMP);
        file_put_contents($path . $date . '.log', var_export(
                array(
                    'ip' => CLIENT_IP,
                    'uniacid' => $uniacid,
                    'args' => $args, //传入值
                    'time' => date('Y-m-d H:i:s', TIMESTAMP)
                ),
                true) . PHP_EOL, FILE_APPEND);

    }

    /**
     * 经纬转地址
     * @param $lat , $lng  经纬
     * @return array    返回详细地址
     */
    public function latlagtoaddress($lat, $lng)
    {
        $url = 'http://api.map.baidu.com/geocoder?location=' . $lat . ',' . $lng . '&output=xml&key=28bcdd84fae25699606ffad27f8da77b';
        if ($result = $this->http_request($url)) {
            if ($result) {
                $res = $this->xml_to_array($result, true);
                return $res['GeocoderSearchResponse']['result']['addressComponent'];
            }
        }
        return '';
    }

    public function http_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    public function xml_to_array($xml)
    {
        $reg = "/<(\w+)[^>]*>([\x00-\xFF]*)<\/\1>/";
        if (preg_match_all($reg, $xml, $matches)) {
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i++) {
                $subxml = $matches[2][$i];
                $key = $matches[1][$i];
                if (preg_match($reg, $subxml)) {
                    $arr[$key] = $this->xml_to_array($subxml);
                } else {
                    $arr[$key] = $subxml;
                }
            }
        }
        return $arr;
    }

    public function postData($post_data, $sendUrl)
    {
        $post_data = json_encode($post_data);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $sendUrl);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            return 'Errno' . curl_error($curl);
        }
        curl_close($curl);
        return $result;
    }

    public function httpGet($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }

    public function juhecurl($url, $params = false, $ispost = 0)
    {
        $httpInfo = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($ispost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($params) {
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }
        $response = curl_exec($ch);
        if ($response === FALSE) {
            //echo 'cURL Error: ' . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
    }
    
     /**
     * 创建订单
     * 接口一个名为'recharge'的接口
     * 响应json串.
     */
    public function doMobileRecharge()
    {
        global $_W, $_GPC;

        //获取用户要充值的金额数
        $setting = pdo_get(MC_NAME . '_' . 'setting', ['uniacid' => $this->uniacid]);
        $member = $this->mc_member;
        $fee = 0;
        $title = '';
        $type = 1;
        $op = trim($_GPC['op']) ? trim($_GPC['op']) : 'borrow';
        if ($op == 'borrow') {
            if ($member['status'] == 1) {
                $fee = floatval($setting['nomember_money']);
            } else if ($member['status'] == 2) {
                $fee = floatval($setting['member_money']);
            }
            $type = 1;
            $borrow_type = 2;
            $title = trim('借伞押金');
        } else if ($op == 'member') {
            $fee = floatval($setting['annual_fee']);
            $type = 2;
            $borrow_type = 0;
            $title = trim('年费会员');
        }
        if ($setting['borrow_type'] == 2 || $type == 2) {
            $status = 2;
            $message = '下单成功';
        } else {
            $status = 1;
            $fee = 0;
            $borrow_type = 1;
            $message = '下单成功';
            $title = trim('免费借伞');
        }
        if ($type == 1 && $setting['borrow_type'] == 2 && $member['status'] == 2 && $member['failtime'] > TIMESTAMP) {
            $count = pdo_fetchcolumn('select count(id) from ' . tablename(MC_NAME . '_' . 'log') . ' where uniacid = :uniacid and openid = :openid and status in (1,2,7,8) and type = 1 ', [':uniacid' => $this->uniacid, ':openid' => $this->openid]);
            if ($count < intval($setting['member_free_umbrella'])) {
                $status = 1;
                $fee = 0;
                $message = '下单成功';
                $title = trim('免押金借伞');
            }
        }
        if ($fee <= 0) {
            if ($status == 2) {
                die(json_encode(['status' => 0, 'message' => '支付错误, 金额小于0']));
            }
        }
        if ($fee > 0 && $type == 1) {
            $log = pdo_fetch('select * from ' . tablename(MC_NAME . '_' . 'log') . ' where uniacid = :uniacid and openid = :openid and status in (1,2,7,8) and type = 1 and price > 0 ', [':uniacid' => $this->uniacid, ':openid' => $this->openid]);
            if ($log) {
                die(json_encode(['status' => 0, 'message' => '您当前存在未还的伞，请还伞后再借']));
            }
        }
        if ($type == 1) {
            $log = pdo_fetch('select * from ' . tablename(MC_NAME . '_' . 'log') . ' where uniacid = :uniacid and openid = :openid and status = 1 and type = 1 ', [':uniacid' => $this->uniacid, ':openid' => $this->openid]);
            if ($log) {
                die(json_encode(['status' => 1, 'message' => '您已下单成功，请凭此二维码向店员领取', 'orderid' => $log['id']]));
            }
        }
        $store_id = intval($_GPC['store_id']);
        $createtime = TIMESTAMP - 60 * 60;
        $log = pdo_get(MC_NAME . '_' . 'log', ['uniacid' => $this->uniacid, 'openid' => $member['openid'], 'type' => $type, 'store_id' => $store_id, 'status' => 0, 'createtime >=' => $createtime]);

        $store = pdo_get(MC_NAME . '_' . 'store', ['id' => $store_id]);
        if ($store['umbrella_num'] <= 0) {
            die(json_encode(['status' => 0, 'message' => '非常抱歉！库存不足，无法借伞']));
        }

        if (!$log) {
            $tid = date('YmdHis') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
            $log['uniacid'] = $this->uniacid;
            $log['uid'] = $member['uid'];
            $log['openid'] = $member['openid'];
            $log['avatar'] = $member['avatar'];
            $log['nickname'] = $member['nickname'];
            $log['borrow_type'] = $borrow_type;
            $log['type'] = $type;
            $log['store_id'] = $store_id;
            $log['title'] = $title;
            $log['ordersn'] = $tid;
            $log['price'] = $fee;
            $log['status'] = 0;
            $log['createtime'] = TIMESTAMP;
            if ($status == 1) {
                $log['status'] = 1;
                $log['pay_time'] = TIMESTAMP;
//                $log['borrow_type'] = 1;
            }
            pdo_insert(MC_NAME . '_' . 'log', $log);
            $log['id'] = pdo_insertid();
        }
        die(json_encode(['status' => $status, 'message' => $message, 'orderid' => $log['id']]));

    }

    /**
     *  执行支付.
     */
    public function doMobilePay()
    {
        global $_W, $_GPC;

        $orderid = intval($_GPC['orderid']);
        // 判断权限
        $log = $this->hasOrder($orderid);
        if ($log && $log['status'] == 0) {
            //构造支付请求中的参数
            $params = array(
                'tid' => $log['ordersn'],      //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
                'ordersn' => $log['ordersn'],  //收银台中显示的订单号
                'title' => $log['title'],          //收银台中显示的标题
                'fee' => $log['price'],      //收银台中显示需要支付的金额,只能大于 0
                'user' => $log['uid'],     //付款用户, 付款的用户名(选填项)
            );
            $store = pdo_get(MC_NAME . '_' . 'store', ['id' => $log['store_id']]);
            if ($store['umbrella_num'] <= 0) {
                message('非常抱歉！库存不足，无法借伞');
            }

            //调用pay方法
            $this->pay($params);
        } else {
            message('订单不存在或已支付');
        }
    }

    /**
     * 判断当前用户有没这个订单
     * @param $orderid
     * @return bool
     */
    private function hasOrder($orderid)
    {
        $recharge = pdo_get(MC_NAME . '_' . 'log', ['id' => $orderid]);
        if (!$recharge) {
            return false;
        }
        return $recharge;
    }

    /**
     * 获取支付结果.
     */
    public function payResult($params)
    {
        global $_GPC;
        global $_W;
//        $this->internal_log('pay', $params);
        //根据参数params中的result来判断支付是否成功
        if ($params['result'] == 'success' && $params['from'] == 'notify') {

            //订单
            $paylog = pdo_get('core_paylog', array('uniacid' => $this->uniacid, 'module' => MC_NAME, 'tid' => $params['tid']));
            $tag = unserialize($paylog['tag']);
            $transaction_id = $tag['transaction_id'];
            $status = intval($paylog['status']) === 1;
            if ($status) {
                $log = pdo_get(MC_NAME . '_' . 'log', ['uniacid' => $this->uniacid, 'openid' => $this->openid, 'ordersn' => $params['tid']]);
                $setting = pdo_get(MC_NAME . '_' . 'setting', ['uniacid' => $this->uniacid]);

                if ($log['type'] == 1) {

                    pdo_update(MC_NAME . '_' . 'log', ['status' => 1, 'pay_time' => TIMESTAMP, 'transid' => $transaction_id], ['id' => $log['id']]);
                    $store = pdo_get(MC_NAME . '_' . 'store', ['id' => $log['store_id']]);
                    if ($store['umbrella_num'] <= 0) {
                        //todo  退款  未完
                        $this->refund($log['id'], 3);
                    }
//                    pdo_update(MC_NAME . '_' . 'store', ['umbrella_num -=' => 1], ['id' => $log['store_id']]);
                } else {
                    if ($this->mc_member['failtime'] > TIMESTAMP) {
                        $expiretime = strtotime('+1 year', $this->mc_member['failtime']);
                    } else {
                        $expiretime = strtotime('+1 year');
                    }
                    pdo_update(MC_NAME . '_' . 'log', ['status' => 1, 'pay_time' => TIMESTAMP, 'transid' => $transaction_id, 'expiretime' => $expiretime], ['id' => $log['id']]);
                    pdo_update(MC_NAME . '_' . 'member', ['jointime' => TIMESTAMP, 'failtime' => $expiretime, 'status' => 2], ['uniacid' => $this->uniacid, 'openid' => $this->openid]);
                }
            }
        }
        if ($params['from'] == 'return') {
            $log = pdo_get(MC_NAME . '_' . 'log', ['uniacid' => $this->uniacid, 'openid' => $this->openid, 'ordersn' => $params['tid']]);
            if ($params['result'] == 'success') {
                if ($log['type'] == 1) {
                    message('支付成功！', $this->createMobileUrl('log', ['op' => 'detail', 'orderid' => $log['id']]), 'success');
                } else if ($log['type'] == 2) {
                    message('支付成功！', $this->createMobileUrl('index', ['op' => 'user']), 'success');
                }
            } else {
                message('支付失败！', $this->createMobileUrl('index'), 'error');
            }
        }
    }

    /**
     * 退款
     * @param $orderid  订单号
     * @param int $type 类型
     * @param int $money 金额
     * @return array|bool|mixed|string|void
     * @throws WxPayException
     */
    public function refund($orderid, $type, $money = 0)
    {
        global $_W;
        include_once MC_CERT . 'WxPay.Api.php';
        load()->model('account');
        load()->func('communication');

        $WxPayApi = new WxPayApi();
        $input = new WxPayRefund();

        $account_info = pdo_fetch("select * from" . tablename('account_wechats') . "where uniacid={$_W['uniacid']}");
        $refund_order = pdo_get(MC_NAME . '_' . 'log', ['uniacid' => $this->uniacid, 'id' => $orderid]);
        $setting = uni_setting_load('payment', $_W['uniacid']);
        $settings = $setting['payment']['wechat'];
        $refunfd = $setting['payment']['wechat_refund'];

        if ($refund_order['refunded'] == 1) {
            message('此订单已退款，请勿重复退款');
        }
        $path = IA_ROOT . '/addons/' . MC_NAME . '/cert/' . $_W['uniacid'];
        //首先判断目录存在否
        if (!is_dir($path)) {
            //第3个参数“true”意思是能创建多级目录，iconv防止中文目录乱码
            $res = mkdir(iconv('UTF-8', 'GBK', $path), 0777, true);
        }
        $certfile = $path . '/apiclient_cert.pem';
        $keyfile = $path . '/apiclient_key.pem';
        if (!file_exists($certfile)) {
            file_put_contents($certfile, $refunfd['cert']);
        }
        if (!file_exists($keyfile)) {
            file_put_contents($keyfile, $refunfd['key']);
        }

        $key = $settings['apikey'];
        $mchid = $settings['mchid'];
        $appid = $account_info['key'];
        if (!$money) {
            $fee = $refund_order['price'] * 100;
        } else {
            $fee = $money * 100;
        }
        $refund = floatval($refund_order['refund']) + $fee * 0.01;
        if ($refund > floatval($refund_order['price'])) {
            message('退款金额不能大于订单金额');
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

        if ($result['return_code'] == 'SUCCESS') {

            if ($refund == floatval($refund_order['price'])) {
                $type = 3;
            }
            $data['refund'] = $refund;
            $data['refunded'] = 1;
            $data['refund_time'] = TIMESTAMP;
            $data['status'] = $type;
            $data['refund_id'] = $result['refund_id'];
            pdo_update(MC_NAME . '_' . 'log', $data, ['id' => $refund_order['id']]);

            return 'success';
        } else {

            return 'fail';
        }
    }

}