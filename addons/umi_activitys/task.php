<?php
//error_reporting(0);
require("../../framework/bootstrap.inc.php");
require("./core/defines.php");
require("./core/loader.php");
global $_W;
global $_GPC;
ignore_user_abort();
set_time_limit(0);
$uniacid=intval($_GPC['i']);
$_W['uniacid']=$uniacid;
$global_task=pdo_get('umi_activitys_task',array('uniacid'=>$uniacid));
$time_i=1;
$setting = youmi_setting_get_list();
if($global_task['state']==0&&!empty($global_task)){
    exit();
}
do {
    $global_task=pdo_get('umi_activitys_task',array('uniacid'=>$uniacid));
    $time=intval($global_task['execute_times'])+1;
    $time_i+=1;
    if($global_task['state']==0){
        break;
    }
    //任务代码 开始
    //红包拓客
    $umiacp_fission_order=pdo_getall('umiacp_fission_order',array('status'=>2,'send_status'=>0,'commission >'=>0,'uniacid'=>$uniacid,'fmid >'=>0));
    foreach ($umiacp_fission_order as $order){
        $f_member=pdo_get('umiacp_fission_member',array('mid'=>$order['fmid'],'uniacid'=>$order['uniacid']));
        $f_cut=pdo_get('umiacp_fission_cut',array('mid'=>$order['fmid'],'activity_id'=>$order['activity_id']));
        $tid='fiss'.time().$order['id'];
        if($order['price']>$order['commission']){
            $res=youmi_finance($f_member['openid'],$tid,$order['commission'],'奖金');
//            youmi_internal_log('fission_finace',$res);
            if($res['result_code']=='SUCCESS'){
                pdo_update('umiacp_fission_order',array('send_status'=>1),array('id'=>$order['id']));
                pdo_update('umiacp_fission_cut',array('commision'=>$order['commission']+$f_cut['commision'],'share_num'=>$f_cut['share_num']+1),array('mid'=>$order['fmid'],'activity_id'=>$order['activity_id']));
//            youmi_internal_log('pdo',pdo_debug());
            }

        }

    }
//youmi_internal_log('$umiacp_fission_order',$umiacp_fission_order);
    //任务代码 截止
    $data = array(
        'uniacid'=>$uniacid,
        'execute_time'=>time(),
        'execute_times'=>$time
    );
    if(empty($global_task)){
        $data['create_time']=time();
        $data['state']=1;
        pdo_insert('umi_activitys_task',$data);
    }else{
        pdo_update('umi_activitys_task',$data,array('id'=>$global_task['id']));
    }

sleep(5);

} while(true);
exit();
function youmi_finance($openid = '', $tid='',$commission=0, $desc = '')
{

    $setting = youmi_setting_get_list();
    $pay = new WeixinPay($setting['wxapp_appid'], $openid, $setting['wxapp_mchid'], $setting['wxapp_signkey'], $tid, '商家提现', $commission);

    if (empty($openid)) return error(-1, 'openid不能为空');

    $pars = array();
    $pars['mch_appid'] = $setting['wxapp_appid'];
    $pars['mchid'] = $setting['wxapp_mchid'];
    $pars['partner_trade_no'] = $tid;
    $pars['openid'] = $openid;
    $pars['check_name'] = 'NO_CHECK';
    $pars['amount'] = floatval($commission) * 100;
    $pars['desc'] = empty($desc) ? '商家提现' : $desc;
    $pars['spbill_create_ip'] = gethostbyname($_SERVER["HTTP_HOST"]);
    if (empty($pars['mch_appid']) || empty($pars['mchid'])) {
        $rearr['err_code'] = '请先在系统设置-小程序参数设置内设置微信商户号和秘钥';
        return $rearr;
    }

    $rearr = $pay->finance($pars);

    return $rearr;

}
?>