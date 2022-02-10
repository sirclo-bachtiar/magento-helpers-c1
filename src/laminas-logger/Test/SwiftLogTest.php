<?php

namespace Bachtiar\Helper\LaminasLogger\Test;

use Bachtiar\Helper\LaminasLogger\Service\SwiftLog;

class SwiftLogTest
{
    use SwiftLog;

    public function __invoke()
    {
        return $this->channel('default')
            ->mode('default')
            ->classLimit(2)
            ->group('group_title')
            ->title('log_title')
            ->log('message_to_log');
    }
}
