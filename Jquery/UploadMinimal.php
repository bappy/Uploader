<?php
include "config.php";
include "lib/WideImage.php";
include("amazon/s3.php");
$name=time();

if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"],
    "img/" . $name.str_replace(" ","",$_FILES["fileToUpload"]["name"])));
 $im=$name.str_replace(" ","",$_FILES["fileToUpload"]["name"]);

$size=filesize('img/'.$im);

if($size>size_limit)
{
    echo 2;
    return false;
}
//$image = WideImage::load("img/".$im);

//$resized = $image->resize(400, 300);
//$resized->saveToFile("img/".$im);

$url="img/".$im;
$url=uploadImage($url);
echo $url["out"];
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