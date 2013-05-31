<?php
namespace Inviqa;

class SkypeEngine {
    protected $dbus = null;

    protected $commands = array();

    public function __construct(\DbusObject $dbus)
    {
        $this->dbus = $dbus;
    }

    public function parse($a)
    {
        list($cmd, $name, $arg, $val) = explode(' ', $a) + array(null, null, null, null);
        switch ($cmd) {
            case 'USER':
                switch($arg) {
                    case 'BUDDYSTATUS':
                        if ($val == 3) {
                            // user accepted friend request - send welcome
                            $this->parse($this->dbus->Invoke("CHAT CREATE $name,$name"));
                            $this->parse($this->dbus->Invoke("CHATMEMBER $name SETROLETO MASTER"));
                        }
                    break;
                }
                break;
            case 'CHAT':
                switch($arg) {
                    case 'NAME':
                        $this->commands[':info']($this, array('val' => $name), null, null);
                        break;
                }
                break;
            case 'CHATMESSAGE':
                if ($arg == 'STATUS' && $val == 'RECEIVED') {
                    $chatname = $this->getInfo($this->dbus->Invoke("GET CHATMESSAGE $name CHATNAME"));
                    $handle = $this->getInfo($this->dbus->Invoke("GET CHATMESSAGE $name FROM_HANDLE"));
                    $body = $this->getInfo($this->dbus->Invoke("GET CHATMESSAGE $name BODY"));
                    printf(
                        "%s %s: %s\n",
                        $chatname['val'],
                        $handle['val'],
                        $body['val']
                    );


                    $msg = array_map('strtolower', explode(' ', $body['val']));
                    if (array_key_exists($msg[0], $this->commands)) {
                        $this->commands[$msg[0]]($this, $chatname, $handle, $body);
                    }

                    //special case for Andrew "dog boy" Baker
                    if ($handle['val'] == "abaker.inviqa" && $chatname['val'] == '#ben.longden/$rowan.merewood;1d3ab49e7f5995e1') {
                        if (stristr(preg_replace('#[\W]#', '', $body['val']), 'dog')) {
                            $this->dbus->Invoke("CHATMESSAGE {$chatname['val']} Potential dog story detected. :-O");
                            $this->dbus->Invoke("ALTER CHAT {$chatname['val']} KICK {$handle['val']}");
                        }
                    }
                }
                $this->dbus->Invoke("SET CHATMESSAGE $name SEEN");
                break;
        }
    }

    public function add($cmd, callable $action)
    {
        $this->commands[strtolower($cmd)] = $action;
        return $this;
    }


    public function cmd($str)
    {
        return $this->dbus->Invoke($str);
    }

    protected function getInfo($str)
    {
        $split = explode(' ', $str);
        list($cmd, $name, $arg) = $split;
        $val = implode(' ', array_slice($split, 3));

        return array(
            'cmd' => $cmd,
            'name' => $name,
            'arg' => $arg,
            'val' => $val
        );
    }

    protected function showChatInfo($name)
    {
        $githubBase = "http://incubator.inviqa.com:9001/github.php";
        $jenkinsBase = "http://incubator.inviqa.com:9001/jenkins.php";
        $this->dbus->Invoke("CHATMESSAGE $name For github integration, add this URL; $githubBase?id=".urlencode($name)." as a commit hook in your github repository.\n\nFor Jenkins Notifications use $jenkinsBase?id=".urlencode($name)."");
    }

    public static function getDbusProxy()
    {
        $proxy = (new \Dbus(\Dbus::BUS_SESSION, true))->createProxy( "com.Skype.API", "/com/Skype", "com.Skype.API");
        $proxy->Invoke( "NAME PHP" );
        $proxy->Invoke( "PROTOCOL 7" );
        return $proxy;
    }
}

