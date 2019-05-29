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

require_once IA_ROOT . '/addons/umiacp_leapcliff/core/defines.php';
require_once IA_ROOT . '/addons/umiacp_leapcliff/core/loader.php';

class umiacp_leapcliffModuleSite extends WeModuleSite
{

    public $uid;
    public $mid;
    public $openid;
    public $user_type;
    public $username;
    public $member;
    public $umi_member;

    public function __construct()
    {
        global $_GPC, $_W;

        $this->uniacid = intval($_W['uniacid']);

        if ($_W['user']) {
            $this->uid = $_W['uid'];
            $this->username = trim($_W['username']);
            $this->user_type = 1;

        } else {

            $this->user_type = intval($_GPC['user_type']) ? intval($_GPC['user_type']) : 2;
            $this->mid = intval($_GPC['mid']);

            if ($this->mid) {
                $this->member = $this->getMemberInfo($this->mid);
            }

            if (in_array($this->user_type, [2, 3])) {

                $this->openid = $_W['openid'] ? $_W['openid'] : ($_W['fans']['openid'] ? $_W['fans']['openid'] : $_GPC['openid']);
                if (!$this->member && $this->openid) {
                    $this->member = $this->getMemberInfo($this->openid);
                    $this->mid = $this->member['mid'];
                }

                $this->uid = $this->member['uid'];
                $this->username = $this->member['nickname'];
            }
            if ($this->user_type == 4) {

                if ($this->member['openid'] || $this->member['wxopenid']) {
                    $this->openid = $this->member['openid'] ? $this->member['openid'] : $this->member['wxopenid'];
                    $this->uid = $this->member['uid'];
                    $this->username = $this->member['nickname'];
                }
            }

        }

    }

    public function doWxapp()
    {

        global $_W, $_GPC;

        $do = trim($_GPC['do']);

        $dir = IA_ROOT . '/addons/' . $this->modulename . '/';

        $dir .= 'inc/mobile/';
        $file = $dir . str_replace('', '', $do) . '.inc.php';
        if (file_exists($file)) {
            require_once $file;
            exit();
        }
        trigger_error("访问的方法 {$file} 不存在.", E_USER_WARNING);
        return null;
        exit();

    }

