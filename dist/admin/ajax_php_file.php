<?php

include_once "../connMysql.php";

// var_dump($_FILES);





// print_r($_POST);

if (is_ajax()) {

  if (isset($_POST["action"]) && !empty($_POST["action"])) { //Checks if action value exists

    $action = $_POST["action"];

    switch($action) { //Switch case for value of action
      case "createPitems":
         //新增商品
         createPitems($crud);
      break;
      case "uploadPitems":
         //修改商品
         uploadPitems($crud);
      break;
      case "delPitems":
         //刪除商品
         delPitems($crud);
      break;
    }

  }

}

//Function to check if the request is an AJAX request

function is_ajax() {

  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

}



function createPitems($crud){

	$process = 1;
	$tblName = 'albumphoto';

	if(isset($_FILES["file"]["type"])){

		if ( $_FILES['file']['error'][0] > 0 ) {
		       echo 'Error: ' . $_FILES['file']['error'] . '<br>';
		   }
		   else {
		   	    $target_dir = "upload/";
			   	foreach ($_FILES["file"]["name"] as $key => $value) {
			   		$target_file = $target_dir . $_FILES["file"]["name"][$key];
				   //限定尺寸
				   $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
				   //確認格式
				   if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
				   && $imageFileType != "gif" ){
				   	  $errLog .= $_FILES["file"]["name"][$key]."格式不正確,需為jpg/png/jpeg檔案";
				   	  $process = 0;
				   }

				   //確認尺寸
			   	    $allowed_file_size = 500000;
			       	if ($_FILES["file"]["size"][$key] > $allowed_file_size) {
			   			   $errLog .= $_FILES["file"]["name"][$key]."檔案過大";
			   			   $process = 0;
			        }

		            if( $process != 0 ){
		            	    //檔案格式大小正確
		            	    //寫入資料庫
		            	
		            		$moveFile = move_uploaded_file($_FILES['file']['tmp_name'][0],$target_file);

		            		if($moveFile != 1){
		            		   $errLog = '檔案上傳失敗';
		            		   //檔案上傳失敗錯誤							

							   $result = array(
								  'status' =>0,
								  'errorLog' => $errLog,
								  'message' => '新增失敗!!'
	                           );     		 	

		            		}else{

		            		   $userData = array(
		            			  'album_id' => $_POST['item'][0],
		            			  'ap_subject' => trim($_POST['pname']),
		            			  'ap_desc' => trim($_POST['pdesc']),
		            			  'ap_date' => date("Y-m-d H:i:s"),
		            			  'ap_picurl' => $target_file
		            			);

		            		    $lastId = $GLOBALS['crud']->create($tblName,$userData);

		            		    if($lastId){
		            		    	$result = array(
    									  'status' => 1,
    									  'lastId' => $lastId,
    									  'message' => '新增成功!!'
		            		        ); 

		            		    }
		            		}	            		

		            }else{
							//檔案格式大小錯誤 		

							$result = array(
								'status' =>0,
								'errorLog' => $errLog,
								'message' => '新增失敗!!'
	                       );           	

		            } 	

		            echo json_encode($result);			   		

		        }

		    }



	}

}



//上傳檔案	

function uploadPitems($crud){

    $process = 1;
	$tblName = 'albumphoto';

    if(isset($_FILES[0]["type"])){

	   if ($_FILES[0]['error'][0]> 0){
	       echo 'Error: ' . $_FILES[0]['error'] . '<br>';
	   }
	   else {

	   	      $target_dir = "upload/"; 
		   	  $target_file = $target_dir . $_FILES[0]["name"];

			  //限定尺寸
			   $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
			  //確認格式
			   if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			   && $imageFileType != "gif" ){
			   	  $errLog .= $_FILES[0]["name"]."格式不正確,需為jpg/png/jpeg檔案";
			   	  $process = 0;
			   }

			   //確認尺寸

		   	    $allowed_file_size = 500000;
		       	if ($_FILES[0]["size"] > $allowed_file_size) {
		   			   $errLog .= $_FILES[0]["name"]."檔案過大";
		   			   $process = 0;
		        }  		

	            if( $process != 0 ){
	            	    //檔案格式大小正確
	            	    //寫入資料庫
	            		$movefile = move_uploaded_file($_FILES[0]['tmp_name'],$target_file);
	            		if($movefile != 1){
	            		   $errLog = '檔案上傳失敗';
	            		   //檔案上傳失敗錯誤						
						   $result = array(
							  'status'   =>0,
							  'errorLog' => $errLog,
							  'message'  => '修改失敗!!'
                           ); 

	            		}else{

	            		//修改含上傳檔案 

            		        $userData = array(
            				  'ap_picurl' => $target_dir . $_FILES[0]["name"],
            		    	  'album_id' => $_POST['album_id'][0],
					  		  'ap_subject' => trim($_POST['ap_subject']),
					          'ap_desc' => trim($_POST['ap_desc'])
            				);

            			    $conditions = array('ap_id' => $_POST['ap_id']);
	            			$rowCount = $crud->update($tblName,$userData,$conditions);

	            		    if($rowCount){
	            		    	 $result = array(
									  'status' => 1,
									  'message' => '修改成功!!'
                          		 ); 
	            		    }else{
	            		    	 $result = array(
									  'status'   => 1,
									  'errorLog' => $errLog,
									  'message'  => '修改失敗!!'
                          		 ); 	            		    	
	            		    };
	            		}	
	            }else{
						//檔案格式大小錯誤 
						$result = array(
							'status'   => 0,
							'errorLog' => $errLog,
							'message'  => '修改失敗!!'
                       );           	
	            } 
	    }

        echo json_encode($result);
       
    }else{

	    		//沒有上傳檔案 僅改文字
			        $userData = array(
					  'album_id' => $_POST['album_id'][0],
					  'ap_subject' => trim($_POST['ap_subject']),
					  'ap_desc' => trim($_POST['ap_desc'])
					);

				    $conditions = array('ap_id' => $_POST['ap_id']);

	    			$rowCount = $crud->update($tblName,$userData,$conditions);

        		    if($rowCount){

        		    	 $result = array(
							  'status' => 1,
							  'message' => $rowCount
                 		 ); 

        		    }else{

        		    	 $result = array(
							  'status'   => 0,
							  'errorLog' => '寫入資料庫失敗',
							  'message'  => '修改失敗!!'
                 		 ); 	            		    	

        		    };

	    		    echo json_encode($result);

    }

   

}


//刪除

function delPitems($crud){
	$tblName = 'albumphoto';
	$conditions = array('ap_id' => $_POST['ap_id']);
	$delete = $crud->delete($tblName,$conditions);
	if($delete){
    	 $result = array(
			  'status'   => 1,
			  'message'  => '刪除成功!!'
  		 ); 	
	}else{
		 $result = array(
			  'status'   => 0,
			  'errorLog' => '刪除資料庫失敗',
			  'message'  => '刪除失敗!!'
  		 ); 
	}
	 echo json_encode($result);
}



?>