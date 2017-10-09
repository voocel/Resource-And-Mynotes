<?php

/* * *************************
 * 生成随机字符串，可以自己扩展   //若想唯一，只需在开头加上用户id
 * $type可以为：upper(只生成大写字母)，lower(只生成小写字母)，number(只生成数字)
 * $len为长度，定义字符串长度
 * date 2017/10/5
 * ************************** */

function random($type, $len = 0) {
    $new = '';
    $string = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';  //数据池
    if ($type == 'upper') {
        for ($i = 0; $i < $len; $i++) {
            $new .= $string[mt_rand(36, 61)];
        }
        return $new;
    }
    if ($type == 'lower') {
        for ($i = 0; $i < $len; $i++) {
            $new .= $string[mt_rand(10, 35)];
        }
        return $new;
    }
    if ($type == 'number') {
        for ($i = 0; $i < $len; $i++) {
            $new .= $string[mt_rand(0, 9)];
        }
        return $new;
    }
}

//计算该月有几天
function getdaysInmonth($month, $year) {
    $days = '';
    if ($month == 1 || $month == 3 || $month == 5 || $month == 7 || $month == 8 || $month == 10 || $month == 12)
        $days = 31;
    else if ($month == 4 || $month == 6 || $month == 9 || $month == 11)
        $days = 30;
    else if ($month == 2) {
        if (isLeapyear($year)) {
            $days = 29;
        } else {
            $days = 28;
        }
    }
    return ($days);
}

//判断是否为润年
function isLeapyear($year) {
    if ((($year % 4) == 0) && (($year % 100) != 0) || (($year % 400) == 0)) {
        return (true);
    } else {
        return (false);
    }
}

//生成订单15位
function auto_order($ord = 0) {
    //自动生成订单号  传入参数为0 或者1   0为本地  1为线上订单
    if ($ord == 0) {
        $str = '00' . time() . rand(1000, 9999); //00 本地订单
    } else {
        $str = '99' . time() . rand(1000, 9999);  //11 线上订单
    }
    return $str;
}

//生成订单15位
function auto_new_order($ord = 0) {
    srand(time());
    //自动生成订单号  传入参数为0 或者1   0为本地  1为线上订单
    if ($ord == 0) {
        $str = '00' . time() . mt_rand(100000,999999); //00 本地订单
    } else {
        $str = '11' . time() . mt_rand(100000,999999);  //11 线上订单
    }
    return $str;
}

/**
 * 检测是否为UTF8编码 
 * @param $string 检测字符串
 * @return bool
 */
function is_utf8($string) {
    return preg_match('%^(?:
          [\x09\x0A\x0D\x20-\x7E]            # ASCII
        | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
        |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
        | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
        |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
        |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
        | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
        |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
    )*$%xs', $string);
}

//处理json字符中的特殊字符
function getJsonToArr($result,$return_array=true)
{
        $tempArr = NULL;
        $result = preg_replace('/([^\\\])(":)(\d{9,})(,")/i', '${1}${2}"${3}"${4}', $result); //taobao bug,number >2^32
        $tempArr = json_decode($result, $return_array);
        if ($tempArr == NULL) {
            $patterns = array('/,+\s*\}/', '/,+\s*\]/', '/"\s+|\s+"/', '/\n|\r|\t/','/\\\/','/ss+/');
            $replacements = array('}', ']', '"', ' ','',' ');
            $result = preg_replace($patterns, $replacements, $result);            
            $tempArr = json_decode($result, $return_array);
        }

        return $tempArr;
}


//非法字符过滤函数
function has_unsafeword($str) {
    $regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\.|\/|\;|\'|\`|\=|\\\|\|/";
    return preg_replace($regex,"",$str);   
}

//去空格，以及字符添加斜杠
function _trim(&$value) {
    Return addslashes(trim($value));
}

/**
 * 将多维数组转为一维数组
 * @param array $arr
 * @return array
 */
