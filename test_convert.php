<?php
echo "*********Wilkommen zu Videoconverter*********\n<br>";

$base_dir =getcwd();
// $input_dir ="/test/video_convert/test_video";
$input_dir ="/home/httpd/htdocs/test/images/animation/";
echo "*********Wir sind im $base_dir*********\n";
//$input_dir =$base_dir."/images/";
// $input_dir ="/test/images/";
// $bilder="";
// if($handle =opendir('.')){
//  while(false !== ($entry = readdir($handle))){
//  	if($entry != "." && $entry != ".." ){
//  		if(is_file($entry) && preg_match('/image.*\.jpg/i', $entry)){
//  			$bilder= $bilder=="" ? $entry : $bilder.", ".$entry;
//  		}
//  	}
 		
//  }

//   // $closedir($handle);
// }
exec('ffmpeg -f image2  -i  '.$input_dir.'img_%3d.jpg -r 25 -b 512k -s vga  videoa5.avi');
//exec('ffmpeg -i  videoa12.avi  testvideo.mp4');


?>