<?php
/*
异常信息处理
*/
function filterExceptionMessage($e, $message)
{
    $json = json_decode($e->getMessage());
    if (!is_null($json)) {
        return $json->errors;
    }
    if (!$message) {
        $message = '操作失败';
    }
    return $message;
}

function checkAccess($session_name,$operation)
{
    if(session($session_name)[$operation] == '1'){
        // exit('<script> alert("无权限操作");window.location="'. $redirect .'"</script>');
        exit('<script> alert("无权限操作");window.history.go(-1);</script>');
    }

}

function checkAjaxAccess($session_name,$operation)
{
    if (session($session_name)[$operation] == '1') {
        throw new \Exception("无权操作");
    }
}

function ajaxReturnJsonp($data)
{
    header('Content-Type:application/json; charset=utf-8');
    $handler = isset($_GET[C('VAR_JSONP_HANDLER')]) ? $_GET[C('VAR_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');
    exit($handler . '(' . json_encode($data) . ');');
}

function CommonQrCodeGenerator($url, $size)
{
    if (!$url) {
        throw new \Exception('URL NULL');
    }
    if (!$ecc = I('param.ecc')) {
        $ecc = 'H';
    }
    $errorCorrectionLevel = $ecc;
    if (!$size = intval(I('param.size'))) {
        $size = 10;
    }
    $matrixPointSize = $size;
    require(C('APPLICATION_DIR') . DIRECTORY_SEPARATOR . 'Home' . DIRECTORY_SEPARATOR . 'Widget' . DIRECTORY_SEPARATOR . 'CommonQrCodeGenerator' . DIRECTORY_SEPARATOR . 'qrlib.php');
    $filename = 'CommonQrCodeStorage' . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . uniqid() . '.png';
    $file = C('CACHE_DIR') . DIRECTORY_SEPARATOR . $filename;
    $dir_path = dirname($file);
    if (!is_dir($dir_path)) {
        mkdir($dir_path, 0777, true);
    }
    \QRcode::png($url, $file, $errorCorrectionLevel, $matrixPointSize);
    return $filename;


    //        $logo='Images/logo.png';
    //        $qrcode='Images/qrcode.jpg';
    //        $QR = imagecreatefrompng($file);
    //        if($logo !== FALSE){
    //            $logo = imagecreatefromstring(file_get_contents($logo));
    //            $QR_width = imagesx($QR);
    //            $QR_height = imagesy($QR);
    //            $logo_width = imagesx($logo);
    //            $logo_height = imagesy($logo);
    //            $logo_qr_width = $QR_width / 5;
    //            $scale = $logo_width / $logo_qr_width;
    //            $logo_qr_height = $logo_height / $scale;
    //            $from_width = ($QR_width - $logo_qr_width) / 2;
    //            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
    //        }
    //        //header('Content-type: image/png');
    //        imagepng($QR,$qrcode);
    //        imagedestroy($QR);
}

/*
function logout(){
	session('employee_system_id',null);
	session('employee_system_username',null);
	session('employee_system_account_creator_id',null);
}
*/

function validateIsLoginRedirect()
{
    if (!(session('employee_system_id') || session('employee_shop_id'))) {
    }
}

function validateUnLoginRedirect($parent = 0)
{
    if (!(session('employee_system_id') || session('employee_shop_id'))) {
        // if($parent){
        exit('<script type="text/javascript">parent.location.href="' . C('EMPLOYEE_SYSTEM_LOGIN') . '";</script>');
        // }else{
        // 	exit('<script type="text/javascript">location.href="'.C('EMPLOYEE_SYSTEM_LOGIN').'";</script>');
        // }

    }
}

function validatePermissionAllow($url, $back = 'exit', $type = 'action')
{
    list($class, $method,) = explode('/', $url);
    $permission = C('PERMISSION');
    if (session('employee_id')) {
        /*
        $employee=D('Employee')->getResultById(session('employee_id'));
        if(!$employee['employee_account_creator_id']){
            return true;
        }
        */
        if (!session('employee_account_creator_id')) {
            return true;
        }
        /*
        $department=D('Department')->getResultById($employee['department_id']);
        */
        //是否在权限定义数组中
        foreach ($permission as $permission_childs) {
            if (array_key_exists($method, $permission_childs[$type])) {
                //是否在数据库中
                $keys = 'department_permission_' . $type;
                if (!session($keys)) {
                    if ($back == 'exit') {
                        exit('<div style=" text-align:center;margin:80px;color:red;font-size:18px;font-weight:bold;">暂无权限！</div>');
                    }
                    return false;
                    //exit('<div style=" text-align:center;margin:80px;color:red;font-size:18px;font-weight:bold;">暂无权限！</div>');
                }
                if (strpos(session($keys), $class . '_' . $method) === false) {
                    if ($back == 'exit') {
                        exit('<div style=" text-align:center;margin:80px;color:red;font-size:18px;font-weight:bold;">暂无权限！</div>');
                    }
                    return false;
                } else {
                    if ($back != 'exit') {
                        return true;
                    }
                }
            } else {
                return true;
            }

        }
    }
}

function passwordEncode($password)
{
    if (!$password) {
        return;
    }
    return md5(md5($password));
}

function ifNotAjaxGetException()
{
    if (!(IS_AJAX && IS_GET)) {
        throw new \Exception('请求方式错误');
    }
}

function ifNotAjaxPostException()
{
    if (!(IS_AJAX && IS_POST)) {
        throw new \Exception('请求方式错误');
    }
}

function generateUniqid($prefix = '')
{
    $uniqid = uniqid($prefix, true);
    return str_replace('.', '', $uniqid);
}

function cmsLog($log)
{
    exit($log);
}

function ajaxRequestCommonProcess($model_object, $method, $param = '', $successful_message = '', $failed_message = '')
{
    ifNotAjaxPostException();
    if (!$method) {
        $method = ACTION_NAME;
    }
    try {
        if ($param) {
            $param = explode(',', $param);
        }
        $model_object->$method($param);
        $ajaxReturnData['status'] = 1;
        $ajaxReturnData['message'] = L('OPERATION_SUCCESSFUL');
    } catch (\Exception $e) {
        $ajaxReturnData['status'] = 0;
        $ajaxReturnData['message'] = $e->getMessage();
    }
    return $ajaxReturnData;
}

/*
 * 根据post数组自动生成对应变量
 */
function generatePostParamVars()
{
    if (!$_POST) {
        return;
    }
    foreach ($_POST as $k => $v) {
        $params[$k] = I('post.' . $k);
    }
    return $params;
}

/*
 * 根据get数组自动生成对应变量
 */
function generateGetParamVars()
{
    if (!$_GET) {
        return;
    }
    foreach ($_GET as $k => $v) {
        $params[$k] = I('get.' . $k);
    }
    return $params;
}

function generateRequestParamVars()
{
    if (!$_REQUEST) {
        return;
    }
    $params = array();
    foreach ($_REQUEST as $k => $v) {
        $params[$k] = $v;
    }
    return $params;
}

/*
*从临时图片文件夹移动图片到正式图片文件夹
*/
function processMoveFilesFromTmpsToStorage($file1, $file2 = '')
{
    $file = new \Common\Common\File();
    return $file->processMoveFilesFromTmpsToStorage($file1, $file2);
}

/*
 *从临时文件夹移动文件到正式文件夹
 */
function processMoveFilesFromTmpsToStorageForContent($content1, $content2 = '')
{
    $file = new \Common\Common\File();
    return $file->processMoveFilesFromTmpsToStorageForContent($content1, $content2);
}

function downLoadRemoteImagesToStorage($content)
{
    $file = new \Common\Common\File();
    return $file->downLoadRemoteImagesToStorage($content);
}

/*
 *删除文件
 */
function processDeleFiles($files)
{
    if (!$files) {
        return;
    }
    $file = new \Common\Common\File();
    return $file->processDeleFiles($files);
}

/*
 *删除匹配文件
 */
function processDeleFilesForContent($content)
{
    if (!$content) {
        return;
    }
    $file = new \Common\Common\File();
    return $file->processDeleFilesForContent($content);
}

function generateToken()
{
    $uniqid = uniqid('', true);
    return str_replace('.', '', $uniqid);
}

function getLevelRelationResults($results)
{
    $results_parent = array();
    $results_childs = array();
    foreach ($results as $result) {
        if ($result['parent_id'] == 0) {
            $results_parent[] = $result;
        } else {
            $results_childs[$result['parent_id']][] = $result;
        }
    }
    $results_struct_arrs = array();
    $count = count($results_parent);
    for ($i = 0; $i < $count; $i++) {
        $results_struct_arrs[$i]['parent'] = $results_parent[$i];
        if (!array_key_exists($results_parent[$i]['id'], $results_childs)) {
            continue;
        }
        $results_struct_arrs[$i]['childs'] = $results_childs[$results_parent[$i]['id']];
    }
    return $results_struct_arrs;
}

function getWechatPlatformAccessToken($app_id, $secret)
{
    if (!$app_id) {
        throw new \Exception(L('APP_ID_NULL'));
    }
    if (!$secret) {
        throw new \Exception(L('APP_SECRET_NULL'));
    }

    $wechat_platform_access_token_model_object = D('WechatPlatformAccessToken');

    $result = $wechat_platform_access_token_model_object->order('update_time desc')->find();
    //if(!($result && $result['content'] && (($result['update_time']+7200)>time()))){
    //缩短比较时间 希望能保证token有效

    if ($result && $result['content'] && (($result['update_time'] + 3600) > time())) {
        return '{"access_token":"' . $result['content'] . '","expires_in":7200}';
    } else {
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $app_id . '&secret=' . $secret;
        $curl = new \Home\Common\Curl();
        $result = $curl->go($url, 'get');
        $condition['id'] = 1;
        $aa = json_decode($result, true);
        $data['content'] = $aa['access_token'];
        $data['update_time'] = time();
        $wechat_platform_access_token_model_object->where($condition)->save($data);

        return $result;
    }

}

/*
删除无用的access token
*/
function deleWechatPlatformAccessToken()
{
    $wechat_platform_access_token_model_object = D('Home/WechatPlatformAccessToken');
    $wechat_platform_access_token_model_object->dele();
}

/*
 通用参数验证函数
*/
function validateCommonPara($param, $class, $errMsg = '')
{
    $et_default = array(
        'numeric' => '参数必须是数字类型！',
        'common_id' => 'ID格式错误！',
        'verify_code' => '验证码错误！',
        'verify_code_test' => '验证码错误！',
        'email' => '电子邮件格式错误！',
        'mobile' => '手机号码格式错误！',
        'date' => '日期格式错误,正确格式例如1970-01-01！',
        'gender' => '性别格式错误！',
        'orders_id' => '订单ID格式错误！',
        'password' => '密码格式错误,请输入6-20位长度任意字符！',
        'passport' => '护照号码不能空！',
        'passenger_last_name_pinyin' => '旅客姓的格式错误,请输入2-10位长度英文字符！',
        'passenger_first_name_pinyin' => '旅客名的格式错误,请输入2-20位长度英文字符！',
    );
    $errMsg = $errMsg ? $errMsg : $et_default[$class];
    switch ($class) {
        //通用ID验证  数字格式
        case 'numeric':
            if (!is_numeric($param)) {
                throw new \Exception($errMsg);
            }
            break;
        case 'common_id':
            if (!is_numeric($param)) {
                throw new \Exception($errMsg);
            }
            break;
        case 'verify_code':
            if (strlen($param) != 4) {
                throw new \Exception($errMsg, 'VERIFY_CODE_ERROR');
            }
            $verify = new \Think\Verify();
            if (!$verify->check($param)) {
                throw new \Exception($errMsg);
            }
            break;
        case 'verify_code_test':
            if (strlen($param) != 4) {
                throw new \Exception($errMsg, 'VERIFY_CODE_ERROR');
            }
            $verify = new \Think\Verify();
            if (!$verify->check($param, '', true)) {
                throw new \Exception($errMsg);
            }
            break;
        case 'email':
            $length = strlen($param);
            //长度范围在6-40内
            if ($length < 6 || $length > 40) {
                throw new \Exception($errMsg);
            }
            if (!preg_match('/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/i', $param)) {
                throw new \Exception($errMsg);
            }
            break;
        case 'mobile':
            if (!preg_match('/^1[3-8]{1}\d{9}$/', $param)) {
                throw new \Exception($errMsg);
            }
            break;
        case 'date':
            if (!preg_match('/^(19|20)(\d){2}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[0-1])$/', $param)) {
                throw new \Exception($errMsg);
            }
            break;
        case 'gender':
            if (!strlen($param)) {
                throw new \Exception($errMsg);
            }
            if (!in_array($param, array(0, 1))) {
                throw new \Exception($errMsg);
            }
            break;
        case 'orders_id':
            if (!is_numeric($param)) {
                throw new \Exception($errMsg);
            }
            break;
        case 'password':
            if (!preg_match('/^.{6,20}$/', $param)) {
                throw new \Exception($errMsg);
            }
            break;
        case 'passport':
            if (!$param) {
                throw new \Exception($errMsg);
            }
            break;
        case 'passenger_first_name_pinyin':
            //名
            /*
             if(!preg_match('/^[\x{4e00}-\x{9fa5}]{1,6}$/u',$first_name[$i])){
             throw new \Exception("旅客名的格式错误,请输入1-6位长度中文字符！");
             }
             */
            if (!preg_match('/^[a-zA-Z]{2,20}$/u', $param)) {
                throw new \Exception($errMsg);
            }
            break;
        case 'passenger_last_name_pinyin':
            //姓
            /*
             if(!preg_match('/^[\x{4e00}-\x{9fa5}]{1,4}$/u',$last_name[$i])){
             throw new \Exception("旅客姓的格式错误,请输入1-4位长度中文字符！");
             }
             */
            if (!preg_match('/^[a-zA-Z]{2,10}$/u', $param)) {
                throw new \Exception($errMsg);
            }
            break;
        default:
            throw new \Exception("校验参数类型无法匹配！" . $param);
    }

}

/*
地图坐标转换
*/
function getBaiduMapCoords($coords = '')
{
    $apiUrl = 'http://api.map.baidu.com/geoconv/v1/';
    $param = "?coords=$coords&from=3&to=5&ak=" . C('MAP_BAI_DU_AK');
    $api = $apiUrl . $param;
    //$res = file_get_contents($api);
    $res = url_get_contents($api);
    $res = $res ? json_decode($res) : '';
    if ($res->status == 0) {
        return $res->result;
    }
    return false;
}

function url_get_contents($strUrl, $boolUseCookie = false)
{
    $ch = curl_init($strUrl);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_REFERER, $_SERVER['HTTP_REFERER']);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
    if ($boolUseCookie && is_array($_COOKIE) && count($_COOKIE) > 0) {
        $cookie_str = '';
        foreach ($_COOKIE as $key => $value) {
            $cookie_str .= "$key=$value; ";
        }
        curl_setopt($ch, CURLOPT_COOKIE, $cookie_str);
    }
    $response = curl_exec($ch);
    if (curl_errno($ch) != 0) {
        return false;
    }
    curl_close($ch);
    return $response;
}


