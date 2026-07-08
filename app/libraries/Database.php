<?php

class Database {

    private $dbHost = DB_HOST;
    private $dbUser = DB_USER;
    private $dbPass = DB_PASS;
    private $dbName = DB_NAME;
     
    private $statement;
    private $dbHandler;
    private $error;

    public function __construct()
    {
        $conn = 'mysql:host=' . $this->dbHost . ';dbname=' . $this->dbName . ';charset=utf8mb4';
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false, // Use real prepared statements
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ // Default to objects
        );
        
        try {
            $this->dbHandler = new PDO($conn, $this->dbUser, $this->dbPass, $options);
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            
            // Log error instead of printing in production
            error_log('Database Connection Error: ' . $this->error);
            
            // Show user-friendly message
            die('Database connection failed. Please contact administrator.');
        }
    }

    // ==================== QUERY METHODS ====================

    /**
     * Prepare SQL query
     */
    public function query($sql)
    {
        $this->statement = $this->dbHandler->prepare($sql);
    }

    /**
     * Bind values to prepared statement
     */
    public function bind($parameter, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        $this->statement->bindValue($parameter, $value, $type);
    }

    /**
     * Execute prepared statement
     */
    public function execute()
    {
        try {
            return $this->statement->execute();
        } catch(PDOException $e) {
            error_log('Query Execution Error: ' . $e->getMessage());
            return false;
        }
    }

    // ==================== FETCH METHODS ====================

    /**
     * Return array of objects
     */
    public function resultSet()
    {
        $this->execute();
        return $this->statement->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Return single row as object
     */
    public function single()
    {
        $this->execute();
        return $this->statement->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Return array of arrays (associative)
     */
    public function resultSetAssoc()
    {
        $this->execute();
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Return single row as associative array
     */
    public function singleAssoc()
    {
        $this->execute();
        return $this->statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Return single column value
     */
    public function singleColumn()
    {
        $this->execute();
        return $this->statement->fetchColumn();
    }

    // ==================== UTILITY METHODS ====================

    /**
     * Get row count
     */
    public function rowCount()
    {
        return $this->statement->rowCount();
    }

    /**
     * Get last insert ID
     */
    public function lastInsertId()
    {
        return $this->dbHandler->lastInsertId();
    }

    /**
     * Begin transaction
     */
    public function beginTransaction()
    {
        return $this->dbHandler->beginTransaction();
    }

    /**
     * Commit transaction
     */
    public function commit()
    {
        return $this->dbHandler->commit();
    }

    /**
     * Rollback transaction
     */
    public function rollback()
    {
        return $this->dbHandler->rollBack();
    }

    /**
     * Check if inside a transaction
     */
    public function inTransaction()
    {
        return $this->dbHandler->inTransaction();
    }

    /**
     * Get PDO instance (for advanced usage)
     */
    public function getPDO()
    {
        return $this->dbHandler;
    }

    /**
     * Get last error message
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Close database connection
     */
    public function closeConnection()
    {
        $this->dbHandler = null;
    }

    // ==================== DATATABLES ====================

    /**
     * Get database credentials for DataTables SSP
     */
    public function dataTableSqlDetails()
    {
        return [
            'user' => $this->dbUser,
            'pass' => $this->dbPass,
            'db' => $this->dbName,
            'host' => $this->dbHost
        ];
    }

    // ==================== DEBUGGING METHODS ====================

    /**
     * Debug - Print last SQL query
     */
    public function debugDumpParams()
    {
        return $this->statement->debugDumpParams();
    }

    /**
     * Get prepared SQL query (for debugging)
     */
    public function getSQL()
    {
        return $this->statement->queryString;
    }
}