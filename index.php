<?php
require_once 'vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

$app->register(
    new Silex\Provider\TwigServiceProvider(),
    array(
        'twig.path'       => __DIR__.'/views'
    )
);

$app['skype'] = $app->share(function() {
    return Inviqa\SkypeEngine::getDbusProxy();
});

$app['bot'] = $app->protect(function($dbus) {
    $bot = new Inviqa\SkypeEngine($dbus);
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
