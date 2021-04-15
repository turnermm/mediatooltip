        
    
    jQuery( document ).ready(function() {            

    jQuery("img.media,img.mediacenter,img.mediaright" ).each (function( index ) {   
          var date, output="",title = "";    
          var url  = jQuery( this ).parent().attr('href');      
          var camera = jQuery( this ).parent().attr('data-rel');      
          var copy = jQuery( this ).parent().attr('license');           
          var caption = jQuery( this ).parent().attr('data-caption');            
          var imgsize =  jQuery( this ).parent().attr('data-size');    
          var fileSize = jQuery( this ).parent().attr('data-fsize');         
        var width = jQuery( this ).attr('width');        
        var copypos, captionpos;
        if(caption) {       
            var ar = place_caption(caption.split('::'), this);
            caption = ""; ;
            captionpos = ar[0];       
        }      
        if(copy) {
          if(captionpos == 'on-screen') {
              copypos = 'tooltip';  
              copy = copy.replace(/^.+::/,"");
          } 
          else {
              var ar = place_caption(copy.split('::'), this);
            copy = ar[1];
            copypos = ar[0]; 
            }
         
        
        }

          var title = jQuery( this ).parent().attr('title');  
          if(camera) {              
              var tarray  = title.match(/_(\d\d[.\-_]\d\d([.\-_]\d\d)?)_/);
              var ar = camera.split(/tm=/);            
              camera = camera.replace(/tm=.*?$/,"");

              if(tarray) {                            
                  date  = tarray[1].replace(/[.\-_]\(\)/g,':');                 
                  date = ' <i> (' + date + ') </i>';         
                  title = title.replace(/_(\d\d[.\-_]\d\d([.\-_]\d\d)?)_/, date);     
              }
              else {
                        date = ar[1] ;
                        if(date) {
                            ar = date.split(/\s/);
                            date = ar[0] + ' (<i>' + ar[1] + '</i>)';                                                 
                            title += "<br />" + date;
                        }
              }
          }
          
          if(imgsize && fileSize) {
              title += "<br />" + LANG.plugins.mediatooltip.img + imgsize + " " + LANG.plugins.mediatooltip.file + fileSize;       
          }
              if(camera && camera != 'noopener')  {
                  var patt = new RegExp("^&nbsp;&nbsp;");                  
                  if(patt.test(camera)) {  // no camera type found
                      title += camera; 
                  }
                  else title += '<br />' + camera; 
              }  
           
        if(copy && !copypos.match(/tooltip|both/)) {             
            copy = "";   
        }        
        if(caption && !captionpos.match(/tooltip/)) {             
            caption = "";   
        }         
         output =  caption ? (title + '<br />' + caption): title;
         output =  copy ? (output + '<br />' + copy ): output;
             
              jQuery( this ).tooltip({
                     content: output 
             });      
            
      
   });
    
    jQuery("a.mediafile" ).each (function( index ) {        
       var title  = jQuery( this ).attr('title');      
             
      if(title.match(/google/)) return;   
                       jQuery( this ).tooltip({
                         content: title
        });        
    }); 
});
    
function place_caption(ar, obj) {
    var width = jQuery( obj ).attr('width');
    text = ar[1];
    pos = ar[0]; 

    if(pos.match(/on-screen|both/)) {  
        jQuery( obj ).parent().append('<p class="mtip_copy">' + text + '</p>');
            if(width) jQuery("p.mtip_copy").css("max-width", parseInt(width) +8);
       } 
 	
    return ar;       
           
} 	


