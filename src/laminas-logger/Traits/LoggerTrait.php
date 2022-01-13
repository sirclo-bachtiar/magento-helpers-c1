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
    /**
     * log channel
     *
     * @var string
     */
    private static string $channel = LoggerInterface::CHANNEL_DEBUG_NAME;

    /**
     * log mode
     *
     * @var string
     */
    private static string $mode = LoggerInterface::MODE_DEFAULT;

    /**
     * log title
     *
     * @var string
     */
    private static string $title = LoggerInterface::DEFAULT_TITLE;

    /**
     * log message
     *
     * @var mixed
     */
    private static $message = LoggerInterface::DEFAULT_MESSAGE;

    // ? Private Methods
    /**
     * start open stream for logger
     *
     * @return Logger
     */
    private static function OpenStream(): Logger
    {
        $writer = new Stream(LoggerInterface::BASE_DIR . self::fileNameResolver());

        $logger = new Logger;

        return $logger->addWriter($writer);
    }

    /**
     * resolve channel selected
     *
     * @return integer
     */
    private static function channelResolver(): int
    {
        try {
            return LoggerInterface::CHANNEL_AVAILABLE[self::$channel];
        } catch (\Throwable $th) {
            return LoggerInterface::CHANNEL_DEBUG_CODE;
        }
    }

    /**
     * resolve saving log location
     *
     * @return string
     */
    private static function fileNameResolver(): string
    {
        $_mode = LoggerInterface::MODE_DEFAULT;

        try {
            if (!in_array(self::$mode, LoggerInterface::MODE_AVAILABLE)) throw new \Exception("");

            $_mode = self::$mode;
        } catch (\Throwable $th) {
            //
        } finally {
            return (string) LoggerInterface::LOCATION . LoggerInterface::IDENTITY . $_mode . LoggerInterface::FILE_FORMAT;
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

            if (in_array(gettype($_message), LoggerInterface::MESSAGE_TO_JSON_CONVERT_TYPE))
                $_message = json_encode($_message);

            if (iconv_strlen(self::$title))
                $_message = self::$title . ": {$_message}";

            return $_message;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
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
    public static function channel(string $channel = LoggerInterface::CHANNEL_DEBUG_NAME): self
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
    public static function mode(string $mode = LoggerInterface::MODE_DEFAULT): self
    {
        self::$mode = $mode;

        return new self;
    }

    /**
     * Set the value of title
     *
     * -> set title for log,
     * if null then there is no title for log.
     *
     * @param string $title
     * @return self
     */
    public static function title(string $title = LoggerInterface::DEFAULT_TITLE): self
    {
        self::$title = $title;

        return new self;
    }

    /**
     * Set log message
     *
     * -> set message for log,
     * if null then will set default message for log.
     *
     * @param mixed $message log message
     * @return self
     */
    public static function message($message = LoggerInterface::DEFAULT_MESSAGE): self
    {
        self::$message = $message;

        return new self;
    }
}
