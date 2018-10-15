<?php

function GetWechatOpenId($js_code)
{
    if (!$js_code) {
        throw new \Exception('code参数为null！');
    }

    $url=C('WECHAT_GET_OPEN_ID');
    $wechat_data=C('WECHAT_XCX_DATA');
    $param=array();
    $param[]='appid='.$wechat_data['appid'];
    $param[]='secret='.$wechat_data['appsecret'];
    $param[]='js_code='.$js_code;
    $param[]='grant_type=authorization_code';

    $params=join('&',$param);

    $url=$url.'?'.$params;
    $curl=new \Home\Common\Curl();
    $result=$curl->go($url,'post');

    $result=json_decode($result,true);
    // if($result[0]!='1000'){
    //     //throw new \Exception('系统繁忙，请稍后再试！');
    //     $message=C('SMS_RETURN_MESSAGE');
    //     throw new \Exception($message[$result[0]]);
    // }
    return $result;
}

function convertDate($time){
    if (!$time)
        return false;
    $fdate = '';
    $d = time() - intval($time);
    $ld = $time - mktime(0, 0, 0, 0, 0, date('Y')); //得出年
    $md = $time - mktime(0, 0, 0, date('m'), 0, date('Y')); //得出月
    $byd = $time - mktime(0, 0, 0, date('m'), date('d') - 2, date('Y')); //前天
    $yd = $time - mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')); //昨天
    $dd = $time - mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天
    $td = $time - mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')); //明天
    $atd = $time - mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')); //后天
    if ($d == 0) {
        $fdate = '刚刚';
    } else {
        switch ($d) {
            case $d < $atd:
                $fdate = date('Y年m月d日', $time);
                break;
            case $d < $td:
                $fdate = '后天' . date('H:i', $time);
                break;
            case $d < 0:
                $fdate = '明天' . date('H:i', $time);
                break;
            case $d < 60:
                $fdate = $d . '秒前';
                break;
            case $d < 3600:
                $fdate = floor($d / 60) . '分钟前';
                break;
            case $d < $dd:
                $fdate = floor($d / 3600) . '小时前';
                break;
            case $d < $yd:
                $fdate = '昨天' . date('H:i', $time);
                break;
            case $d < $byd:
                $fdate = '前天' . date('H:i', $time);
                break;
            case $d < $md:
                $fdate = date('m月d日 H:i', $time);
                break;
            case $d < $ld:
                $fdate = date('m月d日', $time);
                break;
            default:
                $fdate = date('Y年m月d日', $time);
                break;
        }
    }
    return $fdate;
}
