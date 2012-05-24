<?php

class SkypeBotEngine {
    protected $dbus = null;

    public function __construct(DbusObject $dbus)
    {
        $this->dbus = $dbus;
    }

    public function parse($a)
    {
        list($cmd, $name, $arg, $val) = explode(' ', $a) + array(null, null, null, null);
        if ($cmd === 'USER') {
            switch($arg) {
                case 'BUDDYSTATUS':
                    if ($val == 3) {
                        // user accepted friend request - send welcome
                        $this->parse($this->dbus->Invoke("CHAT CREATE $name,$name"));
                    }
                break;
            }
        } else if($cmd === 'CHAT') {
            switch($arg) {
                case 'NAME':
                    $this->parse($this->dbus->Invoke("CHATMESSAGE $name Hi there! This is your new group chat channel. Unfortunately I can't complete this whole process for you (though I would like to). For github integration, just add this URL; http://incubator.inviqa.com:9001/github.php?id=".urlencode($name)." as a commit hook in your github repository, and I will post all the commits that are pushed to it right here in this channel."));
                break;
            }
        } else {
            throw new Exception($a);
        }
    }
}

