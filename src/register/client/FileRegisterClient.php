<?php

namespace devatmaliance\file_service\register\client;

use devatmaliance\file_service\file\path\Path;
use devatmaliance\file_service\file\path\RelativePath;

interface FileRegisterClient
{
    public function registerFile(Path $filePath, RelativePath $aliasPath): Path;

    public function getPathByAlias(RelativePath $relativePath): Path;
}