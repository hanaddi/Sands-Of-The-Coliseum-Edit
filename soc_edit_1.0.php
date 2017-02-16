
<?php

error_reporting(0);
class Edit{
    var $KONG_NUMBER="";
    var $NAME="";
    var $DATA="";
    var $data="";
    var $TOKEN="";
    var $SITE="";

    
    function __construct($token){
        $token = implode("+", explode(" ", $token));
        $this->TOKEN = $token;
        $ada=false;
        $i = strrpos($token, "A+4cAAA3HAAA");
        if($i!==false && $i<3 ){ //on kongregate
            $ada=true;
            $this->SITE="kong";

            $this->kong_get_name($token);
        }

        $i = strrpos($token, "A2YeAAA3HAAA");
        if($i!==false && $i<3 && !$ada){ //on armor games
            $ada=true;
            $this->SITE="armor";
            $this->get_info($token);
            if($this->data!=""){
                $this->NAME = substr($this->data, strpos($this->data, chr(7)), strpos($this->data, chr(18))-strpos($this->data, chr(7)) );
            }else{
                $ada=false;
            }


        }
        if(!$ada){
            $this->TOKEN="";
        }
    }


    //Get username from Kongregate
    function kong_get_name($token){
        $headers = array(
            "playertoken" => $token
        );
        if(sizeof($this->KONG_NUMBER)!=8){
            $this->get_info($token);
        }
        $result = $this->get_request("http://www.kongregate.com/users/". $this->KONG_NUMBER. "/posts", "", $headers);
        $s = $result["content"];
        $idx = strrpos($s, "Recent posts by ");
        if($idx!==false){
            $this->NAME = substr($s, $idx+16, strpos($s,' ', $idx+16)-16-$idx);
        }
    }


    //Getting proggress data
    function get_info($token){
        $headers = array(
            "playertoken" => $token
        );
        $result = $this->post_request("http://api.playerio.com/api/103" , "", $headers);

        if ($result['status'] == 'ok'){
            
            $r = $result["content"];
            $this->data = $r;


            $s = strrpos($r, "A+4cAAA3HAAA");
            if($s!==false){
                $this->TOKEN = substr($r, strrpos($r, "A+4cAAA3HAAA"), 88);
            }
            $s = strrpos($r, "A2YeAAA3HAAA");
            if($s!==false){
                $this->TOKEN = substr($r, strrpos($r, "A2YeAAA3HAAA"), 88);
            }

            $pos = strrpos($r, "kong");
            if(!($pos===false)){
                $kong_number = substr($r, $pos+4,8);
                $this->KONG_NUMBER = $kong_number;

            }
            $pos = strrpos($r, "<School");
            if($pos!==false){
                $this->DATA = substr($r, $pos, strripos($r,"[SOC_GP]") - $pos+8);
            }
            if(strrpos($r, "IThe method LoadMyPlayerObject can only be called when connected to a game")!==false){
                $this->TOKEN="";
                $this->DATA="";
                $this->data="";
            }
            if(strrpos($r, "HTTP Error")!==false){
                $this->TOKEN="";
                $this->DATA="";
                $this->data="";
            }
        }
        else {
            echo 'A error occured: ' . $result['error'];
            $this->TOKEN="";
        }

    }
    

    //Sending new data to Kongregate
    function kong_set_info($token, $data){
        $post_data = "";

        $post_data.=$this->hexToStr(array("18", "01", "08", "02", "12", "bf", "c7", "05", "22", "9c", "c7", "05", "0a", "08", "73", "61", "76", "65", "66", "69", "6c", "65", "12", "8e", "c7", "05", "50", "00", "12", "f0", "c6", "05", "32", "7e", "45", "4e", "7e", "30", "7e", "31", "7e", "30", "7e", "31", "34", "7e", "32", "30", "7e", "2a", "7e"));
        
        $post_data.=$data;
        $datalength = strlen($data);
        $ll=90969;
        if($datalength>$ll){
            echo("overload ".$datalength."/".$ll);
            return;
        }
        while ($datalength<90969) {
            $post_data.=$datalength%2==0?" ":"\n";
            $datalength++;
        }

        $tail = array("31", "2e", "34", "32", "28", "00", "20", "00", "18", "00", "41", "00", "00", "00", "e0", "ff", "ff", "ff", "7f", "30", "00", "08", "00", "3d", "ff", "ff", "ff", "7f", "12", "0c", "6b", "6f", "6e", "67",
            "3". substr($this->KONG_NUMBER,0,1),
            "3". substr($this->KONG_NUMBER,1,1),
            "3". substr($this->KONG_NUMBER,2,1),
            "3". substr($this->KONG_NUMBER,3,1),
            "3". substr($this->KONG_NUMBER,4,1),
            "3". substr($this->KONG_NUMBER,5,1),
            "3". substr($this->KONG_NUMBER,6,1),
            "3". substr($this->KONG_NUMBER,7,1),
            "28", "00", "0a", "0d", "50", "6c", "61", "79", "65", "72", "4f", "62", "6a", "65", "63", "74", "73");
        $post_data.=$this->hexToStr($tail);

        $headers = array(
            "playertoken" => $token
        );

        $result = $this->post_request("http://api.playerio.com/api/88" , $post_data, $headers);

        if ($result['status'] == 'ok'){
            return $result['content'];
        }
        else {
            echo 'A error occured: ' . $result['error'];
        }

    }
    