//图片截取

function cropImages($image = '', $image_size = '')
{
    if (!$image || !$image_size) {
        return;
    }
    $image = C('CACHE_DIR') . $image;
    $fileInfo = pathinfo($image);
    $info = getimagesize($image);
    if (!empty($info)) {
        switch ($info[2]) {
            case 1:
                $src_img = imagecreatefromgif($image);
                break;
            case 2:
                $src_img = imagecreatefromjpeg($image);
                break;
            case 3:
                $src_img = imagecreatefrompng($image);
                break;
            case 6:
                $src_img = imagecreatefromwbmp($image);
                break;
            default:
                die("不支持的文件类型");
                exit;
        }

        $new_img = '';
        $w = imagesx($src_img);
        $h = imagesy($src_img);
        $ratio_w = 1.0 * $image_size[0] / $w;
        $ratio_h = 1.0 * $image_size[1] / $h;
        $ratio = 1.0;
        if (($ratio_w < 1 && $ratio_h < 1) || ($ratio_w > 1 && $ratio_h > 1)) {
            if ($ratio_w < $ratio_h) {
                $ratio = $ratio_h;
            } else {
                $ratio = $ratio_w;
            }

            $new_w = (int)($image_size[0] / $ratio);
            $new_h = (int)($image_size[1] / $ratio);
            $img_map = imagecreatetruecolor($new_w, $new_h);

            imagecopy($img_map, $src_img, 0, 0, 0, 0, $new_w, $new_h);
            $new_img = imagecreatetruecolor($image_size[0], $image_size[1]);
            imagecopyresampled($new_img, $img_map, 0, 0, 0, 0, $image_size[0], $image_size[1], $new_w, $new_h);
        } else {
            $ratio = $ratio_h > $ratio_w ? $ratio_h : $ratio_w;
            $new_w = (int)($w * $ratio);
            $new_h = (int)($h * $ratio);
            $img_map = imagecreatetruecolor($new_w, $new_h);
            imagecopyresampled($img_map, $src_img, 0, 0, 0, 0, $new_w, $new_h, $w, $h);
            $new_img = imagecreatetruecolor($image_size[0], $image_size[1]);
            imagecopy($new_img, $img_map, 0, 0, 0, 0, $image_size[0], $image_size[1]);
        }
        $new_img_path = $fileInfo['dirname'] . '/' . $fileInfo['filename'] . '_' . $image_size[0] . 'x' . $image_size[1] . '.' . $fileInfo['extension'];
        if ($new_img) {
            switch ($info[2]) {
                case 1:

                    imagejpeg($new_img, $new_img_path);
                    break;
                case 2:
                    imagejpeg($new_img, $new_img_path);
                    break;
                case 3:
                    imagepng($new_img, $new_img_path);
                    break;
                case 6:
                    imagewbmp($new_img, $new_img_path);
                    break;
            }
            imagedestroy($new_img);
        }

    }

}

