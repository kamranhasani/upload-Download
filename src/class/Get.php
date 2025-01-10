<?php
namespace Kamranhasani\UploadDownload\class;



class Get implements Up
{
//database
     private $hostname="localhost";
     private $dbname="file";
     private $username="root";
     private $password="";
     private $connect;
     private $tablename="table";
     private $selectone;
     private $row;

//upload
    private $namefile;
    private $tmpfile;
    private string $path;
    private $pasvand;
    private $one;
//download
    private $file;
    private $name;
    private $boundary;
    private $delay = 0;
    private $size = 0;



    //database-connect-start
    public function DB()
    {

        try{
           $this->connect= new \PDO ("mysql:host=$this->hostname;dbname=$this->dbname",$this->username,$this->password);
           $this->connect->exec("SET CHARACTER SET utf8");
           $this->connect->exec('set names utf8');
  
           return true;

       }catch(\PDOException $error){

            return 'error';
                
            }

        }
        //database-end

        //Upload-strat
    public function upload_one($newname,$tmp)

    {
    $this->namefile=$newname; 
    $this->tmpfile=$tmp;

    $this->path='images/';

    $this->pasvand = array(
        "gif" => "image/gif",
        "jpeg" => "image/jpeg",
        "jpg" => "image/jpg",
        "png" => "image/png",
        "pdf" => "application/pdf",
   
    );
  // $this->pasvand=array("jpeg","jpg","png","pdf","gif"); 

   
    $this->one=pathinfo($this->namefile, PATHINFO_EXTENSION);
 
    if (!in_array($this->one, $this->pasvand)  && file_exists($this->namefile) ) 
 
     {
 
      return false;
      
 
    } else {
  
    mkdir($this->path);

    if(move_uploaded_file($this->tmpfile ,$this->path . $this->namefile))

    {

        $all =$this->path . $this->namefile;
        $zero=$this->connect->prepare("INSERT INTO  `$this->tablename`(`path`)VALUES(?)");
        $zero->bindvalue(1 ,$all);
        $zero->execute();

        if($zero){

            return true;

        }else{

            return false;
        }

           }
      
          }
          
         }
         //Upload-end

         //download-start

         function set($file, $delay = 0) {
          
            $search_res = explode("/" , $file);
    
           
            if (! is_file($file) || @$search_res[0] != "images") {
               
                header("HTTP/1.1 400 Invalid Request");

                header('location:index.php');

            }else{
          
    
            $this->size = filesize($file);
            $this->file = fopen($file, "r");
            $this->boundary = md5($file);
            $this->delay = $delay;
            $this->name = basename($file);
        }
      
    }

        public function Get_download() {

            $ranges = NULL;
            $t = 0;
            if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_SERVER['HTTP_RANGE']) && $range = stristr(trim($_SERVER['HTTP_RANGE']), 'bytes=')) {
                $range = substr($range, 6);
                $ranges = explode(',', $range);
                $t = count($ranges);
            }
    
            header("Accept-Ranges: bytes");
            header("Content-Type: application/octet-stream");
            header("Content-Transfer-Encoding: binary");
            header(sprintf('Content-Disposition: attachment; filename="%s"', $this->name));
    
            if ($t > 0) {
                header("HTTP/1.1 206 Partial content");
                $t === 1 ? $this->pushSingle($range) : $this->pushMulti($ranges);
            } else {
                header("Content-Length: " . $this->size);
                $this->readFile();
            }
    
            flush();
        }
    

        public function readFile() {

            while ( ! feof($this->file) ) {
                echo fgets($this->file);
                flush();
                usleep($this->delay);
            }
        }
    

        public function readBuffer($bytes, $size = 1024) {

            $bytesLeft = $bytes;
            while ( $bytesLeft > 0 && ! feof($this->file) ) {
                $bytesLeft > $size ? $bytesRead = $size : $bytesRead = $bytesLeft;
                $bytesLeft -= $bytesRead;
                echo fread($this->file, $bytesRead);
                flush();
                usleep($this->delay);
            }
        }
    
    

        public function pushSingle($range) {

            $start = $end = 0;
            $this->getRange($range, $start, $end);
            header("Content-Length: " . ($end - $start + 1));
            header(sprintf("Content-Range: bytes %d-%d/%d", $start, $end, $this->size));
            fseek($this->file, $start);
            $this->readBuffer($end - $start + 1);
            $this->readFile();
        }
    
        public function getRange($range, &$start, &$end) {

            list($start, $end) = explode('-', $range);
            $fileSize = $this->size;
            if ($start == '') {
                $tmp = $end;
                $end = $fileSize - 1;
                $start = $fileSize - $tmp;
                if ($start < 0)
                    $start = 0;
            } else {
                if ($end == '' || $end > $fileSize - 1)
                    $end = $fileSize - 1;
            }
    
            if ($start > $end) {
                header("Status: 416 Requested range not satisfiable");
                header("Content-Range: */" . $fileSize);
                exit();
            }
    
            return array(
                    $start,
                    $end
            );
        }
    

        public function pushMulti($ranges) {

            $length = $start = $end = 0;
            $output = "";
    
            $tl = "Content-type: application/octet-stream\r\n";
            $formatRange = "Content-range: bytes %d-%d/%d\r\n\r\n";
    
            foreach ( $ranges as $range ) {
                $this->getRange($range, $start, $end);
                $length += strlen("\r\n--$this->boundary\r\n");
                $length += strlen($tl);
                $length += strlen(sprintf($formatRange, $start, $end, $this->size));
                $length += $end - $start + 1;
            }
            $length += strlen("\r\n--$this->boundary--\r\n");
            header("Content-Length: $length");
            header("Content-Type: multipart/x-byteranges; boundary=$this->boundary");
            foreach ( $ranges as $range ) {
                $this->getRange($range, $start, $end);
                echo "\r\n--$this->boundary\r\n";
                echo $tl;
                echo sprintf($formatRange, $start, $end, $this->size);
                fseek($this->file, $start);
                $this->readBuffer($end - $start + 1);
            }
            echo "\r\n--$this->boundary--\r\n";
        }

        //download-end


        //show and delete item-start
        public function show_item()
           {

            $this->selectone=$this->connect->prepare("SELECT * FROM `$this->tablename`");
    
            $this->selectone->execute();
  
               if ($this->selectone){

                $this->row = $this->selectone->fetchAll(\PDO::FETCH_ASSOC);
     
                return $this->row;
    
  
             }else {
  
                  return false;
          }


             }


             public function delete_path($path){

                $delete=$this->connect->prepare("DELETE FROM `$this->tablename` WHERE path =? ");
                $delete->bindvalue(1,$path);
              
               
                $delete->execute();
        
                if($delete){
        
                    return  true;
                }
        
                else{
        
                    return false;
                }
        
            }
            //show and delete item- end

}
?>