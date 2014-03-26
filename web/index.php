<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
    $last_update = filemtime($path);
    return $app['twig']->render('index.twig', array('state'=>$state,'last_update'=>$last_update));
});

$app->run();
