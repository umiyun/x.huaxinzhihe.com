<?php

global $_W, $_GPC;

$uniacid = intval($_W['uniacid']);
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

if ($op == 'display') {
    $condition = '';
    $pindex = max(1, intval($_GPC['page']));
    $psize = !empty($_GPC['psize']) ? $_GPC['psize'] : 10;
    $paras[':uniacid'] = $uniacid;

    $status = intval($_GPC['status']);
    if ($status) {
        $condition .= ' and status = ' . $status;
    } else {
        $condition .= ' and status > -1 ';
    }
    $keyword = trim($_GPC['keyword']);
    if ($keyword) {
        $condition .= " and title like '%{$keyword}%' ";
    }
    $orderby = ' order by ';

    $orderby .= ' createtime desc ';
    $list = pdo_fetchall('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'withdraw') . ' where uniacid = :uniacid ' . $condition . $orderby . ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $paras);
    foreach ($list as &$item) {

        if ($item['shop_id']) {
            $shop = pdo_get(YOUMI_NAME . '_' . 'shop', ['id' => $item['shop_id']]);
            $item['shoptitle'] = $shop['title'];
        }
        switch ($item['status']) {
            case 1 :
                $item['statusname'] = '申请中';
                break;
            case 2 :
                $item['statusname'] = '已确认';
                break;
            case 3 :
                $item['statusname'] = '已打款';
                break;
            case 4 :
                $item['statusname'] = '打款失败';
                break;
            case 5 :
                $item['statusname'] = '拒绝打款';
                break;
            default :
                $item['statusname'] = '';
                break;
        }

        unset($item);
    }
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(YOUMI_NAME . '_' . 'withdraw') . ' WHERE uniacid = :uniacid ' . $condition, $paras);
    $pager = pagination($total, $pindex, $psize);
    include $this->template('withdraw');
}




if ($op == 'check') {
    $id = intval($_GPC['id']);
    $status = intval($_GPC['status']);
    $paras[':id'] = $id;
    $item = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'withdraw') . ' WHERE id = :id limit 1 ', $paras);

    if ($item['status'] != 1 && $item['status'] != 4) {
        itoast('温馨提示：提现记录已处理！', $this->createWebUrl('withdraw'), 'error');
    }
    if ($status == 2) {

        $shop = pdo_get(YOUMI_NAME . '_' . 'shop', ['id' => $item['shop_id']]);

        if (!$shop['mid']) {
            itoast('该商家未绑定微信号', $this->createWebUrl('withdraw'), 'error');
        }
        $member = $this->getMemberInfo($shop['mid'], 2);
        $item['withdraw'] = floatval($item['apply']) - floatval($item['commission']);
        $result = youmi_finance($member['openid'], $item);

        if (($result['return_code'] == 'SUCCESS') && ($result['result_code'] == 'SUCCESS')) {

            $data['withdraw'] = $item['withdraw'];
            $data['pay_time'] = TIMESTAMP;
            $data['transid'] = $result['payment_no'];
            $data['status'] = 3;
            $data['result'] = serialize($result);
            pdo_update(YOUMI_NAME . '_' . 'withdraw', $data, ['id' => $item['id']]);

            youmi_settlement_log($item, 4, $item['withdraw'], '后台打款：提现ID：' . $item['id'] . '，打款人：' . $_W['username'] . '，打款时间：' . date('Y-m-d H:i:s'));

            itoast('温馨提示：打款成功！', $this->createWebUrl('withdraw'), 'success');
        } else if (($result['return_code'] == 'FAIL') || ($result['result_code'] == 'FAIL')) {
            $result['err_code_des'] = (empty($result['err_code_des']) ? $result['return_msg'] : $result['err_code_des']);
            $result['errno'] = 1;

            $data['status'] = 4;
            $data['result'] = $result['err_code_des'];
            pdo_update(YOUMI_NAME . '_' . 'withdraw', $data, ['id' => $item['id']]);

            itoast($result['err_code_des'], $this->createWebUrl('withdraw'), 'error');
        } else {
            $result['err_code_des'] = (empty($result['err_code_des']) ? $result['return_msg'] : $result['err_code_des']);
            $result['errno'] = 1;

            $data['status'] = 4;
            $data['result'] = $result['message'];
            pdo_update(YOUMI_NAME . '_' . 'withdraw', $data, ['id' => $item['id']]);

            itoast($result['message'], $this->createWebUrl('withdraw'), 'error');
        }

        itoast('温馨提示：打款成功！', $this->createWebUrl('withdraw'), 'success');
    }
    if ($status == 5) {
        pdo_update(YOUMI_NAME . '_' . 'withdraw', ['status' => 5], array('id' => $id));
        $money=  $item['withdraw']+floatval($item['commission']);

        youmi_settlement_log($item, 5,$money, '拒绝打款：提现ID：' . $item['id'] . '，打款人：' . $_W['username'] . '，拒绝打款时间：' . date('Y-m-d H:i:s'));
        itoast('温馨提示：拒绝成功！', $this->createWebUrl('withdraw'), 'success');
    }
    include $this->template('withdraw');
}

