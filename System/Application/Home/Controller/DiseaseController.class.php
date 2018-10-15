<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;

class DiseaseController extends Controller{


    // static $Photo_Album='PhotoAlbum';
    static $DISEASE_MODEL='disease';
    static $DEPARTMENT_MODEL='department';
    static $BODYDISEASE_MODEL='BodyDisease';
    
    public function __construct(){
        // validateUnLoginRedirect();
        parent::__construct();
    }

    public function diseaselists(){
        validateUnLoginRedirect();
        try{
            $diseaselists = D(self::$DISEASE_MODEL)->diseaselists();

            $ajaxReturnData['status']=1;
            $ajaxReturnData['message']='操作成功';
            $ajaxReturnData['diseaselists']=$diseaselists;
        }catch(\Exception $e){
            $ajaxReturnData['status']=0;
            $ajaxReturnData['message']='操作失败'.$e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

    
    public function get_do(){
        try {
            // validateUnLoginRedirect();
            $results=D(self::$CATEGORY_MODEL)->getzi();
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
        // echo "string";
        extract(generateRequestParamVars());
        validateUnLoginRedirect();
        list($paging, $results) = D(self::$DISEASE_MODEL)->lists();
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
    

    //部位删除
    public function dele_do(){
        try{
            // echo "123";
            validateUnLoginRedirect();
            D(self::$BODYDISEASE_MODEL)->dele();
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
        $result=D(self::$DISEASE_MODEL)->getResultByConditions(array('id'=>I('get.id')));
        $keshi=D(self::$DEPARTMENT_MODEL)->get();
        $shenti=D(self::$BODYDISEASE_MODEL)->getzi();
        // $result['content']=json_decode($result['content'],true);
        $this->assign('result',$result);
        $this->assign('keshi',$keshi);
        $this->assign('shenti',$shenti);
        $this->display();
    }

    //添加修改药品
    public function raeditdrug(){
        validateUnLoginRedirect();
        $result=D(self::$DISEASE_MODEL)->getResultByConditions(array('id'=>I('get.id')));
        list($paging, $results) = D(self::$DISEASE_MODEL)->listsdrug();
        $this->assign('paging', $paging);
        $this->assign('result',$result);
        $this->display();
    }


    //修改主科室
    public function raedit_do(){
        try{
            // echo "123123132";
            validateUnLoginRedirect();
            D(self::$DISEASE_MODEL)->raedit();
            $ajaxReturnData['status']=1;
            $ajaxReturnData['message']='操作成功';
        }catch(\Exception $e){
            $ajaxReturnData['status']=0;
            $ajaxReturnData['message']='操作失败'.$e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

    //添加病理页显示
    public function adds(){
        // echo "string";
        validateUnLoginRedirect();
        $keshi=D(self::$DEPARTMENT_MODEL)->get();
        $shenti=D(self::$BODYDISEASE_MODEL)->getzi();
        // var_dump($keshi);
        $this->assign('keshi',$keshi);
        $this->assign('shenti',$shenti);
        $this->display();
    }

    //添加病理
    public function adds_do(){
        try{
            validateUnLoginRedirect();
            D(self::$DISEASE_MODEL)->adds();
            $ajaxReturnData['status']=1;
            $ajaxReturnData['message']='操作成功';
        }catch(\Exception $e){
            $ajaxReturnData['status']=0;
            $ajaxReturnData['message']='操作失败'.$e->getMessage();
        }
        $this->ajaxReturn($ajaxReturnData);
    }

    //添加药品
    public function choiceadd_do(){
        try{
            validateUnLoginRedirect();
            D(self::$DISEASE_MODEL)->choiceadd_do();
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

    