<?php
//mysql数据库封装
class Modmysql extends mysqli{

	function __construct() {
		
	}
	//链接数据库 地址，用户名，密码，编码
	public function load($ip,$user,$pwd,$db,$charset='utf8',$port=3306)
	{
		
		$this->connect($ip,$user,$pwd,$db,$port);
		
		$this->set_charset($charset);
		

		return $this->connect_error;

	}
	//执行sql
	public function exec($sql)
	{
		return $this->query($sql);

	}
	//cls字节集
	public function clear($jlj)
	{
		mysqli_free_result($jlj);
	}
	//返回单独记录
	public function fetch_array($jlj)
	{
		return (mysqli_fetch_array($jlj,MYSQLI_ASSOC));
	}
	//返回全部
	public function fetch_all($jlj)
	{
		return (mysqli_fetch_all($jlj,MYSQLI_ASSOC));
	}
	//关闭数据库
	public function exit()
	{
		$this->close();
	}
}

//Sqlite3

class Modsqlite extends SQLite3{

	function __construct() {
		
	}
	//打开数据库
	public function load($file)
	{	
		$this->open($file,SQLITE3_OPEN_READWRITE);
	}
	//新建数据库
	public function new($file)
	{		
		$this->open($file,SQLITE3_OPEN_CREATE);
	}
	//返回单记录
	public function fetch_array($jlj)
	{
		return ($jlj->fetchArray(SQLITE3_ASSOC));
	}
	//返回全部记录
	public function fetch_all($jlj)
	{
		$row= array();
		while ($temp=$jlj->fetchArray(SQLITE3_ASSOC)){
			array_push($row, $temp);
		}
		return ($row);
	}
	//关闭数据库
	public function exit()
	{
		$this->close();
	}
	//转义编码
	public function tosafetext($value)
	{
		return ($this->escapeString($value));
	}
	//取最近错误信息
	public function error()
	{
		return ($this->lastErrorCode().$this->lastErrorMsg());
	}
}
?>