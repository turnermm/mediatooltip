        
    
    jQuery( document ).ready(function() {            
    jQuery("img.media,img.mediacenter,img.mediaright" ).each (function( index ) {   
          var date, output="",title = "";    
          var url  = jQuery( this ).parent().attr('href');      
          var camera = jQuery( this ).parent().attr('data-rel');      
          var copy = jQuery( this ).parent().attr('license');           
          var caption = jQuery( this ).parent().attr('data-caption');            
          var imgsize =  jQuery( this ).parent().attr('data-size');    
          var fileSize = jQuery( this ).parent().attr('data-fsize');         
        var copypos;
        if(copy) {
            var ar = copy.split('::');
            copy = ar[1];
            copypos = ar[0]; 
            if(copypos.match(/on-screen|both/)) {             
              jQuery( this ).parent().append('<p class="mtip_copy">' + copy + '</p>');
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
          title += "<br />Image: " +imgsize + " File: "  + fileSize;  

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
    
 	


