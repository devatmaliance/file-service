<?php

namespace devatmaliance\file_service;

use devatmaliance\file_service\file\File;
use devatmaliance\file_service\file\path\Path;

interface StorageManager
{
    public function write(File $file, Path $aliasPath): Path;

    public function checkAvailability(File $file): array;

    public function read(Path $path): File;
}