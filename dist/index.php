<?php
  include 'headmeta.php';
  include 'fn.php';
?>

<body class="index_header_bg">
     <!-- 開頭動畫 -->
    <div class="logoSvg" id="logoSvg">
       <div style=" width:30%; margin:0 auto">
      <object><embed src="logo.svg"></object>
      </div>
    </div>
    <!-- header -->
    <header class="header-nav">
    <?php include 'header.php';?>
    </header>
    <!--./header-->
    <main class="index_header">
        <h1 class="heading-1 main_slogan"> handcrafted soap </h1>
        <h5 class="heading-4 main_desc"> 傳遞手中的溫暖 來至你的祝福  </h5>
        <button class="button">開始選購</button>
    </main>
    <!-- contact -->
    <section class="container contact">

      <div class="row">
      <div class="col s12">
      <h2 class="heading-1 title">填寫需求/詢價表單</h2>
      <div class="heading-2 sub_title">
      <div class="paragraph text">
        如有任何詢價或規格詢問 歡迎填寫下列表單 我們會立即與您洽談
        或直接<a href="#">聯絡我們</a>
      </div>
      </div>
      <form action="" method="post" class="form"  id="submitBtn">
        <div class="contact_filed">
           <label class="heading-4">聯絡人<b class="required">*</b></label>
           <input type="text" name="name" placeholder="必填">
        </div>
        <div class="contact_filed">
           <label class="heading-4">聯絡手機<b class="required">*</b></label>
          <input type="text" name="phone" placeholder="必填 09********">
        </div>
        <div class="contact_filed">
           <label class="heading-4">聯絡信箱</label>
           <input type="text" name="mail" >
        </div>
        <div class="contact_filed">
           <label class="heading-4">驗證碼<b class="required">*</b></label>
           <img id="captcha" style="width:100px;" src="/securimage/securimage_show.php" alt="CAPTCHA Image" />
           <a href="#" onclick="document.getElementById('captcha').src = '/securimage/securimage_show.php?' + Math.random(); return false"><i class="fa fa-undo ";></i></a>
           <input type="text" name="captcha" placeholder="必填">
        </div>
        <div class="contact_filed">
           <label class="heading-4">需求說明<b class="required">*</b></label>
           <textarea rows="8" cols="0"  name="comment" placeholder="必填 請簡述訂製需求"></textarea>
        </div>
        <div class="center_align">
        <button class="button" type="submit" >送出表單</button>
        </div>
      </form>
      </div>
      </div>
    </section>
        <!--相片集-->
    <section class="container contact row">
      <div class="col s12">
          <h2 class="heading-1 title">最新力作</h2>
          <ul class="index_gallery" id="index_gallery">
          </ul>
      </div>
    </section>
    <?php
      include 'footer.php';
    ?>
<script>
(function (){
  //當今天第一次進入才跑動畫
  var username = getCookie("username");
  var el = document.querySelector('.logoSvg');
    if(getCookie('visted') == ''){
        el.style.display = "block";
     setTimeout(function(){ 
        el.style.display = "none";
     }, 2000);
       setCookie('visted', 'visted', 365);
  };

  loadProducts();

})();

  //滾動 fixed nav

  $(window).on('scroll',scrolling);

  function scrolling(){
    if($(window).scrollTop()>88){
      $('.nav').addClass('nav_bgcolor');
    }else{
      $('.nav').removeClass('nav_bgcolor');
    }
  }

function loadProducts(){
    var data = {
      "action": "getProducts"
    };
    getJSON("useAPI.php",data,function (rs){
      var obj = rs.datas;
      var html = '';
      if(rs.status == 1){
        $.each(obj,function (i){
          console.log(obj[i]['ap_picurl']);
          html += '<li class="col s12 m4 l4">';
          html += '<figure>';
          html += '<span class="index_gallery_title">'+obj[i]['ap_subject']+'</span>';
          html += '<img src="admin/'+obj[i]['ap_picurl']+'" alt="'+obj[i]['ap_subject']+'">';
          html += '</figure>';
          html += '</li>';
        });

        $('#index_gallery').html(html);
      }
    });

};



//表單送出
var checkContactValidatin = false;

$("#submitBtn input,#submitBtn textarea").change(function(event){  
    //validation格式驗證  
    checkLoginValidatin = vali("#submitBtn");
});

$("#submitBtn").submit(function(event){
  event.preventDefault();
  checkLoginValidatin = vali("#submitBtn");
  console.log(checkLoginValidatin);
  if(checkLoginValidatin){
    var data = {
      "action": "inqueryForm"
    };
    data = $(this).serialize() + "&" + $.param(data);
    getJSON("useAPI.php",data,function (rs){
      if(rs.status == 1){
          var msg = rs.message;
          dialog(msg);
          //清除值
          $('input,textarea').val('');

      }else if(rs.status == 2)
        //驗證碼錯誤
        {
          $('input[name=captcha]').val('').parent().append('<div class="errmsg"><i class="fa fa-warning ";></i><div>'+rs.message+'</div></div>'); 

        };
    });
  }
  return false;
});



function vali(formID){
    var constraints = {
    name:{
      format:{
        required: true,
        type:'input',
        pattern: /^[\u4e00-\u9fa5_a-zA-Z]+$/,
        errorMessage: '格式錯誤',
        emptyMessage: '不能為空值'
      }
    },
    comment:{
      format:{
        required: true,
        type:'textarea',
        pattern: /^[\u4e00-\u9fa5_a-zA-Z0-9]+$/,
        errorMessage: '格式錯誤',
        emptyMessage: '不能為空值'
      }
    },
    phone:{
      format:{
        required: true,
        type:'input',
        pattern: /^(0)(9)([0-9]{8})$/,
        errorMessage: '格式錯誤',
        emptyMessage: '不能為空值'
      }
    },
    mail:{
      format:{
        required: false,
        type:'input',
        pattern: /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/,
        errorMessage: '格式錯誤'
      }
    },
    captcha:{
      format:{
        required: false,
        type:'input',
        pattern: /\w+/,
        errorMessage: '格式錯誤'
      }
    }
  }
  console.log(formID,constraints);
  var checkFormVal = validation(formID,constraints).check();
  return checkFormVal; 
}



</script>



