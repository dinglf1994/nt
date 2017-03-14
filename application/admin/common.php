<?php

/**
 * 后台模块公共函数
 */

function isLogin()
{
    if (!isset($_SESSION['adminId']) || !isset($_SESSION['adminNickname'])) {
        echo "<script> alert('请登录');parent.location.href='/admin/index/login'; </script>";
    }
}
