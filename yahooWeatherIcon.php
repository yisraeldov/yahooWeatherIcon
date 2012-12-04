<?PHP

//curl "http://weather.yahooapis.com/forecastrss?w=1968207&u=c" | grep -Po '(?s)Current Conditions:.*?<BR />$' | /usr/local/bin/w3m -dump  -T text/html
$url = "http://weather.yahooapis.com/forecastrss?w=1968207&u=c";
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
foreach(explode("\n",$text) as $line){
    if(strlen($line)>0 and $line!='Full Forecast at Yahoo! Weather' and $line != '(provided by The Weather Channel)'){
        imagestring($im,2,$gifH,$y,$line,imagecolorallocate($im, 255, 255, 200));
        $y+=9;
    }
}
imagecopymerge($im,$gifim,0,0,0,0,$gifW,$gifH,100);
imagepng($im);

imagedestroy($im);
