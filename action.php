<?php
/**
 * DokuWiki Plugin anonip (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Andreas Gohr <andi@splitbrain.org>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

require_once DOKU_PLUGIN.'action.php';

class action_plugin_anonip extends DokuWiki_Action_Plugin {

    public function register(Doku_Event_Handler $controller) {
         $controller->register_hook('INIT_LANG_LOAD', 'BEFORE', $this, 'handle_dokuwiki_started');
    }

    public function handle_dokuwiki_started(Doku_Event &$event, $param) {
        // try to use the session ID as identifier
        $ses = session_id();
        if (!$ses) {
            // no session running, randomize
            $ses = mt_rand();
        }
        $uid = md5($ses);

        // build pseudo IPv6 (local)
        $ip = 'fe80:'.substr($uid,0,4).
                  ':'.substr($uid,4,4).
                  ':'.substr($uid,8,4).
                  ':'.substr($uid,12,4).
                  ':'.substr($uid,16,4).
                  ':'.substr($uid,20,4).
                  ':'.substr($uid,24,4);

        // reset server variables
        $_SERVER['REMOTE_ADDR'] = $ip;
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) unset($_SERVER['HTTP_X_FORWARDED_FOR']);
        if(isset($_SERVER['HTTP_X_REAL_IP'])) unset($_SERVER['HTTP_X_REAL_IP']);
    }
}

// vim:ts=4:sw=4:et:
