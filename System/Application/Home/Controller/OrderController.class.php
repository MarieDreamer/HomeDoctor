<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;

class OrderController extends Controller{


    static $ORDER_MODEL='order';
    static $DEPARTMENT_MODEL='department';
    
    public function __construct(){
        // validateUnLoginRedirect();
        parent::__construct();
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

    //订单管理页面显示
    public function lists(){
        extract(generateRequestParamVars());
        validateUnLoginRedirect();
        list($paging, $results) = D(self::$ORDER_MODEL)->lists();
        $this->assign('paging', $paging);
        $this->assign('results', $results);
        $this->display();
    }

    

    //删除订单
    public function dele_do(){
        try{
            // echo "123";
            validateUnLoginRedirect();
            D(self::$ORDER_MODEL)->dele();
            $ajaxReturnData['status']=1;
            $ajaxReturnData['message']='操作成功';
        }catch(\Exception $e){
            $ajaxReturnData['status']=0;
            $ajaxReturnData['message']='操作失败'.$e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

    //修改订单页面显示
    public function raedit(){
        validateUnLoginRedirect();
        checkAccess('category','edit');
        $result=D(self::$ORDER_MODEL)->getResultByConditions(array('id'=>I('get.id')));
        // $result['content']=json_decode($result['content'],true);
        $this->assign('result',$result);
        $this->display();
    }


    //修改主科室
    public function raedit_do(){
        try{
            // echo "123123132";
            validateUnLoginRedirect();
            checkAjaxAccess('category','raedit');
            D(self::$ORDER_MODEL)->raedit();
            $ajaxReturnData['status']=1;
            $ajaxReturnData['message']='操作成功';
        }catch(\Exception $e){
            $ajaxReturnData['status']=0;
            $ajaxReturnData['message']='操作失败'.$e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

    //添加订单页显示
    public function adds(){
        validateUnLoginRedirect();
        $this->display();
    }
    //添加订单
    public function adds_do(){
        try{
            validateUnLoginRedirect();
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

    