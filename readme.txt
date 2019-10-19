lib/modphp=[
	class Modtemplate{

		//载入模板文件
		public function load($file)
		
		//页面显示
		public function show()

		//获取将要显示的页面字符串
		public function getshowtext()

		//置键值
		//参数1:键值名
		//参数2:数据
		public function settext($name,$date)

		//置代码块键值
		//参数1:代码块键值名
		//参数2:数据库查询返回集
		public function setrows($name,$rows)

		//安全验证
		//参数1:账号
		//参数2:密码
		public function BasicAuth($id,$pw)

	}
		//定义错误页面
		function Bind_Error($a)

		//订阅视图函数
		//绑定的url
		//页面函数
		function Bind_View_Fun($url,$fun)

		//订阅视图文件
		//绑定的url
		//页面文件
		function Bind_View_File($url,$file)

		//订阅跳出其他文件
		//绑定的url
		//文件
		function Bind_View_Jump($url,$file)

		//取两文本之间内容
		function getcentstr($str, $leftStr, $rightStr)
		
		//取某文本右边
		function getrightstr($str, $leftStr)
		
		//取某文本左边
		function getleftstr($str, $rightStr)

		//判断请求来源是否为GET
		function isGET()

		//判断请求来源是否为GET
		function isPOST()
		
		//判断请求来源是否为GET
		function isAJAX()
]

lib/moddb.php=[

	class Modmysql{
		
		//链接数据库 地址，用户名，密码，编码
		public function load($ip,$user,$pwd,$db,$charset='utf-8')

		//执行sql
		public function exec($sql)

		//cls字节集
		public function clear($jlj)

		//返回单独记录
		public function fetch_array($jlj)

		//返回全部
		public function fetch_all($jlj)

		//关闭数据库
		public function exit()

	}

	class Modsqlite{
		//执行exec
		public function exec($sql)

		//执行sql返回记录集
		public function query($sql)
		//打开数据库
		public function load($file)

		//新建数据库
		public function new($file)

		//返回单记录
		public function fetch_array($jlj)

		//返回全部记录
		public function fetch_all($jlj)

		//关闭数据库
		public function exit()

		//转义编码
		public function tosafetext($value)

		//取最近错误信息
		public function error()

	}

]


lib/modphp=[

	class Modrsa{

	   	/*
	   	设置字节集类型
		0.字节集
		1.十六进制
		2.base64
		*/
	   	function setcharset($type)

		//初始化公钥或者公钥证书地址
		public function setup_public($public)

		//初始化私钥或者私钥证书地址
		public function setup_private($private)

		//初始化公钥或者公钥证书地址
		public function setup_public_file($public)

		//初始化私钥或者私钥证书地址
		public function setup_private_file($private)

		//公钥加密
		public function public_encrypt($data)

		//公钥解密
		public function public_decrypt($data)

		//私钥加密
		public function private_encrypt($data)

		//私钥解密
		public function private_decrypt($data)

		//签名
		public function sign($data)

		//验签
		public function verify($data,$sign)
	}

]