<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;

class AbcController extends Controller{


    // static $Photo_Album='PhotoAlbum';
    static $Abc_MODEL='abc';
    
    public function __construct(){
        // validateUnLoginRedirect();
        parent::__construct();
    }

    //查询科室
    public function lists(){
        try{
            $lists = D(self::$Abc_MODEL)->departmentlists();

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

    