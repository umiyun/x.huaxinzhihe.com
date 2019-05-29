<?php
/**
 * Created by IntelliJ IDEA.
 * User: 7³
 * Date: 2018/7/30 0030
 * Time: 12:18
 */
ignore_user_abort();
set_time_limit(0);
global $_W, $_GPC;

$uniacid = intval($_W['uniacid']);
$openid = trim($_W['openid']);

$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'display';

$acc = WeAccount::create();

if ($op == 'display') {
    $messages = pdo_fetchall('select * from ' . tablename(YOUMI_NAME . '_message') . ' where uniacid = ' . $uniacid . '  and status = 1 limit 20 ');
    $count = 0;
    foreach ($messages as $it) {

        $result = $acc->sendTplNotice($it['openid'], $it['temp_id'], unserialize($it['send']), $it['url']);
        if (is_error($result)) {
            $update['status'] = 4;
            $update['result'] = serialize($result);
        } else {
            $update['status'] = 2;
            $update['result'] = serialize($result);
        }
        pdo_update(YOUMI_NAME . '_message', $update, ['id' => $it['id']]);
        $count++;
    }
    youmi_result(0, '已申请发送' . $count . '条模板消息，请勿刷新');
}
