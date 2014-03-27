<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();
$app['base_dir'] = __DIR__ . '/../upload/';

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => $app['base_dir']. 'kicker.log',
));
$app['debug'] = false;

$app->boot();
$app->post('/update', function (Request $request) use ($app) {
    $newstatus = (float)$request->getContent();
    $app['monolog']->addInfo( $newstatus );
    $status_path = $app['base_dir'].'state';
    $request_time = time();
    $last_update = filemtime($status_path);
    file_put_contents($app['base_dir'].'last_request',$request_time);
    if($request_time-$last_update > 30)
    {
        if ($newstatus>=0.5)
            file_put_contents($status_path,"besetzt");
        elseif ($newstatus<0.5)
            unlink($status_path);
    }
    return $app['twig']->render('state.twig', array());
});

$app->get('/', function () use ($app) {
    $path = $app['base_dir'].'state';
    if(file_exists($path))
        $state = false;
    else
        $state = true;
    $last_update = file_get_contents($app['base_dir'].'last_request');
    return $app['twig']->render('index.twig', array('state'=>$state,'last_update'=>$last_update));
});

$app->run();
