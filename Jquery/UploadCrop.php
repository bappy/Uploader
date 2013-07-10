<?php
include("config.php");
include("amazon/s3.php");
$array=($_REQUEST);
include "lib/WideImage.php";
$area=(json_decode($array["cropSection"],true));
$time=time();

    if (isset($_POST["cropImage"])) {
        //Get the new coordinates to crop the image.
        $x1 = $area[0];
        $y1 = $area[1];
        $x2 = $area[2];
        $y2 = $area[3];
        $w = $area[4];
        $h = $area[5];

        $image = WideImage::load($array["cropImage"]);
        $cropped = $image->crop($x1,$x2, $w, $h);
        $cropped->saveToFile("img/".$time.".jpeg");
        $url="img/".$time.".jpeg";
        $url=uploadImage($url);
    }
function uploadImage($url, $file = '', $awsAccessKey = 'AKIAJ2EVTHZW6KSMJ3JQ', $awsSecretKey = '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', $bucket_name = 'yanfb')
{

    $s3 = new S3($awsAccessKey, $awsSecretKey); //create a new bucket
    $s3->putBucket($bucket_name, S3::ACL_PUBLIC_READ);
    $out = array();
    $time=time();
    $name=$time.'.jpeg';

    if ($s3->putObjectFile($url, $bucket_name,$name, S3::ACL_PUBLIC_READ)) {
        {

            $out["url"] = "http://" . $bucket_name . ".s3.amazonaws.com/" . $name;
            if(!mediaInformation($out["url"]))
            {

               echo 2;

            }
            else echo $out["url"];

            //return $out;
        }
    } else {
        return $out["url"] = false;
    }


}

function mediaInformation($url)
{
    list($width, $height, $type) = getimagesize($url);
    if(($width>0)&&($height>0))
        return true;
    return false;
}
?>