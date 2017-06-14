<?php
class mtLog{
    private $file;
    function __construct($par){
        $this->file= $par;
        $lstr="\n\nMTascks Robot system v0.1 by Combi Security, PE";
        file_put_contents($this->file,$lstr."\n",FILE_APPEND);
        $lstr="Starting Robot loging";
        $this->Append($lstr);
    }
    function __destruct() {
    }
    function Append($lstr){
        $dt=date("[y-m-d H:i:s] ");
        file_put_contents($this->file,$dt.$lstr."\n",FILE_APPEND);
    }
}
?>
