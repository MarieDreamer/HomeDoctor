<?php
namespace Home\Model;
use Home\Abstracts\CommonMAbstract;

class DiseaseModel extends CommonMAbstract {

    public function choiceadd_do(){
        extract(generateRequestParamVars());
        //正整数正则
        $shuziyz='/^\d+$/';
        // $count=0;
        if($drug){



            $data=array();
            $data['drug']=json_encode($drug);
            $conditions=array();
            $conditions['id']=$id;
            if(!$this->where($conditions)->save($data)){
                    echo $this->_sql();
                    throw new \Exception(L('OPERATION_FAILED'));
                }
        }else{
            throw new \Exception(L('缺少必要数据'));
            }
        
    }

    public function getzi(){
        extract(generateRequestParamVars());
        $conditions=array();
        $conditions['status_delete']=1;//屏蔽逻辑删除
        // $conditions['pid']=$id;//如果 子集的pid=顶级的id（有子集）执行查找
        $shenti=$this->where($conditions)->select();
        return $shenti;
        
    }

    //获取role表
    public function get(){
        // echo "string";
        extract(generateRequestParamVars());
        $conditions=array();
        $conditions['status_delete']=1;//屏蔽逻辑删除
        // $conditions['pid']=0;
        $keshi=$this->where($conditions)->select();
        return $keshi;
    }

    

    public function dele(){
            extract(generateGetParamVars());
            if(!$id){
                throw new \Exception(L('MISSING_PARAMETER')); ;
            }
            $conditions['id']=intval($id);
            if(!$result=$this->where($conditions)->find()){
                throw new \Exception(L('NO_DATA'));
            }
            $conditions=array();
            $conditions['id']=$id;
            $data=array();
            $data['status_delete']=0;
            if($this->where($conditions)->save($data)===false){
               throw new \Exception(L('OPERATION_FAILED'));
            }
    }      


        //商品类别管理页面显示
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
            
            //科室name赋予
            foreach ($results as $key => $value) {
                $conditions=array();
                $conditions['id']=$results[$key]['department'];
                $result=D('department')->where($conditions)->find();
                $name=$result['name'];
                // var_dump($name);
                $results[$key]['department']=$name;
            }

            // 部位name赋予
            foreach ($results as $k => $v) {
                $conditions=array();
                $conditions['id']=$results[$k]['body_position'];
                $result=D('BodyDisease')->where($conditions)->find();
                $positionname=$result['name'];
                $results[$k]['body_position']=$positionname;
            }
            
