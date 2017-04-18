<?php 
    include_once "../connMysql.php";
    header("Content-Type:text/html; charset=utf-8");

    
    /*
    *參照教學:https://gist.github.com/jonsuh/3739844
    */
    if (is_ajax()) {
      if (isset($_POST["action"]) && !empty($_POST["action"])) { //Checks if action value exists
        $action = $_POST["action"];
        switch($action) { //Switch case for value of action
          case "getPItems":
             //取得商品類別
             getPItems($crud);
          break;
        }
      }
    }
    //Function to check if the request is an AJAX request
    function is_ajax() {
      return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }


    //取得商品類別
    function getPItems(){
           $tblName = 'album';
           $conditions = array(
             'select' => '*'
            );
           
            $rows = $GLOBALS['crud']->getRows($tblName,$conditions);

            if($rows){
              //有註冊帳號 檢查密碼
               $result = array(
                      'status' => 1,
                      'lanch' => '../index.php',
                      'resdata' => $rows,
                      'message'=> '商品類別取得成功!!'
               );
            }  
            echo json_encode($result);
    }









 ?>
