<?php

namespace App\Services;

class ImageUploadService
{
    public function upload($image)
    {
        // Obtém a extensão da imagem
        $extension = $image->getClientOriginalExtension();

        // Gera um nome único para a imagem usando o nome original e um timestamp
        $imageName = md5($image->getClientOriginalName() . microtime()) . '.' . $extension;

        // Move a imagem para o diretório público 'img/events'
        $image->move(public_path('img/events'), $imageName);

        // Retorna o nome da imagem gerado para ser salvo no banco de dados
        return $imageName;
    }
}