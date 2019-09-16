<?php
/*
作者:Modble
qq:2829969554
作用:html模板渲染
版本:1.1:
*/

class ModTempLate
{
	
	private	$template='';
	private $rowtemp=array();
	private $rowtext=array();
	function __construct() {

	}
	//载入模板文件
	public function load($file){
		$this->template=file_get_contents($file);
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
			//echo $this->rowtemp[$a].'<br>';
			//echo $this->rowtext[$a].'<br>';
			$this->rowtemp[$a]=preg_replace("/\//","\\\/",$this->rowtemp[$a]);
			//$this->rowtext[$a]=preg_replace("/\//","\\\/",$this->rowtext[$a]);
			$this->template=preg_replace('/'.$this->rowtemp[$a].'/',$this->rowtext[$a],$this->template);
			
		}
		echo($this->template);
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

	//订阅视图函数
	//绑定的url
	//页面函数
	function Bind_View_Fun($url,$fun)
	{
		if($_SERVER["REQUEST_URI"]==$url)
		{
			$fun();
		}
		
	}

	//订阅视图文件
	//绑定的url
	//页面文件
	function Bind_View_File($url,$file)
	{
		if($_SERVER["REQUEST_URI"]==$url)
		{
			require $file;
		}
		
	}
?>