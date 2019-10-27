//头像上传预览
 function preImg(id) {
    var formData = new FormData();
    formData.append("file",$('#'+id)[0].files[0]);
    $.ajax({
        url:'/push',
        dataType:'json',
        type:'POST',
        async: false,
        data: formData,
        processData : false, // 使数据不做处理
        contentType : false, // 不要设置Content-Type请求头
        success: function(data){
            var jlj = data['data'];
             $('#tximg').attr('src',jlj.url);
             document.getElementById('tx').value=jlj.url;
        },
        error:function(response){
            console.log(response);
        }
    });
}

function update_user(){
    var tx = document.getElementById('tx').value;
    var name = document.getElementById('name').value;
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;
    var xingbie = document.getElementById('xingbie').value;
  $.post("/admin",{
    'tx':tx,
    'name':name,
    'username':username,
    'password':password,
    'xingbie':xingbie
},function(result){
    alert(result);
    window.parent.danjicaidan('用户');
  }
  );
}