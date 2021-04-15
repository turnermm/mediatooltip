<?php
$lang['groups'] = 'A comma separated list of groups to be used for creating image ids.  ' .               
                               'It has prioirity over both user names  and  userids.';
$lang['enable_userid'] = 'If set to true and no user group is found it will be used to create the image id.';
$lang['enable_real'] = 'If set to true and no group is selected, and if the userid option is not enabled, then the user\'s real name will be used to create the image name.  ';
$lang['date_style'] = 'This option will set the date-time style for image ids.  Dates are numeric, e.g.  2018-11-09.  A time can be appended to ' .
                                       'the date using  either<code> hour-minutes-seconds</code>, or <code>hour-minutes</code>.  You can use the date by itself by selecting ' .
                                       '<code>date</code> or no date-time at all by selecting  <code>none</code>.';                                         
$lang['time_format'] = 'If a time is appended to the date, then this option sets the time format.  This format will be used for the image id, in the media manager, but when a '.
                                          'page is visited, the time will  be converted for mouse over events to the more familiar <code>hour:minutes:seconds</code>.';
 
$lang['fields'] = 'Select from here the fields which should be included in the tooltips: <ol> <li> <b>Camera:</b> make and type;'
  . '<li> <b>File</b> in which the image is stored on the server <li>the <b>Exposure</b> data, if <code>Camera</code> is '
  . 'selected <li>the <b>Date </b> that the image or photo was created <li>the name of the <b>Artist</b> or potographer who made the '
  . 'image/photo <li> <b>Caption</b>, ' . '<li><b>Copyright</b> info when available ' .
  'if available; <li>the <b>Size</b> of the image in pixels and size of the file on the server.</ol>'; 
$lang['copypos'] = 'Choose where to place the copyright statment: <ol><li>in the <code>tooltip</code></li>'.
'<li><code>on-screen</code> underneath the image</li> <li><code>both</code> in the tooltip and under the image</li>'; 
$lang['captionpos'] = 'This option sets the placement of the caption, either in the tooltip or beneath the image in the ' .
  'traditional position of the caption, i.e. <code>on-screen</code>.  If both copyright and caption are set to <code>on-screen</code> ' .
  ' the caption setting takes precedence and the caption statement will be included in the tooltip.';
                                                             
