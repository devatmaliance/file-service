<?php

namespace devatmaliance\file_service\storage;

use devatmaliance\file_service\file\File;
use devatmaliance\file_service\file\path\Path;

interface Storage
{
    public function write(File $file): Path;

    public function read(Path $path): File;

    public function checkAvailability(File $file): bool;

    public function getConfig(): BaseStorageConfiguration;
}