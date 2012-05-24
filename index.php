<?php
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/skypebot.php';

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

$app->register(
    new Silex\Provider\TwigServiceProvider(),
    array(
        'twig.path'       => __DIR__.'/views',
        'twig.class_path' => __DIR__.'/vendor/twig/lib',
    )
);

$app['skype'] = $app->share(function() {
    $d = new Dbus(Dbus::BUS_SESSION, true);
    $n = $d->createProxy( "com.Skype.API", "/com/Skype", "com.Skype.API");
    $n->Invoke( "NAME PHP" );
    $n->Invoke( "PROTOCOL 7" );
    return $n;
});

$app['bot'] = $app->protect(function($n) {
    $bot = new SkypeBotEngine($n);

    return $bot;
});

$app->get('/', function() use ($app) {
    return $app['twig']->render('index.twig', array());
});

$app->post('/', function(Request $request) use ($app) {
    $username = $request->request->get('username');
    $app['skype']->Invoke( "SET USER $username ISAUTHORIZED TRUE" );
    $app['skype']->Invoke( "SET USER $username ISBLOCKED FALSE" );


    $app['bot']($app['skype'])->parse($app['skype']->Invoke( "SET USER $username BUDDYSTATUS 2"));

    return $app['twig']->render('thanks.twig', array());
});

$app->run();
