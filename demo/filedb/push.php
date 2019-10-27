<?php
//判断请求方式
if(isGET()){
	//判断地址栏fid是否存在
	if(isset($_GET['fid'])){
		//获取文件ID
		$fid=(int)$_GET['fid'];
		//定义并且连接Mysql数据库
		$mysql = new Modmysql;
		$mysql->load(MYSQL_HOST,MYSQL_USER,MYSQL_WORD,MYSQL_DB,MYSQL_CHAR,MYSQL_PORT);	
		//查询id对应文件记录并且返回记录数组			
		$row=$mysql->fetch_array($mysql->query('select name,file,type,size from files where id='.$fid.';'));
		if($row){
			//定义响应协议头Mod模板
			$fileinfo=new Modtemplate;
			//判断是不是纯文本或者脚本或者图片
			if(strpos($row['type'],'ext/')>0 || strpos($row['type'],'script')>0 || strpos($row['type'],'mage/')>0 ){
				//加载文件或者脚本或者图片在浏览器显示的协议头
				$fileinfo->load('demo/filedb/fileshow.cnf');
			}else{
				//加载文件下载的协议头
				$fileinfo->load('demo/filedb/file.cnf');
			}
			//置入模板记录name，type，size
			$fileinfo->setrows('fileinfo',$row);
			//取出响应协议头Mod模板置入后的文本
			$tmp=$fileinfo->getshowtext();
			//分割协议头并且逐行发送到浏览器
			$headrow=explode("\n",$tmp);
			foreach ($headrow as $key => $value) {
				header($value);
			}
			//输出文件内容HEX转二进制文件
			echo(hex2bin($row['file']));
			$mysql->exit();
		}else{
			echo '下载失败：请求文件不存在。';
			$mysql->exit();
		}

	}else{
		//地址栏不存在fid响应上传文件页面
		require 'demo/filedb/push.html';
	}
}

//判断请求方式
if (isPOST()) {
	//判断请求是不是上传文件
	if(isset($_FILES['file'])){
		//上传文件数组信息赋值到￥file
		$file=$_FILES['file'];
		//print_r($file);
		//判断文件上传结果
		switch ($file['error']) {
			//成功上传到临时文件目录
			case 0:
				//获取文件sha1
				$sha1=sha1_file($file['tmp_name']);
				$name=$file['name'];
				$size=$file['size'];
				$type=$file['type'];
				$filehex='';
				//定义并且连接mysql
				$mysql = new Modmysql;
				$mysql->load(MYSQL_HOST,MYSQL_USER,MYSQL_WORD,MYSQL_DB,MYSQL_CHAR,MYSQL_PORT);
				//查询上传文件sha1记录是否存在相同文件				
				$row=$mysql->fetch_array($mysql->query('select id from files where sha1="'.$sha1.'";'));
				if($row){
					//如果存在输出文件id
					//echo '上传成功：'.$row['id'];
						$ttt['error']=0;
						$ttt['data']['url']='push?fid='.$row['id'];
						echo json_encode($ttt);
					$mysql->exit();
				}else{
					//不存在读入文件并且二进制文件转十六进制字符串赋值$filedex并且关闭文件
					$fileid=fopen($file['tmp_name'], 'rb+');
					$filehex=bin2hex(fread($fileid, $size));
					fclose($fileid);
					//插入记录
					$mysql->query('insert into files (id,name,file,type,sha1,time,size) values (null,"'.$name.'","'.$filehex.'","'.$type.'","'.$sha1.'","'.date("Y-m-d H:i:s").'","'.$size.'");');
					$row=$mysql->fetch_array($mysql->query('select id from files where sha1="'.$sha1.'";'));
					//判断是否插入成功
					if($row){						
						//echo '上传成功：'.$row['id'];
						$ttt['error']=0;
						$ttt['data']['url']='push?fid='.$row['id'];
						echo json_encode($ttt);
						$mysql->exit();
					}else{
						echo '上传失败：写入数据库失败。';
						$mysql->exit();
					}
				}
				break;
			case 1:
				exit('上传失败:文件的大小超过了php.ini 配置中 upload_max_filesize 选项指定的值。');
				break;
			case 2:
				exit('上传失败:文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值。');
				break;
			case 3:
				exit('文件只有部分被上传。');
				break;
			case 4:
				exit('没有文件被上传。');
				break;
			case 6:
				exit('php.ini文件中存临时文件的目录没有访问权限。');
				break;
		}
	}
}
?>