<?php
/**
 * Created by PhpStorm.
 * User: furga
 * Date: 01.07.2018
 * Time: 10:54
 */

namespace AppBundle\Service;

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
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->getTargetDir(), $fileName);


        return $fileName;
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }
}