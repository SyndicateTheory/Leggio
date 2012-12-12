<?php

namespace Leggio\Repository;

use Leggio\Parser\Parser;
use Symfony\Component\Finder\Finder;

class PageRepository
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
     * Find a page by slug
     * 
     * @param    string        Page slug
     * @return   array         Page info
     */
    public function find($slug)
    {
        $filename = sprintf('%s/%s.%s', $this->dir, $slug, $this->extension);
        if (!file_exists($filename)) {
            return;
        }
        
        return $this->load($filename);
    }
    
    /**
     * Find all pages
     * 
     * @return    array        Page arrays
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
    
    /**
     * Load a specific page from the filesystem 
     * 
     * @param    string        Filename
     * @return   array         Page info
     */
    protected function load($filename)
    {
        $page = $this->parser->parse($filename);
        $page['slug'] = pathinfo($filename, PATHINFO_FILENAME);
        $page['url'] = sprintf('/%s', $page['slug']);
        
        return $page;
    }
}