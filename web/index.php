<?php

use Golfpost\GolfServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\PlaintextPasswordEncoder;

require_once __DIR__ . '/../vendor/autoload.php';
$app = new Silex\Application();

/*$app->register(new Silex\Provider\SecurityServiceProvider());
$app['security.firewalls'] = array(
    'test' => array(
        'pattern' => '^/',
        'http' => true,
        'users' => array(
            // raw password is foo
            'admin' => array('ROLE_ADMIN', 'fvyIsSwrRpGbHq7GRleUGShbujQhNmpGaASDjd6vTDl0Xe1Ck+yB/z1kE3816ekvWJLwiXAfYy0kAiVJs01v3g=='),
            'golfpost' => array('ROLE_ADMIN', 'wmshpmVJRIEbXcbEHD7TpdKQvn/o5UbpWhd/BXPk9sXEViucjcf50YzbhV2piOhrLKTHqaKp9oUcNNS2vlew9Q=='),
        ),
    ),
);*/
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));
$app['debug'] = false;
$app->boot();

$app->post('/state', function (Request $request) use ($app) {
    $newstatus = $request->get('status');
    if ($newstatus===1)
        file_put_contents(__DIR__.'/../upload/state',"besetzt");
    elseif ($newstatus===0)
        unlink(__DIR__.'/../upload/state');
    return $app['twig']->render('state.twig', array());
});

$app->get('/', function () use ($app) {
    $path = __DIR__.'/../upload/state';
    if(file_exists($path))
        $state = true;
    else
        $state = false;
    return $app['twig']->render('index.twig', array('state'=>$state));
});
