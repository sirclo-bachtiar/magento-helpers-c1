<?php

namespace Bachtiar\Helper\LaminasLogger\Test;

use Bachtiar\Helper\LaminasLogger\Service\LogService;

class LogTest
{
    public function __invoke()
    {
        return LogService::channel('default')->mode('default')->title('log_title')->message('message_to_log');
    }
}
