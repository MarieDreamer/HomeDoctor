<?php
namespace Home\Model;
use Home\Abstracts\CommonMAbstract;

class DoctorModel extends CommonMAbstract {

    public function doctorlists(){
        extract(generateRequestParamVars());
        $conditions=[];
        //0是删除 1是显示
        $conditions['status_delete']=1;
        $lists = $this->where($conditions)->order('id desc')->select();

        $suiji=(array_rand($lists,1));

       // $lists=count($lists);

        // foreach ($lists as $key => $value) {
        //     // var_dump($key);
        //     var_dump($lists[$key]['id']);
        // }
        $lists = $lists[$suiji]['id'];

        
        return $lists;
    }

    //取所有医生
    public function lists(){
        extract(generateRequestParamVars());
        $conditions=[];
        if($doctorid){
            $conditions['id']=$doctorid;
        }
        //0是删除 1是显示
        $conditions['status_delete']=1;
        $lists = $this->where($conditions)->order('id desc')->select();
        
        return $lists;
    }

    //绑定医生微信号
    public function weixin_bind(){
        extract(generateRequestParamVars());
        $conditions=array();
        $conditions['invite_code']=$invite_code;
        if($results=$this->where($conditions)->find()===false){
           throw new \Exception(L('OPERATION_FAILED'));
        }
        $results=$this->where($conditions)->find();
        if($results){
            $data=array();
            $data['user_id']=$user_id;
            $this->where($conditions)->save($data);
        }
    }

}