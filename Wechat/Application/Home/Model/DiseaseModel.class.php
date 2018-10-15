<?php
namespace Home\Model;
use Home\Abstracts\CommonMAbstract;

class DiseaseModel extends CommonMAbstract {

	// 筛选查找
	public function lists(){
        extract(generateRequestParamVars());

        $conditions=[];
        //0是删除 1是显示
        $conditions['status_delete']=1;
        if($firstletter){
        	//首字母筛选
        	$conditions['first_letter']=$firstletter;
            $lists = $this->where($conditions)->field('id,name,first_letter')->order('id desc')->select();
        }elseif ($bodyposition) {
        	//身体筛选
        	$conditions['body_position']=$bodyposition;
            $lists = $this->where($conditions)->field('id,name,body_position')->order('id desc')->select();
            
        }elseif ($name) {
        	//疾病名称筛选
        	$conditions['name']=array('like','%'.$name.'%');
            $lists = $this->where($conditions)->field('id,name')->order('id desc')->select();
        }elseif ($department) {
        	//科室筛选
        	$conditions['department']=$department;
        }elseif ($id) {
            $conditions['id']=$id;
            $lists = $this->where($conditions)->order('id desc')->select();
        }
        
        // $lists = $this->where($conditions)->field('id,name,body_position')->order('id desc')->select();
        // $lists = $this->where($conditions)->order('id desc')->select();

        
        return $lists;
    }
    

}