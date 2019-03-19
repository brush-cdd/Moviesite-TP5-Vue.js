<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/7
 * Time: 16:21
 */
namespace app\admins\controller;
use app\admins\controller\BaseAdmin;

/**
 * 视频管理
 */
class Video extends BaseAdmin {
    //视频列表
    public function index(){
        $data['pageSize'] = 15;
        $data['page'] = max(1,(int)input('get.page'));

        $data['wd'] = trim(input('get.wd'));
        $where = array();
        $data['wd'] && $where = 'title like "%'.$data['wd'].'%"';
        $data['data'] = $this->db->table('video')->where($where)->pages($data['pageSize']);

        $this->assign('data',$data);
        return $this->fetch();
    }

    //添加视频
    public function add(){
        $data['channel'] = $this->db->table('video_label')->where(array('flag'=>'channel'))->lists();

        $id = (int)input('get.id');
        $data['item'] = $this->db->table('video')->where(array('id'=>$id))->item();
        $this->assign('data',$data);
        return $this->fetch();
    }

    //保存视频
    public function save(){
        $id = (int)input('post.id');
        $data['title'] = trim(input('post.title'));
        $data['channel_id'] = (int)input('post.channel_id');
        $data['img'] = trim(input('post.img'));
        $data['url'] = trim(input('post.url'));
        $data['keyword'] = trim(input('post.keyword'));
        $data['desc'] = trim(input('post.desc'));
        $data['status'] = (int)input('post.status');

        if ($data['title'] == ''){
            exit(json_encode(array('code'=>1,"msg"=>'视频名称不能为空')));
        }
        if ($data['url'] == ''){
            exit(json_encode(array('code'=>1,"msg"=>'视频地址不能为空')));
        }

        if ($id){
            $this->db->table('video')->where(array('id'=>$id))->update($data);
        }else{
            $data['add_time'] = time();
            $this->db->table('video')->insert($data);
        }
        exit(json_encode(array('code'=>0,'msg'=>'保存成功')));
    }

    //图片上传
    public function upload_img(){
        $file = request()->file('file');
        if ($file==null){
            exit(json_encode(array('code'=>1,'msg'=>'没有文件上传')));
        }
        $info = $file->move(ROOT_PATH.'public'.DS.'upload');
        $ext = ($info->getExtension());
        if (!in_array($ext,array('jpg','jpeg','gif','png','JPG','JPEG','GIF','PNG'))){
            exit(json_encode(array('code'=>1,'msg'=>'文件格式不支持')));
        }
        $img = '/upload/'.$info->getSaveName();
        exit(json_encode(array('code'=>0,'msg'=>$img)));
    }
}