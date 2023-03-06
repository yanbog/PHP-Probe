<?php
    @header("content-Type:text/html;charset=utf-8");
    error_reporting(E_ALL ^ E_NOTICE);
    include("common.php");
    
    session_start();
    
    // include public
    include("public/public.php");

    $l = get_browser_lang();
    $lang = include("lang/".$l.".php");
    $info = get_server_info();
    $self = htmlentities($_SERVER['PHP_SELF']);
    $exte = get_loaded_extensions();
    $func = get_defined_functions();

    @define("YES", "<span class='mod_enable'>".$lang['enable']."</span>");
    @define("NO", "<span class='mod_disable'>".$lang['disable']."</span>");

    @define("MSGSU", "<span class='msg_success'>".$lang['msg_mysql_success'].':'.$lang['mysql']."-</span>");
    @define("MSGER", "<span class='msg_error'>".$lang['msg_mysql_error'].': '.$errno."</span>");

    $phpinfo = func_enabled("phpinfo") ? YES : NO;
    $phperror = ini_get("display_errors") ? YES : NO;
    $globalreg = ini_get("register_globals") ? YES : NO;

    // php common module
    $mysqli = get_extension_funcs("mysqli") ? YES : NO;
    $openssl = get_extension_funcs("openssl") ? YES : NO;
    $curl = get_extension_funcs("curl") ? YES : NO;
    $json = get_extension_funcs("json") ? YES : NO;
    $xml = get_extension_funcs("xml") ? YES : NO;
    $ftp = get_extension_funcs("ftp") ? YES : NO;
    $exif = get_extension_funcs("exif") ? YES : NO;
    $fileinfo = get_extension_funcs("fileinfo") ? YES : NO;
    $mbstring = get_extension_funcs("mbstring") ? YES : NO;
    $session = get_extension_funcs("session") ? YES : NO;
    $gd = get_extension_funcs("gd") ? YES : NO;
    $bz2 = get_extension_funcs("bz2") ? YES : NO;

    // orther module
    $zo = get_extension_funcs('Zend Optimizer') ? YES : NO;
    $zgl = get_extension_funcs('Zend Guard Loader') ? YES : NO;
    $zil = get_extension_funcs('ionCube Loader') ? YES : NO;
    $zsg = get_extension_funcs('SourceGuardian') ? YES : NO;
    $wc = get_extension_funcs('WinCache') ? YES : NO;
    $zoc = get_extension_funcs('Zend OPcache') ? YES : NO;
    $mem = get_extension_funcs('memcache') ? YES : NO;
    $red = phpversion('redis') ? YES : NO;

    // mysql connect test
    $host = $_POST['hostname'] ? $_POST['hostname'] : '127.0.0.1';
    $user = $_POST['username'] ? $_POST['username'] : 'root';
    $pawd = $_POST['password'] ? $_POST['password'] : '';
    $db = $_POST['database'] ? $_POST['database'] : NULL;
    $port = $_POST['port'] ? $_POST['port'] : 3306;
    $conn= @mysqli_connect($host, $user, $pawd, $db, $port);
    // mysqli_set_charset($conn, 'utf8');
    $errno = mysqli_connect_errno();
?>

