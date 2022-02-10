<?php

namespace Bachtiar\Helper\LaminasLogger\Service;

use Bachtiar\Helper\LaminasLogger\Interfaces\LoggerInterface;
use Bachtiar\Helper\LaminasLogger\Traits\LoggerTrait;

/**
 * Logging Activity Service
 *
 * :: how to use
 * => $this->channel('default')->mode('default')->classLimit('default')->group('group_title')->title('log_title')->log('message_to_log');
 *
 * :: channel('default') => not required
 *
 * :: mode('default') => not required
 *
 * :: classLimit('default') => not required
 *
 * :: group('default') => not required
 *
 * :: title('default') => not required
 *
 * :: log('default') => required
 */
trait SwiftLog
{
    use LoggerTrait;

    /**
     * logger file name
     *
     * @var string
     */
    protected static string $fileName = LoggerInterface::DEFAULT_SWIFT_LOG_FILE_NAME;

    /**
     * current class
     *
     * @var string
     */
    protected static string $class = __CLASS__ ?? LoggerInterface::DEFAULT_SWIFT_LOG_MODULE_NAME;

    /**
     * class namespace limit
     *
     * @var integer
     */
    private static int $classLimit = 2;

    /**
     * group of log
     *
     * @var string
     */
    private static string $group = LoggerInterface::DEFAULT_SWIFT_LOG_GROUP;

    /**
     * module name
     *
     * @var string
     */
    private static string $moduleName = "";

    // ? Private Methods
    /**
     * custom icube swift rule for logging
     *
     * @param mixed $message set value of log here
     * @return boolean
     */
    private function log($message = LoggerInterface::DEFAULT_MESSAGE): bool
    {
        $result = false;

        $logger = self::OpenStream();

        try {
            self::moduleNameResolver();

            $logger->log(self::channelResolver(), $this->message($message)->messageResolver());

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
    private static function moduleNameResolver(): void
    {
        try {
            $_classArray = explode("\\", self::$class);

            foreach ($_classArray as $key => $class)
                if (($key + 1) <= self::$classLimit)
                    $_classProposed[] = $class;

            self::$moduleName = implode("\\", $_classProposed);
        } catch (\Throwable $th) {
            self::$moduleName = LoggerInterface::DEFAULT_SWIFT_LOG_MODULE_NAME;
        } finally {
            self::$moduleName = str_replace('\\', '_', self::$moduleName);
        }
    }

    /**
     * logger file locatio name resolver
     *
     * @return string
     */
    private static function fileNameResolver(): string
    {
        return LoggerInterface::LOCATION . self::$fileName . LoggerInterface::FILE_FORMAT;
    }

    /**
     * resolve message input
     *
     * @return string
     */
    private function messageResolver(): string
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
     * Set class namespace limit
     *
     * @param integer $classLimit class namespace limit
     *
     * @return self
     */
    private function classLimit(int $classLimit): self
    {
        self::$classLimit = $classLimit;

        return $this;
    }

    /**
     * Set group of log
     *
     * @param string $group group of log
     *
     * @return self
     */
    private function group(string $group = LoggerInterface::DEFAULT_SWIFT_LOG_GROUP): self
    {
        self::$group = $group;

        return $this;
    }

    /**
     * Set value of channel.
     *
     * -> select channel, available [ emerg, alert, crit, err, warn, notice, info ],
     * if null then auto set to default.
     *
     * @param string $channel
     * @return self
     */
    private function channel(string $channel = LoggerInterface::CHANNEL_DEBUG_NAME): self
    {
        self::$channel = $channel;

        return $this;
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
    private function mode(string $mode = LoggerInterface::MODE_DEFAULT): self
    {
        self::$mode = $mode;

        return $this;
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
    private function title(string $title = LoggerInterface::DEFAULT_TITLE): self
    {
        self::$title = $title;

        return $this;
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
    private function message($message = LoggerInterface::DEFAULT_MESSAGE): self
    {
        self::$message = $message;

        return $this;
    }
}
