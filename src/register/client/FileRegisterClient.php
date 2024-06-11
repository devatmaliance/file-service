<?php

namespace devatmaliance\file_service\register\client;

use devatmaliance\file_service\file\path\Path;

interface FileRegisterClient
{
    public function register(Path $filePath, Path $aliasPath): Path;
}