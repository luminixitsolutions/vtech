<?php
session_start();
$sessionid = session_id();
// Uncomment if you want to allow posts from other domains
// header('Access-Control-Allow-Origin: *');
require_once ("../config.php");
require_once('slim.php');
$UserId = $_SESSION['User']['id'];
// Get posted data, if something is wrong, exit
try {
    $images = Slim::getImages();
   
}
catch (Exception $e) {

    // Possible solutions
    // ----------
    // Make sure you're running PHP version 5.6 or higher

    Slim::outputJSON(array(
        'status' => SlimStatus::FAILURE,
        'message' => 'Unknown'
    ));

    return;
}

// No image found under the supplied input name
if ($images === false) {

    // Possible solutions
    // ----------
    // Make sure the name of the file input is "slim[]" or you have passed your custom
    // name to the getImages method above like this -> Slim::getImages("myFieldName")

    Slim::outputJSON(array(
        'status' => SlimStatus::FAILURE,
        'message' => 'No data posted'
    ));
   
    return;
}

// Should always be one image (when posting async), so we'll use the first on in the array (if available)
$image = array_shift($images);

// Something was posted but no images were found
if (!isset($image)) {

    // Possible solutions
    // ----------
    // Make sure you're running PHP version 5.6 or higher

    Slim::outputJSON(array(
        'status' => SlimStatus::FAILURE,
        'message' => 'No images found'
    ));

    return;
}

// If image found but no output or input data present
if (!isset($image['output']['data']) && !isset($image['input']['data'])) {

    // Possible solutions
    // ----------
    // If you've set the data-post attribute make sure it contains the "output" value -> data-post="actions,output"
    // If you want to use the input data and have set the data-post attribute to include "input", replace the 'output' String above with 'input'

    Slim::outputJSON(array(
        'status' => SlimStatus::FAILURE,
        'message' => 'No image data'
    ));

    return;
}



// Image Resize
function image_resize($src, $w, $h) {
    $img = imagecreatefromjpeg($src);
    $oldw = imagesx($img);
    $oldh = imagesy($img);
    $template = imagecreatetruecolor($w, $h);
    imagecopyresampled($template, $img, 0, 0, 0, 0, $w, $h, $oldw, $oldh);
    return $template;
}

//imagejpeg(image_resize("$path/pic1.jpg", 600, 400), "$path/pic1-resize.jpg");

function image_watermark($src, $dst, $stamp_x, $stamp_y){
    $fontfile = dirname(__FILE__) . "/arial.ttf";
    //$fontfile = "http://atootdor.com/arial.ttf";
    $text = "";
    
     $stamp_w = 85;
    $stamp_h = 450;
    $stamp = imagecreatetruecolor($stamp_w, $stamp_h);
    
    $white = imagecolorallocate($stamp, 255, 255, 255);
    $grey = imagecolorallocate($stamp, 0, 0, 120);
    imagefilledrectangle($stamp, 50, 50, 50, 100, $stamp_h, $white); // create rectangle
    $angle = 90;
    $fsize = 50; // font size
    $font_x = 65; 
    $font_y = 420;
    // add shadow
    imagettftext($stamp, $fsize, $angle, $font_x+1, $font_y+1, $grey, $fontfile, $text); 
    // add text
    imagettftext($stamp, $fsize, $angle, $font_x, $font_y, $white, $fontfile, $text); 
    
    // merge image and stamp
    $img = imagecreatefromjpeg($src);
    $pct = 50;
    imagecopymerge($img, $stamp, $stamp_x, $stamp_y, 0, 0, $stamp_w, $stamp_h, $pct); 
    imagejpeg($img, $dst);
} 

//image_watermark("$path/pic1-resize.jpg", "$path/pic1-watermark.jpg", 10, 10);

