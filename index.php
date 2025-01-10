<?php
require  "vendor/autoload.php";
require  'helper.php';

use Kamranhasani\UploadDownload\class\Get;

$fone=new Get();
$fone->DB();
$rows=$fone->show_item();
$token=token_file();
$_SESSION['tokenone']=$token;

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>upload</title>
	<link type="text/css" href="css/bootstrap-rtl.min.css" rel="stylesheet">
	<link type="text/css" href="css/font-awesome.min.css" rel="stylesheet" />

</head>
<body >
<section style="margin-top:100px;">
	<div class="container">
		<div class="d-flex justify-content-center ">
			<div class="card" style=" float:right;">
				<div class="card-header" >
					<span style="float:right; font-size:larger; color: red; ">Upload_File</span>
					<?php

                       if (isset($_SESSION['message'])){

                       	echo $_SESSION['message']; 
				
						unset($_SESSION['message']);
	
                        	}
					?>
				
				<div class="card-body" style="direction: rtl;margin-top:50px;">
                
					<form    method="POST"   action="Run.php"  enctype="multipart/form-data" >
					
                       <div class="form-group">
    
                       <input type="file"  class="form-control-file btn" id="upfile" name="upfile" >
                         </div>
						

						<div class="input-group form-group">		
							<input type="hidden" class="form-control"   name="token" id="token" value="<?php echo  $token; ?>"/>
						</div>
						
						<div class="input-group form-group ">
                        <input type="submit" class="btn btn-success" name="btnfile" id="btnfile" value="Send">
                           </div>
				
                        
					</form>
			

				</div>
			
			</div>
			
		</div>
		
	</div>
	<hr>

	<div class="d-flex justify-content-center ">
			<div class="card" style=" float:right;">
				<div class="card-header" >
					<span style="float:right; font-size:larger; color: red; ">Download_File</span>
				
				
				<div class="card-body" style="direction: rtl; margin-top:50px; width: auto; ">
				<?php

                 if (isset($_SESSION['download']))
				 {

	             echo $_SESSION['download']; 

                 unset($_SESSION['download']);

	                 }

?>
<?php

foreach($rows as $items){

	if (file_exists ($items['path'])){
?>

			<p><a href="<?php echo "Run.php?downlod=$items[path]" ?>">Dowload file </a></p>	
<?php
}else {
$fone->delete_path($items['path']);

}
?>
<?php
}
?>

				</div>
			
			</div>
			
		</div>
		
	</div>
    </div>
	</section>
	<script src="js/jquery-3.1.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body><!-- This template has been downloaded from Webrubik.com -->
</html>

<!---php code---->
