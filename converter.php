 <?php
  define('MP4',1);
  define('M3U8',2);
  $run=MP4;
  $run=M3U8|MP4;
  $base_dir    = getcwd();
  //input------------------------------------------
  $tmpFilename = $input_f  = $base_dir."/JulianLePlay.MOV";
  if(!file_exists ( $tmpFilename)){
    echo "das Video".$tmpFilename." ist nicht vorhanden\n";
    exit;
  }
  //output-----------------------------------------
  $baseFolder  =  $base_dir ."/tvmedia_bsp/";
 // $tempFolder  ='/home/httpd/htdocs/test/mymovie/video';
 // $mimeType    ='video/mp4';
 // $extension   = substr($mimeType,strpos($mimeType,'/')+1);

  $height0	  = 1080;
  // $height0	  = 720;
  // $height0	  = 360;
  $bitRate0    = 8192000;

$bitRate    = array(8192000,2048000,512000);
$height     = array(1080, 720, 360);
$videoWidth = array(1920, 1280, 640);



/*************MP4*****************/
if($run & MP4)  { 
  error_log("MP4");
    $preset = 'veryfast'; // ultrafast, superfast, veryfast, faster, fast
    $flags = ' -flags +global_header -movflags +faststart -y -strict experimental ';
    // $profile = '';
    $profile = ' -vprofile main -level 3.1 ';
    $profile = ' -vprofile main -level 3.1 -pix_fmt yuv420p';
    $acodec = '  -acodec aac -ab 128k -ac 2 -ar 48000 ';
    $threads = ' -threads 7 ';

    $execStr='ffmpeg -y -i ' . $tmpFilename . ' -pass 1 -f mp4 ' . $flags. ' -c:v libx264 -vf scale="-2:720" ' . $acodec . ' -preset ' . $preset . $profile . ' -r 25 -g 100 -b:v 1024000 -maxrate 1024000 -bufsize 1024000 ' . $threads . ' -y /dev/null -loglevel quiet  &&  ffmpeg -y -i '.$tmpFilename.' -pass 2 ' . $flags. ' -c:v libx264 -vf scale="-2:720" ' . $acodec . ' -preset ' . $preset . $profile . ' -r 25 -g 100 -b:v 1024000 -maxrate 1024000 -bufsize 1024000 ' . $threads . $baseFolder . 'video.mp4 -loglevel quiet'; // video0.mp4 jetzt video.mp4
    $start1 = time();
   
    $ret = exec($execStr, $op, $retVal);
    $end1 = time();
   
    error_log('FILE: ' . $baseFolder . 'video.mp4 ** BITRATE: 1024000 ** CONVERSION TIME: ' . round($end1-$start1) . 's');

    if($retVal != 0){
        throw new Exception('Cannot exec:'.$execStr.'    retVal:'.$retVal.' RETURN='.$ret);
    } else {

    }
    $end0 = time();
    error_log('CONVERTING FINISHED ** uniqId: ' . $data->uniqId . ' ** CONVERSION TIME: ' . round($end0-$start0) . 's');
  }
/*************MP4*****************/
/*************M3U8*****************/
if($run & M3U8){
 error_log("M3U8"); 
 // // // $tmpFilename =  $tempFolder . '.' . $extension;
 $flags       = ' -flags -global_header -movflags +faststart -y -strict experimental ';
 $vcodec      = '  -c:v libx264 -vf scale="-2:' . $height0 . '" ';
 $acodec      = '  -acodec aac -ab 128k -ac 2 -ar 48000 ';
 $preset      = ' -preset faster ';
 $profile     = ' -vprofile main -level 4.0 -pix_fmt yuv420p';
 $frames      = ' -r 25 -keyint_min 50 -g 100 -force_key_frames "expr:gte(t,n_forced*4)" ';
 $pass        = ' -pass 1 ';
 $pass        = ' ';
 $rates       = ' -b:v ' . $bitRate[0] .' '.$pass .'  -bufsize ' . $bitRate[0] . ' -maxrate ' . $bitRate[0] . ' -minrate ' . $bitRate[0] . ' ';
 $threads     = ' -threads 7 ';
 $sethls      = '-hls_time 8 -hls_list_size 0 ';
 
 $outfile     = $baseFolder.'video_' . $height0 . '.m3u8 ';

 $retVal=0;


// exec('ffmpeg -i '.$input_f.' '.$flags.' '.$vcodec.' '. $acodec.' '. $preset.'  '.$profile.' '.$frames .' '.$rates.' '.$threads.' '.$sethls.' '.$outfile);
  $fp = fopen($baseFolder . 'video.m3u8','w');
  fwrite($fp,"#EXTM3U\n");
  for($i=0;$i<3;$i++){
    $start1 = time();
    $vcodec = '  -c:v libx264 -vf scale="-2:' . $height[$i] . '" ';
    $outfile = $baseFolder . 'video_' . $height[$i] . '.m3u8 ';
    $rates = ' -b:v ' . $bitRate[$i] . ' -bufsize ' . $bitRate[$i] . ' -maxrate ' . $bitRate[$i] . ' -minrate ' . $bitRate[$i] . ' ';
  
    exec('ffmpeg -i ' . $tmpFilename .' '.$pass .' '. $flags . $vcodec . $acodec . $preset . $profile . $frames . $rates . $threads . $sethls . $outfile );
    $tsFiles = glob($baseFolder . 'video_' . $height[$i] . '*.ts');
   
    $tsFile = $tsFiles[0];
    $si = 0;
  
    //fwrite($fp,'#EXT-X-STREAM-INF:PROGRAM-ID=1,BANDWIDTH='.($bitRate[$i]).',RESOLUTION='.$videoWidth[$i].'x'.$height[$i].',CODECS="mp4a.40.2,avc1.77.30",NAME="'.$height[$i].'p"'."\n"); 
    //fwrite($fp,'#EXT-X-STREAM-INF:PROGRAM-ID=1,BANDWIDTH='.($bitRate[$i]).',RESOLUTION='.$videoWidth[$i].'x'.$height[$i].',NAME="'.$height[$i].'p"'."\n");
    fwrite($fp,'#EXT-X-STREAM-INF:PROGRAM-ID=1,BANDWIDTH='.($bitRate[$i]).',RESOLUTION='.$videoWidth[$i].'x'.$height[$i].',CODECS="mp4a.40.2,avc1.4d401e",NAME="'.$height[$i].'p"'."\n"); 

    fwrite($fp,'video_'.$height[$i].'.m3u8'."\n");
    $end1 = time();
    error_log('FILE: ' . $tsFile . ' ** BITRATE: ' . $bitRate[$i] . ' ** CONVERSION TIME: ' . round($end1-$start1) . 's');
  }
  fclose($fp);
}
/*************M3U8*****************/
?>
