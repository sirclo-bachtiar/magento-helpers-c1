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
     * Change the value of BASE_DIR on line 30,
     * adjust to the location where your logs are stored.
     * you can use [ define() ] at [ autoload ] file,
     * default [ dirname(__DIR__) ].
     */
    public const BASE_DIR = BP;
    public const LOCATION = '/var/log/';
    public const IDENTITY = 'bachtiar.';
    public const FILE_FORMAT = '.log';

    /**
     * convert message to json type.
     * where message type is match.
     */
    public const MESSAGE_TO_JSON_CONVERT_TYPE = ["array"];

    /**
     * channel available
     * [emerg, alert, crit, err, warn, notice, info, debug]
     */
    public const CHANNEL_AVAILABLE = [
        'emerg' => self::CHANNEL_EMERGENCY,
        'alert' => self::CHANNEL_ALERT,
        'crit' => self::CHANNEL_CRITICAL,
        'err' => self::CHANNEL_ERROR,
        'warn' => self::CHANNEL_WARNING,
        'notice' => self::CHANNEL_NOTICE,
        'info' => self::CHANNEL_INFO,
        'debug' => self::CHANNEL_DEBUG
    ];

    /**
     * mode available
     * [test, debug, develop, default]
     */
    public const MODE_AVAILABLE = [
        'test' => self::FILE_TEST,
        'debug' => self::FILE_DEBUG,
        'develop' => self::FILE_DEVELOP,
        'default' => self::FILE_DEFAULT
    ];

    /**
     * Logger Priority.
     * source -> https://docs.laminas.dev/laminas-log/intro/#using-built-in-priorities
     */
    public const CHANNEL_EMERGENCY = 0;
    public const CHANNEL_ALERT = 1;
    public const CHANNEL_CRITICAL = 2;
    public const CHANNEL_ERROR = 3;
    public const CHANNEL_WARNING = 4;
    public const CHANNEL_NOTICE = 5;
    public const CHANNEL_INFO = 6;
    public const CHANNEL_DEBUG = 7;

    private const FILE_DEFAULT = 'default';
    private const FILE_TEST = 'test';
    private const FILE_DEBUG = 'debug';
    private const FILE_DEVELOP = 'develop';

    private const DEFAULT_TITLE = 'new log';
    private const DEFAULT_MESSAGE = 'log test successfully';

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
        $logger->addWriter($writer);

        $result = false;

        try {
            $logger->log(self::channelResolver(), self::messageResolver());

            $result = true;
        } catch (\Throwable $th) {
            $logger->log(self::CHANNEL_ERROR, $th->getMessage());
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
            $_message = self::$message;

            if (in_array(gettype(self::$message), LogService::MESSAGE_TO_JSON_CONVERT_TYPE))
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
            $modeResult = self::MODE_AVAILABLE[$getMode];
        } catch (\Throwable $th) {
            $modeResult = self::MODE_AVAILABLE['default'];
        }

        return (string) self::LOCATION . self::IDENTITY . $modeResult . self::FILE_FORMAT;
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
