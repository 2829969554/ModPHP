<?php
/*------------------引入文件------------------*/
require 'lib/modphp.php';
require 'lib/moddb.php';
header("Content-Type: text/html;charset=utf-8");
/*------------------路由定义------------------*/
Bind_View_Fun('/','index');
Bind_View_Fun('/index.php','index');
Bind_View_Fun('/index.php/add','add');
Bind_View_File('/index.php/download','error/404.html');
Bind_Error(404);
/*------------------业务代码------------------*/
function index(){

	$mysql=new Modmysql;
	$mysql->load('localhost','root','root','modphp');
	echo json_encode($mysql->fetch_all($mysql->query('select * from user;')));
	$mysql->close();

}
function add(){
	$mysql=new Modmysql;
	$mysql->load('localhost','root','root','modphp');
	$mysql->query('insert into user (id) values (null);');
	$mysql->close();
}
?>