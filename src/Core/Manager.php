<?php


namespace Core;

use DateTime;
use PDO;
use PDOStatement;
use ReflectionClass;

abstract class Manager
{
    protected $db;
    private $tableName;

    public function __construct()
    {
        $this->db = PDOFactory::getDBConnexion();
        $this->tableName = lcfirst(str_replace('Manager', '', (new ReflectionClass($this))->getShortName()));
    }


    /**
     * @param array $order keys : fieldName<br />value : orderDirection<br />example : ["lastName" => "DESC", "firstName" => "ASC]
     * @param array $limit keys : count_row, offset<br />example ["count_row" => 5, "offset" => 2]
     * @return array
     */
    public function findAll(array $order = [], array $limit = [])
    {
        $orderClause = $limitClause = '';

        if (!empty($order)) {
            $orderClause = $this->addOrderToQuery($order);
        }
        if (!empty($limit)) {
            $limitClause = $this->addLimitToQuery($limit);
        }

        $query = $this->db->prepare("SELECT * FROM " . $this->tableName . $orderClause . $limitClause);

        if ($limitClause) {
            $this->bindArrayOfValues($query, $limit);
        }

        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, '\Entity\\' . ucfirst($this->tableName));

        return $query->fetchAll();
    }


    /**
     * @param array $where keys : field Name<br />value : value to Find<br />example : ["userId" => 1, "postId" => 3]
     * @param array $order keys : fieldName<br />value : orderDirection<br />example : ["lastName" => "DESC", "firstName" => "ASC]
     * @param array $limit keys : count_row, offset<br />example ["count_row" => 5, "offset" => 2]
     * @return array
     */
    public function findBy(array $where, array $order = [], array $limit = [])
    {
        $orderClause = $limitClause = '';
        $whereClause = $this->addWhereToQuery($where);
        if (!empty($order)){ $orderClause = $this->addOrderToQuery($order); }
        if (!empty($limit)){ $limitClause = $this->addLimitToQuery($limit); }

        $query = $this->db->prepare("SELECT * FROM " . $this->tableName . $whereClause . $orderClause . $limitClause);

        if ($whereClause) { $this->bindArrayOfValues($query, $where); }
        if ($limitClause) { $this->bindArrayOfValues($query, $limit); }

        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, '\Entity\\' . ucfirst($this->tableName));
        return $query->fetchAll();
    }


    /**
     * @param array $where keys : field Name<br />value : value to Find<br />example : ["userId" => 1, "postId" => 3]
     * @param array $order keys : fieldName<br />value : orderDirection<br />example : ["lastName" => "DESC", "firstName" => "ASC]
     * @return mixed return Entity or FALSE if no result
     */
    public function findOneBy(array $where, array $order = [])
    {
        $result = $this->findBy($where, $order, ['count_row' => 1]);
        if (!empty($result)) {
            return $result[0];
        }
        return FALSE;
    }

    /**
     * @param Entity $entity
     */
    public function delete(Entity $entity)
    {
        $query = $this->db->prepare('DELETE FROM ' . $this->tableName . ' WHERE id = :id');
        $query->bindValue(':id', $entity->getId());

        $query->execute();
    }


    /**
     * @param Entity $entity
     */
    public function update(Entity $entity)
    {
        $fields = array_keys($entity->entityToArray());
        $setClause = $this->addSetClauseToQuery($fields);

        $query = $this->db->prepare("UPDATE " . $this->tableName . $setClause . ' WHERE id = :id');
        $entity->setDateModified(new DateTime());

        $paramsToBind = [];
        foreach ($fields as $field) {
            $method = 'get' . ucfirst($field);
            $paramsToBind[$field] = $entity->$method();
        }
        $this->bindArrayOfValues($query, $paramsToBind);

        $query->execute();
    }

    /**
     * @param Entity $entity
     */
    public function create(Entity $entity)
    {
        $fields = array_keys($entity->entityToArray());
        $insertClause = $this->addInsertClauseToQuery($fields);

        $query = $this->db->prepare('INSERT INTO ' . $this->tableName . $insertClause);
        $entity->setDateCreated(new DateTime());

        $paramsToBind = [];
        foreach ($fields as $field) {
            if ($field != 'id') {
                $method = 'get' . ucfirst($field);
                $paramsToBind[$field] = $entity->$method();
            }
        }
        $this->bindArrayOfValues($query, $paramsToBind);
        $query->execute();
    }

    /**
     * @param $vars
     * @return string
     */
    protected function addInsertClauseToQuery($vars)
    {
        $fields = [];
        $values = [];
        foreach ($vars as $field) {
            if ($field != 'id') {
                $fields[] = $this->tableName . '.' . $field;
                $values[] = ':' . $field;
            }
        }
        return ' (' . implode(',', $fields) . ') VALUES (' . implode(',', $values) . ')';
    }

    /**
     * @param PDOStatement $preparedQuery
     * @param $params
     */
    protected function bindArrayOfValues(PDOStatement $preparedQuery, $params): void
    {
        foreach ($params as $field => $value) {
            if (is_int($value) || is_bool($value)) {
                $preparedQuery->bindValue(':' . $field, $value, PDO::PARAM_INT);
            } elseif (is_a($value, 'DateTime')) {
                $preparedQuery->bindValue(':' . $field, $value->format('Y-m-d H:i:s'), PDO::PARAM_STR);
            } elseif (is_array($value)) {
                $preparedQuery->bindValue(':' . $field, serialize($value), PDO::PARAM_STR);
            } else {
                $preparedQuery->bindValue(':' . $field, $value, PDO::PARAM_STR);
            }
        }
    }

    /**
     * Construct the WHERE clause
     * @param array $where
     * @return string
     */
    protected function addWhereToQuery(array $where)
    {
        $params = [];
        if (!empty($where)) {
            foreach (array_keys($where) as $field) {
                $params[] = $field . "=:" . "$field";
            }
            return " WHERE " . implode(" AND ", $params);
        }
        return '';
    }

    /**
     * Construct the ORDER BY clause
     * @param array $order
     * @return string
     */
    protected function addOrderToQuery(array $order)
    {
        $params = [];
        foreach ($order as $field => $sort) {
            if (in_array($sort, ['ASC', 'DESC'])) {
                $params[] = $field . " " . $sort;
            }
        }
        return " ORDER BY " . implode(',', $params);
    }


    /**
     * Construct the LIMIT clause
     * @param array $limit
     * @return string
     */
    protected function addLimitToQuery(array $limit)
    {
        $params = [];
        if (isset($limit['count_row']) && is_int($limit['count_row'])) {
            if (isset($limit['offset']) && is_int($limit['offset'])) {
                $params[] = ':offset';
            }
            $params[] = ':count_row';
            return " LIMIT " . implode(',', $params);
        }
        return '';
    }

    /**
     * Construct the SET clause for UPDATE
     * @param array $set
     * @return string
     */
    protected function addSetClauseToQuery(array $set)
    {
        $setClause = [];
        foreach ($set as $field) {
            if ($field != 'id') {
                $setClause[] = $this->tableName . '.' . $field . '=:' . $field;
            }
        }
        return " SET " . implode(',', $setClause);
    }
}