<?php

namespace App\Util;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class Censurator
{
    const UNWANTED_WORDS = ["casino","viagra","bad","banana"];
    //On injecte ContainerBagInterface dans le constructeur pour permettre de récupérer
    //le paramètre dans le fichier services.yaml
    public function __construct(private readonly ContainerBagInterface $params)
    {

    }

    public function purify(?string $text): string
    {
        $filename = $this->params->get('app.censurator_file');
        if(file_exists($filename)){
            //On récupère les mots du fichier sous forme de tableau
            $words = file($filename);
            foreach($words as $unwantedWord){
                //Avant de traiter le mot on retire le retour chariot présent en fin de chaque ligne.
                $unwantedWord = str_replace(PHP_EOL,'',$unwantedWord);
                //Autant d"* que de lettres dans le mot
                $replacement = str_repeat("*",mb_strlen($unwantedWord));
                $text = str_ireplace($unwantedWord, $replacement, $text);
            }
        }


        return $text;
    }
}