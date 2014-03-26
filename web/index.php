<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . '/../vendor/autoload.php';
$app = new Silex\Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));
$app['debug'] = false;
$app->boot();
$app->post('/update', function (Request $request) use ($app) {
    $newstatus = (float)$request->getContent();
    if ($newstatus>=0.5)
        file_put_contents(__DIR__.'/../upload/state',"besetzt");
    elseif ($newstatus<0.5)
        unlink(__DIR__.'/../upload/state');
    return $app['twig']->render('state.twig', array());
});

$app->get('/', function () use ($app) {
    $path = __DIR__.'/../upload/state';
    if(file_exists($path))
        $state = false;
    else
        $state = true;
    return $app['twig']->render('index.twig', array('state'=>$state));
});

$app->run();
