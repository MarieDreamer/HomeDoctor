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


    public function lists(){ 
        extract(generateRequestParamVars());
        validateUnLoginRedirect();
        list($paging, $results) = D(self::$DOCTOR_MODEL)->lists();
        foreach ($results as $key => $value) {
            $department_name=D(self::$DEPARTMENT_MODEL)->department_get($results[$key]['department_id']);
            $results[$key]['department_name']=$department_name;
        }
        $this->assign('paging', $paging);
        $this->assign('results', $results);
        $this->display();
    }
    public function adds(){ 
        extract(generateRequestParamVars());
        $keshi=D(self::$DEPARTMENT_MODEL)->get();
        $this->assign('keshi',$keshi);
        $this->display();
    }
    public function edit(){ 
        extract(generateRequestParamVars());
        $results=D(self::$DOCTOR_MODEL)->edit();
        $keshi=D(self::$DEPARTMENT_MODEL)->get();
        $this->assign('result',$results);
        $this->assign('keshi',$keshi);
        $this->display();
    }
    public function adds_do(){
        try {
            D(self::$DOCTOR_MODEL)->adds_do();
            $ajaxReturnData['status'] = 1;
            $ajaxReturnData['message'] = '操作成功';
        } catch (\Exception $e) {
            $ajaxReturnData['status'] = 0; 
            $ajaxReturnData['message'] = '操作失败,' . $e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }
    public function edit_do(){
        try {
            D(self::$DOCTOR_MODEL)->edit_do();
            $ajaxReturnData['status'] = 1;
            $ajaxReturnData['message'] = '操作成功';
        } catch (\Exception $e) {
            $ajaxReturnData['status'] = 0; 
            $ajaxReturnData['message'] = '操作失败,' . $e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }
    public function delete_do(){
        try {
            D(self::$DOCTOR_MODEL)->delete_do();
            $ajaxReturnData['status'] = 1;
            $ajaxReturnData['message'] = '操作成功';
        } catch (\Exception $e) {
            $ajaxReturnData['status'] = 0; 
            $ajaxReturnData['message'] = '操作失败,' . $e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }
        
}

    