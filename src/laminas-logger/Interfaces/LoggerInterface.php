<?php

namespace Bachtiar\Helper\LaminasLogger\Interfaces;

interface LoggerInterface
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
     * Icube Swift Default Constant
     */
    public const DEFAULT_SWIFT_LOG_FILE_NAME = "swift";
    public const DEFAULT_SWIFT_LOG_MODULE_NAME = 'Swift\Logger';
    public const DEFAULT_SWIFT_LOG_GROUP = 'group default';

    /**
     * convert message to json type.
     * where message type is match.
     */
    public const MESSAGE_TO_JSON_CONVERT_TYPE = ["array", "object"];

    /**
     * channel available
     * [emerg, alert, crit, err, warn, notice, info, debug]
     */
    public const CHANNEL_AVAILABLE = [
        self::CHANNEL_EMERGENCY_NAME => self::CHANNEL_EMERGENCY_CODE,
        self::CHANNEL_ALERT_NAME => self::CHANNEL_ALERT_CODE,
        self::CHANNEL_CRITICAL_NAME => self::CHANNEL_CRITICAL_CODE,
        self::CHANNEL_ERROR_NAME => self::CHANNEL_ERROR_CODE,
        self::CHANNEL_WARNING_NAME => self::CHANNEL_WARNING_CODE,
        self::CHANNEL_NOTICE_NAME => self::CHANNEL_NOTICE_CODE,
        self::CHANNEL_INFO_NAME => self::CHANNEL_INFO_CODE,
        self::CHANNEL_DEBUG_NAME => self::CHANNEL_DEBUG_CODE
    ];

    /**
     * mode available
     * [test, debug, develop, default]
     */
    public const MODE_AVAILABLE = [
        self::MODE_DEFAULT,
        self::MODE_TEST,
        self::MODE_DEBUG,
        self::MODE_DEVELOP
    ];

    /**
     * Logger Priority.
     * source -> https://docs.laminas.dev/laminas-log/intro/#using-built-in-priorities
     */
    public const CHANNEL_EMERGENCY_CODE = 0;
    public const CHANNEL_ALERT_CODE = 1;
    public const CHANNEL_CRITICAL_CODE = 2;
    public const CHANNEL_ERROR_CODE = 3;
    public const CHANNEL_WARNING_CODE = 4;
    public const CHANNEL_NOTICE_CODE = 5;
    public const CHANNEL_INFO_CODE = 6;
    public const CHANNEL_DEBUG_CODE = 7;

    public const CHANNEL_EMERGENCY_NAME = "emerg";
    public const CHANNEL_ALERT_NAME = "alert";
    public const CHANNEL_CRITICAL_NAME = "crit";
    public const CHANNEL_ERROR_NAME = "err";
    public const CHANNEL_WARNING_NAME = "warn";
    public const CHANNEL_NOTICE_NAME = "notice";
    public const CHANNEL_INFO_NAME = "info";
    public const CHANNEL_DEBUG_NAME = "debug";

    public const MODE_DEFAULT = 'default';
    public const MODE_TEST = 'test';
    public const MODE_DEBUG = 'debug';
    public const MODE_DEVELOP = 'develop';

    public const DEFAULT_TITLE = 'new log';
    public const DEFAULT_MESSAGE = 'log test successfully';
}
