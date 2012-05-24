<?php
require_once 'skypebot.php';

$d = new Dbus( Dbus::BUS_SESSION, true );
$n = $d->createProxy( "com.Skype.API", "/com/Skype", "com.Skype.API");
$n->Invoke( "NAME PHP" );
$n->Invoke( "PROTOCOL 7" );

SkypeBot::$n = $n;

$d->registerObject( '/com/Skype/Client', 'com.Skype.API.Client', 'SkypeBot' );

do {
    $s = $d->waitLoop( 1000 );
}
while ( true );
