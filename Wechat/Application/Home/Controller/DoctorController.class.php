<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;

class DoctorController extends Controller{


    static $DOCTOR_MODEL='Doctor';
    static $DEPARTMENT_MODEL='Department';
    public function __construct(){
        // validateUnLoginRedirect();
        parent::__construct();
    }

     public function doctorlists(){
    try{
        $lists = D(self::$DOCTOR_MODEL)->doctorlists();
        $ajaxReturnData['status']=1;
        $ajaxReturnData['message']='操作成功';
        $ajaxReturnData['lists']=$lists;
    }catch(\Exception $e){
        $ajaxReturnData['status']=0;
        $ajaxReturnData['message']='操作失败'.$e->getMessage();
    }
    $this->ajaxReturn($ajaxReturnData);
    }
    
    //取所有医生
    public function lists(){
    try{
        $lists = D(self::$DOCTOR_MODEL)->lists();

        $ajaxReturnData['status']=1;
        $ajaxReturnData['message']='操作成功';
        $ajaxReturnData['lists']=$lists;
    }catch(\Exception $e){
        $ajaxReturnData['status']=0;
        $ajaxReturnData['message']='操作失败'.$e->getMessage();
    }
    $this->ajaxReturn($ajaxReturnData);
    }

    
    public function weixin_bind(){
        try {
            D(self::$DOCTOR_MODEL)->weixin_bind();
            $ajaxReturnData['status'] = 1;
            $ajaxReturnData['message'] = '操作成功';
        } catch (\Exception $e) {
            $ajaxReturnData['status'] = 0; 
            $ajaxReturnData['message'] = '操作失败,' . $e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

        
}

    