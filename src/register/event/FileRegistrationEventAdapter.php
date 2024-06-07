<?php

namespace devatmaliance\file_service\register\event;

use devatmaliance\file_service\file\FilePath;

interface FileRegistrationEventAdapter
{
    public function getFilePath(): FilePath;

    public function getAliasPath(): FilePath;

    public function getException(): \Throwable;

}