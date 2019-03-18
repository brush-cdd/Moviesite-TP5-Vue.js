<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 22:13
 */
namespace app\admins\controller;
use think\Controller;
use Util\data\Sysdb;
/*
 * */

class Api extends Controller{
    public function AdminList(){

        $this->db = new Sysdb;
        $data = $this->db->table('admins')->lists();

        $count = 40;
        $list['msg'] = "";
        $list['code'] = 0;
        $list['count'] = $count;
        $list['data'] = $data;
        return json($list);
    }
}