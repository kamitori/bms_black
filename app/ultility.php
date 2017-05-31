<?php
defined('PROMOTED_3_1')               || define('PROMOTED_3_1', '9999');
defined('PROMOTED_BUY_SELL')               || define('PROMOTED_BUY_SELL', 4);
date_default_timezone_set('Canada/Mountain');
function json_en_de($str){
    return json_decode($str,true);
}
function getInfo()
{
    $serverName = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '';
    $arrInfo = [];
    $arrConfig = [
        'banhmisub.com'  => [
                    'url'       => 'https://banhmisub.com',
                    'db'        => 'banhmisub',
                    'jt_db'     => 'bms',
                    'jt_url'    => 'http://jt.banhmisub.com',
                    'pos_url'    => 'http://pos.banhmisub.com',
                    'official_website'    => true
                ],
        'www.banhmisub.com'  => [
                    'url'       => 'https://banhmisub.com',
                    'db'        => 'banhmisub',
                    'jt_db'     => 'bms',
                    'jt_url'    => 'http://jt.banhmisub.com',
                    'pos_url'    => 'http://pos.banhmisub.com',
                    'official_website'    => true
                ],
        'web.banhmisub.com'  => [
                    'url'       => 'http://web.banhmisub.com',
                    'db'        => 'bmsweb_bmsblack',
                    'jt_db'     => 'bms',
                    'jt_url'    => 'http://newjt.banhmisub.com',
                    'pos_url'    => 'http://newpos.banhmisub.com'
                ],
        'bmsblack.com'  => [
                    'url'       => 'http://bmsblack.com',
                    'db'        => 'bmsblack',
                    'jt_db'     => 'bms',
                    'jt_url'    => 'http://bms.com',
                    'pos_url'    => 'http://retailweb.com'                    
                ],
        'localhost'=> [
                    'url'       => 'http://localhost/bms_black',
                    'db'        => 'bmsblack',
                    'jt_db'     => 'bms',
                    'jt_url'    => 'http://bms.com',
                    'pos_url'    => 'http://localhost/retailweb'                    
                ],
        '192.168.1.19'=> [
                    'url'       => 'http://192.168.1.19/bms_black',
                    'db'        => 'bmsblack',
                    'jt_db'     => 'bms',
                    'jt_url'    => 'http://192.168.1.19/bms',
                    'pos_url'    => 'http://localhost/retailweb'                    
                ],
        'bmsdemo_web.com'  => [
                    'url'       => 'http://bmsdemo_web.com',
                    'db'        => 'bmsblack',
                    'jt_db'     => 'bms',
                    'jt_url'    => 'http://bms.com',
                    'pos_url'    => 'http://retailweb.com'                    
                ],
        'bmsweb.vimpact.ca'  => [
                    'url'       => 'http://bmsweb.vimpact.ca',
                    'db'        => 'vimpact_demo',
                    'jt_db'     => 'bmstest',
                    'jt_url'    => 'http://bmsjt.vimpact.ca',
                    'pos_url'    => 'http://bmspos.vimpact.ca'                    
                ],

    ];
    if (php_sapi_name() === 'cli') {
        if( DIRECTORY_SEPARATOR == '\\' ) {
            $arrInfo = $arrConfig['bmsblack.com'];
        } else {
            $arrInfo = $arrConfig['banhmisub.com'];
        }
    } else {
        $arrInfo = $arrConfig[$serverName];
    }
    if (in_array($serverName, ['bmsblack', '192.168.1.19', 'bmsdemo_web', ''])) {
        $arrInfo['debug'] = true;
    } else {
        $arrInfo['debug'] = false;
    }
    return $arrInfo;
}
function display_format_currency($p_number,$number = 2){
    return DEFAULT_CURRENCY.number_format((double)$p_number,$number);
}
function remove_spaces($str){
    return preg_replace('/\s+/', ' ',$str);
}
function pr($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    // die;
/*
cd /var 
sudo mkdir mysql
sudo chmod 755 mysql
cd mysql
sudo ln -s /var/run/mysqld mysql.sock

*/

}

function create_random_key($p_len=10, $p_character_only = false,$number_only = false, $p_mixed_case=false){
    $v_chars = 'qwertyuiopasdfghjklzxcvbnm0123456789';
    $r='';
    if($p_character_only) $v_chars = preg_replace('/[^a-z]/', '', $v_chars);
    if($number_only) $v_chars = preg_replace('/[^0-9]/', '', $v_chars);
    $l = strlen($v_chars)-1;
    $check_total_key = 0;
    for($i=0;$i<$p_len;$i++){
        $p = rand(0,$l);
        $c = substr($v_chars,$p,1);
        if($p_mixed_case){
            $t = rand(0,1);
            $c = $t==1?strtoupper($c):$c;
        }           
        $r.= strtoupper($c);
    }
    return $r;
}
function isImage($mime)
{
    return in_array($mime, ['image/jpeg', 'image/jpg', 'image/png', 'image/gif']);
}

