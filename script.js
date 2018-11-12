        
    
    jQuery( document ).ready(function() {            
    jQuery("img.media" ).each (function( index ) {  
          var url  = jQuery( this ).parent().attr('href');      
          var camera = jQuery( this ).parent().attr('rel');      
          var date;
                
          if(camera) {              
              var title = jQuery( this ).parent().attr('title');        
              var tarray  = title.match(/_(\d\d[.\-_]\d\d([.\-_]\d\d)?)_/);
              var ar = camera.split(/tm=/);            
             camera = camera.replace(/tm=.*?$/,"");

              if(tarray) {                            
                  date  = tarray[1].replace(/[.\-_]/g,':');
                  date = ' <i>(' + date + ') </i>';         
                  title = title.replace(/_(\d\d[.\-_]\d\d([.\-_]\d\d)?)_/, date);     
              }
              else {
                        date = ar[1] ;
                        ar = date.split(/\s/);
                        date = ar[0] + '&nbsp;<i>(' + ar[1] + ') </i>';         
                        title += "&nbsp;&nbsp;&nbsp;&nbsp;" + date;
              }
            
            
             
              title += '<br>' + camera;             	      
              jQuery( this ).attr('title', title);     
          
              jQuery( this ).tooltip({
                     content: title
             });      
           
         //      jQuery(this).tooltip( "option", "position", { my: "left+15 center", at: "right center" } );
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
    
 	


