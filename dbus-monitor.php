<?php
$d = new Dbus( Dbus::BUS_SESSION, true );
$n = $d->createProxy( "com.Skype.API", "/com/Skype", "com.Skype.API");
$n->Invoke( "NAME PHP" );
$n->Invoke( "PROTOCOL 7" );

class testClass {
    static function notify($a) {
        global $n;
        list($cmd, $name, $arg, $val) = explode(' ', $a) + array(null, null, null, null);
        if ($cmd === 'USER') {
            switch($arg) {
                case 'BUDDYSTATUS':
                    if ($val == 3) {
                        // user accepted friend request - send welcome
                        parseCmd($n->Invoke("CHAT CREATE $name,$name"));
                    }
                break;
            }
        } else if($cmd === 'CHAT') {
            switch($arg) {
                case 'NAME':
                    parseCmd($n->Invoke("CHATMESSAGE $name Hi there! This is your new group chat channel. For github integration, add the URL http://incubator.inviqa.com:9001/github.php?id=".urlencode($name)." as a commit hook in your github repository."));
                break;
            }
        } else {
            echo "$a\n";
        }
    }
}

$d->registerObject( '/com/Skype/Client', 'com.Skype.API.Client', 'testClass' );

do {
    $s = $d->waitLoop( 1000 );
}
while ( true );
