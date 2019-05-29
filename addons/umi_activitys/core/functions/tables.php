<?php
/**
 * Created by IntelliJ IDEA.
 * User: 7³
 * Date: 2018/7/30 0030
 * Time: 12:18
 */

if (!defined('IN_IA')) {
    exit('Access Denied');
}

/**
 * 新增更新数据
 * @param $id       数据id
 * @param $data     表数据
 * @param $table    表名 路由文件名
 */
function post_table($id, $data, $table, $type = 1, $message = '修改')
{
    global $_W;
    $uniacid = $_W['uniacid'];
    if ($id) {
        unset($data['id']);
        $res = pdo_update(YOUMI_NAME . '_' . $table, $data, array('id' => $id));
    } else {
        $data['uniacid'] = $uniacid;
        $data['createtime'] = TIMESTAMP;
        $res = pdo_insert(YOUMI_NAME . '_' . $table, $data);
        $id = pdo_insertid();
    }
    if ($type == 1) {
        itoast('温馨提示：' . $message . ($res ? '成功' : '失败'), $res ? $this->createWebUrl($table) : '', $res ? 'success' : 'error');
    } elseif ($type == 2) {
        $item = pdo_get(YOUMI_NAME . '_' . $table, array('id' => $id));
        return ['errno' => ($id ? 0 : 1), 'message' => ('温馨提示：' . $message . ($id ? '成功' : '失败')), 'data' => [$table . '_id' => $id, 'item' => $item]];
    }
}

/**
 * 获取数据列表
 * @param $m            模块名
 * @param $tablename    表名
 * @param $condition    条件
 * @param $fields       字段
 * @param $orderby      排序
 * @param $limit        分页
 */
function getTable($m, $tablename, $condition = '', $fields = '', $orderby = '', $limit = '')
{
    $list = pdo_getall($m . '_' . $tablename, $condition, $fields, $orderby, $limit);
    foreach ($list as $key => &$item) {
        if (is_serialized($item)) {
            $item = unserialize($item);
        }
        if (strpos($key, 'time')) {
            if (intval($item) > 0) {
                $item = date('Y-m-d H:i:s', $item);
            }
        }
        if (strpos($key, '_id')) {
            $kk = str_replace('_id', '', $key);
            if (is_array($item)) {
                foreach ($item as &$k) {
                    $item[$kk][] = getTable($m, $kk, array('id' => $k), array());
                    unset($k);
                }
            } else {
                $item[] = pdo_get($m . '_' . $kk, array('id' => $item));
            }
        }
        if (in_array($key, ['thumb', 'logo', 'thumbs', 'image', 'images'])) {
            if (is_array($item)) {
                $list[$key] = unserialize($list[$key]);
                foreach ($list[$key] as &$k) {
                    $k = tomedia($k);
                }
                unset($k);
            } else {
                $list[$key] = tomedia($list[$key]);
            }
        }

        if ($key == 'openid') {
            $list[$key] = pdo_get('mc_mapping_fans', array('openid' => $list[$key]));
        }
        unset($item);
    }
    return $list;
}