function youmi_finance($openid = '', $item, $desc = '')
{

    $setting = youmi_setting_get_list();
    $pay = new WeixinPay($setting['wxapp_appid'], $openid, $setting['wxapp_mchid'], $setting['wxapp_signkey'], $item['tid'], '商家提现', $item['withdraw']);

    if (empty($openid)) return error(-1, 'openid不能为空');

    $pars = array();
    $pars['mch_appid'] = $setting['wxapp_appid'];
    $pars['mchid'] = $setting['wxapp_mchid'];
    $pars['partner_trade_no'] = $item['tid'];
    $pars['openid'] = $openid;
    $pars['check_name'] = 'NO_CHECK';
    $pars['amount'] = floatval($item['withdraw']) * 100;
    $pars['desc'] = empty($desc) ? '商家提现' : $desc;
    $pars['spbill_create_ip'] = gethostbyname($_SERVER["HTTP_HOST"]);
    if (empty($pars['mch_appid']) || empty($pars['mchid'])) {
        $rearr['err_code'] = '请先在系统设置-小程序参数设置内设置微信商户号和秘钥';
        return $rearr;
    }

    $rearr = $pay->finance($pars);

    return $rearr;

}

if ($op == 'del') {
    $id = intval($_GPC['id']);
    $paras[':id'] = $id;
    $item = pdo_fetch('SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'withdraw') . ' WHERE id = :id limit 1 ', $paras);
    if ($item['status'] == -1) {
        itoast('温馨提示：商家提现不存在或是已经被删除！', $this->createWebUrl('withdraw'), 'error');
    }
    pdo_update(YOUMI_NAME . '_' . 'withdraw', ['status' => -1], array('id' => $id));
    itoast('温馨提示：商家提现删除成功！', $this->createWebUrl('withdraw'), 'success');
}


if ($op == 'download') {
    include IA_ROOT.'/framework/library/phpexcel/PHPExcel.php';
    $dates = array();
    $condition = '';
    $pindex = max(1, intval($_GPC['page']));
    $psize = !empty($_GPC['psize']) ? $_GPC['psize'] : 10;
    $paras[':uniacid'] = $uniacid;

    $status = intval($_GPC['status']);
    if ($status) {
        $condition .= ' and status = ' . $status;
    } else {
        $condition .= ' and status > -1 ';
    }
    $keyword = trim($_GPC['keyword']);
    if ($keyword) {
        $condition .= " and (tid like '%{$keyword}%' or transid like '%{$keyword}%') ";
    }
    if ($_GPC['time']) {
        $condition .= ' and createtime >= ' . strtotime($_GPC['time']['start']);
        $condition .= ' and createtime <= ' . strtotime($_GPC['time']['end']);
    }
    $orderby = ' order by ';

    $orderby .= ' createtime desc ';

    $sql = 'SELECT * FROM ' . tablename(YOUMI_NAME . '_' . 'withdraw') . ' where uniacid = :uniacid ' . $condition . $orderby;

    $list = pdo_fetchall($sql, $paras);
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator('umi')->setLastModifiedBy('http://www.pintuano2o.com')->setTitle('Office 2007 XLSX Document')->setSubject('Office 2007 XLSX Document')->setDescription('Document for Office 2007 XLSX, generated using PHP classes.')->setKeywords('office 2007 openxml php')->setCategory('Result file');
    $objPHPExcel
        ->setActiveSheetIndex(0)
        ->setCellValue('A2', '商家')
        ->setCellValue('B2', '申请金额')
        ->setCellValue('C2', '提现比例')
        ->setCellValue('D2', '提现手续费')
        ->setCellValue('E2', '打款金额')
        ->setCellValue('F2', '提现单号')
        ->setCellValue('G2', '微信单号')
        ->setCellValue('H2', '打款信息')
        ->setCellValue('I2', '状态')
        ->setCellValue('J2', '申请时间')
        ->setCellValue('K2', '打款时间')
//        ->setCellValue('L2', '核销员')
//        ->setCellValue('M2', '状态')
//        ->setCellValue('N2', '下单时间')
//        ->setCellValue('O2', '支付时间')
//        ->setCellValue('P2', '退款时间')
//        ->setCellValue('Q2', '失效时间')
    ;
    $i = 3;

    foreach ($list as &$item) {

        if ($item['shop_id']) {
            $shop = pdo_get(YOUMI_NAME . '_' . 'shop', ['id' => $item['shop_id']]);
            $item['shoptitle'] = $shop['title'];
        }
        switch ($item['status']) {
            case 1 :
                $item['statusname'] = '申请中';
                break;
            case 2 :
                $item['statusname'] = '已确认';
                break;
            case 3 :
                $item['statusname'] = '已打款';
                break;
            case 4 :
                $item['statusname'] = '打款失败';
                break;
            case 5 :
                $item['statusname'] = '拒绝打款';
                break;
            default :
                $item['statusname'] = '';
                break;
        }

        $objPHPExcel
            ->setActiveSheetIndex(0)
            ->setCellValue('A' . $i, $item['shoptitle'])
            ->setCellValue('B' . $i, $item['apply'])
            ->setCellValue('C' . $i, $item['proportion'] . '%')
            ->setCellValue('D' . $i, $item['commission'])
            ->setCellValue('E' . $i, $item['withdraw'])
            ->setCellValue('F' . $i, $item['tid'])
            ->setCellValue('G' . $i, $item['transid'])
            ->setCellValue('H' . $i, $item['status'] == 4 ? $item['result'] : '')
            ->setCellValue('I' . $i, $item['statusname'])
            ->setCellValue('J' . $i, $item['createtime'] ? date('Y-m-d H:i', $item['createtime']) : '')
            ->setCellValue('K' . $i, $item['pay_time'] ? date('Y-m-d H:i', $item['pay_time']) : '')
//            ->setCellValue('L' . $i, $item['saler_mc_member']['nickname'] ? $item['saler_mc_member']['nickname'] : $item['saler_member']['mobile'])
//            ->setCellValue('M' . $i, $item['statusname'])
//            ->setCellValue('N' . $i, $item['createtime'] ? date('Y-m-d H:i', $item['createtime']) : '')
//            ->setCellValue('O' . $i, $item['pay_time'] ? date('Y-m-d H:i', $item['pay_time']) : '')
//            ->setCellValue('P' . $i, $item['refund_time'] ? date('Y-m-d H:i', $item['refund_time']) : '')
//            ->setCellValue('Q' . $i, $item['validtime'] ? date('Y-m-d H:i', $item['validtime']) : '')
        ;
        $i++;
        unset($item);
    }

    $objRichText = new PHPExcel_RichText();
    $objPayable = $objRichText->createTextRun('提现记录');
    $objPayable->getFont()->setBold(true);
    $objPayable->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_RED));
    $objPHPExcel->getActiveSheet()->getCell('B1')->setValue($objRichText);
    $objPHPExcel->getActiveSheet()->mergeCells('B1:H1');
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(50);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
//    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
//    $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
//    $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
//    $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
//    $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
//    $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getDefaultStyle()->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)));
    $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT)));
    $objPHPExcel->getActiveSheet()->setTitle('提现记录');
    $objPHPExcel->setActiveSheetIndex(0);
    ob_end_clean();//清除缓冲区,避免乱码
    $filename = urlencode('提现记录') . '_' . date('Y-m-dHis');
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}
