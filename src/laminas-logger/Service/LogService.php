<?php

namespace Bachtiar\Helper\LaminasLogger\Service;

use Bachtiar\Helper\LaminasLogger\Interfaces\LoggerInterface;
use Laminas\Log\Writer\Stream;
use Laminas\Log\Logger;

/**
 * Logging Activity Service
 *
 * :: how to use
 * => LogService::channel('default')->mode('default')->title('log_title')->message('message_to_log');
 *
 * :: channel('default') => not required
 *
 * :: mode('default') => not required
 *
 * :: title('default') => not required
 *
 * :: message('default') => required
 */
class LogService
{
    /**
     * log channel
     *
     * @var string
     */
    protected static $channel;

    /**
     * log mode
     *
     * @var string
     */
    protected static $mode;

    /**
     * log title
     *
     * @var string
     */
    protected static $title;

    /**
     * log message
     *
     * @var mixed
     */
    protected static $message;

    // ? Public Methods
    /**
     * set log message
     *
     * @param mixed $message
     * @return boolean
     */
    public static function message($message = LoggerInterface::DEFAULT_MESSAGE): bool
    {
        self::$message = $message;

        return self::createNewLog();
    }

    // ? Private Methods
    /**
     * process for creating log
     *
     * @return boolean
     */
    private static function createNewLog(): bool
    {
        $writer = new Stream(LoggerInterface::BASE_DIR . self::fileNameResolver());
        $logger = new Logger;
        $logger->addWriter($writer);

        $result = false;

        try {
            $logger->log(self::channelResolver(), self::messageResolver());

            $result = true;
        } catch (\Throwable $th) {
            $logger->log(LoggerInterface::CHANNEL_ERROR, $th->getMessage());
        } finally {
            return $result;
        }
    }

    /**
     * resolve channel selected
     *
     * @return integer
     */
    private static function channelResolver(): int
    {
        $getChannel = self::$channel ?? 'debug';

        try {
            return LoggerInterface::CHANNEL_AVAILABLE[$getChannel];
        } catch (\Throwable $th) {
            return LoggerInterface::CHANNEL_AVAILABLE['debug'];
        }
    }

    /**
     * resolve message input
     *
     * @return string
     */
    private static function messageResolver(): string
    {
        try {
            $_message = self::$message;

            if (in_array(gettype(self::$message), LoggerInterface::MESSAGE_TO_JSON_CONVERT_TYPE))
                $_message = json_encode(self::$message);

            if (self::$title)
                $_message = self::$title . ": {$_message}";

            return $_message;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * resolve saving log location
     *
     * @return string
     */
    private static function fileNameResolver(): string
    {
        $getMode = self::$mode ?? 'default';

        try {
            $modeResult = LoggerInterface::MODE_AVAILABLE[$getMode];
        } catch (\Throwable $th) {
            $modeResult = LoggerInterface::MODE_AVAILABLE['default'];
        }

        return (string) LoggerInterface::LOCATION . LoggerInterface::IDENTITY . $modeResult . LoggerInterface::FILE_FORMAT;
    }

    // ? Setter Module
    /**
     * Set value of channel.
     *
     * -> select channel, available [ emerg, alert, crit, err, warn, notice, info ],
     * if null then auto set to default.
     *
     * @param string $channel
     * @return self
     */
    public static function channel(string $channel = 'debug'): self
    {
        self::$channel = $channel;

        return new self;
    }

    /**
     * Set value of mode.
     *
     * -> select log mode, available [ test, debug, develop ],
     * if null then auto set to default.
     *
     * @param string $mode
     * @return self
     */
    public static function mode(string $mode = 'default'): self
    {
        self::$mode = $mode;

        return new self;
    }

    /**
     * Set the value of title
     *
     * -> set title for log,
     * if null then there is no title for log
     *
     * @param string $title
     * @return self
     */
    public static function title(string $title = LoggerInterface::DEFAULT_TITLE): self
    {
        self::$title = $title;

        return new self;
    }
}
