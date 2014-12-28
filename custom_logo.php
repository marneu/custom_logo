<?php

/**
 * CustomLogo
 *
 * Plugin to add a customized logo on printout and mail view
 *
 * @version 1.4
 * @author Markus Neubauer @ std-service.com
 * @http://www.std-soft.com/index.php/hm-service/81-c-std-service-code/4-rc-plugin-custom-logo-eigenes-logo-in-der-roundcube-session-setzen
 * v1.2 inspired by Cassiano Aquino (caquino @ team.br.inter.net)
  *     improved error handling
 * v1.3 inspired by Fabien Amann (rholala_logo) adopted "toplogo" from larry skin
  *     MN: changed to jquery and added common_logo as default fallback in case custom_logo fails
 */

class custom_logo extends rcube_plugin
{
    // all task excluding 'login' and 'logout'
    public $task = '?(?!login|logout).*';
    // we've got no ajax handlers
    public $noajax = true;
    // skip frames
    public $noframe = true;
    // replacement logo
    static $custom_logo = false;
    static $common_logo = false;
                        
        function init()
        {
                $this->add_hook('render_page', array($this, 'add_custom_logo'));
        }

        private function get_custom_logo() {
                $rcmail = rcmail::get_instance();
                $this->load_config();

                if ( $rcmail->user-ID && ($this->custom_logo = $rcmail->config->get('custom_logo_url'))
                    && (preg_match('/%d/',$this->custom_logo)) ) {

                        if ( $identity = $rcmail->user->get_identity() ) {
                                list($name,$domain) = explode('@', $identity['email']);
                                $this->custom_logo = str_replace('%d', $domain, $this->custom_logo);
                        }

                }
                if ( $this->custom_logo and (preg_match('/%d/',$this->custom_logo) 
                        || (preg_match('/^http[s]:/',$this->custom_logo) and !get_headers($this->custom_logo))) )
                                $this->custom_logo = false;
                if ( $rcmail->config->get('common_logo_url') ) $this->common_logo = $rcmail->config->get('common_logo_url');
        }

        public function add_custom_logo($arg)
        {
                if ( !isset($this->custom_logo) ) $this->get_custom_logo();
                // if we have no custom_logo make common_logo the default
                if ( empty($this->custom_logo) ) {
                    $this->custom_logo = $this->common_logo;
                    $this->common_logo = false;
                }

                if ( $this->custom_logo ) {
                    $addstr  = '<script type="text/javascript">';
                    $addstr .= "\n".'/* <![CDATA[ */'."\n";
		    $addstr .= '$(document).ready(function() {';
                    $addstr .=   'var logo=\'#toplogo\';';
		    $addstr .=   'if ($(logo).length == 0 ) logo=\'#logo\';';
                    $addstr .=   'if ($(logo).length == 0 ) return;';
		    $addstr .=   '$(logo).hide();';
		    if ( $this->common_logo ) {
                      $addstr .= '$(logo).error(function() {';
        	      $addstr .=   '$(logo).hide();';
    	    	      $addstr .=   '$(logo).attr("src",\''.$this->common_logo.'\');';
    	    	      $addstr .=   '$(logo).show();';
    	    	      $addstr .= '});';
		    }
		    $addstr .=   '$(logo).attr("src",\''.$this->custom_logo.'\');';
		    $addstr .=   '$(logo).show();';
		    $addstr .= '});';
                    $addstr .= "\n".'/* ]]> */'."\n";
                    $addstr .= '</script>'."\n";
                    rcmail::get_instance()->output->add_footer( $addstr );
                }
                                
                return $arg;
        }
}

?>
