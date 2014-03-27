<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();
$base_dir = __DIR__ . '/../upload/';
$app['controllers']->value('base_dir', $base_dir);

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__ . '/../upload/'. 'kicker.log',
));
$app['debug'] = false;

$app->boot();
$app->post('/update4711', function (Request $request,$base_dir) use ($app) {
    $newstatus = (float)$request->getContent();
    $app['monolog']->addInfo( $newstatus );
    $status_path = $base_dir.'state';
    $request_time = time();
    file_put_contents($base_dir.'last_request',$request_time);
    if ($newstatus>=0.5)
        file_put_contents($status_path,1);
    elseif ($newstatus<0.5)
        file_put_contents($status_path,0);
    return $app['twig']->render('state.twig', array());
});

$app->get('/', function ($base_dir) use ($app) {
    $state = (boolean)file_get_contents($base_dir.'state');
    var_dump($state);
    $last_update = file_get_contents($base_dir.'last_request');
    return $app['twig']->render('index.twig', array('state'=>$state,'last_update'=>$last_update));
});

$app->run();
