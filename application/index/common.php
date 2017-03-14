<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

use think\Session;

/**
 * @return \think\response\Redirect
 * 检验登录状态
 */
function isLogin()
{
    if (!Session::has('id') || !Session::has('name')) {
        echo "<script> alert('请登录');parent.location.href='/index/index/login'; </script>";
//        return redirect('index/index/login');
//        return '<meta http-equiv="Refresh" content="0; url=index/index/login" /> ';
    }
}
