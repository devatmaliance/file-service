<?php

namespace devatmaliance\file_service;

use devatmaliance\file_service\file\File;
use devatmaliance\file_service\file\FilePath;

interface StorageManager
{
    public function write(File $file, FilePath $aliasPath): FilePath;
}