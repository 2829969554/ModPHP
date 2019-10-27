<?php 

 function is_class($name){
	return class_exists($name) ? 'yes' :'no';
 }
 function is_fun($name){
	return function_exists($name) ? 'yes' :'no';
 }
	if(isGET()){
		require 'install.html';
		//echo phpinfo();
		exit;
	}
	
	if (isset($_POST['test'])) {
		$mysql = new Modmysql;
		$isok=$mysql->load($_POST['host'],$_POST['user'],$_POST['pass'],$_POST['db'],'utf8',$_POST['port']);
		if(!$isok){
			$mysql->exit();
			$isok = 'yes';
		}
		
		
		exit($isok);

	}
	if (isset($_POST['install'])) {
		$mysql = new Modmysql;
		$isok=true;
		$mysql->load($_POST['host'],$_POST['user'],$_POST['pass'],$_POST['db'],'utf8',$_POST['port']);
		$sql=<<<EOP
		drop table `blog_type`;
		/***/
		drop table `user`;
		/***/
		drop table `blogs`;
		/***/
		drop table `files`;
		/***/
CREATE TABLE `blog_type`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 19 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;
/***/
CREATE TABLE `user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `tx` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `xingbie` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 103 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;
/***/
CREATE TABLE `blogs`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(225) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `content` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `veiws` int(11) NULL DEFAULT NULL,
  `username` varchar(225) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `time` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `type` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 297 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;
/***/
CREATE TABLE `files`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `file` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `type` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `size` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sha1` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `time` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 25 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;
/***/
INSERT INTO user (id,username,password,tx,name,xingbie) values (null,"{$_POST['au']}","{$_POST['ap']}","img/tx.jpg","Administrator",1);
/***/
INSERT INTO blog_type (id,name) values (null,"首页");
EOP;
$row=explode('/***/',$sql);
foreach ($row as $key=> $value) {
	$isok=$mysql->query($value);
	$isok = $isok ? 'yes' : 'no';
	echo "<p> $key : $isok : $value </p>";
}
$sss= new Modtemplate;
$sss->loadfile('demo/wechat/config.txt');
$row['ip']=$_POST['host'];
$row['username']=$_POST['user'];
$row['password']=$_POST['pass'];
$row['db']=$_POST['db'];
$row['port']=$_POST['port'];
$sss->setrows('config',$row);
$str=$sss->getshowtext();

$wjh=fopen('config.php', 'w+');
fwrite($wjh,$str);
fclose($wjh);

$wjh=fopen('install.lock', 'w+');
fwrite($wjh,'yes');
fclose($wjh);

echo '安装完成！';
echo '<script> window.location.href="/";</script>';
$mysql->exit();
	}
	
?>