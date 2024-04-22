<?php

namespace devatmaliance\file_service\storage;

interface Storage
{
    public function write(string $path, string $content): string;
}