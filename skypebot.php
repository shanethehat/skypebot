<?php

class SkypeBot {
    static public $n = null;

    static function notify($a) {
        list($cmd, $name, $arg, $val) = explode(' ', $a) + array(null, null, null, null);
        if ($cmd === 'USER') {
            switch($arg) {
                case 'BUDDYSTATUS':
                    if ($val == 3) {
                        // user accepted friend request - send welcome
                        SkypeBot::notify(SkypeBot::$n->Invoke("CHAT CREATE $name,$name"));
                    }
                break;
            }
        } else if($cmd === 'CHAT') {
            switch($arg) {
                case 'NAME':
                    SkypeBot::notify(SkypeBot::$n->Invoke("CHATMESSAGE $name Hi there! This is your new group chat channel. For github integration, add the URL http://incubator.inviqa.com:9001/github.php?id=".urlencode($name)." as a commit hook in your github repository."));
                break;
            }
        } else {
            echo "$a\n";
        }
    }
}
