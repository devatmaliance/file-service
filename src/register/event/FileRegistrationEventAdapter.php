<?php

namespace devatmaliance\file_service\register\event;

use devatmaliance\file_service\file\path\Path;
use devatmaliance\file_service\file\path\RelativePath;

interface FileRegistrationEventAdapter
{
    public function getFilePath(): Path;

    public function getAliasPath(): RelativePath;

    public function getException(): \Throwable;

}