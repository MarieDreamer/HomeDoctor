<?php
namespace Home\Model;
use Home\Abstracts\CommonMAbstract;

class PushModel extends CommonMAbstract {

    public function adds(){
        extract(generateRequestParamVars());
        //正整数正则
        $shuziyz='/^\d+$/';
        if($fromid && $client){
                $data=array();
                $data['fromid']=$fromid;
                $data['client']=$client;
                $data['content']=$content;
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

}