<?php
// 应用公共文件

/**
 * 随机生成token
 * @param int $v
 * @return string $token
 */
function create_token($v = 1)
{
    $v = 1;
    $key = mt_rand();
    $hash = hash_hmac("sha1", $v . mt_rand() . time(), $key, true);
    $token = str_replace('=', '', strtr(base64_encode($hash), '+/', '-_'));
    return $token;
}

/**
 * PHP OpenSSL实现字符串加密解密:openssl_encrypt and openssl_decrypt
 * 解密：echo encrypt_decrypt('$data', '$key', 1, $iv);
 * 加密：echo encrypt_decrypt('$data', '$key', 0, $iv);
 * @param string $data data
 * @param string $key token key
 * @param int $decrypt mathod
 * @param string $iv $token key
 * @return string $decrypt|$encrypted
 **/
function encrypt_decrypt($data, $key, $decrypt, $iv)
{
    if ($decrypt) {
        // 解密
        $decrypt = json_decode(base64_decode($data), true);
        $decrypted = openssl_decrypt($decrypt, 'AES-256-CBC', $key, 0, $iv);
        $did = unserialize($decrypted);
        if ($did) {
            return $did;
        } else {
            return 0;
        }
    } else {
        // 加密
        $str = serialize($data);
        $encrypt = openssl_encrypt($str, 'AES-256-CBC', $key, 0, $iv);
        $encrypted = base64_encode(json_encode($encrypt));
        return $encrypted;
    }
}

/**
 * get Server Info
 * @return array $info
 */
function get_server_info()
{
    // date_default_timezone_set("Asia/Shanghai");
    $info = array(
        "server_os"         => PHP_OS,
        "server_name"       => $_SERVER['SERVER_NAME'],
        "server_ip"         => $_SERVER['SERVER_NAME'] . ' [ ' . gethostbyname($_SERVER['SERVER_NAME']) . ' ]',
        "server_host"       => $_SERVER['HTTP_HOST'],
        "server_software"   => $_SERVER["SERVER_SOFTWARE"],
        "server_port"       => $_SERVER['SERVER_PORT'],
        "server_protocol"   => $_SERVER['SERVER_PROTOCOL'],
        "server_request"    => $_SERVER['REQUEST_METHOD'],
        "server_language"   => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
        "server_host_name"  => php_uname("s"),
        "server_os_name"    => php_uname("n"),
        "server_os_version" => php_uname("r"),
        "server_os_info"    => php_uname("v"),
        "server_os_mode"    => php_uname("m"),
        "server_time_zone"  => date_default_timezone_get(),
        "server_time"       => date("l jS \of F Y H:i:s A"),
        "beijing_time"      => gmdate("Y年n月j日 H:i:s", time() + 8 * 3600),
        "document_root"     => $_SERVER["DOCUMENT_ROOT"],
        "browser_info"      => $_SERVER['HTTP_USER_AGENT'],
        "max_upload"        => ini_get('upload_max_filesize'),
        "max_runtime"       => ini_get('max_execution_time') . '秒',
        "client_ip"         => $_SERVER['REMOTE_ADDR'],
        "disk_total"        => round((disk_total_space("/") / (1024 * 1024 * 1024)), 2) . 'G',
        "disk_free"         => round((disk_free_space("/") / (1024 * 1024 * 1024)), 2) . 'G',
        "php_version"       => PHP_VERSION,
        "php_zend_version"  => zend_version(),
        "php_sapi_name"    => php_sapi_name(),
        "php_uname"         => php_uname("a"),
        "php_sapi_name"     => php_sapi_name(),
        "php_ini_loaded_file" => php_ini_loaded_file(),
        "free_memory_usage"  => memory_get_usage(true),
        "page_memory_usage"  => memory_get_usage(false),
        // "zend_thread_id"    => zend_thread_id(),
        // "mysql_version"     => mysqli_get_server_info(),
        "system_temp_dir"   => sys_get_temp_dir(),
        "session_id"        => SESSION_ID()
    );
    return $info;
}

/**
 * format disk memory space
 * @param int $size
 * @return string
 */
function convert($size)
{
    $unit = array('byte', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb');
    return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . '' . $unit[$i];
}
// echo convert(memory_get_usage(true));

/**
 * 获取浏览器语言
 * @return string $lang
 * https://my.oschina.net/u/258293/blog/3029576
 */
function get_browser_lang()
{
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 4);
    if (preg_match("/zh-c/i", $lang))
        return $lang = "zh-cn";
    else if (preg_match("/zh/i", $lang))
        return $lang = "zh-cn";
    else if (preg_match("/en/i", $lang))
        return $lang = "en-us";
    else if (preg_match("/fr/i", $lang))
        return $lang = "fr";
    else if (preg_match("/de/i", $lang))
        return $lang = "de";
    else if (preg_match("/jp/i", $lang))
        return $lang = "ja-jp";
    else if (preg_match("/ko/i", $lang))
        return $lang = "ko-kr";
    else if (preg_match("/es/i", $lang))
        return $lang = "es";
    else if (preg_match("/sv/i", $lang))
        return $lang = "sv";
    else return $lang = "en-us";
}

/**
 * 判断PHP函数是否被禁用
 * @param string $func
 * @return string $status
 */
function func_enabled($func)
{
    $status = explode(',', ini_get('disable_functions'));
    return !in_array($func, $status);
}

