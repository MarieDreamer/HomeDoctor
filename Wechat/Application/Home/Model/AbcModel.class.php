<?php
namespace Home\Model;
use Home\Abstracts\CommonMAbstract;

class AbcModel extends CommonMAbstract {

    public function departmentlists(){
        extract(generateRequestParamVars());

        $conditions=[];
        
        $lists = $this->order('id asc')->select();

        
        return $lists;
    }

    

}