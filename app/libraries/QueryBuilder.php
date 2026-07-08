<?php

class QueryBuilder
{
    private $db;
    private $table;
    private $type = 'select'; // select, insert, update, delete
    private $select = '*';
    private $where = [];
    private $bindings = [];
    private $orderBy = '';
    private $limit = '';
    private $data = [];

    public function __construct($db, $table)
    {
        $this->db = $db;
        $this->table = $table;
    }

    /* ========= SELECT ========= */

    public function select($columns = '*')
    {
        $this->type = 'select';
        $this->select = $columns;
        return $this;
    }

    public function where($column, $value, $operator = '=')
    {
        $placeholder = ':' . str_replace('.', '_', $column) . count($this->where);
        $this->where[] = "$column $operator $placeholder";
        $this->bindings[$placeholder] = $value;
        return $this;
    }

    public function whereIn($column, array $values)
    {
        $placeholders = [];
        foreach ($values as $i => $value) {
            $placeholder = ":{$column}_in_{$i}";
            $placeholders[] = $placeholder;
            $this->bindings[$placeholder] = $value;
        }
        $this->where[] = "$column IN (" . implode(',', $placeholders) . ")";
        return $this;
    }

    public function whereLike($column, $value)
    {
        return $this->where($column, $value, 'LIKE');
    }

    public function whereNull($column)
    {
        $this->where[] = "$column IS NULL";
        return $this;
    }

    public function whereNotNull($column)
    {
        $this->where[] = "$column IS NOT NULL";
        return $this;
    }

    public function orderBy($column, $direction = 'ASC')
    {
        $this->orderBy = "ORDER BY $column $direction";
        return $this;
    }

    public function limit($limit, $offset = 0)
    {
        $this->limit = "LIMIT $limit" . ($offset > 0 ? " OFFSET $offset" : "");
        return $this;
    }

    public function get()
    {
        $whereClause = !empty($this->where) ? 'WHERE ' . implode(' AND ', $this->where) : '';
        $sql = "SELECT {$this->select} FROM {$this->table} $whereClause {$this->orderBy} {$this->limit}";

        $this->db->query($sql);

        foreach ($this->bindings as $key => $value) {
            $this->db->bind($key, $value);
        }

        return $this->db->resultSet();
    }

    public function first()
    {
        $this->limit(1);
        $results = $this->get();
        return $results[0] ?? null;
    }

    public function count()
    {
        $whereClause = !empty($this->where) ? 'WHERE ' . implode(' AND ', $this->where) : '';
        $sql = "SELECT COUNT(*) as total FROM {$this->table} $whereClause";

        $this->db->query($sql);

        foreach ($this->bindings as $key => $value) {
            $this->db->bind($key, $value);
        }

        $result = $this->db->single();
        return (int)$result->total;
    }

    /* ========= INSERT ========= */

    public function insert(array $data)
    {
        $fields = array_keys($data);
        $setClause = implode(', ', array_map(fn($field) => "$field = :$field", $fields));

        $sql = "INSERT INTO {$this->table} SET $setClause";

        $this->db->query($sql);

        foreach ($data as $key => $value) {
            $this->db->bind(":$key", $value);
        }

        return $this->db->execute();
    }

    /* ========= UPDATE ========= */

    public function update(array $data)
    {
        $this->data = $data;
        $this->type = 'update';
        return $this;
    }

    public function save()
    {
        if ($this->type !== 'update') {
            throw new Exception("save() can only be used after update()");
        }

        if (empty($this->data)) {
            throw new Exception("No data to update");
        }

        if (empty($this->where)) {
            throw new Exception("UPDATE requires WHERE clause for safety");
        }

        $setClause = implode(', ', array_map(fn($field) => "$field = :set_$field", array_keys($this->data)));
        $whereClause = implode(' AND ', $this->where);

        $sql = "UPDATE {$this->table} SET $setClause WHERE $whereClause";

        $this->db->query($sql);

        // Bind update data
        foreach ($this->data as $key => $value) {
            $this->db->bind(":set_$key", $value);
        }

        // Bind where conditions
        foreach ($this->bindings as $key => $value) {
            $this->db->bind($key, $value);
        }

        return $this->db->execute();
    }

    /* ========= DELETE ========= */

    public function delete()
    {
        if (empty($this->where)) {
            throw new Exception("DELETE requires WHERE clause for safety");
        }

        $whereClause = implode(' AND ', $this->where);
        $sql = "DELETE FROM {$this->table} WHERE $whereClause";

        $this->db->query($sql);

        foreach ($this->bindings as $key => $value) {
            $this->db->bind($key, $value);
        }

        return $this->db->execute();
    }

    /* ========= SOFT DELETE ========= */

    public function softDelete()
    {
        return $this->update(['soft_delete' => 0])->save();
    }
}
