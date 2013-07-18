<?php

namespace Inviqa;

use Inviqa\Command\CommandHandlerInterface;

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
        foreach ($this->commands as $commandHandler) {
            if($commandHandler->handleCommand($cmd, $name, $arg, $val)) {
                break;
            }
        }
    }
    
    public function addCommandHandler(CommandHandlerInterface $commandHandler)
    {
        $commandHandler->setEngine($this);
        $this->commands[] = $commandHandler;
    }

    public function invoke($str)
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

