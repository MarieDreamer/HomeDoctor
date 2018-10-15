<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;

class DrugsController extends Controller{


    static $DRUGS_MODEL='drugs';
    static $DISEASE_MODEL='disease';
    static $DEPARTMENT_MODEL='department';
    static $BODYDISEASE_MODEL='BodyDisease';
    
    public function __construct(){
        // validateUnLoginRedirect();
        parent::__construct();
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
        list($paging, $results) = D(self::$DRUGS_MODEL)->lists();
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
            D(self::$DRUGS_MODEL)->dele();
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
        $result=D(self::$DRUGS_MODEL)->getResultByConditions(array('id'=>I('get.id')));
        // $result['content']=json_decode($result['content'],true);
        $this->assign('result',$result);
        $this->display();
    }


    //修改主科室
    public function raedit_do(){
        try{
            // echo "123123132";
            validateUnLoginRedirect();
            D(self::$DRUGS_MODEL)->raedit();
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
        // echo "string";
        validateUnLoginRedirect();
        $results=D(self::$DRUGS_MODEL)->get();
        // var_dump($keshi);
        $this->assign('results',$results);
        $this->display();
    }
    //添加科室
    public function adds_do(){
        try{
            validateUnLoginRedirect();
            D(self::$DRUGS_MODEL)->adds();
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

    //上传图片
    public function upload_do(){
        extract(generateRequestParamVars());

        /**
         * upload.php
         *
         * Copyright 2013, Moxiecode Systems AB
         * Released under GPL License.
         *
         * License: http://www.plupload.com/license
         * Contributing: http://www.plupload.com/contributing
         */

        #!! IMPORTANT:
        #!! this file is just an example, it doesn't incorporate any security checks and
        #!! is not recommended to be used in production environment as it is. Be sure to
        #!! revise it and customize to your needs.


        // Make sure file is not cached (as it happens for example on iOS devices)
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        echo $fileName;

        // Support CORS
        // header("Access-Control-Allow-Origin: *");
        // other CORS headers if any...
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit; // finish preflight CORS requests here
        }


        if ( !empty($_REQUEST[ 'debug' ]) ) {
            $random = rand(0, intval($_REQUEST[ 'debug' ]) );
            if ( $random === 0 ) {
                header("HTTP/1.0 500 Internal Server Error");
                exit;
            }
        }

        // header("HTTP/1.0 500 Internal Server Error");
        // exit;


        // 5 minutes execution time
        @set_time_limit(5 * 60);

        // Uncomment this one to fake upload time
        usleep(5000);

        // Settings
        // $targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
        $targetDir = C('CACHE_DIR').DIRECTORY_SEPARATOR.'Uploads'.DIRECTORY_SEPARATOR.'Tmps';
        $uploadDir = C('CACHE_DIR').DIRECTORY_SEPARATOR.'Uploads'.DIRECTORY_SEPARATOR.'Tmps'.DIRECTORY_SEPARATOR.date('Y').DIRECTORY_SEPARATOR.date('m').DIRECTORY_SEPARATOR.date('d');
        $uploadUrl = '/Uploads/Tmps/'.date('Y').'/'.date('m').'/'.date('d');

        //创建文件夹
        if(!is_dir($uploadDir)){
            @mkdir($uploadDir,0777,true);
        }

        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds

        // Create target dir
        if (!file_exists($targetDir)) {
            @mkdir($targetDir);
        }

        // Create target dir
        if (!file_exists($uploadDir)) {
            @mkdir($uploadDir);
        }

        // Get a file name
        if (isset($_REQUEST["name"])) {
            $fileName = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
            $fileName = $_FILES["file"]["name"];
        } else {
            $fileName = uniqid();
        }

        //$fileName = uniqid("file_").'.'.pathinfo($fileName, PATHINFO_EXTENSION);
        $extension=pathinfo($fileName, PATHINFO_EXTENSION);
        if($extension){
            $fileName = uniqid().'.'.$extension;
        }else{
            $fileName = uniqid();
        }

        $md5File = @file('md5list.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $md5File = $md5File ? $md5File : array();

        if (isset($_REQUEST["md5"]) && array_search($_REQUEST["md5"], $md5File ) !== FALSE ) {
            die('{"jsonrpc" : "2.0", "result" : null, "id" : "id", "exist": 1}');
        }

        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
        $uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;

        // Chunking might be enabled
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;
        // echo $_REQUEST["chunks"];
        // echo $_REQUEST["chunk"];

        // Remove old temp files
        if ($cleanupTargetDir) {
            if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
            }

            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

                // If temp file is current file proceed to the next
                if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
                    continue;
                }

                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }


        // Open temp file
        if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }

        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            }

            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        }

        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($out);
        @fclose($in);

        rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");

        $index = 0;
        $done = true;
        for( $index = 0; $index < $chunks; $index++ ) {
            if ( !file_exists("{$filePath}_{$index}.part") ) {
                $done = false;
                break;
            }
        }
        if ( $done ) {
            if (!$out = @fopen($uploadPath, "wb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            }

            if ( flock($out, LOCK_EX) ) {
                for( $index = 0; $index < $chunks; $index++ ) {
                    if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
                        break;
                    }

                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }

                    @fclose($in);
                    @unlink("{$filePath}_{$index}.part");
                }

                flock($out, LOCK_UN);
            }
            @fclose($out);
        }

        // Return Success JSON-RPC response
        //die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
        die($uploadUrl .'/'. $fileName);
    }

    

}

    