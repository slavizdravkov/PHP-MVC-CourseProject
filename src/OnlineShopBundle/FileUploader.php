<?php


namespace OnlineShopBundle;

use Symfony\Component\HttpFoundation\File\UploadedFile;


class FileUploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();

        $file->move($this->targetDir, $fileName);

        return $fileName;
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }

    public function remove($fileName)
    {
        $filePath = $this->getTargetDir() . '/' . $fileName;

        if (file_exists($filePath)) {
            unlink($filePath);
        }


    }
}