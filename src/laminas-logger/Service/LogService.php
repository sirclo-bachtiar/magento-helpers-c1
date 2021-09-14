<?php

namespace Bachtiar\Helper\LaminasLogger\Service;

use Laminas\Log\Writer\Stream;
use Laminas\Log\Logger;

/**
 * Logging Activity Service
 *
 * :: how to use
 * => LogService::channel('default')->mode('default')->title('log_title')->message('message_to_log');
 *
 * :: channel('default')
 *
 * :: mode('default')
 *
 * :: title('default')
 *
 * :: message('default')
 */
class LogService
{
    protected static $channel;
    protected static $mode;
    protected static $title;
    protected static $message;

    /**
     * Change the value of BASE_DIR on line 35,
     * adjust to the location where your logs are stored.
     * you can use [ define() ] at [ autoload ] file,
     * default [ dirname(__DIR__) ].
     */
    private const BASE_DIR = BP;
    private const LOCATION = '/var/log/';
    private const IDENTITY = 'bachtiar.';
    private const FILEFORMAT = '.log';

    /**
     * Logger Priority.
     * source -> https://docs.laminas.dev/laminas-log/intro/#using-built-in-priorities
     */
    private const CHANNEL_EMERGENCY = 0;
    private const CHANNEL_ALERT = 1;
    private const CHANNEL_CRITICAL = 2;
    private const CHANNEL_ERROR = 3;
    private const CHANNEL_WARNING = 4;
    private const CHANNEL_NOTICE = 5;
    private const CHANNEL_INFO = 6;
    private const CHANNEL_DEBUG = 7;

    private const FILE_DEFAULT = 'default';
    private const FILE_TEST = 'test';
    private const FILE_DEBUG = 'debug';
    private const FILE_DEVELOP = 'develop';

    private const DEFAULT_TITLE = 'new log';
    private const DEFAULT_MESSAGE = 'log test successfully';

    private const CHANNEL_AVAILABLE = [
        'emerg' => self::CHANNEL_EMERGENCY,
        'alert' => self::CHANNEL_ALERT,
        'crit' => self::CHANNEL_CRITICAL,
        'err' => self::CHANNEL_ERROR,
        'warn' => self::CHANNEL_WARNING,
        'notice' => self::CHANNEL_NOTICE,
        'info' => self::CHANNEL_INFO,
        'debug' => self::CHANNEL_DEBUG
    ];

    private const MODE_AVAILABLE = [
        'test' => self::FILE_TEST,
        'debug' => self::FILE_DEBUG,
        'develop' => self::FILE_DEVELOP,
        'default' => self::FILE_DEFAULT
    ];

    // ? Public Methods
    /**
     * set log message
     *
     * @param mixed $message
     * @return boolean
     */
    public static function message($message = self::DEFAULT_MESSAGE): bool
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
        $writer = new Stream(self::BASE_DIR . self::fileNameResolver());

        $logger = new Logger;

        $result = false;

        try {
            $logger->addWriter($writer)->log(self::channelResolver(), self::messageResolver());
        } catch (\Throwable $th) {
            $logger->addWriter($writer)->log(self::CHANNEL_AVAILABLE['debug'], $th->getMessage());
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
            return self::CHANNEL_AVAILABLE[$getChannel];
        } catch (\Throwable $th) {
            self::$message .= $th->getMessage();
            return self::CHANNEL_AVAILABLE['debug'];
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
            $_message = json_encode(self::$message);

            if (self::$title) $_message = self::$title . ": " . $_message;

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
            $modeResult = self::MODE_AVAILABLE[$getMode];
        } catch (\Throwable $th) {
            echo $th->getMessage();
            $modeResult = self::MODE_AVAILABLE['default'];
        }

        return (string) self::LOCATION . self::IDENTITY . $modeResult . self::FILEFORMAT;
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
    public static function title(string $title = self::DEFAULT_TITLE): self
    {
        self::$title = $title;

        return new self;
    }
}
