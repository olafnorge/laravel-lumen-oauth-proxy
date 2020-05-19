<?php
namespace App\Exceptions;

use Laravel\Lumen\Application;
use Rollbar\Laravel\RollbarLogHandler;
use Rollbar\RollbarLogger;

class RollbarHandler extends RollbarLogHandler {


    /**
     * RollbarHandler constructor.
     *
     * @param RollbarLogger $logger
     * @param Application $app
     * @param string $level
     */
    public function __construct(RollbarLogger $logger, Application $app, $level = 'debug') {
        $this->logger = $logger;
        $this->app = $app;
        $this->level = $this->parseLevel($level ?: 'debug');
    }


    /**
     * We don't have a session so we override this from rollbar
     *
     * @param array $context
     * @return array
     */
    protected function addContext(array $context = []) {
        return $context;
    }
}
