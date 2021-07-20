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
    $func = get_defined_functions();

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
    <meta name="robots" content="all" />
    <meta name="referrer" content="always" />
    <meta name="keywords" content="PHP,probe,phpinfo," />
    <meta name="description" content="" />
    <title><?=$lang['php'].' '.$lang['probe'].' v'.$version;?></title>
    <link rel="bookmark" href="favicon.ico">
    <link rel="shortcut icon" href="favicon.ico" type="image/ico" sizes="16x16">
    <style>
        body,html{margin: 0;padding: 0;color: #525252;font-size: 14px;font-family: Arial, Helvetica, sans-serif;}
        div#header{position: fixed;top: 0;left: 0;width: 100%;background-color: rgba(0, 0, 0, .5);}
        main,footer{margin: 10px auto;width: 90%;}
        main{margin-top: 62px;}
        h1{margin: 0 auto;line-height: 42px;text-align: center;color: #fff;}
        a{text-decoration: none;color: #4b8bf4;}
        hr.bunting{height:5px;border:0;background:#c4e17f;border-radius:5px;background-image:-webkit-linear-gradient(left,#c4e17f,#c4e17f 12.5%,#f7fdca 12.5%,#f7fdca 25%,#fecf71 25%,#fecf71 37.5%,#f0776c 37.5%,#f0776c 50%,#db9dbe 50%,#db9dbe 62.5%,#c49cde 62.5%,#c49cde 75%,#669ae1 75%,#669ae1 87.5%,#62c2e4 87.5%,#62c2e4);background-image:-moz-linear-gradient(left,#c4e17f,#c4e17f 12.5%,#f7fdca 12.5%,#f7fdca 25%,#fecf71 25%,#fecf71 37.5%,#f0776c 37.5%,#f0776c 50%,#db9dbe 50%,#db9dbe 62.5%,#c49cde 62.5%,#c49cde 75%,#669ae1 75%,#669ae1 87.5%,#62c2e4 87.5%,#62c2e4);background-image:-o-linear-gradient(left,#c4e17f,#c4e17f 12.5%,#f7fdca 12.5%,#f7fdca 25%,#fecf71 25%,#fecf71 37.5%,#f0776c 37.5%,#f0776c 50%,#db9dbe 50%,#db9dbe 62.5%,#c49cde 62.5%,#c49cde 75%,#669ae1 75%,#669ae1 87.5%,#62c2e4 87.5%,#62c2e4);background-image:linear-gradient(to right,#c4e17f,#c4e17f 12.5%,#f7fdca 12.5%,#f7fdca 25%,#fecf71 25%,#fecf71 37.5%,#f0776c 37.5%,#f0776c 50%,#db9dbe 50%,#db9dbe 62.5%,#c49cde 62.5%,#c49cde 75%,#669ae1 75%,#669ae1 87.5%,#62c2e4 87.5%,#62c2e4)}
        main table{margin: 20px 0;}
        main table input{width: 90%;height: 30px;border: 1px solid #e3e3e3;padding: 0 5px;color: #409eff;outline: none;}
        main th,main td{padding: 2px 10px;height: 30px;}
        th.info-title{width: 15%;text-align: right;background-color: #eeeeee;color: #4b8bf4;}
        td.info-main{width: 35%;text-align: left;background-color: #f7f7f7;}
        .title-main,.title-sub{color: #fff;}
        .title-sub{background-color: #e3e3e3;}
        .phpfunc .title-main{background-color: #c4e17f;}
        .phpparam th.param-t{background-color:#eeeeee;text-align: center;}
        .phpparam td.param-v{background-color: #f7f7f7;text-align: center;}
        ul li{list-style: none;float: left;}
        ul.func li{margin: 5px;padding: 0 10px;background-color: #c5d0d7;border-radius: 3px;color: #5082d5;line-height: 32px;}
        ul.func li::before{margin: 2px;line-height: 16px;display: inline-grid;content: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAAAA3NCSVQICAjb4U/gAAABO1BMVEXu7u7n5+fk5OTi4uLg4ODd3d3X19fV1dXU1NTS0tLPz8+7z+/MzMy6zu65ze65zu7Kysq3zO62zO3IyMjHx8e1yOiyyO2yyOzFxcXExMSyxue0xuexxefDw8OtxeuwxOXCwsLBwcGuxOWsw+q/v7+qweqqwuqrwuq+vr6nv+qmv+m7u7ukvumkvemivOi5ubm4uLicuOebuOeat+e0tLSYtuabtuaatuaXteaZteaatN6Xs+aVs+WTsuaTsuWRsOSrq6uLreKoqKinp6elpaWLqNijo6OFpt2CpNyAo92BotyAo9+dnZ18oNqbm5t4nt57nth7ntp4nt15ndp3nd6ZmZmYmJhym956mtJzm96WlpaVlZVwmNyTk5Nvl9lultuSkpKNjY2Li4uKioqIiIiHh4eGhoZQgtVKfNFdha6iAAAAaXRSTlMA//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////914ivwAAAACXBIWXMAAAsSAAALEgHS3X78AAAAH3RFWHRTb2Z0d2FyZQBNYWNyb21lZGlhIEZpcmV3b3JrcyA4tWjSeAAAAKFJREFUGJVjYIABASc/PwYkIODDxBCNLODEzGiQgCwQxsTlzJCYmAgXiGKVdHFxYEuB8dkTOIS1tRUVocaIWiWI8IiIKKikaoD50kYWrpwmKSkpsRC+lBk3t2NEMgtMu4wpr5aeuHcAjC9vzadjYyjn7w7lK9kK6tqZK4d4wBQECenZW6pHesEdFC9mbK0W7otwsqenqmpMILIn4tIzgpG4ADUpGMOpkOiuAAAAAElFTkSuQmCC");}
    </style>
</head>
<body>
    <header>
        <?php include("public/header"); ?>
    </header>
    
    <main>
        <table class="phpfunc" width="100%">
            <tr>
                <th class="title-main" colspan="4"><?=$lang['php'].$lang['free'].$lang['functions'];?></th>
            </tr>
            <tr>
                <td colspan="4" class="info-main">
                    <ul class="func">
                    <?php
                        for ($i=0; $i < count($func['internal']); $i++) { 
                            echo "<li>".$func['internal'][$i]."()</li>";
                        }
                    ?>
                    </ul>
                </td>
            </tr>
        </table>
    </main>

    <footer>
        <?php include_once("./public/footer");?>
    </footer>
</body>
</html>