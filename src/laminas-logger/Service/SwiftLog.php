<?php

namespace Bachtiar\Helper\LaminasLogger\Service;

use Bachtiar\Helper\LaminasLogger\Interfaces\LoggerInterface;
use Laminas\Log\Logger;
use Laminas\Log\Writer\Stream;

class SwiftLog
{
    public const LOGGER_FILE_NAME = "swift";

    /**
     * module name
     *
     * @var string
     */
    public static string $moduleName = "";

    /**
     * custom icube swift rule for logging
     *
     * @param mixed $message set value of log here
     * @param string $groupTitle
     * @param string $title
     * @param integer $priority [0 => "EMERG", 1 => "ALERT", 2 => "CRIT", 3 => "ERR", 4 => "WARN", 5 => "NOTICE", 6 => "INFO", 7 => "DEBUG"] default = 7 (DEBUG)
     * @return boolean
     */
    public static function log($message, string $groupTitle = "", string $title = "", int $priority = LoggerInterface::CHANNEL_DEBUG): bool
    {
        $writer = new Stream(LoggerInterface::BASE_DIR . self::fileNameResolver());
        $logger = new Logger();
        $logger->addWriter($writer);

        $result = false;

        try {
            self::getModuleName();

            $header = iconv_strlen($groupTitle)
                ? self::$moduleName . " - {$groupTitle}"
                : self::$moduleName;

            if (in_array(gettype($message), LoggerInterface::MESSAGE_TO_JSON_CONVERT_TYPE))
                $message = json_encode($message);

            if (iconv_strlen($title))
                $message = "{$title}: {$message}";

            $logger->log($priority, "{$header} | {$message}");

            $result = true;
        } catch (\Throwable $th) {
            $logger->log(LoggerInterface::CHANNEL_ERROR, $th->getMessage());
        } finally {
            return $result;
        }
    }

    /**
     * get module name
     *
     * @return string|null
     */
    public static function getModuleName(): ?string
    {
        if (!self::$moduleName) {
            $class = __CLASS__;
            self::$moduleName = substr($class, 0, strpos($class, '\\Helper'));
        }

        self::$moduleName = str_replace('\\', '_', self::$moduleName);

        return self::$moduleName ?? null;
    }

    /**
     * logger file locatio name resolver
     *
     * @return string
     */
    private static function fileNameResolver(): string
    {
        return LoggerInterface::LOCATION . self::LOGGER_FILE_NAME . LoggerInterface::FILE_FORMAT;
    }
}
