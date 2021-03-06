<?php

use Illuminate\Contracts\Debug\ExceptionHandler;
use App\Exceptions\Handler;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost:2221';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    /** 模拟用户登录 */
    protected function signIn($user = null)
    {
        if (! $user) $user = App\User::orderBy('id', 'desc')->where('name', '!=', 'JohnDoe')->where('confirmed', 1)->first();
        if (! $user) $user = create('App\User');
        $this->be($user);
        return $this;
    }

    /**
     * Lesson 8: https://gist.github.com/adamwathan/125847c7e3f16b88fa33a9f8b42333da
     * ExceptionHandler defined in bootstrap/app.php
     */
    protected function disableExceptionHandling()
    {
        $this->oldExceptionHandler = $this->app->make(ExceptionHandler::class);
        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct() {}
            public function report(\Exception $e) {}
            public function render($request, \Exception $e) {
                throw $e;
            }
        });
    }

    protected function withExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, $this->oldExceptionHandler);
        return $this;
    }
    
    protected function requestWithUpload($method, $uri, $headers, $file_paths) {
        $server = $this->transformHeadersToServerVars($headers);

        $files = [];
        foreach ($file_paths as $name => $f) {
            $files[$name] = new \Symfony\Component\HttpFoundation\File\UploadedFile($f, basename($f), mime_content_type($f), filesize($f), UPLOAD_ERR_OK, true);
        }

        $this->call($method, $uri, [], [], $files, $server);

        return $this;
    }
}
