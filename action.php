<?php

if(!defined('DOKU_INC')) die();
require_once(DOKU_PLUGIN.'action.php');
 
class action_plugin_mediatooltip extends DokuWiki_Action_Plugin { 
   private $fields;
   private $toolTipOptions;
 function register(Doku_Event_Handler $controller) {       
       $controller->register_hook('MEDIA_UPLOAD_FINISH', 'BEFORE', $this, '_media_finish');
       $controller->register_hook('TPL_CONTENT_DISPLAY', 'BEFORE', $this, '_insert_exif'); 
 }

   function __construct() {
      $this->init_fields();
      $this->$toolTipOptions = explode(',',$this->getConf('fields'));
      //if(!empty($this->$toolTipOptions)) $this->$toolTipOptions = explode($this->$toolTipOptions);
   }

 /*
 Array
(
    [FileModified] => 1541022351
    [Time] => 1541022351
    [TimeSource] =>.
    [TimeStr] => 2018-10-31 16:45:51
    [EarliestTime] => 1541022351
    [EarliestTimeSource] =>.
    [EarliestTimeStr] => 2018-10-31 16:45:51
    [LatestTime] => 1541022351
    [LatestTimeSource] => FileModified
    [LatestTimeStr] => 2018-10-31 16:45:51
)
 */
 
 function _media_finish(Doku_Event $event, $param) {
     global   $USERINFO, $INPUT;
     $this->setupLocale();
     
     $meta = new JpegMeta($event->data[0]);   
      $camera = $meta->getCamera(); 
     if(!$camera) return;    
 
     $dt = $this->date_time($event->data[0]);
     $user =$this->_user_name();
     if(!$dt  && !$user)  return;
     if($user) {
        $user = '_' . $user . '.';   
     }
     else $user = '.';  
     if($dt) {
         $dt .= '_';
     }
    $parts = pathinfo($event->data[1]);
    $parts['filename'] = trim($parts['filename'],'_');
    $file_name = $dt . $parts['filename'] . $user . $parts['extension']; 

    $path =  $parts['dirname'] . "/$file_name";
    
    $ow = $INPUT->str('ow');   
    if(!empty($camera) &&  file_exists($path) && $ow == 'false') {
         msg("$file_name exists");            
         $event->preventDefault();
    }
     $event->data[1] = $path;

   }
   
   /*set up user and client (user's real name)  */
  function _user_name()  {
      global   $USERINFO;
      
     $user = ""; 
     $dw_session = array_keys($_SESSION);
     $auth =$_SESSION[$dw_session[0]];          
     
     $use_real = $this->getConf('enable_real');
     $photogroups = $this->getConf('groups');
     
     
     if(!empty($photogroups) ) {
         if(isset($auth)) {         
            $user_grps =  $auth['auth']['info']['grps'];
         }
         else if(isset($USERINFO) ) {
            $user_grps =  $auth['auth']['info']['grps'];
         }
         
         if(!empty($user_grps)) {
            // $this->write_debug($user_grps);     
             foreach($user_grps as $grp) {
             //  $this->write_debug($grp);
               if(strpos($photogroups,$grp) !== false) {
                   return $grp;
               }
             }
         }
      }
     if(isset($auth)) {         
        if($this->getConf('enable_userid')) {
            $user =  $auth['auth']['user'];
        }
        else if($use_real) {
            $user =  $auth['auth']['info']['name'];
            $user=preg_replace('/\s+/', '_',$user);
        }
     }
     if($use_real && empty($user) && isset($USERINFO)) {
            $client_name = $USERINFO['name'];
            if(!empty($client_name))
                $client_name = preg_replace('/\s+/', '_',$client_name);
                if(empty($user)) {
                    $user = $client_name;
                }
           }
       
     if(!empty($user)) {
         if(function_exists ( 'utf8_strtolower' )) {
             $user = utf8_strtolower($user);
         }
         else $user = strtolower($user);
     }
     return $user;
  }
  
