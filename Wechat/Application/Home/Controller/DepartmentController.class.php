<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;

class DepartmentController extends Controller{


    // static $Photo_Album='PhotoAlbum';
    static $DEPARTMENT_MODEL='department';
    
    public function __construct(){
        // validateUnLoginRedirect();
        parent::__construct();
    }

    //查询科室
    public function departmentlists(){
        try{
            $departmentlists = D(self::$DEPARTMENT_MODEL)->departmentlists();

            $ajaxReturnData['status']=1;
            $ajaxReturnData['message']='操作成功';
            $ajaxReturnData['departmentlists']=$departmentlists;
        }catch(\Exception $e){
            $ajaxReturnData['status']=0;
            $ajaxReturnData['message']='操作失败'.$e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

    
    public function get_do(){
        try {
            // validateUnLoginRedirect();
            $results=D(self::$DEPARTMENT_MODEL)->getzi();
            $ajaxReturnData['status'] = 1;
            $ajaxReturnData['message'] = '操作成功';
            $ajaxReturnData['results'] = $results;
        } catch (\Exception $e) {
            $ajaxReturnData['status'] = 0; 
            $ajaxReturnData['message'] = '操作失败,' . $e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);

    }

    //类别管理页面显示
    public function lists(){
        extract(generateRequestParamVars());
        validateUnLoginRedirect();
        checkAccess('category','view');
        list($paging, $results) = D(self::$DEPARTMENT_MODEL)->lists();
        $this->assign('paging', $paging);
        $this->assign('results', $results);
        $this->display();
    }

    //子类别管理页面显示
    public function listspid(){
        extract(generateRequestParamVars());
        validateUnLoginRedirect();
        checkAccess('category','view');
        list($paging, $results) = D(self::$DEPARTMENT_MODEL)->listspid();
        $this->assign('paging', $paging);
        $this->assign('results', $results);
        $this->display();
    }
    

    //图片删除
    public function dele_do(){
        try{
            // echo "123";
            validateUnLoginRedirect();
            D(self::$DEPARTMENT_MODEL)->dele();
            $ajaxReturnData['status']=1;
            $ajaxReturnData['message']='操作成功';
        }catch(\Exception $e){
            $ajaxReturnData['status']=0;
            $ajaxReturnData['message']='操作失败'.$e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

    //修改主科室页面显示
    public function raedit(){
        validateUnLoginRedirect();
        checkAccess('category','edit');
        $result=D(self::$DEPARTMENT_MODEL)->getResultByConditions(array('id'=>I('get.id')));
        // $result['content']=json_decode($result['content'],true);
        $this->assign('result',$result);
        $this->display();
    }

    public function raedit2(){
        validateUnLoginRedirect();
        checkAccess('category','edit');
        $result=D(self::$DEPARTMENT_MODEL)->getResultByConditions(array('id'=>I('get.id')));
        $result['content']=json_decode($result['content'],true);
        $this->assign('result',$result);
        $this->display();
    }


    //修改主科室
    public function raedit_do(){
        try{
            // echo "123123132";
            validateUnLoginRedirect();
            checkAjaxAccess('category','raedit');
            D(self::$DEPARTMENT_MODEL)->raedit();
            $ajaxReturnData['status']=1;
            $ajaxReturnData['message']='操作成功';
        }catch(\Exception $e){
            $ajaxReturnData['status']=0;
            $ajaxReturnData['message']='操作失败'.$e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

    //添加科室页显示
    public function adds(){
        validateUnLoginRedirect();
        checkAccess('category','adds');
        $this->display();
    }
    //添加科室
    public function adds_do(){
        try{
            validateUnLoginRedirect();
            D(self::$DEPARTMENT_MODEL)->adds();
            $ajaxReturnData['status']=1;
            $ajaxReturnData['message']='操作成功';
        }catch(\Exception $e){
            $ajaxReturnData['status']=0;
            $ajaxReturnData['message']='操作失败'.$e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

    //添加子科室
    public function adds_do2(){
        try{
            validateUnLoginRedirect();
            D(self::$DEPARTMENT_MODEL)->adds2();
            $ajaxReturnData['status']=1;
            $ajaxReturnData['message']='操作成功';
        }catch(\Exception $e){
            $ajaxReturnData['status']=0;
            $ajaxReturnData['message']='操作失败'.$e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

    

}

    