function slug($string, $separator = '-')
{
    $title = \Patchwork\Utf8::toAscii($string);
    // Convert all dashes/underscores into separator
    $flip = $separator == '-' ? '_' : '-';

    $title = preg_replace('!['.preg_quote($flip).']+!u', $separator, $title);

    // Remove all characters that are not the separator, letters, numbers, or whitespace.
    $title = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', mb_strtolower($title));

    // Replace all separator characters and whitespace by a single separator
    $title = preg_replace('!['.preg_quote($separator).'\s]+!u', $separator, $title);

    return trim($title, $separator);
}
function return_to_array($obj){
    if(MONGO_VERSION) return (array) $obj;
    return $obj->toArray();

}
//=================================================================================
defined('DS')               || define('DS', DIRECTORY_SEPARATOR);
defined('APP_PATH')         || define('APP_PATH', realpath('.'));
defined('PUBLIC_PATH')      || define('PUBLIC_PATH', APP_PATH . DS .'public');
defined('PHAMTOM_CONVERT')  || define('PHAMTOM_CONVERT', APP_PATH.DS.'phantomjs'.DS.'rasterize.js');
defined('THEME')            || define('THEME','themes/pizzahut/');
//=================================================================================
$info = getInfo();

// ''
defined('DEBUG')    || define('DEBUG', $info['debug']);
defined('MONGO_VERSION')    || define('MONGO_VERSION', 1);
defined('URL')      || define('URL', $info['url']);
defined('DB')    || define('DB', $info['db']);
defined('JT_DB')    || define('JT_DB', $info['jt_db']);
defined('JT_URL')    || define('JT_URL', $info['jt_url']);
defined('POS_URL')    || define('POS_URL', $info['pos_url']);
defined('DEFAULT_CURRENCY')    || define('DEFAULT_CURRENCY', '$');
defined('ORDER_GROUP_DISCOUNT_CONDITION') || define('ORDER_GROUP_DISCOUNT_CONDITION', 50); // >= 50 Dollars
defined('ORDER_GROUP_DISCOUNT') || define('ORDER_GROUP_DISCOUNT', 0.1); // reduce price to 10%
//=================================================================================
if(isset($info['official_website']) && $info['official_website']){
    defined('DEFAULT_EMAIL')    || define('DEFAULT_EMAIL', 'baker@banhmisub.com');
    defined('ADDITIONAL_EMAIL')    || define('ADDITIONAL_EMAIL', 'loann@banhmisub.com');
} else {
    defined('DEFAULT_EMAIL')    || define('DEFAULT_EMAIL', 'hunglmkpc044@gmail.com');
    defined('ADDITIONAL_EMAIL')    || define('ADDITIONAL_EMAIL', 'hunglmkpc045@gmail.com');
}
// Sort mảng theo giá trị key, hàm đơn giản
function aasort(&$array=array(), $key='',$order=1,$isResetKey = false) {
    $sorter=array();
    $ret=array();
    if(is_array($array) && count($array)>0){
        reset($array);
        foreach ($array as $ii => $va) {
            if(!isset($va[$key])) continue;
            $sorter[$ii]=$va[$key];
        }
    }
    if($order==1)
        asort($sorter);
    else
        arsort($sorter);
    if(!$isResetKey)
        foreach ($sorter as $ii => $va) {
            $ret[$ii]=$array[$ii];
        }
    else
        foreach ($sorter as $ii => $va) {
            $ret[]=$array[$ii];
        }
    $array=$ret;
    return $array;
}

// Sort mảng theo giá trị key, cho phép theo nhiều cách sort_flags
function msort($array, $key,$order=1,$sort_flags = SORT_REGULAR) {
    if (is_array($array) && count($array) > 0) {
        if (!empty($key)) {
            $mapping = array();
            foreach ($array as $k => $v) {
                $sort_key = '';
                if (!is_array($key)) {
                    $sort_key = $v[$key];
                } else {
                    // @TODO This should be fixed, now it will be sorted as string
                    foreach ($key as $key_key) {
                        $sort_key .= $v[$key_key];
                    }
                    $sort_flags = SORT_STRING;
                }
                $mapping[$k] = $sort_key;
            }
            if($order==1)
                asort($mapping, $sort_flags);
            else
                arsort($mapping, $sort_flags);
            $sorted = array();
            foreach ($mapping as $k => $v) {
                $sorted[] = $array[$k];
            }
            return $sorted;
        }
    }
    return $array;
}

function GroupToKey($str){
    $str = explode(".",$str);
    $str = end($str);
    $str = strtolower(str_replace(" ","_",trim($str)));
    return $str;
}
function GroupToName($str){
    $str = explode(".",$str);
    $str = end($str);
    return $str;
}
function removeVietnamseChac($p_fragment , $p_lower = true,$doc = false){
    $translite_simbols = array (
        '#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#',
        '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#',
        '#(ì|í|ị|ỉ|ĩ)#',
        '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#',
        '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#',
        '#(ỳ|ý|ỵ|ỷ|ỹ)#',
        '#(đ)#',
        '#(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#',
        '#(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#',
        '#(Ì|Í|Ị|Ỉ|Ĩ)#',
        '#(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)#',
        '#(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#',
        '#(Ỳ|Ý|Ỵ|Ỷ|Ỹ)#',
        '#(Đ)#',
        "/[^a-zA-Z0-9\-\.\_\'\"\ ]/",
    ) ;
    $replace = array (
        'a',
        'e',
        'i',
        'o',
        'u',
        'y',
        'd',
        'A',
        'E',
        'I',
        'O',
        'U',
        'Y',
        'D',
        '-',
    ) ;
    $v_fragment = preg_replace($translite_simbols, $replace, $p_fragment);
    if(!$doc) $v_fragment = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , '-', $v_fragment);
    else $v_fragment = preg_replace(array('/[^a-z0-9.]/i', '/[-]+/') , '-', $v_fragment);
    $v_temp = $p_lower?strtolower($v_fragment):$v_fragment;        
    $v_temp = preg_replace('/\+s/','-',$v_temp);
    return $v_temp;
}
