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

//http://wx.longyue8.com/app/index.php?i=3&c=entry&do=sendmessage&m=umi_ticketdys&user_type=1

$uniacid = intval($_W['uniacid']);
$openid = trim($_W['openid']);

$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'display';

$acc = WeAccount::create();

if ($op == 'display') {
    $messages = pdo_fetchall('select * from ' . tablename(YOUMI_NAME . '_message') . ' where uniacid = ' . $uniacid . '  and status = 1 limit 20 ');
    $count = 0;
    foreach ($messages as $it) {

        $result = $acc->sendTplNotice($it['openid'], $it['temp_id'], json_decode($it['send'], true), $it['url']);
        if (is_error($result)) {
            $data['status'] = 3;
            $data['result'] = $result['message'];
        } else {
            $data['status'] = 2;
            $data['result'] = '发送成功';
        }
        pdo_update(YOUMI_NAME . '_message', $data, ['id' => $it['id']]);
        $count++;
    }
    youmi_result(0, '已申请发送' . $count . '条模板消息，请勿刷新');
}
