<?php

namespace devatmaliance\file_service\register\client;

use devatmaliance\file_service\file\FilePath;

interface FileRegisterClient
{
    public function register(FilePath $filePath, FilePath $aliasPath): FilePath;
}