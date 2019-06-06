<?php
/**
 * Created by PhpStorm.
 * User: umi
 * Date: 2019/6/3
 * Time: 10:01
 */

 function _selectCount($table,$cond=array())
 {
     return pdo_getcolumn($table, $cond, 'count(id)');
 }
