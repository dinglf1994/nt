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

class Rectous extends Model implements SqlInterface
{

    protected $model;
    protected $refToCreModel;
    public function _init()
    {
        $this->model = new Rectous();

        $this->refToCreModel = model('Referrertocredit');
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
    public function tableSelect($where = true, $field = '*')
    {
        $this->_init();
        return $this->model->where($where)->field($field)->select();
    }

    public function pageSelect($where = true, $field = '*')
    {
        $this->_init();
        $list = $this->model
            ->alias('co')
            ->join('nt_referrertocredit re', "co.id = re.course_id and re.referrer_id = {$where['id']}", 'LEFT')
            ->where(true)
            ->field($field)
            ->paginate(Config::get('paginate.list_rows'));

        $page = $list->render();
        return ['list' => $list, 'page' => $page];
    }

    public function recPageSelect($where = true, $field = '*')
    {
        $this->_init();

        $field = ['rec.id as id', 'rec.name as name', 'rec.gender as gender', 'rec.company as company', 'rec.phone as phone', 'rec.status as status', 'rec.qq as qq', 'rec.wechat as wechat', 'rec.email as email', 'co.cname as cname', 're.name as rname'];
        if (isset($where['id'])) {
            $list = $this->model
                ->alias('rec')
                ->join('nt_course co', "co.id = rec.course_id and rec.referrer_id = {$where['id']}")
                ->join('nt_referrer re', "re.id = rec.referrer_id and rec.referrer_id = {$where['id']}")
                ->where(true)
                ->field($field)
                ->paginate(Config::get('paginate.list_rows'));
        } else {
            $list = $this->model
                ->alias('rec')
                ->join('nt_course co', "co.id = rec.course_id")
                ->join('nt_referrer re', "re.id = rec.referrer_id")
                ->where(true)
                ->field($field)
                ->paginate(Config::get('paginate.list_rows'));
        }

        $page = $list->render();
        return ['list' => $list, 'page' => $page];
    }

    public function handleRec($where, $data)
    {
        $this->_init();
        $info = $this->where($where)->field(['course_id', 'referrer_id'])->find();
        $creWhere['referrer_id'] = $info['referrer_id'];
        $creWhere['course_id'] = $info['course_id'];

        $credit = $data['credit'];
        unset($data['credit']);
        if ($this->model->where($where)->data($data)->update()) {
            if ($this->refToCreModel->creditUpdate($creWhere, $credit)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}