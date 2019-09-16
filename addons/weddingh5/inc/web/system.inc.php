<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/7 0007
 * Time: 20:20
 */

global $_W, $_GPC;

$uniacid = intval($_W['uniacid']);
$_W['page']['title'] = '系统设置';
$op = $_GPC['op'];

if (empty($op)) {
    $op = 'display';
}

$setting = pdo_get(MC_NAME . '_' .'setting', array('uniacid' => $uniacid));

if ($op == 'display') {
    if (checksubmit()) {
        $data = $_GPC['setting'];
        $data['thumbs'] = serialize($data['thumbs']);
        if (empty($setting)) {
            $data['uniacid'] = $uniacid;
            $data['createtime'] = TIMESTAMP;
            $status = pdo_insert(MC_NAME . '_' .'setting', $data);
        } else {
            $status = pdo_update(MC_NAME . '_' .'setting', $data, array('id' => $setting['id']));
        }
        itoast('温馨提示：修改成功', referer());
    }
    $setting['thumbs'] = unserialize($setting['thumbs']);
    include $this->template('system');
}