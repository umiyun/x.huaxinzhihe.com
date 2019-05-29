<?php
error_reporting(0);
require("../../../../framework/bootstrap.inc.php");
global $_W;
global $_GPC;
ignore_user_abort();
set_time_limit(0);
youmi_internal_log('task',array('sta'=>$task_setting['lasttasktime'],'$task_nowtime'=>$task_nowtime));
pdo_update('umi_activitys_setting',array('lasttasktime'=>time()),array('uniacid'=>$this->uniacid));
?>