    /**
     * 获取会员信息
     * @param $openid
     * @return bool|string
     */
    public function getMemberInfo($mid, $user_type = null)
    {
        global $_GPC;
        $uniacid = $this->uniacid;

        if (empty($mid)) {
            $mid = $this->mid;
        }
        if (intval($mid)) {
            $con = ' and u.mid = :mid ';
        } else {
            $con = ' and f.openid = :mid ';
        }
        switch ($user_type) {
            case 1 :
                $fopenid = ' f.openid = u.openid ';
                break;
            case 2 :
                $fopenid = ' f.openid = u.openid ';
                break;
            case 3 :
                $fopenid = ' f.openid = u.wxopenid ';
                break;
            default :
                $fopenid = ' f.openid = u.openid ';
                break;
        }

        $sql = 'SELECT u.*,f.uid,f.nickname,f.groupid,f.follow,f.followtime,f.unfollowtime,f.tag,f.unionid,m.credit1,m.credit2,m.avatar,m.gender FROM ' . tablename('mc_mapping_fans') . ' AS f LEFT JOIN ' . tablename(YOUMI_NAME . '_member') . ' AS u ON ' . $fopenid . ' LEFT JOIN ' . tablename('mc_members') . ' AS m ON f.uid = m.uid where u.uniacid = :uniacid ' . $con;
        $member = pdo_fetch($sql, array(':uniacid' => $uniacid, ':mid' => $mid));

        if (empty($member)) {
            if (in_array($this->user_type, [2, 3])) {
                if (intval($mid)) {
                    return $this->getMemberInfo($this->openid);
                }
                $member['uniacid'] = $uniacid;
                $this->user_type == 2 ? $member['openid'] = $this->openid : $member['wxopenid'] = $this->openid;
                $member['fmid'] = intval($_GPC['fmid']);
                $member['status'] = 1;
                $member['createtime'] = TIMESTAMP;
                pdo_insert(YOUMI_NAME . '_member', $member);
                $member['mid'] = pdo_insertid();
                return $member;
            }
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
    public function getMemberKeyword($keyword, $user_type = null)
    {
        $uniacid = $this->uniacid;
        $con = '';
        $pamas['uniacid'] = $uniacid;
        $con .= " and (f.nickname like '%{$keyword}%' or u.mobile like '%{$keyword}%' )";

        switch ($user_type) {
            case 1 :
                $fopenid = ' f.openid = u.openid ';
                break;
            case 2 :
                $fopenid = ' f.openid = u.openid ';
                break;
            case 3 :
                $fopenid = ' f.openid = u.wxopenid ';
                break;
            default :
                $fopenid = ' f.openid = u.openid ';
                break;
        }

        $sql = 'SELECT u.*,f.uid,f.nickname,f.groupid,f.follow,f.followtime,f.unfollowtime,f.tag,f.unionid,m.credit1,m.credit2,m.avatar,m.gender FROM ' . tablename('mc_mapping_fans') . ' AS f LEFT JOIN ' . tablename(YOUMI_NAME . '_member') . ' AS u ON ' . $fopenid . ' LEFT JOIN ' . tablename('mc_members') . ' AS m ON f.uid = m.uid where u.uniacid = :uniacid ' . $con;

        $member = pdo_fetchall($sql, $pamas);
        foreach ($member as &$item) {
            $item['avatar'] = str_replace('132132', '132', $item['avatar']);
            $item['tag'] = iunserializer(base64_decode($item['tag']));
            unset($item);
        }

        return $member;
    }


    /**
     * 操作日志
     * @param $op
     * @param $content
     * @param null $user_type
     * @return mixed
     */
    public function youmi_operate_log($op, $content, $user_type = null)
    {
        if (!$user_type) {
            $user_type = $this->user_type;
        }
        $data['uniacid'] = $this->uniacid;
        $data['user_type'] = $user_type;
        $data['uid'] = $this->uid;
        $data['mid'] = $this->mid;
        $data['username'] = $this->username;
        $data['op'] = $op;
        if (is_array($content)) {
            $content = serialize($content);
        }
        $data['content'] = $content;
        $data['createtime'] = TIMESTAMP;
        $res = pdo_insert(YOUMI_NAME . '_' . 'log', $data);
        $id = pdo_insertid();
        return $id;
    }

    /**
     * @param $uid
     * @param $data
     * @param $tpwd
     * @param $moudle
     * @param $permission
     * @return array
     */
    public function youmi_operate_user($uid, $data, $tpwd, $moudle, $permission)
    {
        global $_W;
        load()->model('user');

        if ($uid) {
            $user = pdo_fetch("select * from " . tablename("users") . " where uid = :id ", array(':id' => $uid));
            $npwd = $data['password'];
            if (empty($npwd) && empty($tpwd)) {
                $user_permission = pdo_getcolumn('users_permission', ['uid' => $uid], 'permission');
                $ret = pdo_update('users_permission', ['permission' => $permission], ['uid' => $uid]);
                if ($ret) {
                    $op = '修改操作员权限';
                    $content = '修改操作员【' . $user['username'] . '】权限，修改前【' . $user_permission . '】修改后【' . $permission . '】';
                    $this->youmi_operate_log($op, $content);
                }
            } else {
//                if ($opwd != $data['password']) {
//                    return ['status' => 0, 'message' => '原密码错误！'];
//                }
                if ($npwd != $tpwd) {
                    return ['status' => 0, 'message' => '两次密码输入不一致！'];
                    exit;
                }
                if (istrlen($npwd) < 8) {
                    return ['status' => 0, 'message' => '必须输入密码，且密码长度不得低于8位。'];
                }
                $p = user_hash($npwd, $user['salt']);
                $user_permission = pdo_getcolumn('users_permission', ['uid' => $uid], 'permission');
                $ret = pdo_update('users_permission', ['permission' => $permission], ['uid' => $uid]);
                if ($ret) {
                    $op = '修改操作员权限';
                    $content = '修改操作员【' . $user['username'] . '】权限，修改前【' . $user_permission . '】修改后【' . $permission . '】';
                    $this->youmi_operate_log($op, $content);
                }
                $ret = pdo_update('users', array('password' => $p, 'status' => 2), array('uid' => $uid));
                if ($ret) {
                    $op = '修改操作员密码';
                    $content = '修改操作员【' . $user['username'] . '】密码';
                    $this->youmi_operate_log($op, $content);
                }
            }
            return ['status' => 1, 'message' => '更新成功', 'uid' => $uid];
        } else {
            if (!preg_match(REGULAR_USERNAME, $data['username'])) {
                return ['status' => 0, 'message' => '必须输入用户名，格式为 3-15 位字符，可以包括汉字、字母（不区分大小写）、数字、下划线和句点。'];
            }
            if (user_check(array('username' => $data['username']))) {
                return ['status' => 0, 'message' => '非常抱歉，此用户名已经被注册，你需要更换注册名称！'];
            } else {
                $data['password'] = trim($data['password']);
                if (empty($data['password']) || empty($tpwd)) {
                    return ['status' => 0, 'message' => '密码不能为空！'];
                }
                if ($data['password'] != $tpwd) {
                    return ['status' => 0, 'message' => '两次密码输入不一致！'];
                }
                if (istrlen($data['password']) < 8) {
                    return ['status' => 0, 'message' => '必须输入密码，且密码长度不得低于8位。'];
                }
                /*生成用户*/
                $user = array();
                $user['salt'] = random(8);
                $user['username'] = $data['username'];
                $user['password'] = user_hash($data['password'], $user['salt']);
                $user['groupid'] = 0;
                $user['joinip'] = CLIENT_IP;
                $user['joindate'] = TIMESTAMP;
                $user['lastip'] = CLIENT_IP;
                $user['lastvisit'] = TIMESTAMP;
                if (empty($user['status'])) {
                    $user['status'] = 2;
                }
                $result = pdo_insert('users', $user);
                $uid = pdo_insertid();
                /*分配模块*/
                $m = array();
                $m['uniacid'] = $this->uniacid;
                $m['uid'] = $uid;
                $m['type'] = $moudle;
                $m['permission'] = $permission;
                $result = pdo_insert('users_permission', $m);
                if ($result) {
                    $op = '添加操作员';
                    $content = '添加新操作员【' . $user['username'] . '】，并分配权限【' . $permission . '】';
                    $this->youmi_operate_log($op, $content);
                }
                /*添加操作员*/
                $result = pdo_insert('uni_account_users', array('uniacid' => $this->uniacid, 'uid' => $uid, 'role' => 'operator'));

                return ['status' => $result, 'messsage' => $result ? '新增成功' : "新增失败", 'uid' => $uid];
            }
        }
    }


    protected function pay($params = array(), $mine = array())
    {
        global $_W;
        load()->model('activity');
        load()->model('module');
        activity_coupon_type_init();
        if (!$this->inMobile) {
            message('支付功能只能在手机上使用', '', '');
        }

        $order = $this->hasOrder($params['tid'], 2);
        if ($order['status'] == 4) {
            message('订单已取消', '', 'info');
        }
        if ($order['status'] == 2) {
            message('订单已支付', '', 'info');
        }

        $params['module'] = $this->module['name'];
        if ($params['fee'] <= 0) {
            $pars = array();
            $pars['from'] = 'return';
            $pars['result'] = 'success';
            $pars['type'] = '';
            $pars['tid'] = $params['tid'];
            $site = WeUtility::createModuleSite($params['module']);
            $method = 'payResult';
            if (method_exists($site, $method)) {
                exit($site->$method($pars));
            }
        }
        $log = pdo_get('core_paylog', array('uniacid' => $_W['uniacid'], 'module' => $params['module'], 'tid' => $params['tid']));
        if (empty($log)) {
            $log = array(
                'uniacid' => $_W['uniacid'],
                'acid' => $_W['acid'],
                'openid' => $_W['member']['uid'],
                'module' => $this->module['name'],
                'tid' => $params['tid'],
                'fee' => $params['fee'],
                'card_fee' => $params['fee'],
                'status' => '0',
                'is_usecard' => '0',
            );
            pdo_insert('core_paylog', $log);
        }
        if ($log['status'] == '1') {
            message('这个订单已经支付成功, 不需要重复支付.', '', 'info');
        }
        $setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
        if (!is_array($setting['payment'])) {
            message('没有有效的支付方式, 请联系网站管理员.', '', 'error');
        }
        $pay = $setting['payment'];
        $we7_coupon_info = module_fetch('we7_coupon');
        if (!empty($we7_coupon_info)) {
            $cards = activity_paycenter_coupon_available();
            if (!empty($cards)) {
                foreach ($cards as $key => &$val) {
                    if ($val['type'] == '1') {
                        $val['discount_cn'] = sprintf("%.2f", $params['fee'] * (1 - $val['extra']['discount'] * 0.01));
                        $coupon[$key] = $val;
                    } else {
                        $val['discount_cn'] = sprintf("%.2f", $val['extra']['reduce_cost'] * 0.01);
                        $token[$key] = $val;
                        if ($log['fee'] < $val['extra']['least_cost'] * 0.01) {
                            unset($token[$key]);
                        }
                    }
                    unset($val['icon']);
                    unset($val['description']);
                }
            }
            $cards_str = json_encode($cards);
        }
        foreach ($pay as &$value) {
            $value['switch'] = $value['pay_switch'];
        }
        unset($value);
        if (empty($_W['member']['uid'])) {
            $pay['credit']['switch'] = false;
        }
        if ($params['module'] == 'paycenter') {
            $pay['delivery']['switch'] = false;
            $pay['line']['switch'] = false;
        }
        if (!empty($pay['credit']['switch'])) {
            $credtis = mc_credit_fetch($_W['member']['uid']);
            $credit_pay_setting = mc_fetch($_W['member']['uid'], array('pay_password'));
            $credit_pay_setting = $credit_pay_setting['pay_password'];
        }
        $you = 0;
        include $this->template('paycenter');
    }


    /**
     * 判断当前用户有没这个订单
     * @param $order_id
     * @return bool
     */
    protected function hasOrder($order_id, $type = 1)
    {
        if ($type == 1) {
            $order = pdo_get(YOUMI_NAME . '_' . 'order', ['id' => $order_id]);
        } else if ($type == 2) {
            $order = pdo_get(YOUMI_NAME . '_' . 'order', ['uniacid' => $this->uniacid, 'ordersn' => $order_id]);
        }
        if (!$order) {
            return false;
        }
        return $order;
    }

    /**
     * 获取支付结果.
     */
    public function payResult($params)
    {
        global $_GPC;
        global $_W;

        youmi_internal_log('payresult', $params);

        //根据参数params中的result来判断支付是否成功
        if ($params['result'] == 'success' && $params['from'] == 'notify') {

        }
        if ($params['from'] == 'return') {

            //订单
            $paylog = pdo_get('core_paylog', array('uniacid' => $this->uniacid, 'module' => YOUMI_NAME, 'tid' => $params['tid']));
            $tag = unserialize($paylog['tag']);
            $transaction_id = $tag['transaction_id'];
            $status = intval($paylog['status']) === 1;
            if ($status) {
                $order = pdo_get(YOUMI_NAME . '_' . 'order', ['uniacid' => $this->uniacid, 'ordersn' => $params['tid']]);
                pdo_update(YOUMI_NAME . '_' . 'order', ['status' => 2, 'pay_time' => TIMESTAMP, 'transid' => $transaction_id], ['id' => $order['id']]);
                pdo_update(YOUMI_NAME . '_goods', ['success +=' => 1], ['id' => $order['goods_id']]);
                pdo_update(YOUMI_NAME . '_activity', ['success +=' => 1], ['id' => $order['activity_id']]);
                pdo_update(YOUMI_NAME . '_cut', ['status' => 3], ['id' => $order['cut_id']]);

                youmi_settlement_log($order, 1, $order['price'], '用户支付订单：订单ID：' . $order['id'] . '，用户：' . $this->username . '，支付时间：' . date('Y-m-d H:i:s'));

//                $shop = pdo_get(YOUMI_NAME . '_' . 'shop', ['mid' => $order['mid']]);
//                $start = $shop['endtime'] > TIMESTAMP ? $shop['endtime'] : TIMESTAMP;
//                $end = strtotime(date('Y-m-d H:i:s', $start) . ' +' . ($order['buy'] + $order['gift']) . ' month');
//                pdo_update(YOUMI_NAME . '_' . 'shop', ['starttime' => $start, 'endtime' => $end], ['mid' => $order['mid']]);
            }

            $order = pdo_get(YOUMI_NAME . '_' . 'order', ['uniacid' => $this->uniacid, 'ordersn' => $params['tid']]);
            if ($params['result'] == 'success') {
                if ($params['result'] == 'success') {
                    message('支付成功！', $this->createMobileUrl('order'), 'success');
                } else {
                    message('支付失败！', $this->createMobileUrl('index', ['activity_id' => $order['activity_id']]), 'error');
                }
            }
        }
    }

}

