<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/31 0031
 * Time: 21:51
 * QFFJKtBAYJ
 *CREATE TABLE `hjmall_district` (
 * `id` int(11) NOT NULL AUTO_INCREMENT,
 * `parent_id` int(11) NOT NULL DEFAULT '0',
 * `citycode` varchar(255) NOT NULL,
 * `adcode` varchar(255) NOT NULL,
 * `name` varchar(255) NOT NULL,
 * `lng` varchar(255) NOT NULL COMMENT '经度',
 * `lat` varchar(255) NOT NULL COMMENT '纬度',
 * `level` varchar(255) NOT NULL,
 * PRIMARY KEY (`id`) USING BTREE
 * ) ENGINE=MyISAM AUTO_INCREMENT=3264 DEFAULT CHARSET=utf8 COMMENT='高德行政区域数据';
 *
 */
//require "./framework/bootstrap.inc.php";
//require_once './framework/function/pdo.func.php';



//youmi_internal_log('777',pdo_debug()) ;


class test
{
    public function isChild($pid)
    {
        $dataOne = pdo_fetchall("SELECT * FROM hjmall_district WHERE parent_id = '{$pid}'");
//            if (count($dataOne)==0){
//                return $dataOne;
//            }else{
        for ($i = 0; $i < count($dataOne); $i++) {
             $dataOne[$i]['parents'] = test::isChild($dataOne[$i]['id']);
            return $dataOne;
        }
//            }
    }
}
echo 'hello world';
//function endsWith($haystack, $needle){
//    return $needle === '' || substr_compare($haystack, $needle, -strlen($needle)) === 0;
//}
//$str='http://wx.qlogo.cn/mmopen/YwffUhfZ1qwdTpvNAwYkuZo095ntBcLibbv5mBxHlYnbELdj4Dr8Aza2RDicQQicH8bjkV5oezNyoqzxr846qK5VFfzcO4OOFrG/132132';
//$re= substr($str,0,strlen($str)-3);
//echo $re;
//$aa = test::isChild(0);
//youmi_internal_log('777', $data);

//die(json_encode($aa));

//date_default_timezone_set('PRC');
//echo date('Y-m-d H:i:s', rand(time(),time()+3600)) ;


//echo endsWith('abc123','13');
//    //$data=test::getStart();
//
//    //查询
//    $dataSelect=pdo_fetchall("SELECT * FROM hjmall_district WHERE `id` = 1000 ");
//
//    die(json_encode($dataSelect)[0]['id']);
//
////    for ($i=0;$i<$count;$i++){
////        $aa=test::isChild($dataSelect[$i]);
////    }
//
//    //$a=$dataSelect[0]['id'];
////    $arr=[];
////    for ($i=0;$i<$count;$i++){
////        if ($dataSelect[$i]['parent_id']==0){
////            return $dataSelect[$i];
////            die("aa");
////        }
////    }
//////    $aa=test::isChild($a);
////    die($arr);
//    die(json_encode($dataSelect));
//    //$dataSelect=pdo_fetch("SELECT * FROM hjmall_district WHERE adcode=:adcode ",array(':adcode'=>100000));
//
//    //$dataInsert=pdo_fetch("INSERT INTO hjmall_district ('parent_id','citycode','adcode','name','lng','lat','level')VALUE ('1','2','3','4','5','6','7')");
//
////    $dataInsert=pdo_query("INSERT INTO hjmall_district
////        ('parent_id','citycode','adcode','name','lng','lat','level')
////        VALUE (
////        parent_id=:parent_id,citycode=:citycode,
////        adcode=:adcode,name=:name,lng=:lng,lat=:
////        lat,level=:level)", array(
////            ':parent_id'=>'1',
////            ':citycode'=>'2',
////            ':adcode'=>'3',
////            ':name'=>'4',
////            ':lng'=>'5',
////            ':lat'=>'6',
////            ':level'=>'7',
////        ));
//
//
//
//
//
////    $sql=<<<EOF
////    INSERT INTO hjmall_district ('parent_id','citycode','adcode','name','lng','lat','level') VALUE ('1','2','3','4','5','6','7');
////EOF;
////    pdo_run($sql);
////pdo_debug();
//
//    //$dataUp=pdo_query("UPDATE hjmall_district SET citycode=:citycode",array(':citycode'=>'3'));
//    //$dataDel=pdo_query("DELETE FROM hjmall_district WHERE citycode=:citycode",array(':citycode'=>'3'));
//    //$dataSelect=pdo_fetch("SELECT * FROM hjmall_district WHERE adcode=:adcode ",array(':adcode'=>3));
//    $dataSelect=pdo_fetch("SELECT * FROM hjmall_district ");
////$data=array();
//    die(json_encode($dataSelect));
