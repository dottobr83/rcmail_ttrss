<?php
/**
 * Kolab IFRAME Plugin
 *
 * @author Aleksander 'A.L.E.C' Machniak <machniak@kolabsys.com>
 * @licence GNU AGPL
 *
 * Configuration (see config.inc.php)
 * 
 * Modified OwnCloud Plugin
 *
 * For description visit:
 * http://blog.sleeplessbeastie.eu/2013/06/24/kolab-how-to-integrate-piwik/
 */
class rcmail_ttrss extends rcube_plugin
{
    // all task excluding 'login' and 'logout'
    public $task = '?(?!login).*';
    // we've got no ajax handlers
    public $noajax = true;
    // skip frames
    public $noframe = true;
    function init()
    {
        $rcmail = rcube::get_instance();

        $this->add_texts('localization/', false);
		
        // register task
        $this->register_task('rcmail_ttrss');
		
        // register actions
        $this->register_action('index', array($this, 'action'));
        $this->register_action('redirect', array($this, 'redirect'));
		$this->add_hook('session_destroy', array($this, 'logout'));
		
        // add taskbar button
        $this->add_button(array(
            'command'    => 'rcmail_ttrss',
            'class'      => 'button-rcmail_ttrss',
            'classsel'   => 'button-rcmail_ttrss button-selected',
            'innerclass' => 'button-inner',
            'label'      => 'rcmail_ttrss.rcmail_ttrss',
            ), 'taskbar');
			
        // add style for taskbar button (must be here) and Help UI
        $this->include_stylesheet($this->local_skin_path()."/rcmail_ttrss.css");
    }
    function action()
    {
        $rcmail = rcube::get_instance();
        $rcmail->output->add_handlers(array('rcmail_ttrssframe' => array($this, 'frame')));
        $rcmail->output->set_pagetitle($this->gettext('rcmail_ttrss'));
        $rcmail->output->send('rcmail_ttrss.rcmail_ttrss');
    }
	
    function frame()
    {
		
		$rcmail = rcube::get_instance();
        $this->load_config();
		
		$plain_pass = $rcmail->decrypt($_SESSION['password']);
		
        $src  = $this->urlbase . $rcmail->config->get('ttrss_location') . 'index.php' . '?user=' . $_SESSION['username'] . '&pass=' . $plain_pass];
        return '<iframe id="rcmail_ttrssframe" width="100%" height="100%" frameborder="0"'
            .' src="' . $src. '"></iframe>';
    }
	function logout()
    {
        $rcmail = rcube::get_instance();
        $this->load_config();
		
        // send logout request to ttrss
        $logout_url = $this->urlbase . $rcmail->config->get('ttrss_location') . '/backend.php?op=logout';
        $rcmail->output->add_script("new Image().src = '$logout_url';", 'foot');
    }
	
}