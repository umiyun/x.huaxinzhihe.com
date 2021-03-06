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
 * 登录
 * do : login   op : login
 * mobile   手机号
 * password  密码
 */
if ($op == 'login') {
    $mobile = trim($_GPC['mobile']);
    if (!$mobile) {
        youmi_result(1, '请输入手机号');
    }
    $password = trim($_GPC['password']);
    if (!$password) {
        youmi_result(1, '请输入密码');
    }

    $paras['uniacid'] = $this->uniacid;
    $paras['mobile'] = $mobile;
    $member = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'member') . ' where uniacid = :uniacid and mobile = :mobile ', $paras);
    $pass = md5($password . $member['salt']);
    if ($pass != $member['password']) {
        youmi_result(1, '用户名或密码错误');
    }
    if ($this->user_type == 2 && !$member['openid']) {
        pdo_update(YOUMI_NAME . '_' . 'member', ['openid' => $this->openid], ['id' => $member['id']]);
    } elseif ($this->user_type == 3 && !$member['wxopenid']) {
        pdo_update(YOUMI_NAME . '_' . 'member', ['wxopenid' => $this->openid], ['id' => $member['id']]);
    }
    unset($member['password']);
    unset($member['salt']);
    $data['member'] = $member;
    youmi_result(0, '登录成功', $data);

}


/**
 * 登录+注册
 * do : login   op : mobile_login
 * mobile   手机号
 * mobile_code   手机号验证码
 */
if ($op == 'mobile_login') {
    $mobile = trim($_GPC['mobile']);
    if (!$mobile) {
        youmi_result(1, '请输入手机号');
    }
    if (!$_SESSION['mobile_code'] || !trim($_GPC['mobile_code']) || $_SESSION['mobile_code'] != trim($_GPC['mobile_code'])) {
        youmi_result(1, '验证码错误');
    }

    unset($_SESSION['mobile_code']);
    $paras['uniacid'] = $this->uniacid;
    $paras['mobile'] = $mobile;
    $member = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'member') . ' where uniacid = :uniacid and mobile = :mobile ', $paras);
    if ($member) {
        if ($this->user_type == 2 && !$member['openid']) {
            pdo_update(YOUMI_NAME . '_' . 'member', ['openid' => $this->openid], ['id' => $member['id']]);
        } elseif ($this->user_type == 3 && !$member['wxopenid']) {
            pdo_update(YOUMI_NAME . '_' . 'member', ['wxopenid' => $this->openid], ['id' => $member['id']]);
        }
    } else {
        if ($this->user_type == 3) {
            $member['wxopenid'] = trim($this->openid);
        } elseif ($this->user_type == 2) {
            $member['openid'] = trim($this->openid);
        }
        $member['mobile'] = trim($_GPC['mobile']);
        $member['uniacid'] = $this->uniacid;
        $member['status'] = 1;
        $member['createtime'] = TIMESTAMP;
        $res = pdo_insert(YOUMI_NAME . '_' . 'member', $member);
        $mid = pdo_insertid();
        $member['id'] = $mid;
    }
    $data['member'] = $member;
    youmi_result(0, '登录成功', $data);

}

/**
 * 注册
 * do : login   op : register
 * realname     用户名
 * mobile       手机号
 * password     密码
 */
if ($op == 'register') {

    if ($this->user_type == 3) {
        $data['wxopenid'] = trim($this->openid);
    } elseif ($this->user_type == 2) {
        $data['openid'] = trim($this->openid);
    }
    $data['realname'] = trim($_GPC['realname']);
    $data['mobile'] = trim($_GPC['mobile']);
    $data['password'] = trim($_GPC['password']);
    $data['salt'] = random(8);
    $data['password'] = md5($data['password'] . $data['salt']);
    $data['uniacid'] = $this->uniacid;
    $data['status'] = 1;
    $data['createtime'] = TIMESTAMP;

    $paras['uniacid'] = $this->uniacid;
    $paras['mobile'] = $data['mobile'];
    $member = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'member') . ' where uniacid = :uniacid and mobile = :mobile ', $paras);
    if ($member) {
        youmi_result(1, '此用户已注册，请直接登录');
    }
    $res = pdo_insert(YOUMI_NAME . '_' . 'member', $data);
    $mid = pdo_insertid();
    $data['id'] = $mid;
    $status = $res ? 0 : 1;
    unset($data['password']);
    unset($data['salt']);
    $member['member'] = $data;
    youmi_result($status, '注册' . ($status ? '失败' : '成功'), $member);

}

/**
 * 获取验证码
 * do : login   op : mobile_code
 * mobile       手机号
 */
if ($op == 'mobile_code') {

    $mobile = trim($_GPC['mobile']);
    if (!$mobile) {
        youmi_result(1, '请输入手机号');
    }
    $paras['uniacid'] = $this->uniacid;
    $paras['mobile'] = $mobile;
    $member = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'member') . ' where uniacid = :uniacid and mobile = :mobile ', $paras);
//    if ($member) {
//        youmi_result(1, '此手机号已注册');
//    }

    $mobile_code = '';
    for ($i = 0; $i < 5; $i++) {
        $mobile_code .= rand(0, 9);
    }
    $result = youmi_send_sms($mobile, $mobile_code);

    if ($result['errno']) {
        youmi_result(1, '短信验证码发送失败', $result['result']);
    } else {
        $_SESSION['mobile_code'] = $mobile_code;
        youmi_result(0, '短信验证码发送成功', $mobile_code);
    }

}

/**
 * 查询用户是否已注册
 * do : login   op : search_member
 * mobile   手机号
 */
if ($op == 'search_member') {
    $paras['uniacid'] = $this->uniacid;
    $paras['mobile'] = $data['mobile'];
    $member = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'member') . ' where uniacid = :uniacid and mobile = :mobile ', $paras);
    if ($member) {
        youmi_result(0, '此账户已注册，请直接登录');
    } else {
        youmi_result(1, '此账号可注册');
    }
}

/**
 * 绑定用户
 * do : login   op : binding
 */
if ($op == 'binding') {

    $openid = trim($_W['openid']);
    if (!$openid) {
        youmi_result(1, '参数错误');
    }

    $paras['uniacid'] = $this->uniacid;
    $paras['id'] = intval($this->mid);
    $member = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'member') . ' where uniacid = :uniacid and id = :id ', $paras);
    if (!$member) {
        youmi_result(1, '此用户不存在');
    }
    $res = pdo_update(YOUMI_NAME . '_' . 'member', ['openid' => $openid], ['id' => $this->mid]);
    $status = $res ? 0 : 1;
    youmi_result($status, '绑定' . ($status ? '成功' : '失败'), $member);


}

/**
 * 换绑手机号
 * do : login   op : change_mobile
 */
if ($op == 'change_mobile') {
    $mobile = trim($_GPC['mobile']);
    if (!$mobile) {
        youmi_result(1, '请输入手机号');
    }
    $umi_member = $this->umi_member;
    if ($umi_member) {
        $umi_member['mobile'] = $mobile;
        pdo_update(YOUMI_NAME . '_' . 'member', ['mobile' => $mobile], ['id' => $this->mid]);
    }
    $data['member'] = $umi_member;
    youmi_result(0, '换绑成功', $data);
}
