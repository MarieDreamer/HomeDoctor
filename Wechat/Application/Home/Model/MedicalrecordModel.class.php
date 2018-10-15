<?php
namespace Home\Model;
use Home\Abstracts\CommonMAbstract;

class MedicalrecordModel extends CommonMAbstract {

    public function resume_lists(){
        extract(generateRequestParamVars());

        if($patientid){
            $conditions=array();
            $conditions['status_delete']=1;
            $conditions['doctorid']=0;
            $conditions['orderid']=0;
            $conditions['patientid']=$patientid;
            $lists=$this->where($conditions)->find();//查询病人自己填写的病历
        }else{
            throw new \Exception(L("缺少数据"));
        }

        return $lists;

    }

   public function resume_do(){
        extract(generateRequestParamVars());

        if($patientid && $doctorid && $orderid && $now && $testing){
            $conditions=array();
            $conditions['status_delete']=1;
            $conditions['doctorid']=0;
            $conditions['orderid']=0;
            $conditions['patientid']=$patientid;
            $lists=$this->where($conditions)->find();//查询病人自己填写的病历

            $conditionss=array();
            $conditionss['status_delete']=1;
            $conditionss['doctorid']=$doctorid;
            $conditionss['orderid']=$orderid;
            $lists2=$this->where($conditionss)->find();//查询相应订单的病历

            $conditionsss=array();
            $conditionsss['status_delete']=1;
            $conditionsss['id']=$orderid;
            $lists3=D('Order')->where($conditionsss)->find();//查询订单状态判断是否是可以更改

            if($lists3['receipt']==0 && $lists3['doctorid']==$doctorid){
                if($lists2['id']){
                    $data=array();
                    $data['name']=$lists3['name'];
                    $data['doctorid']=$doctorid;
                    $data['patientid']=$patientid;
                    $data['now']=$now;
                    $data['orderid']=$orderid;
                    $data['history']=$lists['history'];
                    $data['personal']=$lists['personal'];
                    $data['family']=$lists['family'];
                    $data['marriage']=$lists['marriage'];
                    $data['birth']=$lists['birth'];
                    $data['testing']=$testing;
                    $data['status_delete']=1;
                    $data['create_time']=time();
                    $addcondition=array();
                    $addcondition['id']=$lists2['id'];
                    if($this->where($addcondition)->save($data)===false){
                        throw new \Exception(L('OPERATION_FAILED'));
                    }

                }else{
                    $data=array();
                    $data['name']=$lists3['name'];
                    $data['doctorid']=$doctorid;
                    $data['patientid']=$patientid;
                    $data['now']=$now;
                    $data['orderid']=$orderid;
                    $data['history']=$lists['history'];
                    $data['personal']=$lists['personal'];
                    $data['family']=$lists['family'];
                    $data['marriage']=$lists['marriage'];
                    $data['birth']=$lists['birth'];
                    $data['testing']=$testing;
                    $data['status_delete']=1;
                    $data['create_time']=time();
                    if(!$this->add($data)){
                        // echo $this->_sql();
                        throw new \Exception(L('OPERATION_FAILED'));
                    }
                }

            }else{
                    throw new \Exception(L("订单状态不可提交"));
                }

        }else{
            throw new \Exception(L("缺少数据"));
        }
        
    }
}