<?php

namespace Synd\Blog\Repository;

use Synd\Blog\Parser\Parser;
use Symfony\Component\Finder\Finder;

class PostRepository
{
    protected $dir;
    protected $extension;
    protected $parser;
    
    public function __construct($dir, $extension, Parser $parser)
    {
        $this->dir = $dir;
        $this->extension = $extension;
        $this->parser = $parser;
    }
    
    /**
     * Find a post by date/slug
     * 
     * @param    DateTime
     * @param    string        Post slug
     */
    public function find(\DateTime $date, $slug)
    {
        $filename = sprintf('%s/%s-%s.%s', $this->dir, $date->format('Y-m-d'), $slug, $this->extension);
        if (!file_exists($filename)) {
            return;
        }
        
        return $this->load($filename);
    }
    
    /**
     * Find all posts
     */
    public function findAll()
    {
        $finder = new Finder();
        $finder
            ->files()
            ->in($this->dir)
            ->name('*.' . $this->extension)
            ->depth('== 0')
        ;
        
        $out = array();
        
        foreach ($finder as $filename => $info) {
            $out[] = $this->load($filename);
        }
        
        return $out;
    }
    
    protected function load($filename)
    {
        $parts = explode('-', pathinfo($filename, PATHINFO_FILENAME), 4);
        
        $post = $this->parser->parse($filename);
        $post['date'] = new \DateTime($post['date']);
        $post['slug'] = $parts[3];
        $post['url'] = sprintf('/%s/%s', $post['date']->format('Y/m/d'), $post['slug']);
        
        return $post;
    }
}