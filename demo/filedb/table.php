<?php
	$mysql=new Modmysql;
	$mysql->load('localhost','root','root','filedb');
	$row = $mysql->fetch_all($mysql->query('select id,name,type,size,sha1,time from files order by id desc;'));
	$index = new Modtemplate;
	$index->load('demo/filedb/table.html');
	foreach ($row as $key => $value) {
		$index->setrows('jilu',$value);
	}
	$index->show();
	$mysql->close();
?>