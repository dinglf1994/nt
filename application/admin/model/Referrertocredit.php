<?php
/**
 * Created by PhpStorm.
 * User: lyon
 * Date: 17-3-14
 * Time: 下午7:58
 */

namespace app\admin\model;


use app\admin\repositories\SqlInterface;
use think\Config;
use think\Controller;
use think\Model;

class Referrertocredit extends Model implements SqlInterface
{
    protected $model;
    public function _init()
    {
        $this->model = new Referrertocredit();
    }
    public function _checkSql($sql)
    {
        // TODO: Implement _checkSql() method.
    }
    public function tableAdd($data)
    {
        // TODO: Implement tableAdd() method.
    }
    public function tableDelete($where)
    {
        // TODO: Implement tableDelete() method.
    }
    public function tableSelect($where, $field='*')
    {
        $field = ['rc.id as id', 'rc.recommendation as recommendation', 'rc.credits as credits', 'rc.tocash as tocash', 'co.cname as cname', 're.name as name', 're.alibaba as alibaba'];
        $this->_init();
        $list = $this->model
            ->alias('rc')
            ->join('nt_course co', 'co.id = rc.course_id')
            ->join('nt_referrer re', 're.id = rc.referrer_id')
            ->where($where)
            ->field($field)
            ->paginate(Config::get('paginate.list_rows'));

        $page = $list->render();
        return ['list' => $list, 'page' => $page];
    }
    public function tableUpdate($where, $data)
    {
        // TODO: Implement tableUpdate() method.
    }

}