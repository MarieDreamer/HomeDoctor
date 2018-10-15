<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;

class OrderController extends Controller{

    static $ORDER_MODEL='Order';
    static $ADDRESS_MODEL='Address';
    static $DOCTOR_MODEL='Doctor';
    static $DEPARTMENT_MODEL='Department';
    public function __construct(){
        // validateUnLoginRedirect();
        parent::__construct();
    }
    //接单接口
    public function receipt_do(){
        try{
            D(self::$ORDER_MODEL)->receipt_do();
            $ajaxReturnData['status']=1;
            $ajaxReturnData['message']='操作成功';
        }catch(\Exception $e){
            $ajaxReturnData['status']=0;
            $ajaxReturnData['message']='操作失败'.$e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

    //订单显示
    public function lists(){
        try{
            $lists = D(self::$ORDER_MODEL)->lists();

            $ajaxReturnData['status']=1;
            $ajaxReturnData['message']='操作成功';
            $ajaxReturnData['lists']=$lists;
        }catch(\Exception $e){
            $ajaxReturnData['status']=0;
            $ajaxReturnData['message']='操作失败'.$e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

    //单给订单详细显示
    public function listsfind(){
        try{
            $lists = D(self::$ORDER_MODEL)->listsfind();

            $ajaxReturnData['status']=1;
            $ajaxReturnData['message']='操作成功';
            $ajaxReturnData['lists']=$lists;
        }catch(\Exception $e){
            $ajaxReturnData['status']=0;
            $ajaxReturnData['message']='操作失败'.$e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

    //用户添加订单
    public function adds_do(){
        try{
            D(self::$ORDER_MODEL)->adds();
            $ajaxReturnData['status']=1;
            $ajaxReturnData['message']='操作成功';
        }catch(\Exception $e){
            $ajaxReturnData['status']=0;
            $ajaxReturnData['message']='操作失败'.$e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

    
        
}

    