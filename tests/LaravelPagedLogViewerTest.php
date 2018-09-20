<?php

namespace Rap2hpoutre\LaravelLogViewer;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

/**
 * Class LaravelPagedLogViewerTest
 * @package Rap2hpoutre\LaravelLogViewer
 */
class LaravelPagedLogViewerTest extends OrchestraTestCase
{

    public function setUp()
    {
        parent::setUp();
        // Copy "laravel.log" file to the orchestra package.
        if (!file_exists(storage_path() . '/logs/laravel.log')) {
            copy(__DIR__ . '/laravel.log', storage_path() . '/logs/laravel.log');
        }
    }

    public function testAll()
    {
        $laravel_log_viewer = new LaravelPagedLogViewer(new LaravelLogViewer());
        $data = $laravel_log_viewer->all();
        $this->assertEquals('local', $data[0]['context']);
        $this->assertEquals('error', $data[0]['level']);
        $this->assertEquals('danger', $data[0]['level_class']);
        $this->assertEquals('exclamation-triangle', $data[0]['level_img']);
        $this->assertEquals('2018-09-05 20:20:51', $data[0]['date']);
    }

    public function testPagedItemDefaults()
    {
        $laravel_log_viewer = new LaravelPagedLogViewer(new LaravelLogViewierFaker(new LaravelLogViewer()));
        $data = $laravel_log_viewer->all();
        $this->assertEquals('local', $data[0]['context']);
        $this->assertEquals('error', $data[0]['level']);
        $this->assertEquals('danger', $data[0]['level_class']);
        $this->assertEquals('exclamation-triangle', $data[0]['level_img']);
        $this->assertEquals('2018-09-05 20:20:51', $data[0]['date']);
    }

    public function testShouldOnlySeePagedItems()
    {
        $laravel_log_viewer = new LaravelPagedLogViewer(new LaravelLogViewierFaker(new LaravelLogViewer()), ['length' => 10]);
        $data = $laravel_log_viewer->all();
        $this->assertEquals(10, count($data));

        $laravel_log_viewer = new LaravelPagedLogViewer(new LaravelLogViewierFaker(new LaravelLogViewer()), ['length' => 25, 'draw' => 2]);
        $data = $laravel_log_viewer->all();
        $this->assertEquals(25, count($data));
        $this->assertEquals(26, $data[0]["item"]);

        $laravel_log_viewer = new LaravelPagedLogViewer(new LaravelLogViewierFaker(new LaravelLogViewer()), ['length' => 25, 'draw' => 3]);
        $data = $laravel_log_viewer->all();
        $this->assertEquals(25, count($data));
        $this->assertEquals(51, $data[0]["item"]);
    }
}

class LaravelLogViewierFaker implements LaravelLogViewerInterface
{
    private $logViewer;

    public function __construct(LaravelLogViewerInterface $logViewer)
    {
        $this->logViewer = $logViewer;
    }

    public function all()
    {
        $logs = $this->logViewer->all();
        $logs[0]['item'] = 1;

        for($i = 0; $i < 100; $i++) {
            $all = $this->logViewer->all();
            $all[0]['item'] = $i + 2;
            $logs = array_merge($logs, $all);
        }
        
        return $logs;
    }
}
