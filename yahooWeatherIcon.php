<?PHP

//curl "http://weather.yahooapis.com/forecastrss?w=1968207&u=c" | grep -Po '(?s)Current Conditions:.*?<BR />$' | /usr/local/bin/w3m -dump  -T text/html

//get the location from the query string and default to rechovot
$locationId = empty($_REQUEST['w'])?'1968207':$_REQUEST['w'];
$url = "http://weather.yahooapis.com/forecastrss?w=$locationId&u=c";
$xml = new SimpleXmlElement($url,LIBXML_NOCDATA,1);
$description = $xml->channel->item->description;
preg_match('/<img src="([^"]*)"/i',$description,$result);
$gifim=imagecreatefromgif($result[1]);
//print_r(getimagesize($result[1]));
$gifW=52;
$gifH=52;
$text='test';
$text = strip_tags($description);
//echo $text;
//die();
header("Content-type: image/png");
$im = imagecreatetruecolor(300,$gifH);
$black = imagecolorallocate($im, 0, 0, 0);
// Make the background transparent
imagecolortransparent($im, $black);
$y = 0;

//this is where you set the text color
$textColor = imagecolorallocate($im, 255, 255, 200);
foreach(explode("\n",$text) as $line){
    if(strlen($line)>0 and $line!='Full Forecast at Yahoo! Weather' and $line != '(provided by The Weather Channel)'){
        imagestring($im,2,$gifH,$y,$line,$textColor);
        $y+=9;
    }
}
imagecopymerge($im,$gifim,0,0,0,0,$gifW,$gifH,100);
imagepng($im);

imagedestroy($im);
