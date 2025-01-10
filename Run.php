<?php
require  "vendor/autoload.php";
require  'helper.php';

use Kamranhasani\UploadDownload\class\Get;

$fone=new Get();
$fone->DB();

//Upload-stert

if (isset($_POST['btnfile']) && ($_POST['token']) ) {

    if(!empty($_FILES['upfile'])  && ($_FILES["upfile"]["size"] < 1500000) && ($_FILES['upfile']['error']==false) && (token_check($_POST['token'])==true))
    {

    $name=strtolower(data_clear($_FILES["upfile"]["name"]));
    $filename=explode(".", $name);
    $extension=end($filename);
    $newname=md5(renamefile() . $name) . '.' . $extension;

    $size=$_FILES["upfile"]["size"];
    $tmp=$_FILES['upfile']['tmp_name'];

     
    $three=$fone->upload_one($newname,$tmp);

    if($three){

    $_SESSION['message']='File uploaded';
    unset($_SESSION['tokenone']); 
    header('location:index.php');

    }else{

    $_SESSION['message']='File could not be uploaded';
    unset($_SESSION['tokenone']); 
    header('location:index.php');

    }

    }else{

    $_SESSION['message']='The file is not suitable';
    unset($_SESSION['tokenone']); 
    header('location:index.php');
   
    }
    }

//Upload-end


//Download-stert

if(isset($_GET['downlod']) && !empty($_GET['downlod']))
{

    set_time_limit(seconds: 800);
    
    $filenames=data_clear($_GET['downlod']);

    $fone->set($filenames, 10000);
    $four=$fone->Get_download();

    if($four){

        header('location:index.php');

    }else{

        $_SESSION['download']='File could not be download';
      
        header('location:index.php');

    }

}

//Download-end





?>