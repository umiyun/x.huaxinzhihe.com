<?php
/**
 * Created by PhpStorm.
 * User: umiyun
 * Date: 2019-04-27
 * Time: 09:28
 */
global $_GPC,$_W;
$order = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'order') . ' where uniacid = :uniacid and openid = :openid and status=2', [':uniacid' =>  $_W['uniacid'],':openid' =>  $_W['openid']]);
include $this->template('renew');