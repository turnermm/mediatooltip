<?php

/**
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
   *
   * class       plugin_ckgedit_specials
   * @author     Myron Turner <turnermm02@shaw.ca>
*/
        
// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');


class syntax_plugin_mediatooltip extends DokuWiki_Syntax_Plugin {


    /**
     * What kind of syntax are we?
     */
    function getType(){
        return 'substition';
    }
   
    /**
     * What about paragraphs?
     */

    function getPType(){      
       //return 'block';
    }

    /**
     * Where to sort in?
     */ 
    function getSort(){
        return 155;
    }


    /**
     * Connect pattern to lexer
     */
     public function connectTo($mode) {
		 $this->Lexer->addEntryPattern(
		 '<@anno:\d\d?>(?=.*?</@anno>)',$mode,
		 'plugin_mediatooltip');
		 
		 $this->Lexer->addSpecialPattern(
		 '<anno:\d\d?>.*?</anno>',$mode,'plugin_mediatooltip');	
      
	 }
     

 public function postConnect() { 
       $this->Lexer->addExitPattern('</@anno>','plugin_mediatooltip'); 
 }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler $handler){

        switch($state) {    
          case DOKU_LEXER_ENTER :
		   $match = str_replace(':','_',substr($match,2,-1));	
			   return array($state, $match);
          case DOKU_LEXER_UNMATCHED :  
            //  msg(htmlentities($match));		  
              return array($state, $match);
          case DOKU_LEXER_EXIT :   return array($state, '');                
          case DOKU_LEXER_SPECIAL: 			  
		      $inner = substr($match,6,-7);
			 // msg($inner);	
			  return array($state, $inner); 
       }
         return array($state, "" );
       
    }

   /**
     * Create output
     */
    function render($mode, Doku_Renderer $renderer, $data) {
		//return false;
        if($mode == 'xhtml'){
            list($state, $xhtml) = $data;
            switch ($state) {
                case DOKU_LEXER_ENTER : 
				    $tip = '<span class="annotation ui-widget-content '. $xhtml . '">';
				    $renderer->doc .= $tip;
				break;                                                        
                case DOKU_LEXER_UNMATCHED : 
				 // msg(htmlentities($xhtml));
                $renderer->doc .= '<span id="anno_close"><span class="anno_exit">close</span> </span>';
       		    $xhtml = trim($xhtml);
                 if(preg_match('/^\{\{([\w\:]+)\}\}$/',$xhtml,$matches)) {					
				  	   $html = p_wiki_xhtml($matches[1]);                        
                        
                        $html = preg_replace('/<\/?p>/ms',"",$html);
                        $html = preg_replace_callback('/<(\/)?h(\d).*?>/ms',
						function($matches){
							if($matches[1] == '/'){
								$style = '</b>';	
                                if($matches[2] <= 3){
									$style .= '<br />';
								}						
							}
							else {
								if($matches[2] <= 3){
								  $style = '<br /><b class="extra_bold">';
								}
								else $style = '<br /><b>';
							}
							return $style;
						}, $html);
						
                        $html = preg_replace('/<\/?div.*?>/ms',"",$html);
						$html = preg_replace('/<!--.*?-->/ms',"",$html);						  
                        //msg(htmlentities($html));    
			        }
			    else {
				       //$html = html_secedit(p_render('xhtml',p_get_instructions($xhtml),$info),$secedit);				 
                      $html =  htmlentities($xhtml); 
				} 
                $renderer->doc .= $html;  break;
                case DOKU_LEXER_EXIT : 
				    $renderer->doc .= "</span>"; break; 
				case DOKU_LEXER_SPECIAL:
				  //  msg(htmlentities($xhtml));
					list($which,$text)= explode('>',$xhtml); 
                    $title = 'anno_' .$which;
				    $renderer->doc .= '<span class="anno" title="' .$title.'">' .htmlentities($text).'</span>';				     break;	
				
        }
           return true;
        }
        return false;
    }
    
}


