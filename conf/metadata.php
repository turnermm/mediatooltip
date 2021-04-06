<?php
$meta['fields'] = array('multicheckbox','_choices' => array('Camera','Exposure','Date','Artist',
                                                            'Copyright','Caption','ImgSize','FileSize'));
$meta['groups'] = array('string');
$meta['enable_userid'] = array('onoff');
$meta['enable_real'] = array('onoff');
$meta['date_style'] = array('multichoice','_choices'=>array('date_hms','date_hm', 'date','none'));
$meta['time_format'] = array('multichoice','_choices'=>array('hour.min.sec','hour-min-sec','hour_min_sec'));