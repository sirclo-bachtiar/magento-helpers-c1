<?php

namespace Bachtiar\Helper\LaminasLogger\Service;

use Bachtiar\Helper\LaminasLogger\Interfaces\LoggerInterface;
use Bachtiar\Helper\LaminasLogger\Traits\LoggerTrait;

/**
 * Logging Activity Service
 *
 * :: how to use
 * => LogService::channel('default')->mode('default')->title('log_title')->log('message_to_log');
 *
 * :: channel('default') => not required
 *
 * :: mode('default') => not required
 *
 * :: title('default') => not required
 *
 * :: log('default') => required
 */
class LogService
{
    use LoggerTrait;

    // ? Public Methods
    /**
     * set log message
     *
     * @param mixed $message set value of log here
     * @return boolean
     */
    public static function log($message = LoggerInterface::DEFAULT_MESSAGE): bool
    {
        $result = false;

        $logger = self::OpenStream();

        try {
            $logger->log(self::channelResolver(), self::message($message)->messageResolver());

            $result = true;
        } catch (\Throwable $th) {
            $logger->log(LoggerInterface::CHANNEL_ERROR_CODE, $th->getMessage());
        } finally {
            return $result;
        }
    }
}
