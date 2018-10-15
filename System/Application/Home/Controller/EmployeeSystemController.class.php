<?php

namespace Home\Controller;

use Think\Controller;
use Think\Model;

class EmployeeSystemController extends Controller
{

    static $EMPLOYEE_SYSTEM_MODEL = 'EmployeeSystem';
    // static $EMPLOYEE_SHOP_MODEL = 'EmployeeShop';
    // static $DEPARTMENT_MODEL = 'Department';
    // static $ROLE_MODEL = 'Role';
    // static $Photo_Album='PhotoAlbum';
    public function login()
    {
        // phpinfo();
        // validateIsLoginRedirect();
        $this->display();
    }

    public function logout()
    {
        validateUnLoginRedirect();
        session('[destroy]');
        redirect('/');
        exit();
    }

    public function adds()
    {
        validateUnLoginRedirect();
        checkAccess('employee','add');
        //取department表中的数据
        $department=D(self::$DEPARTMENT_MODEL)->get();
        $this->assign('department',$department);
        //取Role表中的数据
        $role=D(self::$ROLE_MODEL)->get();
        $this->assign('role',$role);
        //  $departments=D(self::$DEPARTMENT_MODEL)->getResults();
            // $this->assign('departments',$departments);
        $this->display();
        
    }

    public function edit()
    {
        validateUnLoginRedirect();
        checkAccess('employee','edit');
        //取department表中的数据
        $department=D(self::$DEPARTMENT_MODEL)->get();
        $this->assign('department',$department);
        //取Role表中的数据
        $role=D(self::$ROLE_MODEL)->get();
        $this->assign('role',$role);
        //	$departments=D(self::$DEPARTMENT_MODEL)->getResults();
        $result = D(self::$EMPLOYEE_SYSTEM_MODEL)->getResultByConditions(array('id' => I('get.id')));
        //	$this->assign('departments',$departments);
        
        $this->assign('result', $result);
        $this->display();
    }

    public function password_reset()
    {
        validateUnLoginRedirect();
        $result = D(self::$EMPLOYEE_SYSTEM_MODEL)->getResultById(I('get.id'));
        $this->assign('result', $result);
        $this->display();
    }

    public function password_edit()
    {   
        validateUnLoginRedirect();
        checkAccess('password_edit','view');
        $this->display();
    }

    
    public function lists()
    {
        validateUnLoginRedirect();
        checkAccess('employee','view');
        //  $departments=D(self::$DEPARTMENT_MODEL)->getResults();
        // lists.html 传输部门和角色
        list($paging, $results) = D(self::$EMPLOYEE_SYSTEM_MODEL)->lists();
        foreach ($results as $key => $value) {
            $conditions=array();
            $conditions['id']= $value['department_id'];
            if($department=D(self::$DEPARTMENT_MODEL)->where($conditions)->find()){
                $results[$key]['department_name']=$department['name'];
            }

            $conditions=array();
            $conditions['id']= $value['role_id'];
            if($role=D(self::$ROLE_MODEL)->where($conditions)->find()){
                $results[$key]['role_name']=$role['name'];
            }
        }
        //  $this->assign('departments',$departments);
        $this->assign('paging', $paging);
        $this->assign('results', $results);
        
        $this->display();
        
    }

    public function login_do()
    {
        try {

            // ifNotAjaxPostException();
            extract(generateRequestParamVars());
            // $params = validateAccountExists($account);
            D(self::$EMPLOYEE_SYSTEM_MODEL)->login();
            $module_name = 'Home';
            session('login_success_request_url', C('SITE_URL') . '/' . $module_name . '/Index/index');
            session('wechat_platform_id', 1);
            $ajaxReturnData['status'] = 1;
            $ajaxReturnData['login_success_request_url'] = session('login_success_request_url');
            $ajaxReturnData['message'] = "登陆成功！";
        } catch (\Exception $e) {
            $ajaxReturnData['status'] = 0;
            $ajaxReturnData['message'] = '操作失败,' . $e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

    public function adds_do()
    {
        try {
            validateUnLoginRedirect();
            D(self::$EMPLOYEE_SYSTEM_MODEL)->adds();
            $ajaxReturnData['status'] = 1;
            $ajaxReturnData['message'] = '操作成功';
        } catch (\Exception $e) {
            $ajaxReturnData['status'] = 0;
            $ajaxReturnData['message'] = '操作失败,' . $e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

    public function edit_do()
    {
        try {
            validateUnLoginRedirect();
            D(self::$EMPLOYEE_SYSTEM_MODEL)->edit();
            $ajaxReturnData['status'] = 1;
            $ajaxReturnData['message'] = '操作成功';
        } catch (\Exception $e) {
            $ajaxReturnData['status'] = 0;
            $ajaxReturnData['message'] = '操作失败,' . $e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

    public function password_reset_do()
    {
        try {
            validateUnLoginRedirect();
            D(self::$EMPLOYEE_SYSTEM_MODEL)->password_reset();
            $ajaxReturnData['statusCode'] = 200;
            $ajaxReturnData['message'] = '操作成功';
            $ajaxReturnData['callbackType'] = 'closeCurrent';
        } catch (\Exception $e) {
            $ajaxReturnData['statusCode'] = 300;
            $ajaxReturnData['message'] = '操作失败,' . $e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

    public function password_edit_do()
    {
        try {
            ifNotAjaxPostException();
             // echo "1212";
            validateUnLoginRedirect();
            D(self::$EMPLOYEE_SYSTEM_MODEL)->password_edit();
            $ajaxReturnData['status'] = 1;
            $ajaxReturnData['message'] = '操作成功';
        } catch (\Exception $e) {
            $ajaxReturnData['status'] = 0;
            $ajaxReturnData['message'] = '操作失败,' . $e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

    public function dele_do()
    {
        try {
            validateUnLoginRedirect();
            checkAjaxAccess('employee','delete');
            D(self::$EMPLOYEE_SYSTEM_MODEL)->dele();
            $ajaxReturnData['status'] = 1;
            $ajaxReturnData['message'] = '操作成功';
        } catch (\Exception $e) {
            $ajaxReturnData['status'] = 0;
            $ajaxReturnData['message'] = '操作失败,' . $e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

    public function batch_dele_do(){
        extract(generateRequestParamVars());        
        header("Cache-control:no-cache,no-store,must-revalidate");
        header("Pragma:no-cache");
        header("Expires:0");
        $model = new Model();
        $model->startTrans();
        
        $flag=false;

        try{
            validateUnLoginRedirect();
            checkAjaxAccess('employee','delete');
            D(self::$EMPLOYEE_SYSTEM_MODEL)->batch_dele();
            $ajaxReturnData['statusCode']=200;
            $ajaxReturnData['message']='操作成功';
            $ajaxReturnData['callbackType']='';

            $flag=true;
        }catch(\Exception $e){
            $ajaxReturnData['statusCode']=300;
            $ajaxReturnData['message']='操作失败'.$e->getMessage();

            $flag=false;
        }

        if($flag){
            $status=1;
            $model->commit();
        }else{
            $status=0;
            $model->rollback();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

}