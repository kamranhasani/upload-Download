<?php
namespace Kamranhasani\UploadDownload\class;





interface Up{

    public function upload_one($newname,$tmp);
    function set($file, $delay = 0);
    public function Get_download();
    public function readFile();
    public function readBuffer($bytes, $size);
    public function pushSingle($range);
    public function getRange($range, &$start, &$end);
    public function pushMulti($ranges);

    public function show_item();
    public function delete_path($path);
    
}
?>