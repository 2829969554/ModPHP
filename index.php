<?php
/*------------------引入文件------------------*/
require 'lib/modphp.php';
require 'lib/moddb.php';
require 'lib/modrsa.php';
header("Content-Type: text/html;charset=utf-8");
/*------------------路由定义------------------*/
Bind_View_Fun('/','index');
Bind_View_Fun('/index.php','index');
Bind_View_Fun('/index.php/','index');
Bind_View_Fun('/index.php/add','add');
Bind_View_Fun('/index.php/rsa','rsa');
Bind_View_File('/index.php/download','error/404.html');
Bind_Error(404);
/*------------------业务代码------------------*/

//首页，查询并输出user表全部数据
function index(){

	$mysql=new Modmysql;
	$mysql->load('localhost','root','root','modphp');
	echo json_encode($mysql->fetch_all($mysql->query('select * from user;')));
	$mysql->close();

}
//访问自动插入一个新纪录
function add(){
	$mysql=new Modmysql;
	$mysql->load('localhost','root','root','modphp');
	$mysql->query('insert into user (id) values (null);');
	$mysql->close();

	echo '成功插入一条新数据';
}
//rsa例子
function rsa(){
	$rsa = new Modrsa;
	$show = new Modtemplate;
	$show->load('demo/rsa.html');

	$rsa->setup_public('demo/public.crt');
	$rsa->setup_private('demo/private.pem');
	$rsa->setcharset(2);

	$temp['内容']='我有一个小老虎我从来也不骑';
	$temp['公钥加密']= $rsa->public_encrypt($temp['内容']);
	$temp['私钥解密']= $rsa->private_decrypt($temp['公钥加密']);
	$temp['私钥加密'] = $rsa->private_encrypt($temp['私钥解密']);
	$temp['公钥解密'] = $rsa->public_decrypt($temp['私钥加密']);
	$temp['签名'] =$rsa->sign("我爱你");
	$temp['验签'] =$rsa->verify("我爱你",$temp['签名']);

	$show->setrows('rsa',$temp);
	$show->show();

}
?>