<?php

declare(strict_types=1);

use Psr\Log\LoggerInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Log\Logger;
use Yiisoft\Log\Target\File\FileRotator;
use Yiisoft\Log\Target\File\FileRotatorInterface;
use Yiisoft\Log\Target\File\FileTarget;

/* @var $params array */

return [
    LoggerInterface::class => static fn (FileTarget $fileTarget) => new Logger(['file' => $fileTarget]),
    FileRotatorInterface::class => [
        '__class' => FileRotator::class,
        '__construct()' => [
            $params['yiisoft/log-target-file']['file-rotator']['maxfilesize'],
            $params['yiisoft/log-target-file']['file-rotator']['maxfiles'],
            $params['yiisoft/log-target-file']['file-rotator']['filemode'],
            $params['yiisoft/log-target-file']['file-rotator']['rotatebycopy']
        ]
    ],
    FileTarget::class => static function (Aliases $aliases, FileRotatorInterface $fileRotator) use ($params) {
        $fileTarget = new FileTarget(
            $aliases->get($params['yiisoft/log-target-file']['file-target']['file']),
            $fileRotator,
            $params['yiisoft/log-target-file']['file-target']['dirMode'],
            $params['yiisoft/log-target-file']['file-target']['fileMode']
        );

        $fileTarget->setLevels($params['yiisoft/log-target-file']['file-target']['levels']);

        return $fileTarget;
    },
];