/**
 * 下载远程图片到服务器
 * @param string $url
 * @param string $savePath
 * @return string $fileName
 * */
function remote_image_save($url, $savePath)
{
    if (remote_file_exists($url)) {
        $savePath = './Public/update/image/'; //默认保存路径
        $ext = strrchr($url, '.'); //获取扩展名
        $fileName = date("YmdHis") . $ext; //保存文件名
        ob_start(); //打开输出
        readfile($url); //读取远程文件
        $img = ob_get_contents(); //得到浏览器输出
        ob_end_clean(); //清除输出并关闭
        $size = strlen($img); //获取图片文件大小
        $file = $savePath . $fileName;
        $fp2 = @fopen($savePath . $fileName, "w"); //
        fwrite($fp2, $img); //写入文件并重命名
        fclose($fp2); //关闭
        return $fileName; //返回新文件名
    }
    return false;
}

/**
 * 检查远程文件是否存在
 * @param string $url
 * @return int
 * */
function remote_file_exists($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_NOBODY, 1); // 不下载
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    if (curl_exec($ch) !== false) {
        return true;
    } else {
        return false;
    }
}

/**
 * CURL get
 * @param string $url
 * @param array $params
 * @param int $timeout
 */
function curl_get($url, array $params = array(), $timeout = 5)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_NOBODY, 0); // 不下载
    curl_setopt($ch, CURLOPT_HEADER, 0); //设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

/**
 * 关键词中的空格替换为-
 * @param string $str
 * @return string $str
 */
function emptyreplace($str)
{
    //替换全角空格为半角
    $str = str_repeat(" ", " ", $str);
    //替换连续的空格为一个
    $str = str_repeat(" ", " ", $str);
    // 是否遇到不是空格的字符
    $none = false;
    for ($i = 0; $i < strlen($str); $i++) {
        if ($none && $str[$i] == '') {
            $str[$i] = ',';
        } elseif ($str[$i] != ' ') {
            $none = true;
        }
        return $str;
    }
}

/** 
 * 将HEX16进制颜色转换为RGB
 * @param string $hexColor
 * @return array $rgb
 */
function hexrgb($hexColor)
{
    $color = str_replace('#', '', $hexColor);
    if (strlen($color) > 3) {
        $rgb = array(
            'r' => hexdec(substr($color, 0, 2)),
            'g' => hexdec(substr($color, 2, 2)),
            'b' => hexdec(substr($color, 4, 2))
        );
    } else {
        $r = substr($color, 0, 1) . substr($color, 0, 1);
        $g = substr($color, 1, 1) . substr($color, 1, 1);
        $b = substr($color, 2, 1) . substr($color, 2, 1);
        $rgb = array(
            'r' => hexdec($r),
            'g' => hexdec($g),
            'b' => hexdec($b)
        );
    }
    return $rgb;
}

/**
 * HEX十六进制转RGB
 * @param string $color 16进制颜色值
 * @return array $rgb RGB字符串
 */
function hexrgb2($color)
{
    $hexColor = str_replace('#', '', $color);
    $lens = strlen($hexColor);
    if ($lens != 3 && $lens != 6) {
        return false;
    }
    $newcolor = '';
    if ($lens == 3) {
        for ($i = 0; $i < $lens; $i++) {
            $newcolor .= $hexColor[$i] . $hexColor[$i];
        }
    } else {
        $newcolor = $hexColor;
    }
    $hex = str_split($newcolor, 2);
    $rgb = [];
    foreach ($hex as $key => $vls) {
        $rgb[] = hexdec($vls);
    }
    return $rgb;
}

/**
 * RGB转十六进制HEX
 * @param string $rgb RGB颜色的字符串 如：rgb(255,255,255);
 * @return string $hexColor 十六进制颜色值 如：#FFFFFF
 */
function rgbtohex($rgb)
{
    $regexp = "/^rgb\(([0-9]{0,3})\,\s*([0-9]{0,3})\,\s*([0-9]{0,3})\)/";
    $re = preg_match($regexp, $rgb, $match);
    $re = array_shift($match);
    $hexColor = "#";
    $hex = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F');
    for ($i = 0; $i < 3; $i++) {
        $r = null;
        $c = $match[$i];
        $hexAr = array();
        while ($c > 16) {
            $r = $c % 16;
            $c = ($c / 16) >> 0;
            array_push($hexAr, $hex[$r]);
        }
        array_push($hexAr, $hex[$c]);
        $ret = array_reverse($hexAr);
        $item = implode('', $ret);
        $item = str_pad($item, 2, '0', STR_PAD_LEFT);
        $hexColor .= $item;
    }
    return $hexColor;
}

/**
 * HEX十六进制转RGB
 * @param string $hexColor
 * @return string $rgb
 */
function hextorgb($hexColor)
{
    $color = str_replace('#', '', $hexColor);
    if (strlen($color) > 3) {
        $rgb = array(
            'r' => hexdec(substr($color, 0, 2)),
            'g' => hexdec(substr($color, 2, 2)),
            'b' => hexdec(substr($color, 4, 2))
        );
    } else {
        $color = $hexColor;
        $r = substr($color, 0, 1) . substr($color, 0, 1);
        $g = substr($color, 1, 1) . substr($color, 1, 1);
        $b = substr($color, 2, 1) . substr($color, 2, 1);
        $rgb = array(
            'r' => hexdec($r),
            'g' => hexdec($g),
            'b' => hexdec($b)
        );
    }
    return $rgb;
}
