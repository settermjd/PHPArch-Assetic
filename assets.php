<?php

require_once __DIR__.'/vendor/autoload.php';

use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Asset\GlobAsset;
use Assetic\Filter\CssMinFilter;
use Assetic\Filter\JSMinFilter;
use Assetic\Filter\JSqueezeFilter;
use Assetic\AssetManager;
use Assetic\Factory\AssetFactory;
use Assetic\AssetWriter;
use Assetic\Factory\Worker\CacheBustingWorker;
use Assetic\Asset\AssetReference;
use Assetic\FilterManager;

// Initialise the AssetManager
$am = new AssetManager();
$am->set('jquery', new FileAsset('./static/js/jquery-1.11.1.js'));
$am->set('base_css', new FileAsset('./static/css/bootstrap.css'));

// Create a JavaScript Collection
$js = new AssetCollection(array(
    new GlobAsset('./static/js/*', array(new JSMinFilter())),
    new AssetReference($am, 'jquery'),
    new GlobAsset('./static/js/*'),
));

// Create a CSS Collection
$css = new AssetCollection(array(
    new GlobAsset('./static/css/*'),
    new AssetReference($am, 'base_css'),
    new FileAsset('./static/css/bootstrap.min.css'),
    new FileAsset('./static/css/blog.css'),
), array(new CssMinFilter()));

// Initialise the FilterManager
$fm = new FilterManager();
$fm->set('jsmin', new JSMinFilter());
$fm->set('jscompress', new JSqueezeFilter());
$fm->set('cssmin', new CssMinFilter());

$am->set('javascript', $js);
$am->set('css', $css);

// Initialise the AssetFactory
$factory = new AssetFactory('./static/build');
$factory->setAssetManager($am);
$factory->setFilterManager($fm);
$factory->setDebug(true);
$factory->addWorker(new CacheBustingWorker());
