<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;

class WechatUserController extends Controller{

    static $WECHAT_USER='wechat_user';
    
    public function __construct(){
        // validateUnLoginRedirect();
        parent::__construct();
    }

    public function login_do()
    {
        try{
            extract(generateRequestParamVars());
            $result=GetWechatOpenId($code);
            $conditions = array();
            $conditions['openid'] = $result['openid'];
            //判断用户是否存在
            if (!$user=D(self::$WECHAT_USER)->where($conditions)->find()) {
                //用户不存在，创建用户，没有详细信息
                $userid = D(self::$WECHAT_USER)->adds_do($result['openid']);
                $conditions = array();
                $conditions['id'] = $userid;
                $user = D(self::$WECHAT_USER)->where($conditions)->find();
            }
            else{
                //用户存在，修改登录时间
                D(self::$WECHAT_USER)->login_do($user['id']);//用户登录
            }

            $data=array();
            $data['userid']=$user['id'];
            $data['username']=$user['nickname'];
            $data['userimage']=$user['imageurl'];
            $data['is_doctor']=$user['is_doctor'];

            $ajaxReturnData['status'] = 1;
            $ajaxReturnData['message'] = '成功';
            $ajaxReturnData['data'] = $data;

        }catch (\Exception $e){
            $ajaxReturnData['status'] = 0;
            $ajaxReturnData['message'] = '失败';
        }
        $this->ajaxReturn($ajaxReturnData);
    }

    public function save_do()
    {
        try{
            D(self::$WECHAT_USER)->save_do();//保存用户信息
            $ajaxReturnData['status']=1;
            $ajaxReturnData['message']='操作成功';
        }catch(\Exception $e){
            $ajaxReturnData['status']=0;
            $ajaxReturnData['message']='操作失败'.$e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

    public function share_do()
    {
        try{
            D(self::$WECHAT_USER)->share_do();
            $ajaxReturnData['status']=1;
            $ajaxReturnData['message']='操作成功';
        }catch(\Exception $e){
            $ajaxReturnData['status']=0;
            $ajaxReturnData['message']='操作失败'.$e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

    public function getFollow()
    {
        try{
            $data = D(self::$WECHAT_USER)->getFollow();
            $ajaxReturnData['status']=1;
            $ajaxReturnData['message']='操作成功';
            $ajaxReturnData['data']=$data;
        }catch(\Exception $e){
            $ajaxReturnData['status']=0;
            $ajaxReturnData['message']='操作失败'.$e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

    public function getUser()
    {
        try{
            $data = D(self::$WECHAT_USER)->getUser();
            $ajaxReturnData['status']=1;
            $ajaxReturnData['message']='操作成功';
            $ajaxReturnData['data']=$data;
        }catch(\Exception $e){
            $ajaxReturnData['status']=0;
            $ajaxReturnData['message']='操作失败'.$e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

}

    