            return array($paging,$results);
        }

        public function listsdrug(){
            extract(generateRequestParamVars());
            
            $conditions=array();
            //0是删除 1是显示
            $conditions['status_delete']=1;
            $conditions['id']=$id;
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
            
            //科室name赋予
            foreach ($results as $key => $value) {
                $conditions=array();
                $conditions['id']=$results[$key]['department'];
                $result=D('department')->where($conditions)->find();
                $name=$result['name'];
                // var_dump($name);
                $results[$key]['department']=$name;
            }

            // 部位name赋予
            foreach ($results as $k => $v) {
                $conditions=array();
                $conditions['id']=$results[$k]['body_position'];
                $result=D('BodyDisease')->where($conditions)->find();
                $positionname=$result['name'];
                $results[$k]['body_position']=$positionname;
            }
            $results[0]['drug']=json_decode($results[0]['drug'],true);
            // echo count($diseaselists[0]['drug']);
            // var_dump($results[0]['drug']);
            return array($paging,$results);
        }

        public function diseaselists(){
        extract(generateRequestParamVars());
        $conditions=[];
        //0是删除 1是显示
        $conditions['status_delete']=1;
        $conditions['id']=$id;
        $diseaselists = $this->where($conditions)->find();

        $drug=json_decode($diseaselists['drug'],true);
        // var_dump($drug);
        $id=array();
        foreach ($drug as $key => $value) {
            $id[]=$value;
        }
        // // echo count($diseaselists[0]['drug']);
        // $countfun=count($diseaselists[0]['drug']);
        // // echo $countfun;
        //     $id=array();
        // for($i=0; $i<$countfun; $i++){
        //     // echo $i;
        //     // echo $diseaselists[0]['drug'][$i];
        //     $id[$i]=$diseaselists[0]['drug'][$i];
        //     var_dump($diseaselists[0]['drug'][$i]);
        //     // echo $diseaselists[0]['drug'][$i];
        // }

            // var_dump($id);
            $con=array();
            $con['id']=array('in',$id);
            $res=D('drugs')->where($con)->select();
            // echo $res;;
            // var_dump($res);

        // echo $diseaselists[0]['drug'][$i];
        // foreach ($diseaselists[0]['drug'] as $key => $value) {
        // }

        // foreach ($results as $k => $v) {
        //         $conditions=array();
        //         $conditions['id']=$results[$k]['body_position'];
        //         $result=D('BodyDisease')->where($conditions)->find();
        //         $positionname=$result['name'];
        //         $results[$k]['body_position']=$positionname;
        //     }
            // var_dump($diseaselists[0]['drug']);

        // var_dump($hotlists);
        return $res;
    }

        //商品子类别管理页面显示
        public function listspid(){
            extract(generateRequestParamVars());
            
            $conditions=array();
            //0是删除 1是显示
            $conditions['status_delete']=1;
            $conditions['pid']=$id;
            // echo $id;
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
            
            // //用户名赋给用户id
            // foreach ($results as $key => $value) {
            //     $conditions=array();
            //     $conditions['id']=$results[$key]['user_id'];
            //     $result=D('WechatUser')->where($conditions)->find();
            //     $nick_name=$result['nickname'];
            //     $results[$key]['user_id']=$nick_name;
            // }

            // //相册名字赋给相册id
            // foreach ($results as $k => $v) {
            //     $conditions=array();
            //     $conditions['id']=$results[$k]['album_id'];
            //     $result=D('PhotoAlbum')->where($conditions)->find();
            //     $albumname=$result['name'];
            //     $results[$k]['album_id']=$albumname;
            // }
            
            return array($paging,$results);
        }

    
        //修改类目
        public function raedit(){
            extract(generateRequestParamVars());
            $shuziyz='/^\d+$/';
            if($name && $first_letter){
                    $data=array();
                    $data['name']=$name;
                    $data['first_letter']=$first_letter;
                    $data['department']=$department;
                    $data['body_position']=$body_position;
                    $data['summarize']=$summarize;
                    $data['pathogenesis']=$pathogenesis;
                    $data['symptoms']=$symptoms;
                    $data['diagnosis']=$diagnosis;
                    $data['treatment']=$treatment;
                    $data['prevention']=$prevention;
                    $data['life']=$life;
                    $data['status_delete']=1;
                    $data['create_time']=time();
                    $conditions=array();
                    $conditions['id']=$id;
                    if(!$this->where($conditions)->save($data)){
                            echo $this->_sql();
                            throw new \Exception(L('OPERATION_FAILED'));
                        }
            }else{
                throw new \Exception(L('缺少数据'));
                }
        }

    //添加疾病
    public function adds(){
        extract(generateRequestParamVars());
        //正整数正则
        $shuziyz='/^\d+$/';
        $conditions=array();
        $conditions['status_delete']=1;
        $conditions['name']=$name; 
        $shenti=$this->where($conditions)->find();

        if($shenti){
            throw new \Exception(L('重复数据'));
        }else{
            if($name && $first_letter){
                $data=array();
                $data['name']=$name;
                $data['first_letter']=$first_letter;
                $data['department']=$department;
                $data['body_position']=$body_position;
                $data['summarize']=$summarize;
                $data['pathogenesis']=$pathogenesis;
                $data['symptoms']=$symptoms;
                $data['diagnosis']=$diagnosis;
                $data['treatment']=$treatment;
                $data['prevention']=$prevention;
                $data['life']=$life;
                $data['drug']="";
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

    public function adds2(){
        extract(generateRequestParamVars());
        //正整数正则
        $shuziyz='/^\d+$/';
        if($name && $content){
               $data=array();
                $data['pid']=$pid;
                $data['content']=$content;
                $data['name']=$name;
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

    public function photoview(){
            extract(generateRequestParamVars());
            $conditions=array();
            //0是删除 1是显示
            
            $conditions['status_delete']=1;
            $conditions['album_id']=$id;

            if(!$conditions){
                // echo "122212";
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
                $results=$this->order('create_time desc')->limit($page->firstRow.','.$page->listRows)->select();
            }else{
                $results=$this->where($conditions)->order('create_time desc')->limit($page->firstRow.','.$page->listRows)->select();
            }
            
            foreach ($results as $key => $value) {
                $conditions=array();
                $conditions['id']=$results[$key]['user_id'];
                $result=D('WechatUser')->where($conditions)->find();
                $nick_name=$result['nickname'];
                $results[$key]['user_id']=$nick_name;
            }

            //相册名字赋给相册id
            foreach ($results as $k => $v) {
                $conditions=array();
                $conditions['id']=$results[$k]['album_id'];
                $result=D('PhotoAlbum')->where($conditions)->find();
                $albumname=$result['name'];
                $results[$k]['album_id']=$albumname;
            }

            return array($paging,$results);
        }

        public function userphotoview(){
            extract(generateRequestParamVars());
            $conditions=array();
            //0是删除 1是显示
            
            $conditions['status_delete']=1;
            $conditions['user_id']=$id;

            if(!$conditions){
                // echo "122212";
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
                $results=$this->order('create_time desc')->limit($page->firstRow.','.$page->listRows)->select();
            }else{
                $results=$this->where($conditions)->order('create_time desc')->limit($page->firstRow.','.$page->listRows)->select();
            }
            
            foreach ($results as $key => $value) {
                $conditions=array();
                $conditions['id']=$results[$key]['user_id'];
                $result=D('WechatUser')->where($conditions)->find();
                $nick_name=$result['nickname'];
                $results[$key]['user_id']=$nick_name;
            }

            //相册名字赋给相册id
            foreach ($results as $k => $v) {
                $conditions=array();
                $conditions['id']=$results[$k]['album_id'];
                $result=D('PhotoAlbum')->where($conditions)->find();
                $albumname=$result['name'];
                $results[$k]['album_id']=$albumname;
            }

            return array($paging,$results);
        }

}