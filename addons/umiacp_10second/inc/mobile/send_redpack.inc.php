<?php
/**
 * Created by IntelliJ IDEA.
 * User: 攸米
 * Date: 2018/10/26
 * Time: 14:57
 */
global $_W, $_GPC;
$uniacid=$_W['uniacid'];
//$rewards = pdo_fetchall('select * from ' . tablename(YOUMI_NAME . '_reward') . ' where uniacid = 3 and reward_type = 1 and status = 1 and tid = \'\' and openid in (select openid from ' . tablename(YOUMI_NAME . '_member') . ' where openid <> \'\') order by id asc limit 20 ');

$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'display';

if ($op == 'display') {

    $rewards = pdo_fetchall('SELECT
	m.openid,r.*
FROM
	' . tablename(YOUMI_NAME . '_reward') . ' AS r
	LEFT JOIN ' . tablename(YOUMI_NAME . '_member') . ' AS m ON r.openid = m.openid 
WHERE
	r.uniacid = '.$uniacid.' 
	AND r.reward_type = 1 
	AND r.status = 1 
	AND r.tid = \'\' 
ORDER BY
	r.id ASC 
	LIMIT 20 ');

    $count = 0;
    foreach ($rewards as $item) {

        $params = array();
        $params['tid'] = 'HD' . date('YmdHis') . random(8);
        $params['send_name'] = $item['send_name'];
        $params['openid'] = $item['openid'];
        $params['money'] = $item['reward_money'];
        $params['wishing'] = $item['wishing'];
        $params['act_name'] = $item['act_name'];

        $err = $this->sendredpack($params);
        $data['tid'] = $params['tid'];

        if (is_error($err)) {
            $data['result'] = $err['message'];
            $data['status'] = 4;
        } else {
            $data['result'] = 1;
            $data['status'] = 3;
        }
        pdo_update(YOUMI_NAME . '_reward', $data, ['id' => $item['id']]);

        $count++;
        unset($member);
    }

    youmi_result(0, '此次申请发送' . $count . '个红包');

}

if ($op == 'bufa') {

    $rewards = pdo_fetchall('SELECT
	m.openid,r.*
FROM
	' . tablename(YOUMI_NAME . '_reward') . ' AS r
	LEFT JOIN ' . tablename(YOUMI_NAME . '_member') . ' AS m ON r.openid = m.openid 
WHERE
	r.uniacid = '.$uniacid.'  
	AND r.reward_type = 1 
	AND r.status = 5 
	AND m.openid <> \'\' 
ORDER BY
	r.id ASC 
	LIMIT 20 ');
youmi_result($rewards);
    $count = 0;
    foreach ($rewards as $item) {

        $params = array();
        $params['tid'] = $item['tid'];
        $params['send_name'] = $item['send_name'];
        $params['openid'] = $item['openid'];
        $params['money'] = $item['reward_money'];
        $params['wishing'] = $item['wishing'];
        $params['act_name'] = $item['act_name'];

        $err = $this->sendredpack($params);

        $data['tid'] = $params['tid'];

        if (is_error($err)) {
            $data['result'] = $err['message'];
            $data['status'] = 4;
        } else {
            $data['result'] = 1;
            $data['status'] = 3;
        }
        pdo_update(YOUMI_NAME . '_reward', $data, ['id' => $item['id']]);

        $count++;
        unset($member);
    }

    youmi_result(0, '此次补发' . $count . '个红包');

}