function getUrlInfo($url)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_URL, $url);
    $res = curl_exec($curl);
    curl_close($curl);
    return $res;
}


//fsockopen读取  
function dfopen($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE)
{
    $return = '';
    $matches = parse_url($url);
    $host = $matches['host'];
    $path = $matches['path'] ? $matches['path'] . (isset($matches['query']) && $matches['query'] ? '?' . $matches['query'] : '') : '/';
    $port = !empty($matches['port']) ? $matches['port'] : 80;

    if ($post) {
        $out = "POST $path HTTP/1.0\r\n";
        $out .= "Accept: */*\r\n";
        //$out .= "Referer: $boardurl\r\n";  
        $out .= "Accept-Language: zh-cn\r\n";
        $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
        $out .= "Host: $host\r\n";
        $out .= 'Content-Length: ' . strlen($post) . "\r\n";
        $out .= "Connection: Close\r\n";
        $out .= "Cache-Control: no-cache\r\n";
        $out .= "Cookie: $cookie\r\n\r\n";
        $out .= $post;
    } else {
        $out = "GET $path HTTP/1.0\r\n";
        $out .= "Accept: */*\r\n";
        //$out .= "Referer: $boardurl\r\n";  
        $out .= "Accept-Language: zh-cn\r\n";
        $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
        $out .= "Host: $host\r\n";
        $out .= "Connection: Close\r\n";
        $out .= "Cookie: $cookie\r\n\r\n";
    }

    if (function_exists('fsockopen')) {
        $fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
    } elseif (function_exists('pfsockopen')) {
        $fp = @pfsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
    } else {
        $fp = false;
    }

    if (!$fp) {
        return '';
    } else {
        stream_set_blocking($fp, $block);
        stream_set_timeout($fp, $timeout);
        @fwrite($fp, $out);
        $status = stream_get_meta_data($fp);
        if (!$status['timed_out']) {
            while (!feof($fp)) {
                if (($header = @fgets($fp)) && ($header == "\r\n" || $header == "\n")) {
                    break;
                }
            }

            $stop = false;
            while (!feof($fp) && !$stop) {
                $data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
                $return .= $data;
                if ($limit) {
                    $limit -= strlen($data);
                    $stop = $limit <= 0;
                }
            }
        }
        @fclose($fp);
        return $return;
    }
}

/*
分页处理 以便前端ajax使用
*/
function paging_process($paging)
{
    $paging = str_replace('href', 'attr_href', $paging);
    $paging = str_replace('/index.html', '', $paging);
    return $paging;
}
