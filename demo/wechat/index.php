<?php
	$index = new Modtemplate;
	$mysql = new Modmysql;
	$mysql->load(MYSQL_HOST,MYSQL_USER,MYSQL_WORD,MYSQL_DB,MYSQL_CHAR,MYSQL_PORT);

	$dx_username='admin';
	if(isset($_GET['username'])){
		$row=$mysql->fetch_array($mysql->query('select id from user where username="'.$_GET['username'].'";'));
		if ($row) {
			$dx_username=$_GET['username'];
		}
		
	}

	if(isset($_GET['typeid'])){
		$temp=<<<EOP
					{{文章列表}}
					<tr>
						<td onclick="getblogtext('{{id}}');">
							<img src="{{tx}}"/>
							<span><b>{{title}}</b></span>
							<p>⌚{{time}}</p>
						</td>
					</tr>
					{{/文章列表}}
EOP;
		$index->load($temp);

		if($dx_username=='admin'){
			$row = $mysql->fetch_all($mysql->query('select blogs.id,blogs.title,blogs.time,user.tx from blogs,user where blogs.type='.$_GET['typeid'].' and user.username=blogs.username order by blogs.id desc;'));
		}else{
			$row = $mysql->fetch_all($mysql->query('select blogs.id,blogs.title,blogs.time,user.tx from blogs,user where blogs.type='.$_GET['typeid'].' and blogs.username="'.$dx_username.'" and user.username=blogs.username order by blogs.id desc;'));

		}

		if(!$row){
			$mysql->exit();
			exit("欧尼酱,没有文章了呢~");
		} 
		foreach ($row as $key => $value) {
			if(strlen($value['title'])>23)	$value['title']=substr($value['title'],0,23).'...';
			$index->setrows('文章列表',$value);
		}
		$index->show();
		$mysql->exit();
		exit;
	}

	if(isset($_GET['blogid'])){
		$temp=<<<EOP
					{{文章}}
							<h4>{{title}}</h4>
							<hr/>
							<div id="blogtext" class="blogtext">
								{{content}}
				                <p>&nbsp;</p>
				      		</div>
				      		<hr/>
				    {{/文章}}
EOP;
		$index->load($temp);
		$row = $mysql->fetch_array($mysql->query('select title,content from blogs where id='.$_GET['blogid']));
		$row['content']=base64_decode($row['content']);
		$index->setrows('文章',$row);

		$index->show();
		$mysql->exit();
		exit;
	}
	//*******************默认返回首页

	$index->loadfile('demo/wechat/index.html');

	$row = $mysql->fetch_all($mysql->query('select blog_type.id,blog_type.name,user.tx from blog_type,user where user.username="'.$dx_username.'" order by id asc ;'));
	$index->settext('tx',$row[0]['tx']);
	$index->settext('username',$dx_username);
	foreach ($row as $key => $value) {
		$index->setrows('文章分类',$value);
	}
	$index->show();
	$mysql->exit();
	exit;
?>