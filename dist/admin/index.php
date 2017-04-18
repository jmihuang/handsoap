<?php 
  include 'headmeta.php';
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>管理後台</title>

<link href="style/bootstrap.min.css" rel="stylesheet">
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link href="style/styles.css" rel="stylesheet">

<!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->

</head>


<body>
<?php 
  include 'header.php';
  include 'sidebar.php';
  include 'global-element.php'
?>


<?php 
   include_once "../connMysql.php";
   //建立data1.json文件 bootstrapTable
   try {
   	
   	  if(isset($_SESSION['username'])&&isset($_SESSION['token'])){
          $i = 0;
          $response = array();
          $array = array();
          $albumNo = array();
          $query_Album = "SELECT * FROM `album`";
          $albumstmt = $DB_con->query($query_Album);
          while ($row = $albumstmt->fetch(PDO::FETCH_ASSOC)){
          	array_push($albumNo, $row["album_title"]);
          }
          $query_RecAlbum = "
           SELECT * FROM `albumphoto`
          ";
 
          $stmt = $DB_con->query($query_RecAlbum);
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
          {
          	 $album_id = '';
          	  foreach ($albumNo as $key => $value) {
          	 	if($key == $row['album_id']){
          	 	  $album_id .='<option value='.$value.' selected="selected" name="'.($key+1).'">'.$value.'</option>';
          	 	}else{
          	 	  $album_id .='<option value='.$value.' name="'.($key+1).'">'.$value.'</option>';
          	 	}
          	  }
          	 $ap_picurl = file_exists($row['ap_picurl'])?$row['ap_picurl']:'http://placehold.it/80x80';
                 
          	 $array[$i] = array(
          	 			'no'  => ($i+1),
	              	 	'album_id'  => '<select id="al_id'.($i+1).'">'.$album_id.'</select>',
	              	 	'ap_subject'=> '<input type="text" value='.$row['ap_subject'].' name="subject'.($i+1).'">', 
	              	 	'ap_desc'   => '<textarea cols="30" rows="4" name="desc'.($i+1).'";>'.$row['ap_desc'].'</textarea>', 
	              	 	'ap_date'   => $row['ap_date'], 
	              	 	'ap_picurl' => '<label for="file_upload'.($i+1).'"><input type="file" class="upl" id="file_upload'.($i+1).'" data-itemId="'.($i+1).'" data-apId="'.$row['ap_id'].'"  style="display:none">
	              	 	<img id="ap_picurl'.($i+1).'" src="'.$ap_picurl.'" width="80px"></label>', 
	              	 	'button'    => '<a class="btn btn-warning save_btn" id="save_btn'.($i+1).'" style="display:none" onclick="save_btn('.($i+1).','.$row['ap_id'].')">儲存</a>
                        <a type="button" class="btn btn-danger del_btn" onclick="del_btn(this,'.($i+1).','.$row['ap_id'].')">刪除</a></form>'
              	 	    );

             $i++;
              	 
          }  
         
          $fp = fopen('data1.json', 'w');
          fwrite($fp, json_encode($array));
          fclose($fp);
       }

   } catch (Exception $e) {
       echo 'Caught exception: ',  $e->getMessage(), "\n";
   }
   //./end

    
