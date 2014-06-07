/**
 * CustomLogo
 *
 * Plugin to add a customized logo
 *
 * @version 1.2
 * @author Markus Neubauer @ std-service.com
 * @http://www.std-soft.com/bfaq/52-cat-webmail/106-eigenes-logo-in-roundcube-einblenden.html
 * v1.2 inspired by Cassiano Aquino  caquino @ team.br.inter.net 
  *     improved error handling
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
    public $custom_logo;
                        
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
                                $this->custom_logo = "";
        }

        public function add_custom_logo($arg)
        {
                if ( !isset($this->custom_logo) ) $this->get_custom_logo();

                if ( $this->custom_logo ) {

                        $src = '<script type="text/javascript">';
                        $src .=   'document.getElementById(\'logo\').style.display="none";';
                        $src .=   'var logohid = new Image();';
                        $src .=   'logohid.onload = function() {';
                        $src .=         'document.getElementById(\'logo\').src="'.$this->custom_logo.'";';
                        $src .=         'document.getElementById(\'logo\').style.display="block";';
                        $src .=   '};'; 
                        $src .=   'logohid.onerror = function() {';
                        $src .=         'document.getElementById(\'logo\').style.display="block";';
                        $src .=   '};';
                        $src .=   'logohid.src="'.$this->custom_logo.'";';
                        $src .=   'delete logohid;';
                        $src .= '</script>';
                        rcmail::get_instance()->output->add_footer( $src );

                }
                                
                return $arg;
        }
}

?>