function ArrMd2Ud($arr) {
    #将数值第一元素作为容器，作地址赋值。
    $ar_room = &$arr[key($arr)];
    #第一容器不是数组进去转呀
    if (!is_array($ar_room)) {
        #转为成数组
        $ar_room = array($ar_room);
    }
    #指针下移
    next($arr);
    #遍历
    while (list($k, $v) = each($arr)) {
        #是数组就递归深挖，不是就转成数组
        $v = is_array($v) ? call_user_func(__FUNCTION__, $v) : array($v);
        #递归合并
        $ar_room = array_merge_recursive($ar_room, $v);
        #释放当前下标的数组元素
        unset($arr[$k]);
    }
    return $ar_room;
}

    /**
     * 判断是PC端还是wap端访问
     * @return type判断手机移动设备访问
     */
     function isMobile()
     { 
         // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
         if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
         {
             return true;
         } 
         // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
         if (isset ($_SERVER['HTTP_VIA']))
         { 
             // 找不到为flase,否则为true
             return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
         } 
         // 脑残法，判断手机发送的客户端标志,兼容性有待提高
         if (isset ($_SERVER['HTTP_USER_AGENT']))
         {
             $clientkeywords = array ('nokia',
                 'sony',
                 'ericsson',
                 'mot',
                 'samsung',
                 'htc',
                 'sgh',
                 'lg',
                 'sharp',
                 'sie-',
                 'philips',
                 'panasonic',
                 'alcatel',
                 'lenovo',
                 'iphone',
                 'ipod',
                 'blackberry',
                 'meizu',
                 'android',
                 'netfront',
                 'symbian',
                 'ucweb',
                 'windowsce',
                 'palm',
                 'operamini',
                 'operamobi',
                 'openwave',
                 'nexusone',
                 'cldc',
                 'midp',
                 'wap',
                 'mobile'
                 ); 
             // 从HTTP_USER_AGENT中查找手机浏览器的关键字
             if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
             {
                 return true;
             } 
         } 
         // 协议法，因为有可能不准确，放到最后判断
         if (isset ($_SERVER['HTTP_ACCEPT']))
         { 
             // 如果只支持wml并且不支持html那一定是移动设备
             // 如果支持wml和html但是wml在html之前则是移动设备
             if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
             {
                 return true;
             } 
         } 
         return false;
     }
 
     //判断是否为安卓手机
     function isAndroid()
     {
         if(isset($_SERVER['HTTP_USER_AGENT'])){
             $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
             if(strpos($agent,'android') !== false)
                 return true;
         }
         return false;
     }
 
     //判断是否为iphone或者ipad
     function isIos()
     {
         if(isset($_SERVER['HTTP_USER_AGENT'])){
             $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
             if(strpos($agent, 'iphone')||strpos($agent, 'ipad'))
                 return true;
         }
         return false;
     }
 
     //判断是否为微信内置浏览器打开
     function isWechet()
     {
         if(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false){
             return true;
         }
         return false;
     }
 
     //整合到一起，判断当前设备，1：安卓；2：IOS；3：微信；0：未知
     function isDevice()
     {
         if($_SERVER['HTTP_USER_AGENT']){
             $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
             if(strpos($agent, 'micromessenger') !== false)
                 return 3;
             elseif(strpos($agent, 'iphone')||strpos($agent, 'ipad'))
                 return 2;
             else
                 return 1;
         }
         return 0;
     }




    /**
     * 日期转换成几分钟前
     */
    function formatTime($date) {
        $timer = strtotime($date);
        $diff = $_SERVER['REQUEST_TIME'] - $timer;
        $day = floor($diff / 86400);
        $free = $diff % 86400;
        if($day > 0) {
            if(15 < $day && $day <30){
                return "半个月前";
            }elseif(30 <= $day && $day <90){
                return "1个月前";
            }elseif(90 <= $day && $day <187){
                return "3个月前";
            }elseif(187 <= $day && $day <365){
                return "半年前";
            }elseif(365 <= $day){
                return "1年前";
            }else{
                return $day."天前";
            }
        }else{
            if($free>0){
                $hour = floor($free / 3600);
                $free = $free % 3600;
                if($hour>0){
                    return $hour."小时前";
                }else{
                    if($free>0){
                        $min = floor($free / 60);
                        $free = $free % 60;
                    if($min>0){
                        return $min."分钟前";
                    }else{
                        if($free>0){
                            return $free."秒前";
                        }else{
                            return '刚刚';
                        }
                    }
                    }else{
                        return '刚刚';
                    }
                }
            }else{
                return '刚刚';
            }
        }
    }

    /**
     * 截取长度
     */
    function getSubString($rawString,$length='100',$etc = '...',$isStripTag=true){
        $rawString = str_replace('_baidu_page_break_tag_', '', $rawString);
        $result = '';
        if($isStripTag)
            $string = html_entity_decode(trim(strip_tags($rawString)), ENT_QUOTES, 'UTF-8');
        else
            $string = trim($rawString);
        $strlen = strlen($string);
        for ($i = 0; (($i < $strlen) && ($length > 0)); $i++){
            if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0'))
            {
                if ($length < 1.0){
                    break;
                }
                $result .= substr($string, $i, $number);
                $length -= 1.0;
                $i += $number - 1;
            }else{
                $result .= substr($string, $i, 1);
                $length -= 0.5;
            }
        }
        if($isStripTag)
            $result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');

        if ($i < $strlen){
            $result .= $etc;
        }
        return $result;
    }

    /**
     * utf-8和gb2312自动转化
     * @param unknown $string
     * @param string $outEncoding
     * @return unknown|string
     */
    function safeEncoding($string,$outEncoding = 'UTF-8')
    {
        $encoding = "UTF-8";
        for($i = 0; $i < strlen ( $string ); $i ++) {
            if (ord ( $string {$i} ) < 128)
                continue;

            if ((ord ( $string {$i} ) & 224) == 224) {
                // 第一个字节判断通过
                $char = $string {++ $i};
                if ((ord ( $char ) & 128) == 128) {
                    // 第二个字节判断通过
                    $char = $string {++ $i};
                    if ((ord ( $char ) & 128) == 128) {
                        $encoding = "UTF-8";
                        break;
                    }
                }
            }
            if ((ord ( $string {$i} ) & 192) == 192) {
                // 第一个字节判断通过
                $char = $string {++ $i};
                if ((ord ( $char ) & 128) == 128) {
                    // 第二个字节判断通过
                    $encoding = "GB2312";
                    break;
                }
            }
        }

        if (strtoupper ( $encoding ) == strtoupper ( $outEncoding ))
            return $string;
        else
            return @iconv ( $encoding, $outEncoding, $string );
    }

    /** 
    *对内容中的关键词添加链接 
    *只处理第一次出现的关键词，对已有链接的关键不会再加链接，支持中英文 
    *$content:string 原字符串 
    *$keyword:string  关键词 
    *$link:string,链接 
    */ 
   function yang_keyword_link($content,$keyword,$link){ 
        //排除图片中的关键词 
        $content = preg_replace( '|(<img[^>]*?)('.$keyword.')([^>]*?>)|U', '$1%&&&&&%$3', $content); 
        $regEx = '/(?!((<.*?)|(<a.*?)))('.$keyword.')(?!(([^<>]*?)>)|([^>]*?<\/a>))/si'; 
        $url='<a href="'.$link.'" target="_blank" class-og="content_guanjianci">'.$keyword.'</a>'; 
        $content = preg_replace($regEx,$url,$content,1); 
        //还原图片中的关键词 
        $content=str_replace('%&&&&&%',$keyword,$content); 
        return $content; 
    }