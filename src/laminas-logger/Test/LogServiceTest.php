<?php

namespace Bachtiar\Helper\LaminasLogger\Test;

use Bachtiar\Helper\LaminasLogger\Service\LogService;

class LogServiceTest
{
    public function __invoke()
    {
        return LogService::channel('default')
            ->mode('default')
            ->title('log_title')
            ->log('message_to_log');
    }
}
