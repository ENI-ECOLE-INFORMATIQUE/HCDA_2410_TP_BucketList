<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;


final class FileUploader
{
    private readonly string $targetDirectory;
     public function __construct(ParameterBagInterface $parameterBag){

       $this->targetDirectory = $parameterBag->get('app.images_wish_directory');
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
            if(file_exists($rep.'/'.$filename)){
                unlink($rep.'/'.$filename);
            }
        }
    }
}