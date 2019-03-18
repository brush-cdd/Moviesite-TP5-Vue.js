<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/6
 * Time: 2:22
 */
namespace app\admins\controller;
use think\Controller;
use Util\data\Sysdb;

/*
 * */
class Test extends Controller{
    public function index(){
        $this->db = new Sysdb;
        $res = $this->db->table('admins')->field('id,username')->lists();
        dump($res);

        echo '<hr>';
        $res2 = $this->db->table('admins')->field('id,username')->cates('id');
        dump($res2);
    }
}