	<?php include '../fn.php' ?>

	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle= "collapse" data-target="#sidebar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Admin</a>
				<ul class="user-menu">
					<li class="dropdown pull-right">
								<?php
								//登出
			    	  			
				    	  			if( isset($_GET['logout']) && ($_GET['logout'] == true) ){
				    	  				unset($_SESSION['username']);//清除username session
				    	  				unset($_SESSION['token']);//清除token session
				    	  				$page = $_SERVER['PHP_SELF'];
				    	  				$sec = "10";
				    	  				header("Refresh: $sec; url=$page");
				    	  			}
				    	  		//未登入狀態
									if(!isset($_SESSION['username'])&&!isset($_SESSION['token'])){
										?>
										<script>
										var confirmbox = confirm('請登入此系統');
										if(confirmbox){
											location.href="../login.php";
										}
										</script>										
										<?php
									}else{
								//已登入狀態
										echo '
										<a>歡迎'.$_SESSION['username'].'登入</a>';
									}	    	
						    	?>
						<a href="?logout=true" class="dropdown-toggle" data-toggle="dropdown"><svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg> Logout </a>
					</li>
				</ul>
			</div>
							
		</div><!-- /.container-fluid -->
	</nav>