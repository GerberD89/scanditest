<?php
class DatabaseQueryHandler {
// Fetching columns 9prodcytType, tableName and required_description from productmap table to generate dropdown list items in formhandler.php
    private static function executeQuery($pdo, $query) {
        $statement = $pdo->query($query);
        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function fetchProductTypes($pdo) {
        $query = "SELECT DISTINCT product_type FROM productmap";
        return self::executeQuery($pdo, $query);
    }

    public static function fetchTableNames($pdo) {
        $query = "SELECT DISTINCT table_name FROM productmap";
        return self::executeQuery($pdo, $query);
    }

    public static function fetchRequiredDescriptions($pdo) {
        $query = "SELECT DISTINCT required_description FROM productmap";
        return self::executeQuery($pdo, $query);
    }
}
?>