// if we've received output data save as file
if (isset($image['output']['data'])) {

    // get the name of the file
    $name = $image['output']['name'];
   
    // get the crop data for the output image
    $data = $image['output']['data'];
    
    $filetmp = $image['output']['temp_name'];
    $filename = $image['output']['name'];
    $filetype = $image['output']['type'];
    $filesize = $image['output']['size'];
    $fileinfo = getimagesize($image['output']['temp_name']);
    $filewidth = $fileinfo[0];
    $fileheight = $fileinfo[1];
    $rand_id = rand(1,1000);
  /*  $fnm = substr($filename, 0,strrpos($filename,'.')); 
      $fnm = str_replace(" ","_",$fnm);
      $ext = substr($filename,strpos($filename,"."));
      $dest = '../uploads/'. $rand_id . "_".$fnm . $ext;
      $imagepath =  $rand_id . "_".$fnm . $ext;
      $filepath = $imagepath;
      $filepath_thumb = $imagepath;*/
    //$filename2 = substr($filename, 0,strrpos($filename,'.'));   
    $filename2 = $rand_id.str_replace(" ","_",$filename);
    $filepath = "../../uploads/".$filename2;
               
    if($filetmp == "")
    {
        echo "please select a photo";
    }
    else
    {
        
        if($filesize > 2097152)
        {
            echo "photo > 2mb";
        }
        else
        {
            
            if($filetype != "image/jpeg" && $filetype != "image/jpg" && $filetype != "image/png" && $filetype != "image/gif")
            {
                echo "Please upload jpg / jpeg / png / gif";
            }
            else
            {
                
                move_uploaded_file($filetmp,$filepath);
                
                if($filetype == "image/jpeg")
                {
                    $imagecreate = "imagecreatefromjpeg";
                    $imageformat = "imagejpeg";
                }
                if($filetype == "image/jpg")
                {
                    $imagecreate = "imagecreatefromjpg";
                    $imageformat = "imagejpg";
                }
                if($filetype == "image/png")
                {
                    $imagecreate = "imagecreatefrompng";
                    $imageformat = "imagepng";
                }
                if($filetype == "image/gif")
                {
                    $imagecreate= "imagecreatefromgif";
                    $imageformat = "imagegif";
                }
                
                $new_width = "180";
                $new_height = "180";
                
                $image_p = imagecreatetruecolor($new_width, $new_height);
                $image = $imagecreate($filepath); //photo folder
                
                imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $filewidth, $fileheight);
                $imageformat($image_p, $filepath_thumb);//thumb folder
                
                
                $name2 = $filename2;
                
                 $path = "../../uploads/";
                 $resizeimag = $filepath."_resize.jpg";
                 $fnm = substr($filepath, 0,strrpos($filepath,'.')); 
                 $fnm = str_replace(" ","_",$fnm);
                 $watermarkimag = $fnm."_watermark.jpg";
                 $fnm2 = substr($filename2, 0,strrpos($filename2,'.')); 
                 $fnm2 = str_replace(" ","_",$fnm2);
                 $my_image_path = $fnm2."_watermark.jpg";
                 imagejpeg(image_resize($filepath, 700, 900), $resizeimag); 
                 image_watermark($resizeimag, $watermarkimag, 600, 400); 
                 unlink($filepath);
                 unlink($resizeimag);
                 unlink($filename2);
                 $sql = "DELETE FROM tbl_crop_image WHERE SessionId='$sessionid' AND UserId = '$UserId' AND SrNo = '3'";
                 $conn->query($sql);
                $q = "INSERT INTO tbl_crop_image SET Image = '$my_image_path',SessionId='$sessionid',UserId = '$UserId',SrNo = '3'";
                $conn->query($q);  
                
                
                
            }
            
        }
    }
    

   /* $time = date("y-m-d H.i.s");
    $rand_id = rand(1,1000);
    $name2 = $rand_id.$time.$name;
    $q2 = "DELETE FROM crop_image WHERE SessionId = '$sessionid'";
    $conn->query($q2);
    $q = "INSERT INTO crop_image SET Photo = '$name2',SessionId = '$sessionid'";
    $conn->query($q);*/
  
  
    
    
    
    // If you want to store the file in another directory pass the directory name as the third parameter.
    // $output = Slim::saveFile($data, $name, 'my-directory/');

    // If you want to prevent Slim from adding a unique id to the file name add false as the fourth parameter.
    // $output = Slim::saveFile($data, $name, 'tmp/', false);

    // Default call for saving the output data
    $output = Slim::saveFile($data, $name2, '../../uploads/', false);
    //$_SESSION['photo'] = $output;
    
    
}

// if we've received input data (do the same as above but for input data)
if (isset($image['input']['data'])) {

    // get the name of the file
    $name = $image['input']['name'];
  
    // get the crop data for the output image
    $data = $image['input']['data'];
    
   

    // If you want to store the file in another directory pass the directory name as the third parameter.
    // $input = Slim::saveFile($data, $name, 'my-directory/');

    // If you want to prevent Slim from adding a unique id to the file name add false as the fourth parameter.
    // $input = Slim::saveFile($data, $name, 'tmp/', false);

    // Default call for saving the input data
    $input = Slim::saveFile($data, $name,'../../upload/', false);
    //$_SESSION['photo'] = $input;

}



//
// Build response to client
//
$response = array(
    'status' => SlimStatus::SUCCESS
);

if (isset($output) && isset($input)) {

    $response['output'] = array(
        'file' => $output['name'],
        'path' => $output['path']
    );

    $response['input'] = array(
        'file' => $input['name'],
        'path' => $input['path']
    );

}
else {
    $response['file'] = isset($output) ? $output['name'] : $input['name'];
    $response['path'] = isset($output) ? $output['path'] : $input['path'];
}

// Return results as JSON String
Slim::outputJSON($response);