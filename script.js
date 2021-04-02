        
    
    jQuery( document ).ready(function() {            
    jQuery("img.media,img.mediacenter,img.mediaright" ).each (function( index ) {  
          var url  = jQuery( this ).parent().attr('href');      
          var camera = jQuery( this ).parent().attr('data-rel');      
          var copy = jQuery( this ).parent().attr('license');           
          var caption = jQuery( this ).parent().attr('data-caption');            
          var date, output;
        
          if(camera) {              
              var title = jQuery( this ).parent().attr('title');        
              var tarray  = title.match(/_(\d\d[.\-_]\d\d([.\-_]\d\d)?)_/);
              var ar = camera.split(/tm=/);            
             camera = camera.replace(/tm=.*?$/,"");

              if(tarray) {                            
                  date  = tarray[1].replace(/[.\-_]\(\)/g,':');                 
                  date = ' <i>(' + date + ') </i>';         
                  title = title.replace(/_(\d\d[.\-_]\d\d([.\-_]\d\d)?)_/, date);     
              }
              else {
                        date = ar[1] ;
                        if(date) {
                            ar = date.split(/\s/);
                            date = ar[0] + '(<i>' + ar[1] + '</i>)';                                                 
                            title += "<br />" + date;
                        }
              }
              if(camera != 'noopener')  
                  title += '<br />' + camera;    
              output =  caption ? title + '<br />' + caption: title;
              output =  copy ? output + '<br />' + copy : output; 
                                
              //jQuery( this ).attr('title', title);    
          
              jQuery( this ).tooltip({
                      
                     content: output 
             });      
          }       
          else jQuery( this ).tooltip();
   });
    
    jQuery("a.mediafile" ).each (function( index ) {        
       var title  = jQuery( this ).attr('title');      
      if(title.match(/google/)) return;   
                       jQuery( this ).tooltip({
                         content: title
        });        
    }); 
});
    
 	


