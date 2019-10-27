<?php
$qj_tx='';
	if(isGET()){
		$suomysql = new Modmysql;
		$suomysql->load(MYSQL_HOST,MYSQL_USER,MYSQL_WORD,MYSQL_DB,MYSQL_CHAR,MYSQL_PORT);
		if(!isset($_SERVER['PHP_AUTH_USER']))
		{
            header('WWW-Authenticate: Basic');
    		header('HTTP/1.0 401 Unauthorized');
            exit;
        }
        else
        {
        	$u = addslashes($_SERVER['PHP_AUTH_USER']);
        	$p = addslashes($_SERVER['PHP_AUTH_PW']);
			$row = $suomysql->fetch_array($suomysql->query('select * from user where username="'.$u.'" and password="'.$p.'";'));

        	if(!$row){
        		
        		header('WWW-Authenticate: Basic');
    			header('HTTP/1.0 401 Unauthorized');
    			exit;
    			$suomysql->exit();
        	}
        	$qj_tx=$row['tx'];	
        	$suomysql->exit();
        }



		$admin = new Modtemplate;
		$mysql = new Modmysql;
		$mysql->load(MYSQL_HOST,MYSQL_USER,MYSQL_WORD,MYSQL_DB,MYSQL_CHAR,MYSQL_PORT);

		//新建类型
		if(isset($_GET['xinjiantype'])){
			$isok =$mysql->query('insert into blog_type (name) values ("'.$_GET['xinjiantype'].'");');
			$isok = $isok ? 'yes' :'no';
			$mysql->exit();
			exit($isok);
		}

		//删除类型
		if(isset($_GET['shanchutype'])){
			$isok =$mysql->query('delete from blog_type where id = '.$_GET['shanchutype']);
			$isok = $isok ? 'yes' :'no';
			$mysql->exit();
			exit($isok);
		}

		//获取类型列表
		if(isset($_GET['type_list'])){
			$temp=<<<EOP
			类型名称：<input type="text" style="width:50%;" id="xinjian_name"></input>
					<button class="button_mr" onclick="xinjiantype('xinjian_name');" name="daohanglan_button">新建</button>
					{{分类列表}}
						<tr>
							<td>
								<img src="img/tx.jpg"/>
								<span><b>{{name}}</b></span>
								<button onclick="shanchutype('{{id}}');" style="color:red;float:right;background-color: red;color:white;border: 0px;font-size:15px;margin-left: 60px;" name="daohanglan_button">删除</button>
							</td>
						</tr>
					{{/分类列表}}
EOP;
			$admin->load($temp);
			$row = $mysql->fetch_all($mysql->query('select * from blog_type order by id asc;'));
			//是否存在记录
			if($row){
				foreach ($row as $key => $value) {
					$admin->setrows('分类列表',$value);
				}
				$admin->show();
				$mysql->exit();
				exit;

			}else{

$temp=<<<EOP
			类型名称：<input type="text" style="width:50%;" id="xinjian_name"></input>
					<button class="button_mr" onclick="xinjiantype('xinjian_name');" name="daohanglan_button">新建</button>
EOP;
$mysql->exit();
exit($temp);
			}

		}

		//获取用户列表
		if(isset($_GET['user_list'])){
			$temp=<<<EOP
					{{用户列表}}
						<tr>
							<td onclick="getmaintext('user',{{id}});">
								<img src="{{tx}}"/>
								<span><b>{{name}}</b></span>
								<br/>
								<p>账号:{{username}}</p>
							</td>
						</tr>
					{{/用户列表}}

EOP;
			$admin->load($temp);
			$row = $mysql->fetch_all($mysql->query('select * from user order by id asc;'));
			
			foreach ($row as $key => $value) {
				$admin->setrows('用户列表',$value);
			}
			$admin->show();
			$mysql->exit();
			exit;
		}

		//获取文章列表
		if(isset($_GET['blog_list'])){
			$temp=<<<EOP
			<input type="text" style="width:100%;border: 0px;background-color: transparent;" id="xinjian_name" disabled="disabled"></input>
			<button class="button_mr"  name="daohanglan_button" onclick="setjiaodian(this,'xinjian');">新文章</button>
					{{文章列表}}
						<tr>
							<td onclick="getmaintext('blogtext',{{id}});">
								<img src="{{tx}}"/>
								<span><b>{{title}}</b></span>
								<br/>
								<p>⌚{{time}}</p>
							</td>
						</tr>
					{{/文章列表}}
EOP;
			$admin->load($temp);
			$row = $mysql->fetch_all($mysql->query('select blogs.id,blogs.title,blogs.time,user.tx from blogs,user where  user.username=blogs.username order by blogs.id DESC;'));
			if($row){
					foreach ($row as $key => $value) {
						if(strlen($value['title'])>23){
							$value['title']=substr($value['title'],0,23).'...';
					}
						$admin->setrows('文章列表',$value);
					}
					$admin->show();
					$mysql->exit();
					exit;
			}else{
					$temp=<<<EOP
					<input type="text" style="width:100%;border: 0px;background-color: transparent;" id="xinjian_name" disabled="disabled"></input>
								<button class="button_mr"  name="daohanglan_button" onclick="setjiaodian(this,'xinjian');">新文章</button>

EOP;
					$mysql->exit();
					exit($temp);
				}

			
		}

		//backup导出数据
		if(isset($_GET['getbackup'])){
			$rsa = new Modrsa;
			$rsa->setcharset(1);
			$rsa->setup_public('demo/wechat/verify.crt');
			$rsa->setup_private('demo/wechat/sign.pem');

			$type=json_encode($mysql->fetch_all($mysql->query('select * from blog_type;')));
			$blogs=json_encode($mysql->fetch_all($mysql->query('select * from blogs;')));
			$user=json_encode($mysql->fetch_all($mysql->query('select * from user;')));
			$files=json_encode($mysql->fetch_all($mysql->query('select * from files;')));

			$json['blog_type']=bin2hex($type);
			$json['blogs']=bin2hex($blogs);
			$json['user']=bin2hex($user);
			$json['files']=bin2hex($files);
			$json['qm']=$rsa->sign($json['blog_type'].$json['blogs'].$json['user'].$json['files']);

			$str = json_encode($json);
			$filename = 'backup'.time().'.txt';

			header("Content-type: text/plain");
			header("Accept-Ranges: bytes");
			header("Content-Disposition: attachment; filename=".$filename);
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0" );
			header("Pragma: no-cache" );
			header("Expires: 0" ); 
			exit($str);
			$mysql->exit();
			//exit('<script>alert(1);</script>');
		}
		//获取仪表盘
		if(isset($_GET['backup'])){
			Bind_Error('demo/wechat/backup.html');
			$mysql->exit();
			exit;
		}

		//获取仪表盘
		if(isset($_GET['yibiaopan'])){
			Bind_Error('demo/wechat/yibiaopan.html');
			$mysql->exit();
			exit;
		}

		//获取新建文章页面
		if(isset($_GET['xinjian'])){
			$xinjian = new Modtemplate;
			$xinjian->loadfile('demo/wechat/edit.html');

			$xinjian->show();
			$mysql->exit();
			exit;
		}

		//获取main内容
		if(isset($_GET['type']) && isset($_GET['id'])){
			switch ($_GET['type']) {
				case 'blogtext':
						$admin->loadfile('demo/wechat/edit.html');

						$row = $mysql->fetch_array($mysql->query('select username from blogs where id='.$_GET['id']));

						if($row){
							$admin->settext('id',$_GET['id']);
							$admin->show();	
						}else{
							$mysql->exit();
							Bind_Error(404);
						}
						
					break;
				case 'user':
				
					$admin->loadfile('demo/wechat/user.html');
					$row = $mysql->fetch_array($mysql->query('select * from user where id = '.$_GET['id']));
					//print_r($row);
					if($row){
						$admin->setrows('用户资料',$row);
						$admin->show();
						
					}
					break;

			}
			$mysql->exit();
			exit;
		}


		$admin->loadfile('demo/wechat/admin.html');
		$admin->settext('tx',$qj_tx);
		$admin->show();
		$mysql->exit();
		exit;
	}

	if(isPOST()){
		$mysql = new Modmysql;
		$mysql->load(MYSQL_HOST,MYSQL_USER,MYSQL_WORD,MYSQL_DB,MYSQL_CHAR,MYSQL_PORT);

		if(isset($_FILES['backup'])){
			$rsa = new Modrsa;
			$rsa->setcharset(1);

			$rsa->setup_public('demo/wechat/verify.crt');
			$rsa->setup_private('demo/wechat/sign.pem');
			$file=$_FILES['backup'];

			if ($file['error']==0) {
				$str=file_get_contents($file['tmp_name']);
				$json=json_decode($str,true);
				if($json){

					$blog_type=json_decode(hex2bin($json['blog_type']),true);
					$blogs=json_decode(hex2bin($json['blogs']),true);
					$user=json_decode(hex2bin($json['user']),true);
					$files=json_decode(hex2bin($json['files']),true);
					$qm=$json['qm'];
					$isok=$rsa->verify($json['blog_type'].$json['blogs'].$json['user'].$json['files'],$json['qm']);
					if($isok){
						$mysql->query('delete from user;');
						$mysql->query('delete from blogs;');
						$mysql->query('delete from files;');
						$mysql->query('delete from blog_type;');
						foreach ($blog_type as $key => $value) {
							$mysql->query('insert into blog_type (id,name) values ('.$value['id'].',"'.$value['name'].'");');
						}
						foreach ($blogs as $key => $value) {
							$mysql->query('insert into blogs (id,title,content,username,time,type) values ('.$value['id'].',"'.$value['title'].'","'.$value['content'].'","'.$value['username'].'","'.$value['time'].'",'.$value['type'].');');
						}
						foreach ($user as $key => $value) {
							$mysql->query('insert into user (id,username,password,tx,name,xingbie) values ('.$value['id'].',"'.$value['username'].'","'.$value['password'].'","'.$value['tx'].'","'.$value['name'].'",'.$value['xingbie'].');');
						}
						foreach ($files as $key => $value) {
							$mysql->query('insert into files (id,name,file,type,size,sha1,time) values ('.$value['id'].',"'.$value['name'].'","'.$value['file'].'","'.$value['type'].'","'.$value['size'].'","'.$value['sha1'].'","'.$value['time'].'");');
						}
					}else{
						echo('<script>alert("签名验证失败，当前数据文件已经被修改，不建议还原！");</script>');
					}



				}else{
					echo('<script>alert("您上传的文件不是wechat_blog的备份文件！");</script>');
				}
			}
			$mysql->exit();
			exit;
		}

		if (isset($_POST['tx'])  && isset($_POST['xingbie']) && isset($_POST['name']) && isset($_POST['username']) && isset($_POST['password'])) {

			$isok=$mysql->query('update user set tx="'.$_POST['tx'].'",xingbie="'.$_POST['xingbie'].'",name="'.$_POST['name'].'",password="'.$_POST['password'].'" where username="'.$_POST['username'].'";');
			$isok = $isok ? 'yes' :'no';
			$mysql->exit();
			exit($isok);
		}
	}
?>