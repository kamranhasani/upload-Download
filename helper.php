<?php
session_start();

    function data_clear($value)
    {

        $value = trim($value);
        $value = strip_tags($value);
        $value = stripcslashes($value);
        $value = htmlspecialchars($value);
       
        return $value;

    }
    
    function token_file()
    
    {

        $number = base64_encode( openssl_random_pseudo_bytes(32));
       
        return $number;

    }
    
     function token_check($value)
    {

        if(isset($_SESSION['tokenone']) && !empty($_SESSION['tokenone']) && $_SESSION['tokenone']==$value){

           unset($_SESSION['tokenone']);

         return true;

        }

         else{

            return false;
         }

    }


    function renamefile()
    
    {

    $ip=$_SERVER["REMOTE_ADDR"];
   
     return  $ip;
        

    }
   

?>