    //Sending new data to ArmorGames
    function armor_set_info($token, $data){
        $post_data = "";

        $post_data.=$this->hexToStr(array("08","02","18", "01", "12", "f8", "d1", "05", "0a", "0d", "50", "6c", "61", "79", "65", "72", "4f", "62", "6a", "65", "63", "74", "73", "22", "da", "d1", "05", "0a", "08", "73", "61", "76", "65", "66", "69", "6c", "65", "12", "cc", "d1", "05", "20", "00", "18", "00", "12", "ae", "d1", "05", "32", "7e", "45", "4e", "7e", "30", "7e", "30", "7e", "30", "7e", "33", "32", "7e", "31", "34", "7e", "2a", "7e"));
        $post_data.=$data;
        $tail=array("31", "2e", "34", "32", "28", "00", "30", "00", "41", "00", "00", "00", "e0", "ff", "ff", "ff", "7f", "08", "00", "50", "00", "3d", "ff", "ff", "ff", "7f", "12");//, "07");//, "46", "61", "74", "5f", "48", "61", "6e", "28", "00");
        $datalength = strlen($post_data);
        $ll=92416 - sizeof($tail) - 2 -strlen($this->NAME) ;
        if($datalength>$ll){
            echo("overload ".$datalength."/".$ll);
            //return;
        }
        while ($datalength<$ll) {
            $post_data.=$datalength%2==0?" ":"\n";
            $datalength++;
        }

        $post_data.=$this->hexToStr($tail);
        $post_data.=$this->NAME;
        $post_data.=$this->hexToStr(array("28", "00"));

        //echo($post_data);

        $headers = array(
            "playertoken" => $token
        );

        $result = $this->post_request("http://api.playerio.com/api/88" , $post_data, $headers);

        if ($result['status'] == 'ok'){
            return $result['content'];
        }
        else {
            echo 'A error occured: ' . $result['error'];
        }

    }

    function post_request($url, $data, $headers) {
        return $this->raw_request("POST",$url, $data, $headers);
    }
    function get_request($url, $data, $headers) {
        return $this->raw_request("GET",$url, $data, $headers);
    }

    function raw_request($method, $url, $data, $headers) {
        $url = parse_url($url);

        if ($url['scheme'] != 'http') {
            die('Error: Only HTTP request are supported !');
        }

        $host = $url['host'];
        $path = $url['path'];

        // open a socket connection on port 80 - timeout: 30 sec
        $fp = fsockopen($host, 80, $errno, $errstr, 30);

        if ($fp){

            fputs($fp, $method." $path HTTP/1.1\r\n");
            fputs($fp, "Host: $host\r\n");

            foreach ($headers as $key => $value) {
                fputs($fp, $key.": ". $value ."\r\n");
            }

            fputs($fp, "Content-length: ". strlen($data) ."\r\n");
            fputs($fp, "Connection: close");
            fputs($fp, "\r\n\r\n".$data);

            $result = '';
            while(!feof($fp)) {
                // receive the results of the request
                $result .= fgets($fp, 128);
            }
        }
        else {
            return array(
                'status' => 'err',
                'error' => "$errstr ($errno)"
            );
        }

        // close the socket connection:
        fclose($fp);

        // split the result header from the content
        $result = explode("\r\n\r\n", $result, 2);

        $header = isset($result[0]) ? $result[0] : '';
        $content = isset($result[1]) ? $result[1] : '';

        // return as structured array:
        return array(
            'status' => 'ok',
            'header' => $header,
            'content' => $content);

    }

    function hexToStr($hex){
        $string='';
        foreach ($hex as $value) {
            $string .= chr(hexdec($value));
        }
        return $string;
    }



}


function en($string){
    $hex = '';
    for ($i=0; $i<strlen($string); $i++){
        $ord = ord($string[$i]);
        $hexCode = dechex($ord);
        $hex .= substr('0'.$hexCode, -2);
    }
    return strToUpper($hex);
}
function de($hex){
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2){
        $string .=chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
}




$version="soc_edit_1.0.php";

if(isset($_GET['t']) || isset($_POST['s']) ){
    if(isset($_GET['g'])){
        $t=$_GET['t'];
        $e=new Edit($t);
        {
            $a=array();
            $a['token']=implode(" ",explode("\n",$e->TOKEN));
            $a['data']=implode(" ",explode("\n",$e->DATA));
            $a['name']=$e->NAME;
            $a['number']=$e->KONG_NUMBER;
            $a['site']=$e->SITE;
            echo(json_encode($a));

        }
    }else
    if(isset($_POST['s'])){
        $arr=($_POST['s']);
        $token = implode("+", explode(" ", $_POST['t']));
        $arr=($arr);
        $e=new Edit($token);
        if($e->TOKEN!="")
        {
            echo "Token : OK<br/>";
            if($e->SITE=='kong'){
                echo "Site : Kong<br/> Respose : ";
                echo $e->kong_set_info($e->TOKEN,$arr);
            }
            else if($e->SITE=='armor'){
                //echo("armor");
                echo "Site : ArmorGames<br/> Respose : ";
                echo $e->armor_set_info($e->TOKEN,$arr);
            }
        }
    }
}


?>
