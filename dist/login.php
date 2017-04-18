<?php
  include "headmeta.php";
  include "fn.php";
?>

<body class="log_bg">

  <div class="form login_page" >
    <!-- <p class="welcome_login">Welcome <b>lililala</b></p> -->
    <form action="login.php" method="post" id="checkAccout">
    <div class="login_filed">
         <i class="fa fa-user prepic" aria-hidden="true"></i>
         <input type="text" name="account" placeholder="帳號" value="<?php 
            //記我30天
            if(!empty($_COOKIE['temp_username'])){
              echo $_COOKIE['temp_username'];
            }

         ?>"/>
      </div>
      <div class="login_filed">
            <i class="fa fa-lock prepic" aria-hidden="true"></i>
        <input type="password" name="password" placeholder="密碼(8-12英數)"/>
      </div>

      

      <button class="login_btn" type="submit">送出表單</button>
      <p class="login_remeber">
      <label for="rememberMe"> 
      <?php
        if(isset($_COOKIE['temp_username'])){
           echo '<input type="checkbox" name="rememberMe" id="rememberMe" checked>記住我30天?';
        }else{
           echo '<input type="checkbox" name="rememberMe" id="rememberMe">記住我30天?';
        } 
        
      ?>
      </label>
    </form>
</div>
<div class="dialog animated" id="">
  <p>MSG here</p>
  <button class="alert_btn">確 認</button>
</div>
<script src="https://code.jquery.com/jquery-1.11.3.js"></script>
<script src="js/main.js"></script>
<script src="js/concat.js"></script>
<script src="js/dialog.js"></script>

<script>
  var checkLoginValidatin = false;


  $("#checkAccout input").change(function(event){    
      checkLoginValidatin = vali("#checkAccout");
  });

  $("#checkAccout").submit(function (){
      //驗證
      checkLoginValidatin = vali("#checkAccout");
      //檢查格式是否正確
      if(checkLoginValidatin){
          var data = {
            "action": "checkAccout"
          };
          var account = $('input[name="account"]').val();
          var password = md5($('input[name="password"]').val());
          data = "account="+account+"&password="+password+"&"+$.param(data);
          getJSON("useAPI.php",data,function (rs){
              if(rs.status === 1){
                 //成功轉址至首頁
                 window.location.href="admin/index.php";
              }else{
                 //登入錯誤訊息
                 dialog(rs.message);
              }

          });
        }
        return false;
    });
  
  function vali(formID){
      var constraints = {
      account:{
        format:{
          required: true,
          type:'input',
          pattern: /\w+/,
          errorMessage: '格式錯誤',
          emptyMessage: '不能為空值'
        }
      },
      password:{
        format:{
          required: true,
          type:'input',
          pattern: /(?=^[A-Za-z0-9]{6,12}$)((?=.*[a-z])(?=.*[0-9]))^.*$/,
          errorMessage: '格式錯誤',
          emptyMessage: '不能為空值'
        }
      }
    }
    var checkFormVal = validation(formID,constraints).check();
    return checkFormVal; 
  }

</script>
