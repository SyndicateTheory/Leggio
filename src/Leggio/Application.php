<?php

namespace Leggio;

use Silex\Application as BaseApplication;
use Symfony\Component\HttpFoundation\Request;

class Application extends BaseApplication
{
    /**
     * Builds the application as a static archive
     * @param    string        Build output directory
     */
    public function build($location)
    {
        $pages = array();
        $router = clone $this['controllers'];
        
        foreach ($router->flush() as $route) {
            if (strpos($route->getPattern(), '{') === false) {
                $pages[] = $route->getPattern();
            }
        }
        
        foreach ($this['post.repository']->findAll() as $post) {
            $pages[] = $post['url'];
        }
        foreach ($this['page.repository']->findAll() as $page) {
            $pages[] = $page['url'];
        }
        
        foreach ($pages as $url) {
            $this->buildPage($url, $location);
        }
    }
    
    /**
     * Writes a specific URL to the build location
     * 
     * @param    string        Page URL
     * @param    string        Output dir location
     */
    protected function buildPage($url, $location)
    {
        $request = Request::create($url);
        $response = $this->handle($request);
        
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