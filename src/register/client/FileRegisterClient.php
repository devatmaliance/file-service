<?php

namespace devatmaliance\file_service\register\client;

use devatmaliance\file_service\file\path\Path;
use devatmaliance\file_service\file\path\RelativePath;

interface FileRegisterClient
{
    public function registerFile(Path $filePath, Path $aliasPath): void;

    public function getPathByAlias(RelativePath $relativePath): Path;

    public function aliasExists(RelativePath $getRelativePath): bool;

    public function reserveAlias(RelativePath $aliasPath): Path;
}