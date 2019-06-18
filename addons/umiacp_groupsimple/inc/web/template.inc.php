<?php
/**
 * Created by IntelliJ IDEA.
 * User: 7³
 * Date: 2018/7/30 0030
 * Time: 12:18
 */

global $_W, $_GPC;
$_W['page']['title'] = '系统设置';
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');
$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'display';

$setting = youmi_setting_get_list();

if ($op == 'display') {

    $res = temp_list();
    $list = $res['data'];
    foreach ($list as &$item) {
//        $item['example'] = explode('\\n', $item['example']);
        unset($item);
    }
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

/**
 * 添加模版
 * do   template    op  add
 * opentm   编号
 */
if ($op == 'add') {
    $opentm = trim($_GPC['opentm']);
    $res = add($opentm);
    $temp_id = $res['data']['template_id'];
    $res = temp_list();
    $list = $res['data'];
    foreach ($list as &$item) {
        if ($item['template_id'] == $temp_id) {
            $result = $item;
        }
        unset($item);
    }
    youmi_result(0, '', $result);
}

/**
 * 删除模版
 * do   template    op  del
 * temp_id   模版id
 */
if ($op == 'del') {
    $temp_id = trim($_GPC['temp_id']);
    $res = del($temp_id);
    die(json_encode($res));
}

function getToken() {
    $account_api = WeAccount::create();
    $token = $account_api->getAccessToken();
    return $token;
}

function temp_list() {
    //获取模版列表
    $token = getToken();
    $url = 'https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token=' . $token;
    $res = ihttp_request($url);
    $res = json_decode($res['content'], true);
    return ['errno' => 0, 'message' => '', 'data' => $res['template_list']];
}

function add($opentm) {
    //添加模版
    $token = getToken();
    $url = 'https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token=' . $token;
    $post['template_id_short'] = trim($opentm);
    $res = ihttp_request($url, json_encode($post));
    $res = json_decode($res['content'], true);
    return ['errno' => $res['errcode'] ? 1 : 0, 'message' => $res['errcode'] ? '新增失败' : '新增失败', 'data' => $res];
}

function del($temp_id) {
    //删除模版
    $token = getToken();
    $url = 'https://api.weixin.qq.com/cgi-bin/template/del_private_template?access_token=' . $token;
    $post['template_id'] = trim($temp_id);
    $res = ihttp_request($url, json_encode($post));
    $res = json_decode($res['content'], true);
    return ['errno' => $res['errcode'] ? 1 : 0, 'message' => $res['errcode'] ? '删除失败' : '删除成功', 'data' => $res];
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

