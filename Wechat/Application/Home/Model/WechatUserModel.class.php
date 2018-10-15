<?php
namespace Home\Model;
use Home\Abstracts\CommonMAbstract;

class WechatUserModel extends CommonMAbstract {

    static $DOCTOR_MODEL = 'doctor';

    public function login_do($userid)
    {
        $conditions = array();
        $conditions['id'] = $userid;
        $data = array();
        $data['login_time'] = time();
        if($user=$this->where($conditions)->save($data)===false){
            throw new \Exception('OPERATION_FAILED');
        }
    }

    public function save_do()
    {
        extract(generateRequestParamVars());
        $conditions = array();
        $conditions['id'] = $id;
        $data = array();
        $data['nickname']=$nickname;
        $data['imageurl']=$imageurl;
        $data['gender']=$gender;
        $data['province']=$province;
        $data['city']=$city;
        $data['country']=$country;
        if($this->where($conditions)->save($data)===false){
            // echo $this->_sql();
            throw new \Exception('OPERATION_FAILED');
        }
        // echo $this->_sql();
    }

    public function adds_do($open_id)
    {
        $data = array();
        $data['openid'] = $open_id;
        $data['status_delete'] = 1;
        $data['create_time'] = time();
        $data['login_time'] = time();
        $user = $this->add($data);
        if ($user === false) {
            // echo $this->_sql();
            throw new \Exception('OPERATION_FAILED');
        }
        return $user;
    }

    public function share_do()
    {
        extract(generateRequestParamVars());
        $conditions = [];
        $conditions['id'] = $user_id;
        $data = array();
        $data['is_share'] = 1;
        if ($this->where($conditions)->save($data) === false) {
            throw new \Exception('OPERATION_FAILED');
        }
    }

    public function getFollow(){
        extract(generateRequestParamVars());
        $user = $this->find($user_id);
        $follow_id = explode("_",$user['follow']);
        $conditions = [];
        $conditions['id'] = array('in',$follow_id);
        $follow = $this->where($conditions)->select();
        for($i = 0; $i < count($follow); $i ++){
            $con = [];
            $con['user_id'] = $follow[$i]['id'];
            $doctor = D(self::$DOCTOR_MODEL)->where($con)->find();
            if($doctor){
                $follow[$i]['hospital'] = $doctor['hospital'];
                $follow[$i]['grade'] = $doctor['grade'];
                $follow[$i]['introduce'] = $doctor['introduce'];
            }
        }
        return $follow;
    }

    public function getUser(){
        extract(generateRequestParamVars());
        $user = $this->find($user_id);
        $follow_id = explode("_",$user['follow']);
        $fan_id = explode("_",$user['fan']);
        if($follow_id[0]){
            $user['follow_num'] = count($follow_id);
        }else{
            $user['follow_num'] = 0;
        }
        if($fan_id[0]){
            $user['fan_num'] = count($fan_id);
        }else{
            $user['fan_num'] = 0;
        }
        $doctor = D(self::$DOCTOR_MODEL)->where('user_id='.$user_id)->find();
        $user['introduce'] = $doctor['introduce'];
        return $user;
    }

}