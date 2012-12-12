<?php

$loader = require_once __DIR__.'/../vendor/autoload.php';
$loader->add('Synd', __DIR__ . '/../src');

$app = new Synd\Blog\Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ .'/views'
));

$app['markdown.parser'] = $app->share(function() {
     return new \dflydev\markdown\MarkdownParser();
});
     
$app['twig']->addExtension(new Aptoma\Twig\Extension\MarkdownExtension($app['markdown.parser']));


$app['blog.parser'] = $app->share(function() use ($app) {
    return new Synd\Blog\Parser\Parser();
});

$app['post.repository'] = $app->share(function() use ($app) {
   return new Synd\Blog\Repository\PostRepository(
       $app['blog.post_dir'],
       $app['blog.post_extension'],
       $app['blog.parser']
   );
});

$app['page.repository'] = $app->share(function() use ($app) {
    return new Synd\Blog\Repository\PageRepository(
        $app['blog.page_dir'],
        $app['blog.page_extension'],
        $app['blog.parser']
    );
});

$app['controllers']
    ->convert('date', function($date) {
        return new DateTime($date);
    })
    ->assert('date', '(\d{4})/(\d{2})/(\d{2})')
    ->assert('slug', '([-a-z0-9]+)')
;

return $app;