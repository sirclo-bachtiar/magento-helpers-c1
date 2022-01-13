<?php

namespace Bachtiar\Helper\LaminasLogger\Test;

use Bachtiar\Helper\LaminasLogger\Service\SwiftLog;

class SwiftLogTest
{
    public function __invoke()
    {
        return SwiftLog::channel('default')
            ->mode('default')
            ->class('__CLASS__')
            ->classLimit(2)
            ->group('group_title')
            ->title('log_title')
            ->log('message_to_log');
    }
}
