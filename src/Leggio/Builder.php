<?php

namespace Leggio;

use Symfony\Component\HttpFoundation\Request;

class Builder
{
    public function build(Application $app, $location)
    {
        $pages = array();
        $router = clone $app['controllers'];
        
        foreach ($router->flush() as $route) {
            if (strpos($route->getPattern(), '{') === false) {
                $pages[] = $route->getPattern();
            }
        }
        
        foreach ($app['post.repository']->findAll() as $post) {
            $pages[] = $post['url'];
        }
        foreach ($app['page.repository']->findAll() as $page) {
            $pages[] = $page['url'];
        }
        
        foreach ($pages as $url) {
            $this->buildPage($url, $location, $app);
        }
    }
    
    /**
     * Writes a specific URL to the build location
     * 
     * @param    string        Page URL
     * @param    string        Output dir location
     */
    protected function buildPage($url, $location, Application $app)
    {
        $request = Request::create($url);
        $response = $app->handle($request);
        
        if (substr($url, -1) == '/') {
            $url .= 'index.html';
        }
        
        $outputFile = $location . $url;
        if (!is_dir($dir = dirname($outputFile))) {
            mkdir($dir, 0777, true);
        }
        
        file_put_contents($location . $url, $response->getContent());
    }
}