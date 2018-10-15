<?php
namespace Home\Model;
use Home\Abstracts\CommonMAbstract;

class DoctorModel extends CommonMAbstract {

    
        public function lists(){
            extract(generateRequestParamVars());
            
            $conditions=array();
            //0是删除 1是显示
            $conditions['status_delete']=1;
            if(!$conditions){
                $count=$this->count();
            }else{
                $count=$this->where($conditions)->count();
            }

            if(!$numPerPage=I('param.numPerPage')){
                $numPerPage=C('NUM_PER_PAGE');
            }

            $page=new \Think\Page($count,$numPerPage);
            $paging=$page->show();

            
            if(!$conditions){
                $results=$this->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
            }else{
                $results=$this->where($conditions)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
            }
            
            return array($paging,$results);
        }
        public function adds_do(){
            extract(generateRequestParamVars());
            $data=array();
            $data['realname']=$realname;
            $data['sex']=$sex;
            $data['hospital']=$hospital;
            $data['grade']=$grade;
            $data['department_id']=$department_id;
            $data['introduce']=$introduce;
            $data['work_time_start']=$work_time_start;
            $data['work_time_end']=$work_time_end;
            $data['evaluate']=$evaluate;
            $data['status_delete']=1;
            $data['create_time']=time();
            $data['invite_code']=$this->guid();
            if($re=$this->add($data)===false){
               throw new \Exception(L('OPERATION_FAILED'));
            }
        }
        //生成无序码
        function guid(){
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = substr($charid, 0, 10);
            return $uuid;
        }
        public function edit_do(){
            extract(generateRequestParamVars());
            if($realname!=""&&$department_id!=""&&$sex!=""){
                $conditions=array();
                $conditions['id']=$id;
                $data=array();
                $data['realname']=$realname;
                $data['sex']=$sex;
                $data['hospital']=$hospital;
                $data['grade']=$grade;
                $data['department_id']=$department_id;
                $data['introduce']=$introduce;
                $data['work_time_start']=$work_time_start;
                $data['work_time_end']=$work_time_end;
                $data['evaluate']=$evaluate;
                $data['create_time']=time();
                if($re=$this->where($conditions)->save($data)===false){
                   throw new \Exception(L('OPERATION_FAILED'));
                }
            }else{
                throw new \Exception('请注意填写姓名、科室、性别');
            }
        }
        public function delete_do(){
            extract(generateRequestParamVars());
            $conditions=array();
            $conditions['id']=$id;
            $data=array();
            $data['status_delete']=0;
            if($re=$this->where($conditions)->save($data)===false){
               throw new \Exception(L('OPERATION_FAILED'));
            }
        }
        public function edit(){
            extract(generateRequestParamVars());
            $conditions=array();
            $conditions['id']=$id;
            if($re=$this->where($conditions)->find()===false){
               throw new \Exception(L('OPERATION_FAILED'));
            }
            $results=$this->where($conditions)->find();
            return $results;
        }
}