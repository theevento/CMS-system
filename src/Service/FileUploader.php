<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;
    private $realTargetDirectory;

    public function __construct($targetDirectory, $realTargetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
        $this->realTargetDirectory = $realTargetDirectory;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->getTargetDirectory(), $fileName);

        return $this->realTargetDirectory.$fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}