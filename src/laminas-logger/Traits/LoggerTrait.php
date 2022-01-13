<?php

namespace Bachtiar\Helper\LaminasLogger\Traits;

use Bachtiar\Helper\LaminasLogger\Interfaces\LoggerInterface;
use Laminas\Log\Writer\Stream;
use Laminas\Log\Logger;

/**
 * Logger Trait
 */
trait LoggerTrait
{
    // 

    private static function Stream(): Logger
    {
        $writer = new Stream(LoggerInterface::BASE_DIR . self::fileNameResolver());

        $logger = new Logger;

        return $logger->addWriter($writer);
    }
}
