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
        foreach ($set as$skey => &$item) {
            $item = array_filter($item);
            if (!$item) {
                continue;
            }
            youmi_setting_save($skey, $item);
            unset($item);
        }

        itoast('温馨提示：修改成功', referer());
    }
    include $this->template('system');
}

