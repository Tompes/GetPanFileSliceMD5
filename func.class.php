<?php
/**
 * Author Tompes
 * Github https://github.com/Tompes/GetPanFileSliceMD5
 */

class GetSliceMD5 {
    public $message;
    public $token;
    public function __construct($t=0){
        $this->token = $t;
    }


    public function sliceMD5($link,$referrer=0,$size=262143,$ua=0){
        //if you want get 10 byte slice form the whole file, you must import $size = 9

        $slice = $this->getStream($link,0,$referrer,$size,$ua);
        if(!$slice){
            $this->message = "Failed to got slice,please check parameter out and try again!";
            return false;
        }
        return md5($slice);
    }
    private function getStream($link,$post=0,$referrer=0,$size=0,$ua=0){
        $ua       = $ua?$ua:$_SERVER['HTTP_USER_AGENT'];
        $range    = $size?"Range: bytes=0-{$size}\r\n":"";
        $referrer = $referrer?$referrer:"https://pan.baidu.com/disk/home";
        $link = str_replace("https://","http://",$link);
        $opts = [
            "http" => [
                "method" => $post?"POST":"GET",
                "timeout"=>30,
                "header" => "Accept-language: *\r\n" .
                    $range.
                    "Accept:*/*\r\n".
                    "Accept-Encoding:gzip, deflate, sdch, brr\n".
                    "Cache-Control:no-cache\r\n".
                    "Connection:keep-alive\r\n".
                    "Cookie:{$this->token}\r\n".
                    "Referer:{$referrer}\r\n".
                    "Pragma:no-cache\r\n".
                    "User-Agent:{$ua}\r\n",
                "content"=> $post?$post:"",

            ],
            "ssl"=>[
                "verify_peer"=>false,
                "verify_peer_name"=>false
            ]
        ];

        $context = stream_context_create($opts);

// Open the file using the HTTP headers set above , then return it.
        $timer = 0;
        $result ="";
        while($timer<3 && $result = @file_get_contents($link, false, $context)==false) $timer++;
        if($timer==3){
            $this->message = "Timeout!";
            return false;
        }
        return $result;

    }
}
