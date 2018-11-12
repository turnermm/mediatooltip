        
    
    jQuery( document ).ready(function() {            
    jQuery("img.media" ).each (function( index ) {  
          var url  = jQuery( this ).parent().attr('href');      
          var camera = jQuery( this ).parent().attr('rel');      
          if(url.match(/\d\d\d\d-\d\d-\d\d/)) {              
              var title = jQuery( this ).parent().attr('title');        
              var tarray  = title.match(/_(\d\d[.\-_]\d\d([.\-_]\d\d)?)_/);
 
              if(!tarray) {                            
                  return;
              }
              var date = tarray[1].replace(/[.\-_]/g,':');
              date = ' <i>(' + date + ') </i>';         
            
             title = title.replace(/_(\d\d[.\-_]\d\d([.\-_]\d\d)?)_/, date);     
             if(camera)  title += '<br>' + camera;             	      
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
    
 	


