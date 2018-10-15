<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;

class PushController extends Controller{

    static $PUSH_MODEL='Push';
    public function __construct(){
        // validateUnLoginRedirect();
        parent::__construct();
    }


    //用户添加订单
    public function adds_do(){
        try{
            D(self::$PUSH_MODEL)->adds();
            $ajaxReturnData['status']=1;
            $ajaxReturnData['message']='操作成功';
        }catch(\Exception $e){
            $ajaxReturnData['status']=0;
            $ajaxReturnData['message']='操作失败'.$e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

    
        
}

    