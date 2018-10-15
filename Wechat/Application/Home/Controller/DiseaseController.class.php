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

    // 筛选查找
    public function lists(){
        try{
            $lists = D(self::$DISEASE_MODEL)->lists();

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

    