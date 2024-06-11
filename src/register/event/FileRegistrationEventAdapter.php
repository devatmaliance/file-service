<?php

namespace devatmaliance\file_service\register\event;

use devatmaliance\file_service\file\path\Path;

interface FileRegistrationEventAdapter
{
    public function getFilePath(): Path;

    public function getAliasPath(): Path;

    public function getException(): \Throwable;

}