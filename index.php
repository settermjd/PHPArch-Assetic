<?php

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/assets.php';

/**
 * Initialise a Silex app with debug mode enabled
 */
$app = new Silex\Application(array(
    'debug' => true
));

/**
 * Add Twig support for rendering views
 */
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

/**
 * The default (and only) application route
 */
$app->get('/', function() use($app, $factory) {
    $js = $factory->createAsset(array('@javascript'), array('jsmin', 'jscompress'));
    $css = $factory->createAsset(array('@css'), array('cssmin'));
    return $app['twig']->render('default-route.twig', array(
        'js' => $js,
        'css' => $css
    ));
});

$app->run();