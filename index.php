<?php
/*------------------引入文件------------------*/
require 'config.php';
require 'lib/modphp.php';
require 'lib/moddb.php';
require 'lib/modrsa.php';

header("Content-Type: text/html;charset=utf-8");
/*------------------路由定义------------------*/
Bind_View_File('/','demo/index.html');
Bind_View_File('/table','demo/filedb/table.php');
Bind_View_File('/rsa','demo/rsa/rsa.php');
Bind_View_File('/push','demo/filedb/push.php');
Bind_View_File('/wechat','demo/wechat/index.php');
Bind_View_File('/file','error/404.html');
Bind_Error(404);
/*------------------业务代码------------------*/

?>