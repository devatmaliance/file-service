<?php

namespace devatmaliance\file_service\register\client;

use devatmaliance\file_service\file\path\Path;
use devatmaliance\file_service\file\path\RelativePath;

interface FileRegisterClient
{
    public function registerFile(Path $filePath, RelativePath $aliasPath): Path;

    public function getPathByAlias(RelativePath $relativePath): Path;

    public function aliasExists(RelativePath $getRelativePath): bool;

    public function compareHosts(Path $path1, Path $path2): bool;
}