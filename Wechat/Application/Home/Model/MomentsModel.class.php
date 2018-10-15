<?php
namespace Home\Model;
use Home\Abstracts\CommonMAbstract;

class MomentsModel extends CommonMAbstract {

    static $WECHAT_USER = 'wechat_user';
    static $DOCTOR_MODEL = 'doctor';
    static $PRAISE_MODEL = 'praise';

    public function adds()
    {
        extract(generateRequestParamVars());
        $user = D(self::$WECHAT_USER)->find($user_id);

        $data = [];
        $data['user_id'] = $user_id;
        //如果是评论
        if($m_id){
            $data['pid'] = $m_id;
            if($this->where('id='.$m_id)->setInc('comment_num') === false){
                throw new \Exception('OPERATION_FAILED');
            }
        }
        $data['user_name'] = $user['nickname'];
        $data['user_img'] = $user['imageurl'];
        $data['content'] = $content;
        $data['images'] = $images;
        //如果是转发
        if($fid){
            $data['fid'] = $fid;
            $f = $this->find($fid);
            if($f['fid']){
                $data['fid'] .= '_' . $f['fid'];
            }
            $d = explode("_",$data['fid']);
            for($i=0;$i<count($d);$i++){
                $a = [];
                $a['id'] = $d[$i];
                $this->where($a)->setInc('forward_num');
            }
        }
        $data['create_time'] = time();

        if ($this->add($data) === false) {
            throw new \Exception('OPERATION_FAILED');
        }
        
    }

    public function community()
    {
        extract(generateRequestParamVars());
        $numPerPage=6;
        $page=new \Think\Page($count,$numPerPage);
        $paging=$page->show();
        $conditions = [];
        $conditions['pid'] = 0;

        if($tag == 0){      //关注
            $user = D(self::$WECHAT_USER)->find($user_id);
            $follow = explode("_",$user['follow']);
            $conditions['user_id'] = array('in',$follow);
            $moments = $this->where($conditions)->order('create_time desc')
            ->limit($page->firstRow.','.$page->listRows)->select();    
        }else if($tag == 1){    //推荐
            $docor = D(self::$DOCTOR_MODEL)->select();
            $doctor_id = [];
            for($i = 0; $i < count($docor); $i ++){
                array_push($doctor_id,$docor[$i]['user_id']);
            }
            $conditions['user_id'] = array('in',$doctor_id);
            $moments = $this->where($conditions)->order('create_time desc')
            ->limit($page->firstRow.','.$page->listRows)->select();
        }else if($tag == 2){        //热榜
            $moments = $this->where($conditions)->order('like_num desc,comment_num desc,create_time desc')
            ->limit($page->firstRow.','.$page->listRows)->select();    
        }else if($tag == 3){    //我的动态
            $conditions['user_id'] = $user_id;
            $moments = $this->where($conditions)->order('create_time desc')
            ->limit($page->firstRow.','.$page->listRows)->select();    
        }else if($tag == 4){    //评论
            $conditions['pid'] = $moments_id;
            $moments = $this->where($conditions)->order('create_time desc')
            ->limit($page->firstRow.','.$page->listRows)->select();
        }
        
        for($i = 0 ; $i < $numPerPage ; $i ++){
            if(!$moments[$i]){
                return $moments;
            }
            $moments[$i]['images'] = json_decode($moments[$i]['images'],true);
            $moments[$i]['create_time'] = convertDate($moments[$i]['create_time']);
            //是否点赞过
            $c2 = [];
            $c2['user_id'] = $user_id;
            $c2['moments_id'] = $moments[$i]['id'];
            if(D(self::$PRAISE_MODEL)->where($c2)->find()){
                $moments[$i]['isLike'] = 1;
            }else{
                $moments[$i]['isLike'] = 2;
            }
            //是否医生
            $c3 = [];
            $c3['user_id'] = $moments[$i]['user_id'];
            $docor = D(self::$DOCTOR_MODEL)->where($c3)->find();
            if($docor){
                $moments[$i]['doctor_title'] = $docor['hospital'] . '' . $docor['grade'];
            }else{
                $moments[$i]['doctor_title'] = '';
            }
            //转发内容
            if($moments[$i]['fid']){
                $moments[$i]['f_con'] = [];
                $fids = explode("_",$moments[$i]['fid']);
                if(count($fids) != 1){
                    for($j=0; $j<count($fids)-1; $j++){
                        $a = $this->find($fids[$j]);
                        array_push($moments[$i]['f_con'],[$a['user_name'],$a['content']]);
                    }
                }
                $moments[$i]['forward'] = $this->find($fids[count($fids)-1]);
                $moments[$i]['forward']['images'] = json_decode($moments[$i]['forward']['images'],true);
            }
            
        }
        return $moments;
    }
    //点赞
    public function like(){
        extract(generateRequestParamVars());
        $conditions = [];
        $conditions['moments_id'] = $moments_id;
        $conditions['user_id'] = $user_id;
        if(D(self::$PRAISE_MODEL)->where($conditions)->find()){
            //取消点赞
            if(D(self::$PRAISE_MODEL)->where($conditions)->delete() === false){
                throw new \Exception('DELETE_FAILED');
            }
            $this->where('id='.$moments_id)->setDec('like_num');
        }else{ 
            //点赞
            $conditions['create_time'] = time();
            if(D(self::$PRAISE_MODEL)->add($conditions) === false){
                throw new \Exception('ADD_FAILED');
            }
            $this->where('id='.$moments_id)->setInc('like_num');
        }
    }

}