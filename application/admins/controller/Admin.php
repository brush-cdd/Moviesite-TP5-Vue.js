<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/7
 * Time: 23:39
 */
namespace app\admins\controller;


/**
 * 管理员管理
 */
class Admin extends BaseAdmin {
    //管理员列表
    public function index(){
        $data['lists'] = $this->db->table('admins')->lists();
        //加载角色
        $data['groups'] = $this->db->table('admin_groups')->cates('gid');
        $this->assign('data',$data);
        return $this->fetch();
    }

    // 添加管理员
    public function add(){
        //接收前台传来的id
        $id = (int)input('get.id');
        //加载管理员
        $data['item'] = $this->db->table('admins')->where(array('id'=>$id))->item();
        //加载角色
        $data['groups'] = $this->db->table('admin_groups')->cates('gid');
        $this->assign('data',$data);
        return $this->fetch();
    }

    // 保存管理员
    public function save(){
        $id = (int)input('post.id');
        $data['username'] = trim(input('post.username'));
        $data['gid'] = trim(input('post.gid'));
        //先不放入数据库字段，判断不为空之后经过md5加密之后再放入数据库
        $password = trim(input('post.pwd'));
        $data['realname'] = trim(input('post.realname'));
        $data['status'] = (int)(input('post.status'));

        if(!$data['username']){
            exit(json_encode(array('code'=>1,'msg'=>'用户名不能为空')));
        }
        if(!$data['gid']){
            exit(json_encode(array('code'=>1,'msg'=>'角色不能为空')));
        }
        if($id==0 && !$password){
            exit(json_encode(array('code'=>1,'msg'=>'密码不能为空')));
        }
        if(!$data['realname']){
            exit(json_encode(array('code'=>1,'msg'=>'姓名不能为空')));
        }

        //如果添加用户或编辑用户时修改密码，才处理密码
        if($password){
        //md5加密处理密码
        $data['password'] = md5($password);
        }

        //添加用户的判断
        $res = true;
        if($id==0){
            // 判断用户是否已存在
            $item = $this->db->table('admins')->where(array('username'=>$data['username']))->item();
            if($item){
                exit(json_encode(array('code'=>1,'msg'=>'该用户已存在')));
            }
            $data['add_time']=time();

            //保存到数据库
            $res = $this->db->table('admins')->insert($data);
        }else{
            $this->db->table('admins')->where(array('id'=>$id))->update($data);
        }


        if(!$res){
            exit(json_encode(array('code'=>1,'msg'=>'保存失败')));
        }
        exit(json_encode(array('code'=>0,'msg'=>'保存成功')));
    }

    // 删除管理员
    public function delete(){
        $id = (int)input('post.id');
        $res = $this->db->table('admins')->where(array('id'=>$id))->delete();
        if(!$res){
            exit(json_encode(array('code'=>1,'msg'=>'删除失败')));
        }
        exit(json_encode(array('code'=>0,'msg'=>'删除成功')));
    }
}