<?php

namespace devatmaliance\file_service;

use devatmaliance\file_service\file\File;
use devatmaliance\file_service\file\path\Path;
use devatmaliance\file_service\file\path\RelativePath;

interface StorageManager
{
    public function write(File $file): Path;

    public function checkAvailability(File $file): array;

    public function read(Path $path): File;
}