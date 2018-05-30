<?php
/**
 * User: Andrew
 * Date: 4/11/2017
 * Time: 1:21 PM
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

    /**
     * @param UploadedFile $file
     * @param string|null $fileName
     * @return string
     */
    public function upload(UploadedFile $file, $fileName = null)
    {
        if(!$fileName){
            $fileName = md5(uniqid());
        }
//        dump($file->guessExtension());die;

        $fileName = $fileName.'.'.$file->guessExtension();

        $file->move($this->targetDir, $fileName);

        return $fileName;
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }
}