<?php

namespace App\Core;

class Request {
    
    /**
     * Extrait le chemin du script à appeler
     * lors que l'user effectue une request.
     * 
     * @return string
     */
    public function getPath(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }
        return trim($path, '/');
    }

    /**
     * Retourne le verbe http utilisé pour envoyer la request.
     * 
     * @return string
     */
    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
}