<?php

$app = require __DIR__ . '/bootstrap.php';
require __DIR__ . '/config.php';
if (file_exists(__DIR__ . '/config.local.php')) {
    include __DIR__ . '/config.local.php';
}


$app->get('/', function() use ($app) {
    return $app['twig']->render('index.html.twig', array(
        'posts' => $app['post.repository']->findAll() 
	));
});

$app->get('/{date}/{slug}', function($date, $slug) use ($app) {
    if (!$post = $app['post.repository']->find($date, $slug)) {
        $app->abort(404, "That post could not be found.");
    }

    return $app['twig']->render('post.html.twig', array('post' => $post));
});

$app->get('/{slug}', function($slug) use ($app) {
    if (!$page = $app['page.repository']->find($slug)) {
        $app->abort(404, "That page could not be found.");
    }
    
    return $app['twig']->render('page.html.twig', array('page' => $page));
});

$app->get('/archive', function() use ($app) {
    return $app['twig']->render('archive.html.twig', array(
        'posts' => $app['page.repository']->findAll()
    ));
});

return $app;