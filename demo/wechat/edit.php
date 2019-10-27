<?php


	if (isGET()) {
		$mysql= new Modmysql;
		$mysql->load(MYSQL_HOST,MYSQL_USER,MYSQL_WORD,MYSQL_DB,MYSQL_CHAR,MYSQL_PORT);
		if(isset($_GET['id']) && $_GET['id'] !='{{id}}'){
			$edit = new Modtemplate;
			$edit->loadfile('demo/wechat/trueedit.html');
			$row = $mysql->fetch_all($mysql->query('select * from blog_type;'));
			foreach ($row as $key => $value) {
				$temp=array();
				$temp['tid']=$value['id'];
				$temp['tname']=$value['name'];
				$edit->setrows('文章类型',$temp);
			}
			$row = $mysql->fetch_array($mysql->query('select title,content from blogs where id='.$_GET['id']));
			$row['content']=base64_decode($row['content']);
			$edit->settext('id',$_GET['id']);
			$edit->setrows('文章',$row);
			$edit->show();
			$mysql->exit();
			exit;		
		}else{
			$edit = new Modtemplate;
			$edit->loadfile('demo/wechat/trueedit.html');
			$row = $mysql->fetch_all($mysql->query('select * from blog_type;'));
			foreach ($row as $key => $value) {
				$temp=array();
				$temp['tid']=$value['id'];
				$temp['tname']=$value['name'];
				$edit->setrows('文章类型',$temp);
			}
			$row['title']='';
			$row['content']='';
			$edit->setrows('文章',$row);
			$edit->show();
			$mysql->exit();
			exit;
		}

	}

	if(isPOST()){
		$mysql= new Modmysql;
		$mysql->load(MYSQL_HOST,MYSQL_USER,MYSQL_WORD,MYSQL_DB,MYSQL_CHAR,MYSQL_PORT);

		if (isset($_POST['id']) && isset($_POST['delblog'])) {
			if($_POST['id'] !='{{id}}'){
				$isok=$mysql->query('delete from blogs where id='.$_POST['id']);
				$isok = $isok ? 'yes' :'no';
				$mysql->exit();
				exit($isok);

			}
			
		}
		//新建文章和修改文章
		if(isset($_POST['title'])  && isset($_POST['content']) && isset($_POST['id']) && isset($_POST['type'])){
			if($_POST['id']){
				
				if($_POST['id']=='{{id}}'){
					//新建文章
					$isok=$mysql->query('insert into blogs (title,content,type,username,time) values ("'.$_POST['title'].'","'.base64_encode($_POST['content']).'",'.$_POST['type'].',"admin","'.date("Y-m-d H:i:s").'")');
					$isok = $isok ? 'yes' :'no';
					$mysql->exit();
					exit($isok);	
				}else{
					//修改文章
					$isok=$mysql->query('update blogs set type='.$_POST['type'].',title="'.$_POST['title'].'",content="'.base64_encode($_POST['content']).'" where id ='.$_POST['id']);
					$isok = $isok ? 'yes' :'no';
					$mysql->exit();
					exit($isok);				
				}
			}
		}
	$mysql->exit();
	exit;
	}

?>