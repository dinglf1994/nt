<?php
/**
 * Created by PhpStorm.
 * User: lyon
 * Date: 16-12-23
 * Time: 下午12:39
 */

namespace app\admin\repositories;


interface SqlInterface
{
    public function _checkSql($sql);

    public function _init();

    public function tableAdd($data);

    public function tableDelete($where);

    public function tableUpdate($where, $data);

    public function tableSelect($where, $field);
}