?>
		
	
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active">Icons</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">商品總覽</h1>
			</div>
			<div class="col-lg-12">
			<div class="panel panel-default">
								<div class="panel-body tabs">
									<ul class="nav nav-tabs">
										<li class="active"><a href="#tab1" data-toggle="tab">商品總覽</a></li>
										<li class=""><a href="#tab2" data-toggle="tab">新增商品</a></li>
									</ul>
					
									<div class="tab-content">
										<div class="tab-pane fade active in" id="tab1">
										<!--tab1 content-->
										<div class="col-lg-12">
											<div class="panel panel-default">
												<div class="panel-heading">Advanced Table</div>
												<div class="panel-body">
													<table data-toggle="table" data-url="data1.json"  data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc" id="itemList">
													    <thead>
													    <tr>
													        <!-- <th  data-checkbox="true"></th> -->
													        <th data-field="" data-checkbox="true"></th>
													        <th data-field="no"  data-sortable="true">序號</th>
													        <th data-field="album_id" data-sortable="true">類別</th>
													        <th data-field="ap_subject"  data-sortable="true">標題</th>
													        <th data-field="ap_desc"  data-sortable="true">說明</th>
													        <th data-field="ap_date" data-sortable="true">上傳日期</th>
													        <th data-field="ap_picurl" data-sortable="false">圖片</th>
													        <th data-field="button" data-sortable="false"></th>
													    </tr>
													    </thead>
													</table>
												</div>
											</div>
										</div>
											<!--/.tab1 content-->
										</div>
										<div class="tab-pane fade" id="tab2">
												<div class="panel-body">
													<div class="col-md-12">
													<!--tab2 content-->
														<form data-toggle="validator" type="post" role="form"  enctype="multipart/form-data" id="uploadPitems" >

															<div class="form-group" id="getPItems">
															</div>
														
															<div class="form-group">
																<label>商品名稱</label>
																<input name="pname" class="form-control"  required>
															</div>
													
															<div class="form-group">
																<label>上傳圖片</label>
																<input type="file" name="file[]" id="uploadFile"  required>
																 <p class="help-block">(上限5MB檔案 僅限jpg/png/jpeg檔案)</p>
															</div>
															
															<div class="form-group">
																<label>說明</label>
																<textarea name="pdesc" class="form-control" rows="3" required></textarea>
															</div>

															
														</div>

															
															<button type="submit" class="btn btn-primary" >送出</button>
															<button type="reset" class="btn btn-default">重新設定</button>
													
													</form>
													<!--./tab1 content-->
												</div>
										</div>


									</div>
								</div>
							</div>
			</div>
		</div><!--/.row-->

	</div><!--/.main-->

	<script src="js/lib/jquery-1.11.1.min.js"></script>
	<script src="js/lib/bootstrap.min.js"></script>
	<script src="js/ajax.js"></script>
	<script src="js/lib/validator.js"></script>
	<script src="js/lib/bootstrap-table.js"></script>
	<script>
		!function ($) {
			$(document).on("click","ul.nav li.parent > a > span.icon", function(){		  
				$(this).find('em:first').toggleClass("glyphicon-minus");	  
			}); 
			$(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
		}(window.jQuery);

		$(window).on('resize', function () {
		  if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
		})
		$(window).on('resize', function () {
		  if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
		})

		$(window).on('load',function (){
			console.log('load');
			//取得商品類別
			var data = {
			  "action": "getPItems"
			};
			getJSON("useAPI.php",data,function (rs){
				var html = '<label>類別</label>';
				// rs = JSON.parse(rs);
				var obj = rs.resdata;
				$.each(obj,function (i){
				   html += '<div class="radio">';
				   html += '<label>';
				   html += '<input type="radio" name="item[]" value="'+obj[i]['album_id']+'" name="optionsRadios" value="'+obj[i]['album_title']+'" ">';
				   html += obj[i]['album_title'];
				   html += '</label>';
				   html += '</div>';
				});				
				$('#getPItems').html(html).find($('input[type="radio"]')).eq(0).attr('checked',true);
			});
		});

		//商品列表input/select值有變動
		$(document).on('keyup', 'input[name^="subject"],textarea[name^="desc"]', function(ev){
			//顯示儲存按鈕
			show_save_btn($(this));
		});

		$(document).on('change', 'select[id^="al_id"]', function(ev){
			//顯示儲存按鈕
			show_save_btn($(this));
		});

		function show_save_btn($this){
				var $save_btn = $this.closest( "tr" ).find($('.save_btn'));
				console.log($this.closest( "td" ));
				if(!$save_btn.is(':visible')){
					$save_btn.fadeIn();
				}
		}


		//改變上傳圖片
		var change_file = [];//上傳檔案陣列
		$(document).on('change','input[id^="file_upload"]',function (e){
			var file = this.files[0];
			var itemId = this.getAttribute("data-itemid");
			var apId = this.getAttribute("data-apid");
			var size = file.size;
			var maxSize = 500000;
	        var checkType = file.type != 'image/png' || file.type != 'image/jpg' || file.type != 'image/gif' || file.type != 'image/jpeg';	        
        	
        	if(!checkType){
        		alert('格式不正確,需為jpg/png/jpeg檔案');
        		if(change_file.indexOf(apId)>-1){
        			 array.splice(change_file.indexOf(apId), 1);//移除上傳檔案陣列
        		};
        	}else if(size>maxSize){
        		alert('上傳檔案不得超過50MB請重新上傳');
        		if(change_file.indexOf(apId)>-1){
        			 array.splice(change_file.indexOf(apId), 1);//移除上傳檔案陣列
        		};
        	}else{
        		if (this.files && this.files[0]) {
        		    var reader = new FileReader();			    
        		    reader.onload = function (e) {
        		    	//產生預覽圖
        		        $('#ap_picurl'+itemId).attr('src', e.target.result);
        		    }	
        		    //上傳圖片正確路徑		
        		    target_file = file.name;  
        		    reader.readAsDataURL(file);
        		    //顯示儲存按鈕
        		    show_save_btn($(this));
        		}

        		change_file.push(apId);//新增上傳檔案陣列
        	};	
		


		});

		function format_float(num, pos)
    	{
	        var size = Math.pow(10, pos);
	        return Math.round(num * size) / size;
        }

		//儲存按鈕
		function save_btn(item_id,ap_id){
			 event.preventDefault();
			var form_data = new FormData();
			//有更改圖片
			if(change_file.indexOf(ap_id)>-1){
			   form_data.append('input_file_name', $('#file_upload'+item_id).prop('files')[0]);
			};
			var files = $('#file_upload'+item_id)[0]['files'];
			$.each(files, function(key, value)
			{
			    form_data.append(key,value);
			});

			var data = {
			  "action"     : "uploadPitems",
			  "album_id"   : parseInt($('#al_id'+item_id+' option:selected').attr('name')),
			  "ap_id"      : ap_id,
			  "ap_subject" : $('input[name="subject'+item_id+'"]').val(),
			  "ap_desc"    : $('textarea[name="desc'+item_id+'"]').val(),
			};

			$.each(data, function(key, value)
			{
			    form_data.append(key,value);
			});
			                             
			postUpload("ajax_php_file.php",form_data,function (rs){ 
				rs = JSON.parse(rs);
				if(rs.status == 1){
					var alertMsg = '<strong>Success!</strong>'+rs.message;
					$('.alert-success').html('').html(alertMsg).fadeIn().delay(2000).fadeOut();
			        $('#save_btn'+item_id+'').fadeOut(function (){
			        	// window.location.reload();
			        });//儲存按鈕消失
				};
			});	
		}

		//刪除
		function del_btn($this,item_id,ap_id){
			var confirmAgain = confirm('確定刪除第'+item_id+'筆資料?\n 一但刪除即無法復原'); 
			if(confirmAgain){
				var form_data = new FormData();
				var data = {
				  "action"     : "delPitems",
				  "ap_id"      : ap_id
				};

				$.each(data, function(key, value)
				{
				    form_data.append(key,value);
				});
				                             
				postUpload("ajax_php_file.php",form_data,function (rs){ 
					rs = JSON.parse(rs);
					if(rs.status == 1){
						var alertMsg = '<strong>Success!</strong>'+rs.message;
						$('.alert-success').html('').html(alertMsg).fadeIn().delay(2000).fadeOut();
						$this.closest( "tr" ).style.display="none";
					};
				});			
			}	

		}

		//上傳商品資料
		$('#uploadPitems').on('submit', function (e) {
			 e.preventDefault();
			//驗證
		    var isVali = $(this).validator();
		    if(isVali){			 
				var form_data = new FormData($(this)[0]);                  
				form_data.append('action','createPitems');                             
				postUpload("ajax_php_file.php",form_data,function (rs){
					rs = JSON.parse(rs);
					if(rs.status == 1){
				    $(':input,textarea').val('');
					var alertMsg = '<strong>Success!</strong>'+rs.message;
					$('.alert-success').html('').html(alertMsg).fadeIn().delay(2000).fadeOut(function (){
						window.location.reload();
					});
				};
				});

		    };
		 
		})


	</script>	
<!-- 叫出全部已經上傳的檔案/檔案尺寸
<?php 
  // $target_dir = "./upload/";
  //取出目前有的全部檔案
  // $variable = glob($target_dir."*");
  // foreach ($variable as $filename ) {
  //   echo '<tr>';
  //   echo '<td>'.basename($filename).'</td>';
  //   echo '<td>'.filesize($filename).'</td>';
  //   echo '<td><a href="upload_get.php?del=true&amp;filename=' .basename($filename).'">Delete</a></td>';
  //   echo '</tr>';
  // }
 // $file = basename($path);         // $file is set to "index.php"
 // $file = basename($path, ".php"); // $file is set to "index"
?>
 -->
	
</body>

</html>
