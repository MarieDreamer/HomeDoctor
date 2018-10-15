<?php
namespace Home\Model;
use Home\Abstracts\CommonMAbstract;

class OrderModel extends CommonMAbstract {

    public function receipt_do(){
        extract(generateRequestParamVars());
        if($userid && $id){
            $conditions=array();
            $conditions['status_delete']=1;
            $conditions['user_id']=$userid;
            $lists=D('Doctor')->where($conditions)->find(); //通过userid查询是否是医生
            if($lists){
                $conditionss=array();
                $conditionss['id']=$id;
                $lists2=$this->where($conditionss)->find(); //查询此订单是否被接单
                if($lists2['receipt']==1){
                    $conditionsss=array();
                    $conditionsss['doctorid']=$userid; //查询订单表是否有此医生正在进行的单子
                    $conditionsss['receipt']=0;
                    $lists3=$this->where($conditionsss)->find(); 
                    if($lists3){
                        $judge=4;
                    }else{
                        $judge=5;
                    }
                }else{
                    $judge=3;
                }
            }else{
                $judge=2;
            }
        }else{
            $judge=1;
        }

        if($judge==5){
            $data=array();
            $data['doctorid']=$userid;
            $data['receipt']=0;
            if($this->where($conditionss)->save($data)===false){
                echo $this->_sql();
                throw new \Exception(L('OPERATION_FAILED'));
            }
        }elseif ($judge==1){
            throw new \Exception(L("缺少必要数据"));
        }elseif ($judge==2) {
           throw new \Exception(L("不是医生"));
        }elseif ($judge==3) {
           throw new \Exception(L("订单已被接单!"));
        }elseif ($judge==4) {
           throw new \Exception(L("你已经有正在进行中的订单，不可以接单!"));
        }

    }

    public function lists(){
        extract(generateRequestParamVars());
        $conditions=[];
        //0是删除 1是显示
        $conditions['status_delete']=1;

        if($receipt || $receipt==0){
            $conditions['receipt']=$receipt;
            if($page){
                $numPerPage=6;
                $page=new \Think\Page($count,$numPerPage);
                $paging=$page->show();

                $lists = $this->where($conditions)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
            }else{
                throw new \Exception(L("缺少page"));
            }
        }else{
            throw new \Exception(L("缺少receipt"));
        }

        return $lists;
    }

    public function listsfind(){
        extract(generateRequestParamVars());
        $conditions=[];
        //0是删除 1是显示
        $conditions['status_delete']=1;
        if($id){
            $conditions['id']=$id;
            $lists=$this->where($conditions)->find();

        }else{
            throw new \Exception(L("缺少id"));
        }

        return $lists;
    }


    public function adds(){
        extract(generateRequestParamVars());
        //正整数正则
        $shuziyz='/^\d+$/';
        if($disease && $address && $patientname && $patientphone && $doctorid && $inputage && $inputsex && $userid){
                $data=array();
                $data['doctorid']=$doctorid;
                $data['patientid']=$userid;
                $data['disease']=$disease;
                $data['name']=$patientname;
                $data['phone']=$patientphone;
                $data['addres']=$address;
                $data['age']=$inputage;
                $data['sex']=$inputsex;
                $data['over_time']=0;
                $data['receipt']=1;
                $data['status_delete']=1;
                $data['create_time']=time();
                if(!$this->add($data)){
                    echo $this->_sql();
                    throw new \Exception(L('OPERATION_FAILED'));
                }
        }else{
            throw new \Exception(L('缺少数据'));
            }
        
    }

    // public function receipt_do(){
    //     extract(generateRequestParamVars());
    //     if($userid && $id){
    //         $conditions=array();
    //         //0是删除 1是显示
    //         $conditions['status_delete']=1;
    //         $conditions['id']=$userid;
    //         $lists=D('WechatUser')->where($conditions)->find(); //通过userid查询是否是医生
    //         if($lists['is_doctor']==1){
    //             $conditionss=array();
    //             $conditionss['id']=$id;
    //             $lists2=$this->where($conditionss)->find(); //查询此订单是否被接单
    //             if($lists2['receipt']==1){
    //                 $conditionsss=array();
    //                 $conditionsss['doctorid']=$userid;
    //                 $conditionsss['receipt']=0;
    //                 $lists3=$this->where($conditionsss)->find(); //查询订单表是否有此医生正在进行的单子
    //                 if($lists3){
    //                     throw new \Exception(L("你已经有正在进行中的订单，不可以接单!")); 

    //                 }else{
    //                     $data=array();
    //                     $data['doctorid']=$userid;
    //                     $data['receipt']=0;
    //                     if($this->where($conditionss)->save($data)===false){
    //                         throw new \Exception(L('OPERATION_FAILED'));
    //                     }

    //                 }
                   
    //             }else{
    //                 throw new \Exception(L("订单已被接单!"));
    //             }
                
    //         }else{
    //             throw new \Exception(L("不是医生"));
    //         }
    //     }else{
    //         throw new \Exception(L("缺少必要数据"));
    //     }
        
    // }

}