 function date_time($upload_path) {
     $date_style = $this->getConf('date_style');
     if($date_style == 'none') return "";
  
     $meta = new JpegMeta($upload_path);
     $dates = $meta->getDates();
     $time_str = $dates['TimeStr'];  
     list($dt,$tm) = preg_split("/\s/",$time_str);
     if($date_style=='date') return $dt;
     if(strpos($date_style,'s')== false) {
        $tm = preg_replace("/:\d+$/","",$tm,1);
     }
     $time_format = $this->getConf('time_format');      
     $tmsep = ($time_format == 'hour.min.sec') ? '.': ($time_format == 'hour-min-sec' ? '-' : "_");     
     $tm = str_replace(':',$tmsep,$tm);
     $dt=$dt . '_' .$tm;     
     return $dt;
 } 
 
function _insert_exif(Doku_Event $event) { 

 //  msg(print_r($this->$toolTipOptions,1),1); 
    if(empty($this->$toolTipOptions)) return;
    $event->data = preg_replace_callback(
         "/title=\"([^\"]+\.(jpg|jpeg|tiff))/i",
        function ($matches) {
           //msg($matches[0]);
             
             list($_pre,$_img) = explode('=',$matches[0]); // $matchs[0] has complete path to image
             $meta = new JpegMeta(mediaFN($_img));    
             foreach($this->$toolTipOptions as $tip) {              
                 msg($tip . ' = ' . $this->getFieldValue($tip,$meta),2);
              }   
             $camera = $meta->getCamera();       
             $camera = trim($camera);
           
             $dates = $meta->getDates();
             $time_str = $dates['TimeStr'];  

			 $camera = preg_replace("/\b(\w+)(?:\s+\\1\b)/i", "$1", $camera);
             $speed =  $meta->_info['exif']['ExposureTime']['val'];//getShutterSpeed();
			 $fstop =  $meta->_info['exif']['FNumber']['val'];
             if($fstop) {
			     $camera .= "&nbsp;&nbsp;F:$fstop @ $speed sec.";             
             }
             $camera .= "&nbsp;&nbsp;&nbsp;tm=$time_str"; 
          //   msg($this->getFieldValue('Date',$meta));
           //  $artist =  $meta->_info['exif']['Artist'];
              $artist = $this->getFieldValue('Artist',$meta);
             
             $_title = $meta->getTitle();
             if(!empty($artist)) {
                 $matches[0] .=  "&nbsp;<br />" . trim($artist);
                 if(!empty($_title))$matches[0] .= ",&nbsp;" . $_title;
             } 
          
             $caption =  $meta->getField(array('IPTC.Caption'));
             if(!empty($caption) && $caption != $_title) {                
                 $matches[0] .= '" data-caption ="' . $this->format_attribute($caption);       
             }
             
             $matches[0] .= '"  data-rel ="' .  $this->format_attribute($camera);        
             $copy = $meta->_info['exif']['Copyright'];                      
             if(!empty($copy)) {
                 $matches[0] .= '" license="' . $this->format_attribute($copy); 
             }
             return $matches [0];
        },
        $event->data
    );
}

function format_attribute($value) {
    $value = trim($value);
    $value = htmlentities($value,ENT_DISALLOWED|ENT_QUOTES);
    $value = preg_replace('/\s+/','&nbsp;',$value);
    return $value;
}    

function getFieldValue($field,$meta) {  
    $ar = $this->fields[$field];    
    foreach($ar AS $el) 
    {     
       // msg($el,1);
       if(is_array($el)) {
         while(!empty($el)) {   
             $inner = array_shift($el);  
             msg($inner,2);             
             $value = $meta->getField($inner);
             if($value) return $value;
         }
       }
       $value =  $meta->getField($el);
       
       if($value) {
         return $value;
      }
     }
  
    return false;
}

function init_fields() {    
    $this->fields = array(
    'Title' => array('Iptc.Headline',
                'img_title',
                'text'),

    'Date' => array('',
                'img_date',
                'date',
                array('Date.EarliestTime')),

    'File' => array('',
                'img_fname',
                'text',
                array('File.Name')),

    'Caption' => array('Iptc.Caption',
                'img_caption',
                'textarea',
                array('Exif.UserComment',
                      'Exif.TIFFImageDescription',
                      'Exif.TIFFUserComment')),

   'Artist' => array('Iptc.Byline',
                'img_artist',
                'text',
                array('Exif.TIFFArtist',
                      'Exif.Artist',
                      'Iptc.Credit')),

    'Copyright' => array('Iptc.CopyrightNotice',
                'img_copyr',
                'text',
                array('Exif.TIFFCopyright',
                      'Exif.Copyright')),
                      
    'FileSize'  => array('',
                'img_fsize',
                'text',
                 array('File.NiceSize')),

    'Width'=> array('',
                'img_width',
                'text',
                array('File.Width')),

    'Height' => array('',
                'img_height',
                'text',
                array('File.Height')),

    'Camera' => array('',
                'img_camera',
                'text',
                array('Simple.Camera')),
 
     );
/*
Exif.ApertureValue
Exif.MaxApertureValue
Exif.ShutterSpeedValue
Exif.XResolution
Exif.YResolution
*/
}    


function write_debug($data) {
   return;
  if (!$handle = fopen(DOKU_INC .'uploadnename.txt', 'a')) {
    return;
    }
  if(is_array($data) || is_object($data)) {
     $data = print_r($data,true);
  }
    // Write $somecontent to our opened file.
    fwrite($handle, "$data\n");
    fclose($handle);

}   

} 
 
 
 
 
 
 
 
 
 
 
 
 
