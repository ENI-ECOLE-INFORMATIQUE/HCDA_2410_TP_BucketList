<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class FileUploader
{
     public function __construct(private readonly string $targetDirectory){
     }

     public function upload(UploadedFile $uploadedFile): string{
         $filename = uniqid().'.'.$uploadedFile->guessExtension();
         try{
             $uploadedFile->move($this->targetDirectory, $filename);
         }catch(FileException $e){
             throw new FileException($e->getMessage());
         }
         return $filename;
     }

    /**
     * @return string
     */
    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    public function delete(?string $filename, string $rep): void
    {
        if(null != $filename){
            if(file_exists($filename)){
                unlink($rep.'/'.$filename);
            }
        }
    }
}