<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/18
 * Time: 15:57
 */
namespace app\admins\controller;
use app\admins\controller\BaseAdmin;


/**
 * 角色管理
 */
class Roles extends BaseAdmin {

    //角色列表
    public function index(){
        $data['roles'] = $this->db->table('admin_groups')->lists();
        $this->assign('data',$data);
        return $this->fetch();
    }

    //角色添加
    public function add(){
        $gid = (int)input('get.gid');
        $role = $this->db->table('admin_groups')->where(array('gid'=>$gid))->item();
        $role && $role['rights'] && $role['rights'] = json_decode($role['rights']);
        $this->assign('role',$role);


        $menus_list = $this->db->table('admin_menus')->where(array('status'=>0))->cates('mid');
        $menus = $this->gettreeitems($menus_list);
        $results = array();
        foreach ($menus as $value){
            $_data = isset($value['children'])?$value['children']:$value;
            $value['children'] = isset($value['children'])?$this->formatMenus($_data):false;
            $results[] = $value;
        }
        $this->assign('menus',$results);
        return $this->fetch();
    }

    private function gettreeitems($items){
        $tree = array();
        foreach ($items as $item){
            if(isset($items[$item['pid']])){
                $items[$item['pid']]['children'][] = &$items[$item['mid']];
            }else{
                $tree[] = &$items[$item['mid']];
            }
        }
        return $tree;
    }

    private function formatMenus($items,&$res = array()){
        foreach ($items as $item){
            if(!isset($item['children'])){
                $res[] = $item;
            }else{
                $tem = $item['children'];
                unset($item['children']);
                $res[] = $item;
                $this->formatMenus($tem,$res);
            }
        }
        return $res;
    }

    public function save(){
        $gid = (int)input('post.gid');

        $data['title'] = trim(input('post.title'));
        $menus = input('post.menu/a');
        if(!$data['title']){
            exit(json_encode(array('code'=>1,'msg'=>'角色名称不能为空')));
        }
        $menus && $data['rights'] = json_encode(array_keys($menus));
        if($gid){
            $this->db->table('admin_groups')->where(array('gid'=>$gid))->update($data);
        }else{
            $this->db->table('admin_groups')->insert($data);
        }
        exit(json_encode(array('code'=>0,'msg'=>'保存成功')));
    }

    public function deletes(){
        $gid = (int)input('gid');
        $this->db->table('admin_groups')->where(array('gid'=>$gid))->delete();
        exit(json_encode(array('code'=>0,'msg'=>'删除成功')));
    }
}