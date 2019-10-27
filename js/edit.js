var E = window.wangEditor;
var editor = new E('#editor');

// 自定义菜单配置
editor.customConfig.menus = [
    'head',  // 标题
    'bold',  // 粗体
    'fontSize',  // 字号
    'fontName',  // 字体
    'italic',  // 斜体
    'underline',  // 下划线
    'strikeThrough',  // 删除线
    'foreColor',  // 文字颜色
    'backColor',  // 背景颜色
    'link',  // 插入链接
    'list',  // 列表
    'justify',  // 对齐方式
    'quote',  // 引用
    'emoticon',  // 表情
    'image',  // 插入图片
    'table',  // 表格
    'video',  // 插入视频
    'code',  // 插入代码
    'undo',  // 撤销
    'redo'  // 重复
];
editor.customConfig.uploadImgServer = 'push';  // 上传图片到服务器
// 3M
editor.customConfig.uploadImgMaxSize = 2 * 1024 * 1024;
// 限制一次最多上传 5 张图片
editor.customConfig.uploadImgMaxLength = 5;
// 自定义文件名
editor.customConfig.uploadFileName = 'file';
// 将 timeout 时间改为 3s
editor.customConfig.uploadImgTimeout = 5000;

editor.customConfig.uploadImgHooks = {
    before: function (xhr, editor, files) {
        // 图片上传之前触发
        // xhr 是 XMLHttpRequst 对象，editor 是编辑器对象，files 是选择的图片文件

        // 如果返回的结果是 {prevent: true, msg: 'xxxx'} 则表示用户放弃上传
        // return {
        //     prevent: true,
       //      msg: '放弃上传'
        // }
        // alert("files");
    },
    success: function (xhr, editor, result) {
         //var url = result.data.url;
         //alert(JSON.stringify(url));
        // editor.txt.append('<img src="'+url+'"/>');
         //alert("成功");
    },
    fail: function (xhr, editor, result) {
        alert("上传失败:"+result);
    },
    error: function (xhr, editor) {
        alert("上传失败");
    },

    customInsert: function (insertImg, result, editor) {
        var url = result.data.url;
        insertImg(url);
    }
}

editor.create();
