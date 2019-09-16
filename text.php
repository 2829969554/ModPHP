<?php
//引用ModPHP模块
include 'modphp.php';
//定义模板类型
$index = new ModTempLate;
//载入模板
$index->load('text.html');
//设置模板变量
$index->settext('姓名','鲁班七号');
$index->settext('年龄','7');
//模拟随机生成10用户
for ($i=0; $i < 10; $i++) { 
	$row['id']=$i+1;
	$row['user']=$i.time();
	$row['pwd']=sha1($i);
	$row['ms']='PC';
	//生成的用户信息渲染到模板
	$index->setrows('用户列表',$row);
}
//页面显示
$index->show();
?>