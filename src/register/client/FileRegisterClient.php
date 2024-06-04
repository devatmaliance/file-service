<?php

namespace devatmaliance\file_service\register\client;

interface FileRegisterClient
{
    public function register(string $filePath, string $aliasPath): string;
}