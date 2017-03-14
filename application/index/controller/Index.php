<?php
namespace app\index\controller;

use app\index\model\Course;
use app\index\model\Referrer;
use think\Controller;
use think\Request;
use think\Session;

class Index extends Controller
{
    protected $referrerModel;
    protected $course;
    private $loginCheck = ['login', 'register'];
    public function _initialize()
    {
        parent::_initialize();
//        $this->assign('username', Session::get('name')); 笨方法。。。
        if (!in_array(ACTION_NAME, $this->loginCheck)) {
            isLogin();
        }
    }
    public function __construct(Request $request, Referrer $referrer, Course $course)
    {
        parent::__construct($request);
        $this->referrerModel = $referrer;
        $this->course = $course;
    }

    public function index()
    {
        $where['id'] = Session::get('id');
        $page = $this->course->pageSelect($where);

//        var_dump($page);exit;
        $this->assign('courseInfo', $page['list']);
        $this->assign('coursePage', $page['page']);

        return $this->fetch();
    }
    public function login()
    {
        if (Request::instance()->isPost()) {
            $post = Request::instance()->post();
            $where['nickname'] = $post['referrerNickname'];
            $where['password'] = md5($post['referrerPassword']);
            $field = ['id'];
            $referrerInfo = $this->referrerModel->tableSelect($where, $field);
            if (!empty($referrerInfo)) {
                Session::set('id', $referrerInfo['id']);
                Session::set('name', $where['nickname']);
                $this->success('登录成功，正在进入首页', '/', '' , 2);
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
            if ($this->referrerModel->tableAdd($data)) {
                $this->success('注册成功，正在跳转登录界面', '/index/index/login');
            } else {
                $this->error('注册失败，请重新注册', '/index/index/login');
            }
        } else {
            return $this->error('非法操作，正在返回', '/admin/index/login');
        }
    }

    /*
     * 退出
     */
    public function logout()
    {
        Session::clear('id');
        Session::clear('nickname');
        return $this->redirect('/index/index/login');
    }

    /**
     * 处理提交数据
     */
    private function _param()
    {
        $response = Request::instance()->post();
        $data['nickname'] = trim($response['referrerNickname']);
        $data['name'] = trim($response['referrerName']);
        $data['email'] = trim($response['referrerEmail']);
        $data['gender'] = $response['referrerGender'] == 0 ? '男' : '女';
        $data['company'] = $response['referrerCompany'];
        $data['phone'] = $response['referrerPhone'];
        $data['qq'] = $response['referrerQq'];
        $data['wechat'] = $response['referrerWechat'];
        $data['alibaba'] = $response['referrerAlibaba'];
        $data['password'] = empty($response['referrerPassword']) ? md5('123456') : md5($response['referrerPassword']);

        return $data;
    }

    public function toCash()
    {
        echo '提现';
    }
}

