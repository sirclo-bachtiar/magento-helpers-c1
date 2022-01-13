<?php

namespace Bachtiar\Helper\LaminasLogger\Service;

use Bachtiar\Helper\LaminasLogger\Interfaces\LoggerInterface;
use Bachtiar\Helper\LaminasLogger\Traits\LoggerTrait;

/**
 * Logging Activity Service
 *
 * :: how to use
 * => SwiftLog::class('__CLASS__')->classLimit('default')->channel('default')->mode('default')->group('group_title')->title('log_title')->log('message_to_log');
 *
 * :: class('default') => not required
 *
 * :: classLimit('default') => not required
 *
 * :: channel('default') => not required
 *
 * :: mode('default') => not required
 *
 * :: group('default') => not required
 *
 * :: title('default') => not required
 *
 * :: log('default') => required
 */
class SwiftLog
{
    use LoggerTrait;

    public const LOGGER_FILE_NAME = "swift";
    public const DEFAULT_MODULE_NAME = 'Swift\Logger';
    public const DEFAULT_GROUP = 'group default';

    /**
     * current class
     *
     * @var string
     */
    public static string $class = self::DEFAULT_MODULE_NAME;

    /**
     * class namespace limit
     *
     * @var integer
     */
    public static int $classLimit = 2;

    /**
     * group of log
     *
     * @var string
     */
    public static string $group = self::DEFAULT_GROUP;

    /**
     * module name
     *
     * @var string
     */
    public static string $moduleName = "";

    public function __construct()
    {
        self::moduleNameResolver();
    }

    // ? Public Methods
    /**
     * custom icube swift rule for logging
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

    /**
     * module name resolver
     *
     * @return void
     */
    public static function moduleNameResolver(): void
    {
        try {
            $_classArray = explode("\\", self::$class);

            foreach ($_classArray as $key => $class)
                if (($key + 1) <= self::$classLimit)
                    $_classProposed[] = $class;

            self::$moduleName = implode("\\", $_classProposed);
        } catch (\Throwable $th) {
            self::$moduleName = self::DEFAULT_MODULE_NAME;
        } finally {
            self::$moduleName = str_replace('\\', '_', self::$moduleName);
        }
    }

    // ? Private Methods
    /**
     * logger file locatio name resolver
     *
     * @return string
     */
    private static function fileNameResolver(): string
    {
        return LoggerInterface::LOCATION . self::LOGGER_FILE_NAME . LoggerInterface::FILE_FORMAT;
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

            $_header = iconv_strlen(self::$group)
                ? self::$moduleName . " - " . self::$group
                : self::$moduleName;

            if (in_array(gettype($_message), LoggerInterface::MESSAGE_TO_JSON_CONVERT_TYPE))
                $_message = json_encode($_message);

            if (iconv_strlen(self::$title))
                $_message = self::$title . ": {$_message}";

            return "{$_header} | {$_message}";
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    // ? Setter Modules
    /**
     * Set current class
     *
     * @param string $class current class
     *
     * @return self
     */
    public static function class(string $class = self::DEFAULT_MODULE_NAME): self
    {
        self::$class = $class;

        return new self;
    }

    /**
     * Set class namespace limit
     *
     * @param integer $classLimit class namespace limit
     *
     * @return self
     */
    public static function classLimit(int $classLimit): self
    {
        self::$classLimit = $classLimit;

        return new self;
    }

    /**
     * Set group of log
     *
     * @param string $group group of log
     *
     * @return self
     */
    public static function group(string $group = self::DEFAULT_GROUP): self
    {
        self::$group = $group;

        return new self;
    }
}
