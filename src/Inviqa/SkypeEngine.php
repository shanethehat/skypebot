<?php
namespace Inviqa;

class SkypeEngine {
    protected $dbus = null;

    public function __construct(\DbusObject $dbus)
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
                    $githubBase = "http://incubator.inviqa.com:9001/github.php";
                    $jenkinsBase = "http://incubator.inviqa.com:9001/jenkins.php";
                    $this->parse($this->dbus->Invoke("CHATMESSAGE $name For github integration, add this URL; $githubBase?id=".urlencode($name)." as a commit hook in your github repository.\nFor Jenkins Notifications use $jenkinsBase?id=".urlencode($name).""));
                    $this->parse($this->dbus->Invoke("ALTER CHATMEMBER $name SETROLETO MASTER"));
                break;
            }
        }
    }

    public static function getDbusProxy()
    {
        $proxy = (new Dbus( Dbus::BUS_SESSION, true ))->createProxy( "com.Skype.API", "/com/Skype", "com.Skype.API");
        $proxy->Invoke( "NAME PHP" );
        $proxy->Invoke( "PROTOCOL 7" );
        return $proxy;
    }
}

