<?php
	$mysql=new Modmysql;
	$mysql->load(MYSQL_HOST,MYSQL_USER,MYSQL_WORD,MYSQL_DB,MYSQL_CHAR,MYSQL_PORT);
	$row = $mysql->fetch_all($mysql->query('select id,name,type,size,sha1,time from files order by id desc;'));
	$index = new Modtemplate;
	$index->load('demo/filedb/table.html');
	foreach ($row as $key => $value) {
		$index->setrows('jilu',$value);
	}
	$index->show();
	$mysql->close();
?>