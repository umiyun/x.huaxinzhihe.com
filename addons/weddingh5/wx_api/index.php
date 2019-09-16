<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/10
 * Time: 10:49
 */
global $_GPC, $_W;
$op = $_GPC['op'];
$m=$_GPC['m'];
$tab = $_GPC['tablename'];
$table =$m.'_'. $_GPC['tablename'];
$condition = $_GPC['condition'];
$fields = $_GPC['fields'];
$data = $_GPC['data'];
$orderby = $_GPC['orderby'];

switch ($op) {
    case 'get':
        $list = $this->getTable($m,$tab,$condition,array());
        break;
    case 'getall':
        $list = pdo_getall($table, $condition, $fields);
        foreach ($list as $key => &$item) {
            foreach ($item as $ke => &$it) {
                if (is_serialized($item[$ke])) {
                    $item[$ke] = unserialize($item[$ke]);
                }
                if (strpos($ke , 'time')) {
                    if (intval($item[$ke]) > 0) {
                        $item[$ke] = date('Y-m-d H:i:s', $item[$ke]);
                    }
                }
                if (strpos($ke , '_id')) {
                   $kk=str_replace('_id','',$ke);
                    if (is_array($item[$ke])) {
                        foreach ($item[$ke] as &$k) {
                            $item[$kk][] =$this->getTable($m,$kk,array('id'=> $k),array());

                        }
                        unset($k);
                    } else {
                        $item[$kk]=pdo_get($kk,array('id'=> $item[$ke]));
                    }
                }
                //cate_id
                if (in_array($ke, ['thumb', 'logo', 'thumbs', 'image', 'images'])) {
                    if (is_array($item[$ke])) {
                        foreach ($item[$ke] as &$k) {
                            $k = tomedia($k);
                        }
                        unset($k);
                    } else {
                        $item[$ke] = tomedia($item[$ke]);
                    }
                }
                if ($ke == 'openid') {
                    $item[$ke] = pdo_get('mc_mapping_fans', array('openid' => $item[$ke]));
                }
                unset($it);
            }
            unset($item);
        }
        break;
    case 'getslice':
        $page = max(1, $_GPC['page']);
        $pagesize = 10;
        $limit = [($page - 1) * $pagesize, $pagesize];
        $list = pdo_getslice($table, $condition, $limit, $total, $fields, $keyfield, $orderby);
        foreach ($list as $key => &$item) {
            foreach ($item as $ke => &$it) {
                if (is_serialized($item[$ke])) {
                    $item[$ke] = unserialize($item[$ke]);
                }
                if (strpos($ke , 'time')) {
                    if (intval($item[$ke]) > 0) {
                        $item[$ke] = date('Y-m-d H:i:s', $item[$ke]);
                    }
                }
                if (in_array($ke, ['thumb', 'logo', 'thumbs', 'image', 'images'])) {
                    if (is_array($item[$ke])) {
                        foreach ($item[$ke] as &$k) {
                            $k = tomedia($k);
                        }
                        unset($k);
                    } else {
                        $item[$ke] = tomedia($item[$ke]);
                    }
                }
                if ($ke == 'openid') {
                    $item[$ke] = pdo_get('mc_mapping_fans', array('openid' => $item[$ke]));
                }
                unset($it);
            }
            unset($item);
        }
        break;
    case 'update':
        $res = pdo_update($table, $data, $condition);
        $list = array();
        $list['status'] = 1;
        $list['message'] = '更新成功';
        break;
    case 'insert':
        pdo_insert($table, $data);
        $list = array();
        $list['status'] = 1;
        $list['message'] = '插入成功';
        break;
    case 'delete':
        pdo_delete($table, $condition);
        $list = array();
        $list['status'] = 1;
        $list['message'] = '删除成功';
        break;
}
die(json_encode(array('list' => $list)));
