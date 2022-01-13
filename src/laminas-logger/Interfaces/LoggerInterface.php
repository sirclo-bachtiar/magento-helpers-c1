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
     * convert message to json type.
     * where message type is match.
     */
    public const MESSAGE_TO_JSON_CONVERT_TYPE = ["array", "object"];

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

    public const FILE_DEFAULT = 'default';
    public const FILE_TEST = 'test';
    public const FILE_DEBUG = 'debug';
    public const FILE_DEVELOP = 'develop';

    public const DEFAULT_TITLE = 'new log';
    public const DEFAULT_MESSAGE = 'log test successfully';
}
