<?php
/**
 * Created by IntelliJ IDEA.
 * User: 7³
 * Date: 2018/7/30 0030
 * Time: 12:18
 */

global $_W, $_GPC;
$_W['page']['title'] = '系统设置';

$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'display';

$setting = youmi_setting_get_list();

if ($op == 'display') {
    if (checksubmit()) {

        $set = $_GPC['set'];
        foreach ($set as $skey => &$item) {
            $item = array_filter($item);
            if (!$item) {
                continue;
            }
            youmi_setting_save($skey, $item);
            unset($item);
        }
        if (!is_dir(YOUMI_CERT . $_W['uniacid'] . '/')) {
            //第3个参数“true”意思是能创建多级目录，iconv防止中文目录乱码
            $res = mkdir(iconv('UTF-8', 'GBK', YOUMI_CERT . $_W['uniacid'] . '/'), 0777, true);
        }
        if (!empty($_GPC['apiclient_cert'])) {
            file_put_contents(YOUMI_CERT . $_W['uniacid'] . '/apiclient_cert.pem', trim($_GPC['apiclient_cert']));
        }
        if (!empty($_GPC['apiclient_key'])) {
            file_put_contents(YOUMI_CERT . $_W['uniacid'] . '/apiclient_key.pem', trim($_GPC['apiclient_key']));
        }
        itoast('温馨提示：修改成功', $this->createwebUrl('system'), 'success');
    }
    include $this->template('system');
}

