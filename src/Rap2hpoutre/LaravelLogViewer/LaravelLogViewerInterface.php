<?php

namespace Rap2hpoutre\LaravelLogViewer;

/**
 * @package Rap2hpoutre\LaravelLogViewer
 */
interface LaravelLogViewerInterface
{
    /**
     * @return array
     */
    function all();
    
    /**
     * void
     */
    function setFile($file);
    
    /**
     * void
     */
    function setFolder($folder);

    /**
     * @return string
     */
    function getFolderName();

    /**
     * @return string
     */
    function getFileName();

    /**
     * @return array
     */
    function getFolders();

    /**
     * @param bool $basename
     * @return array
     */
    function getFolderFiles($basename = false);

    /**
     * @param bool $basename
     * @param string $folder
     * @return array
     */
    function getFiles($basename = false, $folder = '');
}
