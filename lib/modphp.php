<?php
/*
作者:Modble
qq:2829969554
作用:html模板渲染
版本:1.7:
*/

class Modtemplate
{
	
	private	$template='';
	private $rowtemp=array();
	private $rowtext=array();
	function __construct() {

	}
	//载入模板或者模板文件
	public function load($file){
		$this->template= file_exists($file) ? file_get_contents($file) : $file;		
		if($this->template){
			return true;
		}else{
			return false;
		}
	}

	//载入模板文件
	public function loadfile($file){
		$this->template= file_get_contents($file);
		
		if($this->template){
			return true;
		}else{
			return false;
		}
	}

	//页面显示
	public function show(){
		for ($i=0; $i < count($this->rowtemp)/2; $i++) { 
			$a=$this->rowtext[$i];

			//过滤特殊符号
			$this->rowtemp[$a]=getsafestr($this->rowtemp[$a]);

			$this->template=preg_replace('/'.$this->rowtemp[$a].'/',$this->rowtext[$a],$this->template);	
		}
		echo($this->template);
	}
	//获取将要显示的页面文本
	public function getshowtext(){
		for ($i=0; $i < count($this->rowtemp)/2; $i++) { 
			$a=$this->rowtext[$i];

			//过滤特殊符号
			$this->rowtemp[$a]=getsafestr($this->rowtemp[$a]);

			$this->template=preg_replace('/'.$this->rowtemp[$a].'/',$this->rowtext[$a],$this->template);
			
		}
		return ($this->template);
	}

	//置键值
	//参数1:键值名
	//参数2:数据
	public function settext($name,$date){
		$this->template=preg_replace('/{{'.$name.'}}/',$date,$this->template);
	}

	//置代码块键值
	//参数1:代码块键值名
	//参数2:数据库查询返回集
	public function setrows($name,$rows){
		$preg= '/{{'.$name."}}[\s\S]*?{{\/".$name.'}}/';
		//echo $preg.'<br>';
		preg_match_all($preg,$this->template,$res);
		$temp=$res[0][0];
		
		if(!isset($this->rowtemp[$name])){
			array_push($this->rowtemp, $name);
			$this->rowtemp[$name]='';
		}

		if(!isset($this->rowtext[$name])){
			array_push($this->rowtext, $name);
			$this->rowtext[$name]='';
			$this->rowtemp[$name]=$temp;
		}

		
		$temp2=preg_replace('/{{'.$name.'}}/','',$temp);
		$temp2=preg_replace('/{{\/'.$name.'}}/','',$temp2);
		//echo $temp2.'<br>';
		preg_match_all("/{{[\s\S]*?}}/",$temp2,$res2);
		for ($i=0; $i < count($res2[0]); $i++) { 
			//echo $res2[0][$i].'<br>';
					$jz=preg_replace('/{{/','',$res2[0][$i]);
					$jz=preg_replace('/}}/','',$jz);
					//echo $jz.'<br>';
					$temp2=preg_replace('/'.$res2[0][$i].'/',$rows[$jz],$temp2);
		}
		$this->rowtext[$name].=$temp2;
		//echo $this->rowtext[$name];
		//preg_replace('/{{'.$name.'}}/',$date,$this->template);
		//$this->template=$res;
	}

	//安全验证
	//参数1:账号
	//参数2:密码
	public function BasicAuth($id,$pw)
	{
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

        	if($u!=$id  &&  $p!=$pw){
        		header('WWW-Authenticate: Basic');
    			header('HTTP/1.0 401 Unauthorized');
        		exit("鉴权失败,终止访问~");

        	}	
        }

	}

}
/*--------------------------路由绑定--------------------------*/
	//定义404
	function Bind_Error($a){
		$a=file_exists($a) ? $a : 'error/'.$a.'.html';
		require $a;
	}

	//订阅视图函数
	//绑定的url
	//页面函数
	function Bind_View_Fun($url,$fun)
	{
		$temp=$_SERVER["REQUEST_URI"];
		$temp=preg_replace("/\/index.php/","",$temp);
		if(getleftstr($temp,'?')==$url)
		{			
			$fun();
			exit();
		}
		
	}
	//订阅视图文件
	//绑定的url
	//页面文件
	function Bind_View_File($url,$file)
	{
		$temp=$_SERVER["REQUEST_URI"];
		$temp=preg_replace("/\/index.php/","",$temp);
		if(getleftstr($temp,'?')==$url)
		{
			require $file;
			exit();
			
		}
		
	}
	//跳出到其他文件
	//绑定的url
	//文件
	function Bind_View_Jump($url,$file)
	{
		$temp=$_SERVER["REQUEST_URI"];
		$temp=preg_replace("/\/index.php/","",$temp);
		if(getleftstr($temp,'?')==$url)
		{
			header('location:'.$file);
			exit();
		}
		
	}
/*--------------------------字符串--------------------------*/
//取某文本两文本之间内容
function getcentstr($str, $leftStr, $rightStr)
{
    $left = strpos($str, $leftStr);
    //echo '左边:'.$left;
    $right = strpos($str, $rightStr,$left);
    //echo '<br>右边:'.$right;
    if($left < 0 or $right < $left){
    	return '';
    }
       return substr($str, $left + strlen($leftStr), $right-$left-strlen($leftStr)); 	

}

//取某文本右边
function getrightstr($str, $leftStr)
{
    $left = strpos($str, $leftStr);
    if($left>0){
 		return substr($str, $left + strlen($leftStr));
 	}
 	return $str;
}

//取某文本左边
function getleftstr($str, $rightStr)
{
    $right = strpos($str, $rightStr);
    if($right>0){
    	return substr($str, 0, $right);
    }
    return $str;
}

//取安全字符串
function getsafestr($text){
	//过滤特殊符号
	$temp=$text;
	$fuhao=$arrayName = array('<','>','?','/','"',"'",'[',']','{','}','=','-','.','$','^','*','|','(',')');
	foreach ($fuhao as $key => $value) {
		$temp=preg_replace( "/\\$value/" , '\\'.$value , $temp);
	}
	return $temp;
}

/*--------------------------请求来源--------------------------*/
//判断请求来源是否为GET
function isGET(){
	return $_SERVER['REQUEST_METHOD'] == 'GET' ? true : false;
}

//判断请求来源是否为POST
function isPOST(){
return ($_SERVER['REQUEST_METHOD'] == 'POST' && (empty($_SERVER['HTTP_REFERER']) || preg_replace("~https?:\/\/([^\:\/]+).*~i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("~([^\:]+).*~", "\\1", $_SERVER['HTTP_HOST']))) ? 1 : 0;
}

//判断请求来源是否为AJAX
function isAJAX(){
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		return true;
	}else{
		return false;
	}
}

?>