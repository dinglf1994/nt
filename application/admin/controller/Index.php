<?php
namespace app\admin\controller;

use app\admin\model\Admin;
use think\Controller;
use think\Request;
use think\Session;

class Index extends Controller
{
    protected $adminModel;
    public function _initialize()
    {
        parent::_initialize();
    }

    public function __construct(Request $request, Admin $admin)
    {
        parent::__construct($request);
        $this->adminModel = $admin;
    }

    public function index()
    {

        $this->assign('index', 'active');
        $where['id'] = Session::get('adminId');
        $field = ['id', 'name', 'nickname', 'phone', 'email', 'register_time'];
        $adminInfo = $this->adminModel->tableSelect($where, $field);
        if (!empty($adminInfo)) {
            $this->assign('adminInfo', $adminInfo);
            return $this->fetch();
        } else {

        }
    }
    public function login()
    {
        if (Request::instance()->isPost()) {
            $post = Request::instance()->post();
            $where['nickname'] = $post['adminNickname'];
            $where['password'] = md5($post['adminPassword']);
            $field = ['id'];
            $adminId = $this->adminModel->tableSelect($where, $field);
            if (!empty($adminId)) {

                Session::set('adminId', $adminId['id']);
                Session::set('adminNickname', $where['nickname']);
                $this->success('登录成功，正在进入后台', '/admin', '' , 2);
            } else {
                $this->error('登录失败');
            }
        } else {
            return $this->fetch();
        }
    }

    /*
     * 用户注册
     */
    public function register()
    {
        if (Request::instance()->isPost()) {
            $data = $this->_param();
            if ($this->adminModel->tableAdd($data)) {
                $this->success('注册成功，正在跳转登录界面', '/admin/index/login');
            } else {
                $this->error('注册失败，请重新注册', '/admin/index/login');
            }
        } else {
            return $this->error('非法操作，正在返回', '/admin/index/login');
        }
    }


    public function changePasswd()
    {
        $_SESSION = null;
        $this->success('修改成功', '/admin/index/login');
    }


    /*
     * 退出
     */
    public function logout()
    {
        return $this->redirect('/admin/index/login');
    }

    /**
     * 处理提交数据
     */
    private function _param()
    {
        $response = Request::instance()->post();
        $data['nickname'] = trim($response['adminNickname']);
        $data['name'] = trim($response['adminName']);
        $data['email'] = trim($response['adminEmail']);
//        $data['gender'] = $response['referrerGender'] == 0 ? '男' : '女';
//        $data['company'] = $response['referrerCompany'];
        $data['phone'] = $response['adminPhone'];
//        $data['qq'] = $response['referrerQq'];
//        $data['wechat'] = $response['referrerWechat'];
//        $data['alibaba'] = $response['referrerAlibaba'];
        $data['password'] = empty($response['referrerPassword']) ? md5('123456') : md5($response['referrerPassword']);

        return $data;
    }
}
