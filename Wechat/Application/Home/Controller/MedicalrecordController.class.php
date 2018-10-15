<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;

class MedicalrecordController extends Controller{

    static $MEDICALRECORD_MODEL='Medicalrecord';
    static $ORDER_MODEL='Order';
    static $ADDRESS_MODEL='Address';
    static $DOCTOR_MODEL='Doctor';
    static $DEPARTMENT_MODEL='Department';
    public function __construct(){
        // validateUnLoginRedirect();
        parent::__construct();
    }
    //医生提交病历
    public function resume_do(){
        try{
            D(self::$MEDICALRECORD_MODEL)->resume_do();
            $ajaxReturnData['status']=1;
            $ajaxReturnData['message']='操作成功';
            // $ajaxReturnData['lists3']=$lists3;
        }catch(\Exception $e){
            $ajaxReturnData['status']=0;
            $ajaxReturnData['message']='操作失败'.$e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

    public function resume_lists(){
        try{
            $lists=D(self::$MEDICALRECORD_MODEL)->resume_lists();
            $ajaxReturnData['status']=1;
            $ajaxReturnData['message']='操作成功';
            $ajaxReturnData['lists']=$lists;
        }catch(\Exception $e){
            $ajaxReturnData['status']=0;
            $ajaxReturnData['message']='操作失败'.$e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

    
        
}

    