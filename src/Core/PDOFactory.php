<?php


namespace Core;


use Exceptions\BlogConfigError;
use PDO;

class PDOFactory
{
    private static $dbConfig;

    public static function getDBConnexion()
    {
        try {
            self::setDbConfig('mysql');
        } catch (BlogConfigError $e) {
            die ('[BlogConfigError]: ' . $e);
        }

        $db = new PDO('mysql:host=' . self::$dbConfig['host'] . ';dbname=' . self::$dbConfig['database'], self::$dbConfig['user'], self::$dbConfig['password']);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    }

    private static function setDbConfig($dbEngine)
    {
        $dbConfig_file = ROOT_DIR . '/config/db-config.yml';
        if (!file_exists($dbConfig_file)) {
            throw new BlogConfigError("Fichier " . $dbConfig_file . " manquant");
        }
        self::$dbConfig = yaml_parse_file($dbConfig_file)[$dbEngine];
    }
}