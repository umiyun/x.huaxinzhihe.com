<?php
/**
 * Created by PhpStorm.
 * User: umi
 * Date: 2019/5/24
 * Time: 16:59
 */
if (!defined('IN_IA')) {
    exit('Access Denied');
}
if(!function_exists('batchInsert')) {
    function batchInsert($table, $data)
    {

        if (count($data) === 0) {
            return 0;
        }

        $keys = [];
        $values = [];
        foreach ($data[0] as $k => $v) {
            $keys[] = $k;
        }
        foreach ($data as $item) {
            $strFields = '';
            foreach ($item as $k => $v) {
                if (is_string($v)) {
                    $strFields .= '\'' . $v . '\',';
                } else {
                    $strFields .= $v . ',';
                }
            }
            $strFields = substr($strFields, 0, strlen($strFields) - 1);
            array_push($values, "($strFields)");
        }
        $keySql = implode(',', $keys);
        $sql = "insert into $table ($keySql) values ";
        $sql .= implode(',', $values);

        return pdo_query($sql);
    }
}
