<?php
/**
 * Created by PhpStorm.
 * User: lyon
 * Date: 17-3-13
 * Time: 下午2:55
 */

namespace app\index\model;


use app\index\repositories\SqlInterface;
use think\Model;

class Referrer extends Model implements SqlInterface
{
    protected $model;

    public function _init()
    {
        // TODO: Implement _init() method.
        $this->model = new Referrer(); // 初始化操作表
    }

    public function _checkSql($sql)
    {
        // TODO: Implement _checkSql() method.
    }

    /**
     * @param $where
     * @param $data
     * @return bool
     * 添加数据
     */
    public function tableAdd($data)
    {
        $this->_init();
        if ($this->model->save($data)) {
            return true;
        } else {
            return false;
        }
    }

    public function tableDelete($where)
    {
        $this->_init();
        if ($this->model->where($where)->delete()) {
            return true;
        } else {
            return false;
        }
    }

    public function tableUpdate($where, $data)
    {
        // TODO: Implement tableUpdate() method.
    }

    public function tableSelect($where, $field)
    {
        $this->_init();
        return $this->model->where($where)->field($field)->find();
        // TODO: Implement tableSelect() method.
    }

    public function find($where)
    {
        $this->_init();
        if ($this->model->where($where)->find()) {
            return true;
        } else {
            return false;
        }
    }
}