<?php
namespace app\index\controller;

use app\index\model\Course;
use app\index\model\Rectous;
use app\index\model\Referrer;
use app\index\model\Referrertocredit;
use think\Controller;
use think\Request;
use think\Session;

class Index extends Controller
{
    protected $referrerModel;
    protected $course;
    protected $recToUs;
    protected $recToCre;
    private $loginCheck = ['login', 'register'];
    public function _initialize()
    {
        parent::_initialize();
//        $this->assign('username', Session::get('name')); 笨方法。。。
        if (!in_array(ACTION_NAME, $this->loginCheck)) {
            isLogin();
        }
    }
    public function __construct(Request $request, Referrer $referrer, Course $course, Rectous $rectous, Referrertocredit $referrertocredit)
    {
        parent::__construct($request);
        $this->referrerModel = $referrer;
        $this->course = $course;
        $this->recToUs = $rectous;
        $this->recToCre = $referrertocredit;
    }

    /**
     * @return mixed
     * 个人课程积分
     */
    public function index()
    {
        $where['id'] = Session::get('id');
        $page = $this->course->pageSelect($where);

//        var_dump($page);exit;
        $this->assign('courseInfo', $page['list']);
        $this->assign('coursePage', $page['page']);

        return $this->fetch();
    }

    /**
     * @return mixed
     * 登录
     */
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

    /**
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

    /**
     * 退出
     */
    public function logout()
    {
        Session::clear('id');
        Session::clear('nickname');
        return $this->redirect('/index/index/login');
    }

    /**
     * @return mixed
     * 向我们推荐
     */
    public function recToUs()
    {
        if (Request::instance()->isPost()) {
            $data = $this->_recToUs();
            if ($this->recToUs->tableAdd($data)) {
                $this->success('提交成功，我们将尽快处理，请关注你的提交记录', '/index/index/recToUs', '', 2);
            } else {
                $this->error('似乎出了点问题，请重新填写提交', '/index/index/recToUs', '', 2);
            }
        } else {
            $field = ['id', 'cname as name'];
            $allCourse = $this->course->tableSelect(true, $field);

            $this->assign('cName', $allCourse);
            return $this->fetch();
        }
    }

    /**
     * @return mixed
     * 过滤
     */
    private function _recToUs()
    {
        $response = Request::instance()->post();
        $data['referrer_id'] = Session::get('id');
        $data['course_id'] = trim($response['courseId']);
        $data['name'] = trim($response['name']);
        $data['email'] = trim($response['referrerEmail']);
        $data['gender'] = $response['referrerGender'] == 0 ? '男' : '女';
        $data['company'] = $response['referrerCompany'];
        $data['phone'] = $response['referrerPhone'];
        $data['qq'] = $response['referrerQq'];
        $data['wechat'] = $response['referrerWechat'];

        return $data;
    }

    /**
     * @return mixed
     * 查看所有课程
     */
    public function allCourse()
    {
        $page = $this->course->pageSelect();

//        var_dump($page);exit;

        $this->assign('courseInfo', $page['list']);
        $this->assign('coursePage', $page['page']);

        return $this->fetch();
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

    /**
     * 积分提现请求
     */
    public function toCash()
    {
        $info = Request::instance()->get();
        $where['id'] = $info['id'];
        $data['tocash'] = $info['status'];

        if ($this->recToCre->recToCredit($where, $data)) {
            $this->success('已经发起提现，请关注提现状态', '/', 2);
        } else {
            $this->success('发起提现失败，请重试', '/', 2);
        }
    }

    /**
     * @return mixed
     * 查看推荐状态
     */
    public function recStatus()
    {
        $where['id'] = Session::get('id');
        $recStatus = $this->recToUs->recPageSelect($where);

        $this->assign('recStatus', $recStatus['list']);
        $this->assign('recStatusPage', $recStatus['page']);
        return $this->fetch();
    }
}

