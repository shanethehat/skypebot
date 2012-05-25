<?php
require_once 'skypebot.php';

class SkypeBot {
    static function notify($a) {
        global $n;
        $engine = new SkypeBotEngine($n);
	try
	{
		$engine->parse($a);
	} catch (Exception $e) {
		echo $e->getMessage().PHP_EOL;
	}
    }
}

$d = new Dbus( Dbus::BUS_SESSION, true );
$n = $d->createProxy( "com.Skype.API", "/com/Skype", "com.Skype.API");
$n->Invoke( "NAME PHP" );
$n->Invoke( "PROTOCOL 7" );

$d->registerObject( '/com/Skype/Client', 'com.Skype.API.Client', 'SkypeBot' );

	do {
	    $s = $d->waitLoop( 1000 );
	}
	while ( true );
