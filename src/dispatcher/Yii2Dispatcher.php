<?php

namespace devatmaliance\file_service\dispatcher;

use Psr\EventDispatcher\EventDispatcherInterface;
use yii\base\Event;
use Yii;

class Yii2Dispatcher implements EventDispatcherInterface
{
    public function dispatch(object $event)
    {
        Yii::$app->trigger($event::NAME, $event->adaptForYii2($event));
    }
}