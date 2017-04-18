<?php 
    include_once "fn.php";
    include_once "connMysql.php";
    header("Content-Type:text/html; charset=utf-8");


    /*
    *參照教學:https://gist.github.com/jonsuh/3739844
    */
    if (is_ajax()) {
        
      if (isset($_POST["action"]) && !empty($_POST["action"])) { //Checks if action value exists
        $action = $_POST["action"];
        switch($action) { //Switch case for value of action
          case "inqueryForm":
             insertContactDB($crud);
             break;
          case "inquerySheet": 
             inquerySheet();
             break;
          case "checkAccout": 
             checkUserAccout(); 
             break;
          case "getProducts":
             getProducts();
             break;
        }
      }
    }
    //Function to check if the request is an AJAX request
    function is_ajax() {
      return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }


    //登入頁
    function checkUserAccout(){
           $tblName = 'admin';
           $password = $_POST['password'];
           $tokenStr = $_POST['account'].$_POST['password'].time();
           $token=md5($tokenStr);
           
           //檢查是否有帳號
            $conditions = array(
             'select' => 'account',
             'where' => array(
                'account' => $_POST['account']),
             'return_type'=>'single'
            );
           
            $rows = $GLOBALS['crud']->getRows($tblName,$conditions);
            if($rows){
              //有註冊帳號 檢查密碼
               $pconditions = array(
                'select' => 'password',
                'where' => array(
                   'account' => $_POST['account']),
                'return_type'=>'single'
               );
              
               $prows = $GLOBALS['crud']->getRows($tblName,$pconditions);
              if($prows['password'] === $password){         
                  $result = array(
                         'status' => 1,
                         'lanch' => '../index.php',
                         'token' => $token,
                         'message'=> '登入成功!!'
                  );
                 //資料正確 寫入session    
                 $_SESSION['token'] = $result['token'];//寫入session
                 $_SESSION['username'] = $_POST['account'];//寫入session
                 

                 //記我30天
                 if( isset($_POST['rememberMe'])){
                     setcookie("temp_username",$_POST['account'], time()+3600*24*30);
                 }else{
                     setcookie ("temp_username", "", time() - 3600);
                 };
                 
              }else{
                $result = array(
                       'status' => 0,
                       'error' => 2,
                       'message'=> '密碼錯誤!!'
                );                
              }
           }else{
              $result = array(
                     'status' => 0,
                     'error' => 1,
                     'send' => $conditions,
                     'message'=> '帳號錯誤!!'
              );
           }
           echo json_encode($result);
    }


    //送出聯絡表單
    function insertContactDB(){
              //驗證碼是否正確
              include_once $_SERVER['DOCUMENT_ROOT'] . '/securimage/securimage.php';
              $captchaVali = checkCaptcha();//驗證碼true正確false失敗

              if($captchaVali){

                    //驗證碼正確送至DB
                    $tblName = 'contact';
                    $userData = array(
                          'Name' => $_POST['name'],
                          'Company' => $_POST['company'],
                          'Phone'   => $_POST['phone'],
                          'Mail'    => $_POST['mail'],
                          'Date'    => date("Y-m-d H:i:s"),
                          'Comment' => $_POST['comment']
                      );
                    $lastId = $GLOBALS['crud']->create($tblName,$userData);
                    if(!empty($lastId)){
                       //phpMailer
                       require './sendmail.php';
                       $sendMailer = new sendMailer();
                       $isSend = $sendMailer -> sendFormToMail($_POST);
                       $result = array(
                              'status' => 1,
                              'lastId'=> $lastId,
                              'restdata'=> $userData,
                              'sendMail'=> $isSend ? '送至信箱成功':'送至信箱失敗',
                              'message'=> '感謝您的填寫<br/>表單已成功送出!!'
                       );
                  }else{
                     $result = array(
                            'status' => 0,
                            'message'=> '新增失敗'
                     );
                  } 
                }else{
                     $result = array(
                            'status' => 2,
                            'message'=> '驗證碼錯誤!'
                     );
                }
                echo json_encode($result); 
      }


   function inquerySheet(){

   }

   //取得產品項目
   function getProducts(){
        $tblName = 'albumphoto';
        $conditions = array(
         'select' => '*'
        );      
        $rows = $GLOBALS['crud']->getRows($tblName,$conditions);
        if($rows){
          $result = array(
                 'status' => 1,
                 'datas'=> $rows
          );
        }else{
          $result = array(
                 'status' => 0,
                 'message'=> '產品資料撈取失敗'
          );
        }
        echo json_encode($result); 
   }


   function checkCaptcha(){
      if(isset($_POST["captcha"])&&!empty($_POST["captcha"])){
        $securimage = new Securimage();
        if ($securimage->check($_POST['captcha']) == false) {
          return false;
        }else{
          return true;
        }
      }
    };








 ?>
