<?php
/**
 * Created by IntelliJ IDEA.
 * User: 7³
 * Date: 2018/7/30 0030
 * Time: 12:18
 */

global $_W, $_GPC;

$uniacid = intval($this->uniacid);

$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'display';

$setting = youmi_setting_get_list();


/**
 * 上传图片
 * do : upload   op : image
 * 传入：
 * file        图片base64值
 * name        图片名
 * size        图片大小
 * type        图片类型
 * 输出：
 * errno    0           1                   2
 * message: 图片路径     生成缩略图错误        远程上传错误
 */
if ($op == 'image') {
    unset($file);
    $file['file'] = $_GPC['file'];
    $file['name'] = $_GPC['name'];
    $file['size'] = $_GPC['size'];
    $file['type'] = $_GPC['type'];
    $file['error'] = 0;
    $res = umi_uploadimg($file, 'image', $_GPC['nosave']);

    youmi_result($res['errno'], $res['errno'] ? $res['message'] : tomedia($res['message']));
}

/**
 * 上传图片
 * do : upload   op : wxapp_image
 * 传入：
 * 输出：
 * errno    0           1                   2
 * message: 图片路径     生成缩略图错误        远程上传错误
 */
if ($op == 'wxapp_image') {

    $res = umi_uploadimg($_FILES['file'], 'image');

    youmi_result($res['errno'], $res['message']);
}

/**
 * 上传图片
 * do : upload   op : wxapp_image
 * 传入：
 * 输出：
 * errno    0           1                   2
 * message: 图片路径     生成缩略图错误        远程上传错误
 */
if ($op == 'form_image') {

    $res = umi_uploadimg($_FILES['file'], 'image');


    die(json_encode(array('status'=>1,'url'=>$res['message'])));
}

