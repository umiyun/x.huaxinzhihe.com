<?php

global $_W, $_GPC;

$uniacid = intval($_W['uniacid']);
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

if ($op == 'display') {

    $pindex = max(1, intval($_GPC['page']));
    $psize = !empty($_GPC['psize']) ? $_GPC['psize'] : 10;

    $condition = '';
    $activity_id = $_GPC['id'];
    $paras[':uniacid'] = $uniacid;

    $activity_id = intval($_GPC['id']);
    if ($activity_id) {
        $condition .= ' and c.activity_id = ' . $activity_id;
    }
    $status = intval($_GPC['status']);
    if ($status) {
        $condition .= ' and c.status = ' . $status;
    } else {
        $condition .= ' and c.status > -1 ';
    }
    $keyword = trim($_GPC['keyword']);
    if ($keyword) {
        $condition .= " and (a.title like '%{$keyword}%' or c.realname like '%{$keyword}%' or c.mobile like '%{$keyword}%') ";
    }
    $orderby = ' order by ';

    $orderby .= ' createtime desc ';
    $list = pdo_fetchall('SELECT a.title as goodstitle,c.*,CASE
		c.status 
		WHEN \'1\' THEN
		\'砍价中\' 
		WHEN \'2\' THEN
		\'砍到底\'  
		WHEN \'3\' THEN
		\'已购买\' 
		ELSE \'\' 
	END AS statusname FROM ' . tablename(YOUMI_NAME . '_' . 'cut') . ' c left join ' . tablename(YOUMI_NAME . '_' . 'goods') . ' a on c.goods_id = a.id where c.uniacid = :uniacid ' . $condition . $orderby . ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);
    $total = pdo_fetchcolumn('SELECT COUNT(c.id) FROM ' . tablename(YOUMI_NAME . '_' . 'cut') . ' c left join ' . tablename(YOUMI_NAME . '_' . 'goods') . ' a on c.goods_id = a.id where c.uniacid = :uniacid ' . $condition, $paras);
    $pager = pagination($total, $pindex, $psize);
    include $this->template('cut');
}

if ($op == 'download') {

    $condition = '';
    $activity_id = $_GPC['id'];
    $paras[':uniacid'] = $uniacid;

    $activity_id = intval($_GPC['id']);
    if ($activity_id) {
        $condition .= ' and c.activity_id = ' . $activity_id;
    }
    $status = intval($_GPC['status']);
    if ($status) {
        $condition .= ' and c.status = ' . $status;
    } else {
        $condition .= ' and c.status > -1 ';
    }
    $keyword = trim($_GPC['keyword']);
    if ($keyword) {
        $condition .= " and (a.title like '%{$keyword}%' or c.realname like '%{$keyword}%' or c.mobile like '%{$keyword}%') ";
    }
    $orderby = ' order by ';

    $orderby .= ' createtime desc ';
    $list = pdo_fetchall('SELECT a.title as goodstitle,c.*,CASE
		c.status 
		WHEN \'1\' THEN
		\'砍价中\' 
		WHEN \'2\' THEN
		\'砍到底\'  
		WHEN \'3\' THEN
		\'已购买\' 
		ELSE \'\' 
	END AS statusname FROM ' . tablename(YOUMI_NAME . '_' . 'cut') . ' c left join ' . tablename(YOUMI_NAME . '_' . 'goods') . ' a on c.goods_id = a.id where c.uniacid = :uniacid ' . $condition . $orderby, $paras);

    $header = [
        '商品',
        '姓名',
        '手机号',
        '具体用户信息',
        '现价',
        '状态',
        '发起时间',
    ];

    $types = [
        ['goodstitle', 300],
        ['realname', 200],
        ['mobile', 200],
        ['userinfo', 500],
        ['nprice', 100],
        ['statusname', 100],
        ['createtime', 200, 'date'],
    ];

    $this->download('报名记录', $list, $header, $types);

}


//if ($op == 'download') {
//    include IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';
//    $condition = '';
//    $activity_id=$_GPC['id'];
//    $paras[':uniacid'] = $uniacid;
//    $paras[':activity_id'] = $activity_id;
//
//    $status = intval($_GPC['status']);
//    if ($status) {
//        $condition .= ' and status = ' . $status;
//    } else {
//        $condition .= ' and status > -1 ';
//    }
//    $keyword = trim($_GPC['keyword']);
//    if ($keyword) {
//        $condition .= " and title like '%{$keyword}%' ";
//    }
//    $orderby = ' order by ';
//
//    $orderby .= ' createtime desc ';
//    $list = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'cut') . ' where uniacid = :uniacid and activity_id = :activity_id ' . $condition . $orderby , $paras);
//
//    $objPHPExcel = new PHPExcel();
//    $objPHPExcel->getProperties()->setCreator('umi')->setTitle('Office 2007 XLSX Document')->setSubject('Office 2007 XLSX Document')->setDescription('Document for Office 2007 XLSX, generated using PHP classes.')->setKeywords('office 2007 openxml php')->setCategory('Result file');
//    $objPHPExcel
//        ->setActiveSheetIndex(0)
//        ->setCellValue('A1', '用户')
//        ->setCellValue('B1', '姓名')
//        ->setCellValue('C1', '联系电话')
//        ->setCellValue('D1', '活动名')
//        ->setCellValue('E1', '现价')
//        ->setCellValue('F1', '状态')
//        ->setCellValue('G1', '发起时间');
//    $i = 2;
//
//    foreach ($list as &$item) {
//
//        $item['member'] = $this->getMemberInfo($item['mid']);
//
//        if ($item['activity_id']) {
//            $activity = pdo_get(YOUMI_NAME . '_' . 'activity', ['id' => $item['activity_id']]);
//            $item['activitytitle'] = $activity['title'];
//        }
//        switch ($item['status']) {
//            case 1 :
//                $item['statusname'] = '砍价中';
//                break;
//            case 2 :
//                $item['statusname'] = '砍到底';
//                break;
//            case 3 :
//                $item['statusname'] = '已购买';
//                break;
//            default :
//                $item['statusname'] = '';
//                break;
//        }
//
//        $objPHPExcel
//            ->setActiveSheetIndex(0)
//            ->setCellValue('A' . $i, $item['member']['nickname'])
//            ->setCellValue('B' . $i, $item['realname'])
//            ->setCellValue('C' . $i, $item['mobile'])
//            ->setCellValue('D' . $i, $item['goodstitle'])
//            ->setCellValue('E' . $i, $item['nprice'])
//            ->setCellValue('F' . $i, $item['statusname'])
//            ->setCellValue('G' . $i, date('Y-m-d H:i' , $item['createtime']));
//        $i++;
//        unset($item);
//        unset($activity);
//    }
//
//    $objRichText = new PHPExcel_RichText();
//    $objPayable = $objRichText->createTextRun('活动用户信息');
//    $objPayable->getFont()->setBold(true);
//    $objPayable->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_RED));
////    $objPHPExcel->getActiveSheet()->getCell('B1')->setValue($objRichText);
////    $objPHPExcel->getActiveSheet()->mergeCells('B1:H1');
//    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
//    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
//    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
//    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
//    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(40);
//    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
//    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
//
//
//    $objPHPExcel->getActiveSheet()->getDefaultStyle()->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)));
////    $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT)));
//    $objPHPExcel->getActiveSheet()->setTitle('活动用户信息');
//    $objPHPExcel->setActiveSheetIndex(0);
//    ob_end_clean();//清除缓冲区,避免乱码
//    $filename = urlencode('活动用户信息') . '_' . date('Y-m-dHis');
//    header('Content-Type: application/vnd.ms-excel');
//    header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
//    header('Cache-Control: max-age=0');
//    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//    $objWriter->save('php://output');
//    exit;
//}

if ($op == 'del') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;
    $item = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'cut') . ' WHERE id = :id limit 1 ', $paras);
    if ($item['status'] == -1) {
        itoast('温馨提示：报名不存在或是已经被删除！', referer(), 'error');
    }
    pdo_update(YOUMI_NAME . '_' . 'cut', ['status' => -1], array('id' => $id));
    itoast('温馨提示：报名删除成功！', referer(), 'success');
}

if ($op == 'search') {
    //选择用户

    $keyword = $_GPC['keyword'];

    $ds = $this->getMemberKeyword($keyword);

    die(json_encode(['status' => $ds ? 1 : 0, 'ds' => $ds]));

}
        