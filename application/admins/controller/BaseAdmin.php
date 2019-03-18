<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/7
 * Time: 16:24
 */
namespace app\admins\controller;
use think\Controller;
use think\Request;
use Util\data\Sysdb;

/*
 * */
class BaseAdmin extends Controller{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->_admin = session('admin');

        //未登录的用户不允许访问
        if(!$this->_admin){
            header('location:/admins/Account/login');
            exit;
        }
        $this->assign('admin',$this->_admin);
        $this->db = new Sysdb();
        // 判断用户是否有权限

    }
}