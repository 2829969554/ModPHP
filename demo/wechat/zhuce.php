<?php
//判断注册提交
if(isset($_POST['button'])){
	header("Content-type:text/html; charset=utf-8");
	if(strlen($_POST['name'])==0){
		echo '<script>alert("请输入昵称！");history.go(-1);</script>';
		exit;
	}
		$name=$_POST['name'];
		$username=$_POST['username'];
		$password=$_POST['password'];
		$repassword=$_POST['repassword'];
	if(strlen($username)<=4){
		echo '<script>alert("账号长度必须大于4位！");history.go(-1);</script>';
		exit;	
	}
	if(strlen($password)<=6){
		echo '<script>alert("密码长度必须大于6位！");history.go(-1);</script>';
		exit;	
	}
	if($password !=$repassword){
		echo '<script>alert("密码与确认密码应该一致");history.go(-1);</script>';
		exit;	
	}
	if($password == $repassword){
            
                $sql = "select * from user where username = '$username';";
                $mysql = new Modmysql;
                //echo($sql);
                $mysql->load(MYSQL_HOST,MYSQL_USER,MYSQL_WORD,MYSQL_DB,MYSQL_CHAR,MYSQL_PORT);
                $row=$mysql->fetch_array($mysql->query($sql));
                
                if(!$row){
                	$sql = "insert into user (name,username,password,tx,xingbie) values('$name','$username','$password','img/tx.jpg',".$_POST['xingbie'].")";
                	$mysql->query($sql);
             	 	echo '<script>alert("注册成功！");window.location="/";</script>';
                }else{
                   	
              		echo '<script>alert("该账号已存在！");</script>';
				}
		$mysql->exit();
            }
     }
                
           
        

require 'demo/wechat/zhuce.html';;

?>
