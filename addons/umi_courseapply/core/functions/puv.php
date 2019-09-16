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
 * 统计访问量
 * @param $uniacid
 * @param $openid
 * @param string $page
 */
if(!function_exists('youmi_puv')) {
    function youmi_puv($page = '', $goods_id = 0, $input = '', $openid = '', $uniacid = 0)
    {
        global $_W;
        if (!$uniacid) {
            $uniacid = intval($_W['uniacid']);
        }
        if (!$openid) {
            $openid = $_W['openid'];
        }
        $new = strtotime(date('Y-m-d'));

        $pu = pdo_get(YOUMI_NAME . '_' . 'puv', ['uniacid' => $uniacid, 'page' => $page, 'goods_id' => $goods_id, 'createtime' => $new]);

        if (empty($pu)) {
            $pu = array(
                'uniacid' => $uniacid,
                'page' => $page,
                'goods_id' => $goods_id,
                'pv' => 0,
                'uv' => 0,
                'createtime' => $new
            );
            pdo_insert(YOUMI_NAME . '_' . 'puv', $pu);
            $pu['id'] = pdo_insertid();
        }
        $myp = pdo_get(YOUMI_NAME . '_' . 'puv_record', ['uniacid' => $uniacid, 'openid' => $openid, 'page' => $page, 'goods_id' => $goods_id, 'createtime >=' => $new]);

        pdo_insert(YOUMI_NAME . '_' . 'puv_record',
            array(
                'uniacid' => $uniacid,
                'openid' => $openid,
                'page' => $page,
                'goods_id' => $goods_id,
                'input' => serialize($input),
                'createtime' => TIMESTAMP
            )
        );
        if (!empty($myp)) {
            pdo_update(YOUMI_NAME . '_' . 'puv', array('pv' => $pu['pv'] + 1), array('id' => $pu['id']));
        } else {
            pdo_update(YOUMI_NAME . '_' . 'puv', array('pv' => $pu['pv'] + 1, 'uv' => $pu['uv'] + 1), array('id' => $pu['id']));
        }

    }
}

