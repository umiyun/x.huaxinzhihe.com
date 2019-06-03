<?php
/**
 * Created by IntelliJ IDEA.
 * User: 7³
 * Date: 2018/7/30 0030
 * Time: 12:18
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

global $_W, $_GPC;

checkauth();

$uniacid = intval($_W['uniacid']);
$openid = trim($_W['openid']);
if(empty($_GPC['status'])){
    $_GPC['status']=3;
}
$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'display';

$setting = youmi_setting_get_list();
$_W['page']['title'] = '报名信息管理';

$member = $this->member;

foreach ($list as &$item){
    $item['member']=pdo_get($_GPC['module'].'_member',array('mid'=>$item['mid']));
    $item['salertime']=date('Y-d-m H:i:s',$item['salertime']);
    unset($item);
}

if ($op == 'display') {
    $activity = pdo_get($_GPC['module'] . '_activity', ['id' => $_GPC['activity_id']]);
    $list = pdo_fetchall('SELECT * FROM ' . tablename($_GPC['module'] . '_cut') . ' where uniacid = :uniacid and activity_id = :activity_id order by createtime DESC ' , [':uniacid'=>$uniacid,':activity_id'=>$_GPC['activity_id']]);

    if($_GPC['module']=='umiacp_vote'){
        foreach ($list as &$item) {
            switch ($item['status']) {
                case 1 :
                    $item['statusname'] = '审核中';
                    break;
                case 2 :
                    $item['statusname'] = '已通过';
                    break;
                case 3 :
                    $item['statusname'] = '已拒绝';
                    break;
                default :
                    $item['statusname'] = '';
                    break;
            }
            $item['vote_imgs']=unserialize($item['vote_imgs']);
            unset($item);
        }
    }else{
        foreach ($list as &$item) {

            switch ($item['status']) {
                case 1 :
                    $item['statusname'] = '已报名';
                    break;
                case 2 :
                    $item['statusname'] = '砍到底';
                    break;
                case 3 :
                    $item['statusname'] = '已支付';
                    break;
                case 4 :
                    $item['statusname'] = '';
                    break;
                default :
                    $item['statusname'] = '';
                    break;
            }
            unset($item);
        }
    }


    include $this->template('cuts');
    exit();
}
if ($op == 'prize') {

    $list = pdo_fetchall('SELECT * FROM ' . tablename($_GPC['module'] . '_reward') . ' where uniacid = :uniacid and activity_id = :activity_id order by createtime DESC ' , [':uniacid'=>$uniacid,':activity_id'=>$_GPC['activity_id']]);
    foreach ($list as &$item) {

        switch ($item['status']) {
            case 1 :
                $item['statusname'] = '已报名';
                break;
            case 2 :
                $item['statusname'] = '已支付';
                break;
            case 3 :
                $item['statusname'] = '已购买';
                break;
            case 4 :
                $item['statusname'] = '';
                break;
            default :
                $item['statusname'] = '';
                break;
        }
        unset($item);
    }

    include $this->template('cuts');
    exit();
}
if($op=='audit'){
    $id = intval($_GPC['id']);
    $status = intval($_GPC['status']);
    $paras[':id'] = $id;
    $item = pdo_fetch('SELECT * FROM ' . tablename($_GPC['module'] . '_cut'). ' WHERE id = :id limit 1 ', $paras);
    if ($item['status'] == -1) {
        youmi_result(1, '报名人员不存在或是已经被删除！');
    }
    pdo_update($_GPC['module'] . '_' . 'cut', ['status' => $status], array('id' => $id));
    youmi_result(0, '操作成功');
}
if ($op=='download'){

    $module=$_GPC['module'];
    $activity_id = $_GPC['activity_id'];
    $paras[':uniacid'] = $uniacid;

    $condition = '';
    $condition .= ' and c.activity_id = ' . $activity_id;
    $list = pdo_fetchall('SELECT a.title as activitytitle,c.*,CASE
		c.status
		WHEN \'1\' THEN
		\'审核中\'
		WHEN \'2\' THEN
		\'已通过\'
		WHEN \'3\' THEN
		\'已拒绝\'
		ELSE \'\'
	END AS statusname  FROM ' . tablename($module . '_' . 'cut') . ' c left join ' . tablename($module . '_' . 'activity') . ' a on c.activity_id = a.id where c.uniacid = :uniacid ' . $condition , $paras);

    $header = [
        '活动',
        '姓名',
        '手机号',
        '具体用户信息',
        '投票总数',
        '状态',
        '报名时间',
    ];

    $types = [
        ['activitytitle', 300],
        ['realname', 200],
        ['mobile', 200],
        ['userinfo', 500],
        ['times', 100],
        ['statusname', 100],
        ['createtime', 200, 'date'],
    ];

    download('报名记录', $list, $header, $types);
}
 function download($name, $list, $header, $types)
{
    $html = "\xEF\xBB\xBF";
    $html .= "<table><tr style=\"text-align: center\" style=\"border: 1px solid\">";
    foreach ($header as $th) {
        $html .= "<th>" . $th . "</th>";
    }
    $html .= "</tr>";

    if ($list) {
        foreach ($list as $item) {

            $html .= "<tr style=\"text-align: center\" style=\"border: 1px solid\">";

            foreach ($types as $it) {
                switch ($it[2]) {
                    case "date" :
                        $html .= "<td width=\"" . ($it[1] ? $it[1] : 100) . "\">" . htmlspecialchars(trim(($item[$it[0]] ? date("Y-m-d H:i", $item[$it[0]]) : ""))) . "</td>";
                        break;
                    default :
                        $html .= "<td width=\"" . ($it[1] ? $it[1] : 100) . "\">&nbsp;" . htmlspecialchars(trim($item[$it[0]])) . "</td>";
                        break;
                }
                unset($it);
            }

            $html .= "</tr>";
        }
    }
    $html .= "</table>";

//    ob_end_clean();//清除缓冲区,避免乱码
    $filename = urlencode($name) . '_' . date('Y-m-dHis').'.xls';
//    header('Content-type: text/html; charset=GB2312');
//    header('Content-Type: application/vnd.ms-excel');
//    header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
//    header('Cache-Control: max-age=0');
//
//    exit($html);
    $path= YOUMI_PATH.$filename;
    file_put_contents($path , $html);
sendExcelEmail($path,$filename);

}
function sendExcelEmail($path,$filename){
    require_once  YOUMI_PATH . 'mailer/Exception.php';
    require  YOUMI_PATH . 'mailer/PHPMailer.php';
    require  YOUMI_PATH . 'mailer/SMTP.php';
    $mail = new PHPMailer(true);

    try {
        //Server settings
//        $mail->SMTPDebug = 2;                                       // Enable verbose debug output
        $mail->isSMTP();                                            // Set mailer to use SMTP
        $mail->Host       = 'smtp.qq.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = '1295200771@qq.com';                     // SMTP username
        $mail->Password   = 'ldywhubnermqgcfc';                               // SMTP password
        $mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
        $mail->Port       = 465;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('1295200771@qq.com', 'Mailer');
//        $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
        $mail->addAddress('1295200771@qq.com');               // Name is optional
//        $mail->addReplyTo('info@example.com', 'Information');
//        $mail->addCC('cc@example.com');
//        $mail->addBCC('bcc@example.com');


        // Attachments
        $mail->addAttachment($path,$filename);         // Add attachments
//        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = '报名记录';
        $mail->Body    = '点击下载文件';
//        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        unlink($path);
        youmi_result(0, '发送成功');
    } catch (Exception $e) {
        youmi_result(1, '发送失败:'.$mail->ErrorInfo);
    }
}
