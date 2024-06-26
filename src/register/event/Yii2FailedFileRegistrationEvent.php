<?php

namespace devatmaliance\file_service\register\event;

use devatmaliance\file_service\file\path\Path;
use yii\base\Event;

class Yii2FailedFileRegistrationEvent extends Event implements FileRegistrationEventAdapter
{
    private FailedFileRegistrationEvent $event;

    public function __construct(FailedFileRegistrationEvent $event)
    {
        $this->event = $event;
    }

    public function getFilePath(): Path
    {
        return $this->event->getFilePath();
    }

    public function getAliasPath(): Path
    {
        return $this->event->getAliasPath();
    }

    public function getException(): \Throwable
    {
        return $this->event->getException();
    }
}