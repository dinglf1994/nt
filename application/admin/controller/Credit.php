<?php
/**
 * Created by PhpStorm.
 * User: lyon
 * Date: 17-3-12
 * Time: 上午11:39
 */

namespace app\admin\controller;


use app\admin\model\Referrertocredit;
use think\Controller;
use think\Request;

class Credit extends Controller
{
    protected $reToCreModel;

    public function __construct(Request $request, Referrertocredit $referrerToCredit)
    {
        parent::__construct($request);
        $this->reToCreModel = $referrerToCredit;
    }

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->assign('creditActive', 'open'); // 打开菜单
    }

    /*
     * 积分信息管理
     */
    public function creditManage()
    {
        $this->assign('creditManage', 'active');

        $page = $this->reToCreModel->tableSelect(true);

        $this->assign('creditsInfo', $page['list']);
        $this->assign('creditsPage', $page['page']);
//        $this->assign('creditsInfo', $creditsInfo);
        return $this->fetch();
    }

    /*
     * 积分提现信息
     */
    public function creditWithdrawals()
    {
        $this->assign('creditWithdrawals', 'active');

        $where['tocash'] = 1;
        $page = $this->reToCreModel->tableSelect($where);

        $this->assign('creditsInfo', $page['list']);
        $this->assign('creditsPage', $page['page']);

        return $this->fetch();
    }

    /*
     * 积分处理结果
     */
    public function hasDone()
    {
        $this->assign('hasDone', 'active');

        $where['tocash'] = 2;
        $page = $this->reToCreModel->tableSelect($where);

        $this->assign('creditsInfo', $page['list']);
        $this->assign('creditsPage', $page['page']);

        return $this->fetch();
    }
}