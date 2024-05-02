<?php

namespace devatmaliance\file_service\storage;

use devatmaliance\file_service\file\File;
use devatmaliance\file_service\file\FilePath;

interface Storage
{
    public function write(File $file): FilePath;

    public function read(FilePath $path): File;

    public function checkAvailability(): bool;
}