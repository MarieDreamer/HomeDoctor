<?php
namespace Home\Model;
use Home\Abstracts\CommonMAbstract;

class OrderModel extends CommonMAbstract {


    public function get(){
        // extract(generateRequestParamVars());
        $conditions=array();
        $conditions['status_delete']=1;//屏蔽逻辑删除
        $results=$this->where($conditions)->select();
        return $results;
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

    
        //修改订单
        public function raedit(){
            extract(generateRequestParamVars());
            //正整数正则
            $shuziyz='/^\d+$/';
            if(!preg_match('/^[1][1,2,3,4,5,7,8,9,0][0-9]{9}$/',$phone)){
                    throw new \Exception(L('手机号格式有误！'));
                }else{
                    if($doctorid && $disease && $phone && $name && $name){
                    $data=array();
                    $data['doctorid']=$doctorid;
                    $data['disease']=$disease;
                    $data['phone']=$phone;
                    $data['name']=$name;
                    $data['addres']=$addres;
                    $conditions=array();
                    $conditions['id']=$id;
                        if(!$this->where($conditions)->save($data)){
                            // echo $this->_sql();
                            throw new \Exception(L('OPERATION_FAILED'));
                        }
                    }else{
                        throw new \Exception(L('缺少数据'));
                        }

                }
            
        }

    //添加科室
    public function adds(){
        extract(generateRequestParamVars());
        //正整数正则
        $shuziyz='/^\d+$/';
        if(!preg_match('/^[1][1,2,3,4,5,7,8,9,0][0-9]{9}$/',$phone)){
            throw new \Exception(L('手机号格式有误！'));
        }else{
            if($doctorid && $disease && $phone && $name && $addres){
                $data=array();
                $data['doctorid']=$doctorid;
                $data['disease']=$disease;
                $data['phone']=$phone;
                $data['name']=$name;
                $data['addres']=$addres;
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


}