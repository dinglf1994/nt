<?php
/**
 * Created by PhpStorm.
 * User: lyon
 * Date: 17-3-13
 * Time: 下午8:47
 */

namespace app\admin\model;


use app\admin\repositories\SqlInterface;
use think\Config;
use think\Model;

class Course extends Model implements SqlInterface
{

    protected $model;
    public function _init()
    {
        // TODO: Implement _init() method.
        $this->model = new Course();
    }
    public function _checkSql($sql)
    {
        // TODO: Implement _checkSql() method.
    }
    public function tableAdd($data)
    {
        $this->_init();
        if ($this->model->data($data)->save()) {
            return true;
        } else {
            return false;
        }
    }
    public function tableDelete($where)
    {
        // TODO: Implement tableDelete() method.
    }
    public function tableUpdate($where, $data)
    {
        // TODO: Implement tableUpdate() method.
    }
    public function tableSelect($where, $field)
    {
        // TODO: Implement tableSelect() method.
    }

    public function pageSelect($where = true, $field = '*')
    {
        $this->_init();
        $list = $this->model->where($where)->field($field)->paginate(Config::get('paginate.list_rows'));
        $page = $list->render();
        return ['list' => $list, 'page' => $page];
    }
}