<?php

namespace DurabolBundle;

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
        $file->move($this->targetDir, $fileName);
        return $fileName;
    }

    public function remove($file)
    {
        $file_path = $this->targetDir.'/'.$file;
        if(file_exists($file_path)){
            $isRemoved = unlink($file_path);
        }else{
            $isRemoved = -1;
        }

        return $isRemoved;
    }
}