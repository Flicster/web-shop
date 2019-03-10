<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;

class FileManager
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(File $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function remove(File $file)
    {
        $filesystem = new Filesystem();

        try {
            $filesystem->remove($file);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}