<!DOCTYPE html>
<html lang="<?=$l;?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="<?=$author?>" />
    <meta name="reply-to" content="<?=$reply?>" />
    <meta name="copyright" content="2012-<?=date("Y").' '.$author?> &copy;Inc." />
    <meta name="robots" content="none" />
    <meta name="referrer" content="always" />
    <meta name="keywords" content="PHP,Probe,PHP Probe,phpinfo,PHP探针,PHP服务器探针" />
    <meta name="description" content="精简版的PHPInfo，快速查看服务器PHP环境配置信息。" />
    <title><?=$lang['php'].' '.$lang['probe'].' v'.$version;?></title>
    <link rel="bookmark" href="favicon.ico">
    <link rel="shortcut icon" href="favicon.ico" type="image/ico" sizes="16x16">
    <style rel="stylesheet">
        body,html{margin: 0;padding: 0;color: #525252;font-size: 14px;font-family: Arial, Helvetica, sans-serif;}
        div#header{position: fixed;top: 0;left: 0;width: 100%;background-color: rgba(0, 0, 0, .5);}
        main,footer{margin: 10px auto;width: 90%;}
        main{margin-top: 62px;}
        h1{margin: 0 auto;line-height: 42px;text-align: center;color: #fff;}
        abbr{text-decoration: none;border:0;border-bottom: 1px dashed;cursor: help;padding-bottom: 2px;}
        a{text-decoration: none;color: #4b8bf4;}
        hr.bunting{height:5px;border:0;background:#c4e17f;border-radius:5px;background-image:-webkit-linear-gradient(left,#c4e17f,#c4e17f 12.5%,#f7fdca 12.5%,#f7fdca 25%,#fecf71 25%,#fecf71 37.5%,#f0776c 37.5%,#f0776c 50%,#db9dbe 50%,#db9dbe 62.5%,#c49cde 62.5%,#c49cde 75%,#669ae1 75%,#669ae1 87.5%,#62c2e4 87.5%,#62c2e4);background-image:-moz-linear-gradient(left,#c4e17f,#c4e17f 12.5%,#f7fdca 12.5%,#f7fdca 25%,#fecf71 25%,#fecf71 37.5%,#f0776c 37.5%,#f0776c 50%,#db9dbe 50%,#db9dbe 62.5%,#c49cde 62.5%,#c49cde 75%,#669ae1 75%,#669ae1 87.5%,#62c2e4 87.5%,#62c2e4);background-image:-o-linear-gradient(left,#c4e17f,#c4e17f 12.5%,#f7fdca 12.5%,#f7fdca 25%,#fecf71 25%,#fecf71 37.5%,#f0776c 37.5%,#f0776c 50%,#db9dbe 50%,#db9dbe 62.5%,#c49cde 62.5%,#c49cde 75%,#669ae1 75%,#669ae1 87.5%,#62c2e4 87.5%,#62c2e4);background-image:linear-gradient(to right,#c4e17f,#c4e17f 12.5%,#f7fdca 12.5%,#f7fdca 25%,#fecf71 25%,#fecf71 37.5%,#f0776c 37.5%,#f0776c 50%,#db9dbe 50%,#db9dbe 62.5%,#c49cde 62.5%,#c49cde 75%,#669ae1 75%,#669ae1 87.5%,#62c2e4 87.5%,#62c2e4)}
        main table{margin: 20px 0;}
        main table input{width: 90%;height: 30px;border: 1px solid #e3e3e3;padding: 0 5px;color: #409eff;outline: none;}
        main th,main td{padding: 2px 10px;height: 30px;}
        th.info-title{width: 15%;text-align: right;background-color: #eeeeee;color: #4b8bf4;}
        td.info-main{width: 35%;text-align: left;background-color: #f7f7f7;}
        .title-main,.title-sub{color: #fff;}
        .title-sub{background-color: #e3e3e3;}
        .phpinfo .title-main,.phpmod .title-main,.phpparam .title-main,.phpextend .title-main,.phpmysql .title-main,.phpfunction .title-main,.phpfunction .title-main{background-color: #4b8bf4;}
        .phpparam th.param-t{background-color:#eeeeee;text-align: center;}
        .phpparam td.param-v{background-color: #f7f7f7;text-align: center;}
        ul li{list-style: none;float: left;}
        ul.extends li,ul.functions li{margin: 5px;padding: 0 10px;background-color: #c5d0d7;border-radius: 6px;color: #505a80;line-height: 32px;cursor: pointer;}
        ul.extends li::before{margin: 2px;line-height: 16px;display: inline-grid;content: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABaUlEQVQ4jbWTz07CQBCH99VMg2ChtLRl2l0oUJBq4sFIiBgXCralUPDgmxgvvt7Pg6G2AWM4OMl32/nmT2YZ+4/wbvewewnsXoLx/RvOSn7/+AT1Y3g3O3g3O9BgfZ6AMcZokKAbZHBHW/h3r38LaJDggO1FsL0I3SBDN8hgeVGOIVanZcWWi4hxluP4G9Tp+bTA8mJ0gj06wR7Uj9HqvBzBhymu7F8F3y1SP4YYZzDEqoTuzuH4G+g8RM2aoWbNcGlMj2UtEYJfZzBEWEJzJFoihOOvcyrNybFA5yHc0RY6X5RokIQhQhSXrWgFgS6W0MUSZjcCH6bQ3XmOShIqSdAggUoSFeMRivYARXsoCPgCfJiCD1OYnSXIT0tobgjqx1CaJ+ZmjLGGs4Djb6A5EuSn0PkiX1aDZH7WpaolAclSxZo1yx9WmhO0exHavQgX9V8EalviQNWcoWr+CJTmFAdU++n8f/FXfAHvyi0k4MEI4QAAAABJRU5ErkJggg==");}
        input[type="text"],input[type="password"]{ime-mode:disabled;}
        input[type="submit"]{width: 80%;cursor: pointer;text-transform: uppercase;font-weight: 700;}
        span.mod_enable,span.msg_success{color: #a6e22e;}
        span.mod_disable,span.msg_error{color: #f92672;}
    </style>
</head>
<body>
    <header>
        <?php include("public/header"); ?>
    </header>
    
    <main>
        <table class="phpinfo" width="100%">
            <tr>
                <th class="title-main" colspan="4"><?=$lang['server'].$lang['information'];?></th>
            </tr>
            <tr>
                <th class="info-title"><?=$lang['server'].$lang['domainname'];?></th>
                <td class="info-main"><?=$info['server_name'];?></td>
                <th class="info-title"><?=$lang['server'].$lang['ip'];?></th>
                <td class="info-main"><?=$info['server_ip'];?></td>
            </tr>
            <tr>
                <th class="info-title"><?=$lang['server'].$lang['port'];?></th>
                <td class="info-main"><?=$info['server_port'];?></td>
                <th class="info-title"><?=$lang['server'].$lang['protocol'];?></th>
                <td class="info-main"><?=$info['server_protocol'];?></td>
            </tr>
            <tr>
                <th class="info-title"><?=$lang['server'].$lang['name'];?></th>
                <td class="info-main"><?=$info['server_os_name'];?></td>
                <th class="info-title"><?=$lang['server'].$lang['environment'];?></th>
                <td class="info-main"><?=$info['server_os_info'];?></td>
            </tr>
            <tr>
                <th class="info-title"><?=$lang['request'].$lang['method'];?></th>
                <td class="info-main"><?=$info['server_request'];?></td>
                <th class="info-title"><?=$lang['software'].$lang['environment'];?></th>
                <td class="info-main"><?=$info['server_software'];?></td>
            </tr>
            <tr>
                <th class="info-title"><?=$lang['disk'].$lang['space'].$lang['total'];?></th>
                <td class="info-main"><?=$info['disk_total'];?></td>
                <th class="info-title"><?=$lang['disk'].$lang['free'].$lang['space'];?></th>
                <td class="info-main"><?=$info['disk_free'];?></td>
            </tr>
            <tr>
                <th class="info-title"><?=$lang['website'].$lang['root'].$lang['directory'];?></th>
                <td class="info-main"><?=$info['document_root'];?></td>
                <th class="info-title"><?=$lang['system'].$lang['temp'].$lang['directory'];?></th>
                <td class="info-main"><?=$info['system_temp_dir'];?></td>
            </tr>
            <tr>
                <th class="info-title"><?=$lang['server'].$lang['time_zone'];?></th>
                <td class="info-main"><?=$info['server_time_zone'];?></td>
                <th class="info-title"><?=$lang['platform'].$lang['architecture'];?></th>
                <td class="info-main"><?=$info['server_os'].' '.$info['server_os_mode'];?></td>  
            </tr>
            <tr>
                <th class="info-title"><?=$lang['server'].$lang['time'];?></th>
                <td class="info-main"><?=$info['server_time'];?></td>
                <th class="info-title"><?=$lang['beijing_time'];?></th>
                <td class="info-main"><?=$info['beijing_time'];?></td>
            </tr>
            <tr>
                <th class="title-sub" colspan="4"><?=$lang['php'].$lang['information'];?></th>
            </tr>
            <tr>
                <th class="info-title"><?=$lang['php'].$lang['version'];?></th>
                <td class="info-main"><?=$info['php_version'];?></td>
                <th class="info-title"><?=$lang['php'].$lang['zend'].$lang['version'];?></th>
                <td class="info-main"><?=$info['php_zend_version'];?></td>
            </tr>
            <tr>
                <th class="info-title"><?=$lang['php'].$lang['profile'];?></th>
                <td class="info-main"><?=$info['php_ini_loaded_file'];?></td>
                <th class="info-title"><?=$lang['php_sapi_name'];?></th>
                <td class="info-main"><?=$info['php_sapi_name'];?></td>
            </tr>
            <tr>
                <th class="info-title"><abbr title="phpinfo">PHPINFO</abbr></th>
                <td class="info-main"><?=$phpinfo;?></td>
                <th class="info-title"><abbr title="PHPSESSID"><?=$lang['session'].' '.$lang['id'];?></abbr></th>
                <td class="info-main"><?=$info['session_id'];?></td>
            </tr>
            <tr>
                <th class="info-title"><abbr title="display_errors"><?=$lang['display'].$lang['error']?></abbr></th>
                <td class="info-main"><?=$phperror;?></td>
                <th class="info-title"><abbr title="register_globals"><?=$lang['global'].$lang['register']?></abbr></th>
                <td class="info-main"><?=$globalreg;?></td>
            </tr>
            <tr>
                <th class="info-title"><abbr title="short_open_tag"><?=$lang['short'].$lang['tag']?></abbr></th>
                <td class="info-main"><?=ini_get('short_open_tag') ? NO : YES;?></td>
                <th class="info-title"><abbr title="engine"><?=$lang['apache'].$lang['engine']?></abbr></th>
                <td class="info-main"><?=ini_get('engine') ? YES : NO;?></td>
            </tr>
        </table>

        <table class="phpmod" width="100%">
            <tr>
                <th class="title-main" colspan="4"><?=$lang['php'].$lang['common'].$lang['module'].$lang['enable'].$lang['status'];?></th>
            </tr>
            <tr>
                <th class="info-title">mysqli</th>
                <td class="info-main"><?=$mysqli;?></td>
                <th class="info-title">openssl</th>
                <td class="info-main"><?=$openssl;?></td>
            </tr>
            <tr>
                <th class="info-title">curl</th>
                <td class="info-main"><?=$curl;?></td>
                <th class="info-title">json</th>
                <td class="info-main"><?=$json;?></td>
            </tr>
            <tr>
                <th class="info-title">xml</th>
                <td class="info-main"><?=$xml;?></td>
                <th class="info-title">ftp</th>
                <td class="info-main"><?=$ftp;?></td>
            </tr>
            <tr>
                <th class="info-title">exif</th>
                <td class="info-main"><?=$exif;?></td>
                <th class="info-title">fileinfo</th>
                <td class="info-main"><?=$fileinfo;?></td>
            </tr>
            <tr>
                <th class="info-title">mbstring</th>
                <td class="info-main"><?=$mbstring;?></td>
                <th class="info-title">session</th>
                <td class="info-main"><?=$session;?></td>
            </tr>
            <tr>
                <th class="info-title">gd</th>
                <td class="info-main"><?=$gd;?></td>
                <th class="info-title">bz2</th>
                <td class="info-main"><?=$bz2;?></td>
            </tr>
        </table>

        <table class="phpparam" width="100%">
            <tr>
                <th class="title-main" colspan="6"><?=$lang['php'].$lang['common'].$lang['param'];?></th>
            </tr>
            <tr>
               <th class="param-t"><abbr title="memory_limit">Memory <?=$lang['limit'];?></abbr></th>
               <td class="param-v"><abbr title="upload_max_filesize">Upload Max</abbr></td>
               <th class="param-t"><abbr title="post_max_size">POST Max</abbr></th>
               <td class="param-v"><abbr title="max_execution_time">Execution <?=$lang['timeout'];?></abbr></td>
               <th class="param-t"><abbr title="max_input_time">Input <?=$lang['timeout'];?></abbr></th>
               <td class="param-v"><abbr title="default_socket_timeout">Socket <?=$lang['timeout'];?></abbr></td>
            </tr>
            <tr>
                <td class="param-v"><?=ini_get('memory_limit') ?></td>
                <th class="param-t"><?=ini_get('upload_max_filesize') ?></th>
                <td class="param-v"><?=ini_get('post_max_size') ?></td>
                <th class="param-t"><?=ini_get('max_execution_time') . 's' ?></th>
                <td class="param-v"><?=ini_get('max_input_time') . 's' ?></td>
                <th class="param-t"><?=ini_get('default_socket_timeout') . 's' ?></th>
            </tr>
        </table>

        <table class="phpparam" width="100%">
            <tr>
                <th class="title-main" colspan="8"><?=$lang['php'].$lang['common'].$lang['tuning'].$lang['module'];?></th>
            </tr>
            <tr>
                <th class="title-sub" colspan="4"><?=$lang['php'].$lang['zend'].$lang['encrypt'].$lang['decrypt'].$lang['module'];?></th>
                <th class="title-sub" colspan="4"><?=$lang['php'].$lang['cache'].$lang['tuning'].$lang['module'];?></th>
            </tr>
            <tr>
                <td class="param-v">Zend Optimizer</td>
                <th class="param-t">Zend GuardLoader</th>
                <td class="param-v">Zend ionCube Loader</td>
                <th class="param-t">Source Guardian</th>
                <td class="param-v">WinCache</td>
                <th class="param-t">Zend OPcache</th>
                <td class="param-v">Memcache</td>
                <th class="param-t">Redis</th>
               
            </tr>
            <tr>
                <th class="param-t"><?=$zo;?></th>
                <td class="param-v"><?=$zgl;?></td>
                <th class="param-t"><?=$zil;?></th>
                <td class="param-v"><?=$zsg;?></td>
                <th class="param-t"><?=$wc;?></th>
                <td class="param-v"><?=$zoc;?></td>
                <th class="param-t"><?=$mem;?></th>
                <td class="param-v"><?=$red;?></td>
            </tr>
            <tr>
                <td class="title-sub" colspan="8" align="center">
                    
                </td>
            </tr>
        </table>

        <table class="phpextend" width="100%">
            <tr>
                <th class="title-main" colspan="4"><?=$lang['php'].$lang['loaded'].$lang['module'];?></th>
            </tr>
            <tr>
                <td colspan="4" class="info-main">
                    <ul class="extends">
                    <?php
                        for ($i=0; $i < count($exte); $i++) { 
                            echo "<li class='enabled_model'><a target='_blank' href='https://www.php.net/manual/zh/book.{$exte[$i]}.php'>{$exte[$i]}</a></li>";
                        }
                    ?>
                    </ul>
                </td>
            </tr>
        </table>

        <table class="phpmysql" width="100%">
            <form action="./" method="post">
                <tr>
                    <th class="title-main" colspan="4"><?=$lang['mysql'].$lang['connection'].$lang['test'];?></th>
                </tr>
                <tr>
                    <th class="info-title"><?=$lang['database'].$lang['host'];?></th>
                    <td class="info-main"><input type="text" name="hostname" id="hostname" value="127.0.0.1" disabled onpaste="return false" ></td>
                    <th class="info-title"><?=$lang['database'].$lang['port'];?></th>
                    <td class="info-main"><input type="text" name="port" id="port" value="3306" onpaste="return false"></td>
                </tr>
                <tr>
                    <th class="info-title"><?=$lang['database'].$lang['username'];?></th>
                    <td class="info-main"><input type="text" name="username" id="username" onpaste="return false"></td>
                    <th class="info-title"><?=$lang['database'];?></th>
                    <td class="info-main"><input type="text" name="database" id="database" onpaste="return false"></td>
                </tr>
                <tr>
                    <th class="info-title"><?=$lang['database'].$lang['password'];?></th>
                    <td class="info-main"><input type="password" name="password" id="password" onpaste="return false"></td>
                    <td class="info-main" colspan="2" style="text-align: center;">
                        <input type="submit" name="submit" id="submit" value="<?=$lang['test'].$lang['connection'];?>">
                    </td>
                </tr>
                <tr>
                    <td class="title-sub" colspan="4" align="center">
                        <?php
                            if($conn){
                                echo MSGSU.mysqli_get_server_info($conn);
                            }else{
                                echo MSGER.mysqli_connect_error();
                            }
                        ?>
                    </td>
                </tr>
            </form>
        </table>

        <table class="phpfunction" width="100%">
            <tr>
                <th class="title-main" colspan="4"><?=$lang['php'].$lang['internal'].$lang['function'];?></th>
            </tr>
            <tr>
                <td colspan="4" class="info-main">
                    <ul class="functions">
                    <?php
                        for ($i=0; $i < count($func['internal']); $i++) { 
                            echo "<li class='enabled_model'><a target='_blank' href='https://www.php.net/manual/zh/function.{$func['internal'][$i]}'>{$func['internal'][$i]}</a></li>";
                        }
                    ?>
                    </ul>
                </td>
            </tr>
        </table>

    </main>

    <footer>
        <?php include("./public/footer");?>
    </footer>
</body>
</html>