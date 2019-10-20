<?php

	$rsa = new Modrsa;
	$show = new Modtemplate;
	$show->load('demo/rsa/rsa.html');

	$rsa->setup_public('demo/rsa/public.crt');
	$rsa->setup_private('demo/rsa/private.pem');
	$rsa->setcharset(2);
	$temp= array();
	if(isset($_POST['text'])){
		$temp['内容']=$_POST['text'];
	}else{
		$temp['内容']='我有一个小老虎我从来也不骑';
	}
	
	$temp['公钥加密']= $rsa->public_encrypt($temp['内容']);
	$temp['私钥解密']= $rsa->private_decrypt($temp['公钥加密']);
	$temp['私钥加密'] = $rsa->private_encrypt($temp['私钥解密']);
	$temp['公钥解密'] = $rsa->public_decrypt($temp['私钥加密']);
	$temp['签名'] =$rsa->sign("我爱你");
	$temp['验签'] =$rsa->verify("我爱你",$temp['签名']);

	$show->setrows('rsa',$temp);
	$show->show();	

?>