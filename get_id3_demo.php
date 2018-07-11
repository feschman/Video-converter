 <?php
/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <info@getid3.org>               //
//  available at http://getid3.sourceforge.net                 //
//            or http://www.getid3.org                         //
//          also https://github.com/JamesHeinrich/getID3       //
/////////////////////////////////////////////////////////////////
//                                                             //
// /demo/demo.simple.php - part of getID3()                    //
// Sample script for scanning a single directory and           //
// displaying a few pieces of information for each file        //
// See readme.txt for more details                             //
//                                                            ///
/////////////////////////////////////////////////////////////////

// die('Due to a security issue, this demo has been disabled. It can be enabled by removing line '.__LINE__.' in '.$_SERVER['PHP_SELF']);

/**********/
function Get_Address_From_Google_Maps($lat, $lon) {

    // $url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lon&sensor=false";
    $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lon).'&sensor=false';
   
    // Make the HTTP request
    $json = @file_get_contents($url);

    // Parse the json response
     $data=json_decode($json);
   
     $status = $data->status;
     if($status=="OK")
     {
       return $data->results[0]->formatted_address;
     }
     else
     {
       return false;
      } 
    
}
/**********/


echo '<html><head>';
echo '<title>getID3() - /demo/demo.simple.php (sample script)</title>';
echo '<style type="text/css">BODY,TD,TH { font-family: sans-serif; font-size: 9pt; }</style>';
echo '</head><body>';


// include getID3() library (can be in a different directory if full path is specified)
require_once('getid3/getID3-1.9.14/getid3/getid3.php');

// Initialize getID3 engine
$getID3 = new getID3;

$DirectoryToScan = '/home/httpd/htdocs/test/video_convert/media'; // change to whatever directory you want to scan
$dir = opendir($DirectoryToScan);



echo '<table border="1" cellspacing="0" cellpadding="3">';
echo 
    '<tr>
        <th>Filename</th>
        <th>Artist</th>
        <th>Title</th>
        <th>Album</th>
        <th>Filepath</th>
        <th>filesize</th>
        <th>video_dataformat</th>
        <th>mime_type</th>
        <th>Audio_Codec</th>
        <th>Playtime</th>
        <th>Datum</th>
        <th>GPS</th>
    </tr>';
while (($file = readdir($dir) ) !== false) {
    $FullFileName = realpath($DirectoryToScan.'/'.$file);
    if ((substr($file, 0, 1) != '.') && is_file($FullFileName)) {
        set_time_limit(30);

        $ThisFileInfo = $getID3->analyze($FullFileName);



        getid3_lib::CopyTagsToComments($ThisFileInfo);

        if(preg_match('/.*\.(mp4|mov|avi|jpg|mp3)/i', $file)){
        $color='black';
        if(preg_match('/audio/', $ThisFileInfo['mime_type'])){
            $color='blue';
        }
         if(preg_match('/video/', $ThisFileInfo['mime_type'])){
            $color='green';
        }
         if(preg_match('/image/', $ThisFileInfo['mime_type'])){
             $color='red';
        }  



// if(preg_match('/.*\.jpg/i', $file)) { 
                 
//            var_dump($ThisFileInfo);
//             echo '<br>+++++++++++++++++++++';
//             echo '+++++++++++++++++++++++++<br><br>';
// }
           $GPS = '';
           $datum ='';
           if($ThisFileInfo['jpg']){  
                if($ThisFileInfo['jpg'] && $ThisFileInfo['jpg']["exif"]["IFD0"]["DateTime"]){
                   $datum = $ThisFileInfo['jpg']["exif"]["IFD0"]["DateTime"];
                }                                      
                if($ThisFileInfo['jpg'] && $ThisFileInfo['jpg']["exif"]["GPS"]["computed"]["latitude"]&&$ThisFileInfo['jpg']["exif"]["GPS"]["computed"]["longitude"]){
                 $latitude  = $ThisFileInfo['jpg']["exif"]["GPS"]["computed"]["latitude"];
                 $longitude = $ThisFileInfo['jpg']["exif"]["GPS"]["computed"]["longitude"];
                 //$GPS = 'latitude: '.$latitude.'  longitude: '. $longitude;
                    //exit;
                  $GPS = Get_Address_From_Google_Maps($latitude, $longitude);
                   
                }
           }
        
            // output desired information in whatever format you want
            echo '<tr style="color:'.$color.'">';
            echo '<td>'.htmlentities($ThisFileInfo['filename']).'</td>';
            echo '<td>'              .htmlentities(!empty($ThisFileInfo['comments_html']['artist']) ? implode('<br>', $ThisFileInfo['comments_html']['artist'])         : chr(160)).'</td>';
            echo '<td>'              .htmlentities(!empty($ThisFileInfo['comments_html']['title'])  ? implode('<br>', $ThisFileInfo['comments_html']['title'])          : chr(160)).'</td>';
            echo '<td>'              .htmlentities(!empty($ThisFileInfo['comments_html']['album'])  ? implode('<br>', $ThisFileInfo['comments_html']['album'])          : chr(160)).'</td>';
            echo '<td>'              .htmlentities(!empty($ThisFileInfo['filepath'])  ? $ThisFileInfo['filepath']: '').'</td>';
            echo '<td>'              .htmlentities(!empty($ThisFileInfo['filesize'])  ? $ThisFileInfo['filesize']: '').'</td>';
            echo '<td>'              .htmlentities(!empty($ThisFileInfo['video']['dataformat'])     ? $ThisFileInfo['video']['dataformat']   : '').'</td>';
            echo '<td>'              .htmlentities(!empty($ThisFileInfo['mime_type'])     ? $ThisFileInfo['mime_type'] : '').'</td>';
            echo '<td align="right">'.htmlentities(!empty($ThisFileInfo['audio']['codec'])        ?    $ThisFileInfo['audio']['codec']       : chr(160)).'</td>';
            echo '<td align="right">'.htmlentities(!empty($ThisFileInfo['playtime_string'])         ?                 $ThisFileInfo['playtime_string']                  : chr(160)).'</td>';
            echo'<td>'.$datum.'</td>';
            echo'<td>'.$GPS.'</td>';
            echo '</tr>';
        }
    }
}
echo '</table>';

echo '</body></html>'; 