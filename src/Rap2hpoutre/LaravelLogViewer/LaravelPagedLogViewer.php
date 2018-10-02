<?php

namespace Rap2hpoutre\LaravelLogViewer;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class LaravelPagedLogViewer
 * @package Rap2hpoutre\LaravelLogViewer
 */
class LaravelPagedLogViewer implements LaravelLogViewerInterface
{
    /**
     * @var log viewer
     */
    private $logViewer;

    /**
     * @var array
     */
    private $requestData;

    /**
     * LaravelPagedLogViewer constructor.
     */
    public function __construct(LaravelLogViewerInterface $logViewer, $requestData = array())
    {
        $this->logViewer = $logViewer;

        $this->requestData = $requestData;
    }

    /**
     * @return array
     */
    public function all()
    {
        $logs = $this->logViewer->all();

        $paginate = $this->length();
        $page = $this->draw();
        
        $offSet = ($page * $paginate) - $paginate;  
        $itemsForCurrentPage = array_slice($logs, $offSet, $paginate, true);  
        $result = $this->make($itemsForCurrentPage, count($logs), $paginate, $page);
        
        if(method_exists($result, 'items')) {
            $result = $result->items();
        } else {
            $result = $result->getItems();
        }
        
        return array_values($result);
    }

    public function setFile($file)
    {
        $this->logViewer->setFile($file);
    }

    public function setFolder($folder)
    {
        $this->logViewer->setFolder($folder);
    }

    /**
     * @return string
     */
    public function getFolderName()
    {
        return $this->logViewer->getFolderName();
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->logViewer->getFileName();
    }

    /**
     * @return array
     */
    public function getFolders()
    {
        return $this->logViewer->getFolders();
    }

    /**
     * @param bool $basename
     * @return array
     */
    public function getFolderFiles($basename = false)
    {
        return $this->logViewer->getFolderFiles($basename);
    }

    /**
     * @param bool $basename
     * @param string $folder
     * @return array
     */
    public function getFiles($basename = false, $folder = '')
    {
        return $this->logViewer->getFiles($basename, $folder);
    }

    protected function draw()
    {
        return $this->input('draw', 1);
    }

    protected function columns()
    {
        return $this->input('columns', []);
    }

    protected function start()
    {
        return $this->input('start', 1);
    }

    protected function length()
    {
        return $this->input('length', 1);
    }

    protected function input($name, $default = null)
    {
        return isset($this->requestData[$name]) ? $this->requestData[$name] : $default;
    }

    protected function make($items, $total, $perPage, $currentPage = null)
    {
        $result = null;

        try {
            $result = app('paginator')->make($items, $total, $perPage);    
        } catch(\Exception $e) {
            $result = new LengthAwarePaginator($items, $total, $perPage, $currentPage);
        }

        return $result;
    }
}
