<?php
/**
 * Created by PhpStorm.
 * User: tw
 * Date: 2017/5/13
 * Time: 17:11
 */

namespace App\Controllers;


use E8\Controller;
use E8\Request;

class LoginController extends Controller
{
    public function login_view()
    {
        return $this->view('auth/login');
    }

    public function do_login()
    {
        if($this->request->get('username') =='root'
            && $this->request->get('password') =='123'){
            return '登录成功';
        }else{
            return '用户名密码错误';
        }
    }

}