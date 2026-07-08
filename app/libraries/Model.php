<?php

class Model
{

    protected $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    /**
     * Get query builder instance
     */
    public function table($table)
    {
        return new QueryBuilder($this->db, $table);
    }

    /* ========= BASIC FINDERS ========= */

    public function findById($table, $id)
    {
        $this->db->query("SELECT * FROM {$table} WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function findOne($table, $column, $value)
    {
        $this->db->query("SELECT * FROM {$table} WHERE {$column} = :val");
        $this->db->bind(':val', $value);
        return $this->db->single();
    }

    public function all($table)
    {
        $this->db->query("SELECT * FROM {$table} WHERE soft_delete = 1 ORDER BY id DESC");
        return $this->db->resultSet();
    }

    public function latest($table)
    {
        $this->db->query("SELECT * FROM {$table} WHERE soft_delete = 1 ORDER BY id DESC");
        return $this->db->single();
    }

    public function whereAll($table, $column, $value, $orderBy = 'id', $direction = 'DESC')
    {
        $this->db->query("
            SELECT * FROM {$table}
            WHERE {$column} = :val AND soft_delete = 1
            ORDER BY {$orderBy} {$direction}
        ");
        $this->db->bind(':val', $value);
        return $this->db->resultSet();
    }

    /* ========= BASIC CHECK ========= */

    public function exists($table, $column, $value): bool
    {
        $this->db->query("SELECT id FROM {$table} WHERE {$column} = :val");
        $this->db->bind(':val', $value);
        $this->db->execute();
        return $this->db->rowCount() > 0;
    }
    /* ========= ADVANCED CHECK ========= */

    public function existsWhere($table, array $conditions): bool
    {
        $where = [];
        foreach ($conditions as $column => $value) {
            $where[] = "{$column} = :{$column}";
        }

        $sql = "SELECT id FROM {$table} WHERE " . implode(' AND ', $where);

        $this->db->query($sql);

        foreach ($conditions as $column => $value) {
            $this->db->bind(":{$column}", $value);
        }

        $this->db->execute();

        return $this->db->rowCount() > 0;
    }


    /* ========= SESSION DATA SHORTCUTS ========= */

    public function userField($field)
    {
        if (!isset($_SESSION['user_id'])) return null;

        $this->db->query("SELECT * FROM users WHERE id = :id");
        $this->db->bind(':id', $_SESSION['user_id']);
        $row = $this->db->single();
        return $row->$field ?? null;
    }

    /* ========= DELETE ========= */

    public function softDelete($table, $id): bool
    {
        $this->db->query("UPDATE {$table} SET soft_delete = 0 WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function hardDelete($table, $id): bool
    {
        $this->db->query("DELETE FROM {$table} WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Simple delete - just returns true/false
    public function delete($table, $id): bool
    {
        if (!$this->exists($table, 'id', $id)) {
            return false;
        }

        return $this->softDelete($table, $id);
    }

    /* ========= COUNTERS ========= */

    public function count($table): int
    {
        $this->db->query("SELECT COUNT(*) AS total FROM {$table} WHERE soft_delete = 1");
        return (int)$this->db->single()->total;
    }

    public function countWhere($table, $column, $value): int
    {
        $this->db->query("SELECT COUNT(*) AS total FROM {$table} 
                           WHERE soft_delete = 1 AND {$column} = :val");
        $this->db->bind(':val', $value);
        return (int)$this->db->single()->total;
    }

    /* ========= CRUD OPERATIONS ========= */

    public function insert($table, $data)
    {
        $fields = array_keys($data);
        $setClause = implode(', ', array_map(fn($field) => "$field = :$field", $fields));

        $this->db->query("INSERT INTO $table SET $setClause");

        foreach ($data as $key => $value) {
            $this->db->bind(":$key", $value);
        }

        return $this->db->execute();
    }

    public function update($table, $data, $where)
    {
        $setClause = implode(', ', array_map(fn($field) => "$field = :$field", array_keys($data)));
        $whereClause = implode(' AND ', array_map(fn($field) => "$field = :where_$field", array_keys($where)));

        $this->db->query("UPDATE $table SET $setClause WHERE $whereClause");

        foreach ($data as $key => $value) {
            $this->db->bind(":$key", $value);
        }

        foreach ($where as $key => $value) {
            $this->db->bind(":where_$key", $value);
        }

        return $this->db->execute();
    }

    /* ========= DYNAMIC SELECT METHODS ========= */

    /**
     * Generic select with WHERE conditions
     */
    public function select($table, $where = [], $columns = '*', $multiple = false)
    {
        $whereClause = '';

        if (!empty($where)) {
            $conditions = array_map(fn($field) => "$field = :$field", array_keys($where));
            $whereClause = 'WHERE ' . implode(' AND ', $conditions);
        }

        $this->db->query("SELECT $columns FROM $table $whereClause");

        foreach ($where as $key => $value) {
            $this->db->bind(":$key", $value);
        }

        return $multiple ? $this->db->resultSet() : $this->db->single();
    }

    /**
     * Select with custom WHERE clause (for complex queries)
     */
    public function selectCustom($table, $whereClause, $bindings = [], $columns = '*', $multiple = false)
    {
        $this->db->query("SELECT $columns FROM $table WHERE $whereClause");

        foreach ($bindings as $key => $value) {
            $this->db->bind(":$key", $value);
        }

        return $multiple ? $this->db->resultSet() : $this->db->single();
    }

    /**
     * Select with ORDER BY
     */
    public function selectOrdered($table, $where = [], $orderBy = 'id', $direction = 'DESC', $columns = '*')
    {
        $whereClause = '';

        if (!empty($where)) {
            $conditions = array_map(fn($field) => "$field = :$field", array_keys($where));
            $whereClause = 'WHERE ' . implode(' AND ', $conditions);
        }

        $this->db->query("SELECT $columns FROM $table $whereClause ORDER BY $orderBy $direction");

        foreach ($where as $key => $value) {
            $this->db->bind(":$key", $value);
        }

        return $this->db->resultSet();
    }

    /**
     * Select with LIMIT
     */
    public function selectLimit($table, $where = [], $limit = 10, $offset = 0, $columns = '*')
    {
        $whereClause = '';

        if (!empty($where)) {
            $conditions = array_map(fn($field) => "$field = :$field", array_keys($where));
            $whereClause = 'WHERE ' . implode(' AND ', $conditions);
        }

        $this->db->query("SELECT $columns FROM $table $whereClause LIMIT $limit OFFSET $offset");

        foreach ($where as $key => $value) {
            $this->db->bind(":$key", $value);
        }

        return $this->db->resultSet();
    }

    /* ========= DATATABLES ========= */

    public function dataTable($table, $columns, $where = null, $primaryKey = 'id')
    {
        $sql_details = $this->db->dataTableSqlDetails();

        // Add soft_delete filter
        if ($where !== null) {
            $where = "soft_delete = 1 AND ($where)";
        } else {
            $where = "soft_delete = 1";
        }

        return json_encode(
            SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, $where)
        );
    }

    public function dataTableSimple($table, $columns, $primaryKey = 'id')
    {
        $sql_details = $this->db->dataTableSqlDetails();

        return json_encode(
            SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns)
        );
    }



    /* ========= DATATABLES SERVER-SIDE PROCESSING ========= */

    public function getDataTableData($table, $columns, $searchColumns, $orderColumn, $orderDir, $start, $length, $searchValue = '', $where = null)
    {
        // Base query for total records
        $totalQuery = "SELECT COUNT(*) as total FROM {$table} WHERE soft_delete = 1";
        if ($where) {
            $totalQuery .= " AND ({$where})";
        }

        $this->db->query($totalQuery);
        $totalRecords = $this->db->single()->total;

        // Build search conditions
        $searchConditions = [];
        $searchBindings = [];

        if (!empty($searchValue)) {
            foreach ($searchColumns as $index => $col) {
                $paramName = ":search_{$index}";
                $searchConditions[] = "{$col} LIKE {$paramName}";
                $searchBindings[$paramName] = '%' . $searchValue . '%';
            }
        }

        // Build filtered count query
        $filteredQuery = "SELECT COUNT(*) as total FROM {$table} WHERE soft_delete = 1";
        if ($where) {
            $filteredQuery .= " AND ({$where})";
        }
        if (!empty($searchConditions)) {
            $filteredQuery .= " AND (" . implode(' OR ', $searchConditions) . ")";
        }

        $this->db->query($filteredQuery);

        // Bind search parameters for filtered count
        foreach ($searchBindings as $param => $value) {
            $this->db->bind($param, $value);
        }

        $filteredRecords = $this->db->single()->total;

        // Build data query
        $dataQuery = "SELECT " . implode(', ', $columns) . " FROM {$table} WHERE soft_delete = 1";
        if ($where) {
            $dataQuery .= " AND ({$where})";
        }
        if (!empty($searchConditions)) {
            $dataQuery .= " AND (" . implode(' OR ', $searchConditions) . ")";
        }
        $dataQuery .= " ORDER BY {$orderColumn} {$orderDir} LIMIT :start, :length";

        $this->db->query($dataQuery);

        // Bind search parameters for data query
        foreach ($searchBindings as $param => $value) {
            $this->db->bind($param, $value);
        }

        // Bind pagination parameters
        $this->db->bind(':start', (int)$start, PDO::PARAM_INT);
        $this->db->bind(':length', (int)$length, PDO::PARAM_INT);

        $data = $this->db->resultSet();

        return [
            'totalRecords' => $totalRecords,
            'filteredRecords' => $filteredRecords,
            'data' => $data
        ];
    }

    /* ========= DATATABLES SERVER-SIDE PROCESSING WITH JOINS ========= */

    public function getDataTableDataWithJoin($query, $countQuery, $searchColumns, $orderColumn, $orderDir, $start, $length, $searchValue = '', $bindings = [])
    {
        // Get total records count
        $this->db->query($countQuery);

        // Bind any additional parameters for count query
        foreach ($bindings as $key => $value) {
            $this->db->bind($key, $value);
        }

        $totalRecords = $this->db->single()->total;

        // Build search conditions
        $searchConditions = [];
        $searchBindings = [];

        if (!empty($searchValue)) {
            foreach ($searchColumns as $index => $col) {
                $paramName = ":search_{$index}";
                $searchConditions[] = "{$col} LIKE {$paramName}";
                $searchBindings[$paramName] = '%' . $searchValue . '%';
            }
        }

        // Build filtered count query
        $filteredCountQuery = $countQuery;
        if (!empty($searchConditions)) {
            // Add search to WHERE or create WHERE clause
            if (stripos($filteredCountQuery, 'WHERE') !== false) {
                $filteredCountQuery = str_replace('WHERE', 'WHERE (' . implode(' OR ', $searchConditions) . ') AND', $filteredCountQuery);
            } else {
                $filteredCountQuery .= ' WHERE (' . implode(' OR ', $searchConditions) . ')';
            }
        }

        $this->db->query($filteredCountQuery);

        // Bind original parameters
        foreach ($bindings as $key => $value) {
            $this->db->bind($key, $value);
        }

        // Bind search parameters
        foreach ($searchBindings as $param => $value) {
            $this->db->bind($param, $value);
        }

        $filteredRecords = $this->db->single()->total;

        // Build data query with search
        $dataQuery = $query;
        if (!empty($searchConditions)) {
            if (stripos($dataQuery, 'WHERE') !== false) {
                $dataQuery = str_replace('WHERE', 'WHERE (' . implode(' OR ', $searchConditions) . ') AND', $dataQuery);
            } else {
                $dataQuery .= ' WHERE (' . implode(' OR ', $searchConditions) . ')';
            }
        }

        $dataQuery .= " ORDER BY {$orderColumn} {$orderDir} LIMIT :start, :length";

        $this->db->query($dataQuery);

        // Bind original parameters
        foreach ($bindings as $key => $value) {
            $this->db->bind($key, $value);
        }

        // Bind search parameters
        foreach ($searchBindings as $param => $value) {
            $this->db->bind($param, $value);
        }

        // Bind pagination
        $this->db->bind(':start', (int)$start, PDO::PARAM_INT);
        $this->db->bind(':length', (int)$length, PDO::PARAM_INT);

        $data = $this->db->resultSet();

        return [
            'totalRecords' => $totalRecords,
            'filteredRecords' => $filteredRecords,
            'data' => $data
        ];
    }



    /* ========= STATUS TOGGLE ========= */

    public function toggleStatus($table, $id, $column = 'user_status'): bool
    {
        // Get current status
        $record = $this->findById($table, $id);

        if (!$record) {
            return false;
        }

        // Toggle status
        $new_status = $record->$column == 1 ? 0 : 1;

        // Update
        return $this->update($table, [$column => $new_status], ['id' => $id]);
    }
}
