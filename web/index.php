<html>
<head>
    <title>IST DER TISCHKICKER FREI?</title>
    <style>
        h1{
            font-size: 20px;
            margin: 300px auto 10px;
            color:black;
            font-family: Courier,sans-serif;
            width:600px;
        }
        h2{
            font-size: 60px;
            margin: 20px auto 10px;
            color:black;
            font-family: Courier,sans-serif;
            width:500px;
        }
        p{
            font-size: 30px;
            margin: 20px auto 10px;
            color:black;
            font-family: Courier,sans-serif;
            width:800px;
        }
    </style>
</head>
<body>
<h1>Ist der Tischkicker im Startplatz gerade frei?</h1>
        <h2>Weiss nicht</h2>
        <p>Zur Zeit musst Du noch nachschauen gehen...</p>
</body>
</html>
<?php
die();
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
$app['debug'] = true;
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
/*    $path = __DIR__.'/../upload/state';
    if(file_exists($path))
        $state = true;
    else
        $state = false;*/
$state = true;
    return $app['twig']->render('index.twig', array('state'=>$state));
});
