<?php

final class DatabaseConnection extends PDO {
    private static $instance = null;

    private static $defaultHost = "fdb1034.awardspace.net";
    private static $defaultDbName = "4435104_testdb";
    private static $defaultUsername = "4435104_testdb";
    private static $defaultPassword = "Root50!!089";
        

    private function __construct(string $dsn, string $username, string $password, array $options) {
        parent::__construct($dsn, $username, $password, $options);
    }

    public static function getInstance(
        $host = null,
        $dbName = null,
        $username = null,
        $password = null
    ) {
        if (is_null(self::$instance)) {
            $host = $host ?? self::$defaultHost;
            $dbName = $dbName ?? self::$defaultDbName;
            $username = $username ?? self::$defaultUsername;
            $password = $password ?? self::$defaultPassword;
            $dsn = "mysql:host=$host;dbname=$dbName";
            $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
            self::$instance = new DatabaseConnection($dsn, $username, $password, $options);
        }
        return self::$instance;
    }
}

