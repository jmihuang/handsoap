<?php 	
		date_default_timezone_set("Asia/Taipei");

		/**
		*啟動session
		**/

		if( !isset($_SESSION) ){
			$mins = 3;
			startSession($mins*60);//啟動session
		}

		function startSession($expire = 0){
			
		     //沒有設定時間 以php.ini為主
		     if( $expire == 0 ){
		        $expire = ini_get('session.gc_maxlifetime');
		     }else{
		        ini_set('session.gc_maxlifetime', $expire);
		     };
		 
		    if( isset($_COOKIE['PHPSESSID'])){
		        //寫入session cookie過期時間
		        /**顯示過期的時間
		        $date = date_create();
		        $timestamp = time() + $expire;
		        $date->setTimestamp( $timestamp );
		        echo $date->format('U = Y-m-d H:i:s') . "\n";
		        **/
		        session_start();
		        setcookie('PHPSESSID', session_id(), time() + $expire);

		       
		     }else{
		        session_set_cookie_params($expire);
		        session_start();
		     };
		}

		/**
		*form input傳進值 過濾空白 反斜線
		**/

		function test_input($data) {
		  $data = trim($data);//空白
		  $data = stripslashes($data);// 反斜線
		  $data = htmlspecialchars($data);//html標籤
		  return $data;
		}


 ?>