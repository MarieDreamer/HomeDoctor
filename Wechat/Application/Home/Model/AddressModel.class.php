<?php
namespace Home\Model;
use Home\Abstracts\CommonMAbstract;

class AddressModel extends CommonMAbstract {

    //修改地址
    public function modify_do(){
        extract(generateRequestParamVars());

        if($name && $phone && $province && $address &&  $id && $userid){
            $data=array();
            $data['name']=$name;
            $data['phone']=$phone;
            $data['province']=$province;
            $data['address']=$address;
            $addcondition=array();
            $addcondition['id']=$id;
            $addcondition['userid']=$userid;
            if($this->where($addcondition)->save($data)===false){
                throw new \Exception(L('OPERATION_FAILED'));
            }
        }else{
            throw new \Exception(L("缺少数据"));
        }
            

    }

    //取保存的地址
    public function lists(){
        extract(generateRequestParamVars());
        $conditions=[];
        //0是删除 1是显示
        $conditions['status_delete']=1;
        $conditions['userid']=$userid;
        $conditions['default']=$defaults;
        $lists = $this->where($conditions)->order('id desc')->select();

        //用户名赋给用户id
            foreach ($lists as $key => $value) {
                if($lists[$key]['default']==1){
                    $lists[$key]['default']="默认地址";
                }else{
                    $lists[$key]['default']="";
                }
            }
        
        return $lists;
    }

    //默认地址修改
    public function defaultchange(){
        extract(generateRequestParamVars());
        $conditions=array();
        $conditions['userid']=$userid;
        $conditions['default']=1;
        $conditions['status_delete']=1;
        $lists = $this->where($conditions)->order('id desc')->select();

        foreach ($lists as $key => $value) {
            $lists = $lists[$key]['id'];
        }

        if($lists){
            $data=array();
            $data['default']=0;
            $conditionsfirst=array();
            $conditionsfirst['id']=$lists;
            if(!$this->where($conditionsfirst)->save($data)){
                echo $this->_sql();
                throw new \Exception(L('OPERATION_FAILED'));
            }
        }

        if($userid && $id){
            $dataecond=array();
            $dataecond['default']=1;
            $conditionsecond=array();
            $conditionsecond['id']=$id;
            $conditionsecond['userid']=$userid;
            if(!$this->where($conditionsecond)->save($dataecond)){
                echo $this->_sql();
                throw new \Exception(L('OPERATION_FAILED'));
            }
        }else{
            throw new \Exception(L('缺少数据'));
            }

        
    }

    ///删除地址
    public function deletefun(){
        extract(generateRequestParamVars());
        //正整数正则
        $shuziyz='/^\d+$/';
        if($userid && $id){
                $data=array();
                $data['status_delete']=0;
                $conditions=array();
                $conditions['id']=$id;
                $conditions['userid']=$userid;
                if(!$this->where($conditions)->save($data)){
                    echo $this->_sql();
                    throw new \Exception(L('OPERATION_FAILED'));
                }
        }else{
            throw new \Exception(L('缺少数据'));
            }
        
    }

    //添加地址
    public function adds(){
        extract(generateRequestParamVars());
        $conditions=[];
        //0是删除 1是显示
        $conditions['status_delete']=1;
        $conditions['userid']=$userid;
        $lists = $this->where($conditions)->order('id desc')->select();
        if($lists){
            $defaultzt=0;
        }else{
            $defaultzt=1;
        }
        //正整数正则
        $shuziyz='/^\d+$/';
        if($userid && $patientname2 && $patientphone2 && $dizhi && $patientcard2){
                $data=array();
                $data['userid']=$userid;
                $data['name']=$patientname2;
                $data['phone']=$patientphone2;
                $data['province']=$dizhi;
                $data['address']=$patientcard2;
                $data['default']=$defaultzt;
                $data['status_delete']=1;
                $data['create_time']=time();
                if(!$this->add($data)){
                    // echo $this->_sql();
                    throw new \Exception(L('OPERATION_FAILED'));
                }
        }else{
            throw new \Exception(L('缺少数据'));
            }
        
    }

}