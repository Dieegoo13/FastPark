<?php

class Autoloader
{
    /**
     * Diretórios onde o autoloader deve procurar classes.
     */
    protected static $directories = [];

    /**
     * Classes que já foram carregadas.
     */
    protected static $loaded_classes = [];

    /**
     * Inicializa o autoloader e define os diretórios base.
     */
    public static function init()
    {
        self::$directories = [
            __DIR__ . '/../controllers/', 
            __DIR__ . '/../models/', 
            __DIR__ . '/../core/',
            __DIR__ . '/../services/'
        ];

        // Registra o método 'load' para autocarregar classes
        spl_autoload_register([__CLASS__, 'load']);
    }

    /**
     * Tenta carregar automaticamente a classe informada.
     */
    public static function load($className)
    {
        if (empty($className)) {
            return false;
        }

        // Se a classe já foi carregada
        if (isset(self::$loaded_classes[$className])) {
            return true;
        }

        // Percorre os diretórios registrados
        foreach (self::$directories as $directory) {
            $file = $directory . $className . '.php';

            if (file_exists($file)) {
                require_once $file;
                self::$loaded_classes[$className] = true;
                return true;
            }
        }

        return false;
    }

    /**
     * Retorna uma lista das classes já carregadas (para debug).
     */
    public static function getLoadedClasses()
    {
        return array_keys(self::$loaded_classes);
    }
}
