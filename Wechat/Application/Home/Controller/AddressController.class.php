<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;

class AddressController extends Controller{

    static $ADDRESS_MODEL='Address';
    static $DOCTOR_MODEL='Doctor';
    static $DEPARTMENT_MODEL='Department';
    public function __construct(){
        // validateUnLoginRedirect();
        parent::__construct();
    }

    //修改地址
    public function modify_do(){
    try{
        D(self::$ADDRESS_MODEL)->modify_do();
        $ajaxReturnData['status']=1;
        $ajaxReturnData['message']='操作成功';
    }catch(\Exception $e){
        $ajaxReturnData['status']=0;
        $ajaxReturnData['message']='操作失败'.$e->getMessage();
    }
    $this->ajaxReturn($ajaxReturnData);
    }
    //取保存的地址
    public function lists(){
    try{
        $lists = D(self::$ADDRESS_MODEL)->lists();

        $ajaxReturnData['status']=1;
        $ajaxReturnData['message']='操作成功';
        $ajaxReturnData['lists']=$lists;
    }catch(\Exception $e){
        $ajaxReturnData['status']=0;
        $ajaxReturnData['message']='操作失败'.$e->getMessage();
    }
    $this->ajaxReturn($ajaxReturnData);
    }

    //修改默认地址
    public function defaultchange(){
    try{
        D(self::$ADDRESS_MODEL)->defaultchange();
        $ajaxReturnData['status']=1;
        $ajaxReturnData['message']='操作成功';
    }catch(\Exception $e){
        $ajaxReturnData['status']=0;
        $ajaxReturnData['message']='操作失败'.$e->getMessage();
    }
    $this->ajaxReturn($ajaxReturnData);
    }

    //添加地址
    public function adds_do(){
    try{
        D(self::$ADDRESS_MODEL)->adds();
        $ajaxReturnData['status']=1;
        $ajaxReturnData['message']='操作成功';
    }catch(\Exception $e){
        $ajaxReturnData['status']=0;
        $ajaxReturnData['message']='操作失败'.$e->getMessage();
    }
    $this->ajaxReturn($ajaxReturnData);
    }

    //删除地址
    public function deletefun(){
    try{
        D(self::$ADDRESS_MODEL)->deletefun();
        $ajaxReturnData['status']=1;
        $ajaxReturnData['message']='操作成功';
    }catch(\Exception $e){
        $ajaxReturnData['status']=0;
        $ajaxReturnData['message']='操作失败'.$e->getMessage();
    }
    $this->ajaxReturn($ajaxReturnData);
    }

    
        
}

    