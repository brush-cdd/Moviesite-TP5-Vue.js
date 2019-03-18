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
        $where = array();
        $data['data'] = $this->db->table('video')->where($where)->lists();

        $this->assign('data',$data);
        return $this->fetch();
    }

    //添加视频
    public function add(){
        $data['channel'] = $this->db->table('video_label')->where(array('flag'=>'channel'))->lists();
        return $this->fetch();
    }

    //图片上传
    public function upload_img(){
        $file = request()->file('file');
        if ($file==null){
            exit(json_encode(array('code'=>1,'msg'=>'没有文件上传')));
        }
        $info = $file->move(ROOT_PATH.'public'.DS.'uploads');
        $ext = ($info->getExtension());
        if (!in_array($ext,array('jpg','jpeg','gif','png'))){
            exit(json_encode(array('code'=>1,'msg'=>'文件格式不支持')));
        }
        $img = '/upload/'.$info->getSaveName();
        exit(json_encode(array('code'=>0,'msg'=>$img)));
    }
}