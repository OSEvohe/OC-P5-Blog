<?php


namespace Core;

use DateTime;
use PDO;
use PDOStatement;

class Manager
{
    protected $db;

    public function __construct()
    {
        $this->db = PDOFactory::getDBConnexion();
    }


    /**
     * @param string $table table for FROM Clause
     * @param array $order keys : fieldName<br />value : orderDirection<br />example : ["lastName" => "DESC", "firstName" => "ASC]
     * @param array $limit keys : count_row, offset<br />example ["count_row" => 5, "offset" => 2]
     * @return array
     */
    public function findAll(string $table, array $order = [], array $limit = [])
    {
        $orderClause = $this->addOrderToQuery($order);
        $limitClause = $this->addLimitToQuery($limit);

        $query = $this->db->prepare("SELECT * FROM " . $table . $orderClause . $limitClause);

        if ($limitClause) $this->bindLimitValues($query, $limit);

        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, '\Entity\\' . ucfirst($table));

        return $query->fetchAll();
    }


    /**
     * @param string $table table for FROM Clause
     * @param array $where keys : field Name<br />value : value to Find<br />example : ["userId" => 1, "postId" => 3]
     * @param array $order keys : fieldName<br />value : orderDirection<br />example : ["lastName" => "DESC", "firstName" => "ASC]
     * @param array $limit keys : count_row, offset<br />example ["count_row" => 5, "offset" => 2]
     * @return array
     */
    public function findBy(string $table, array $where, array $order = [], array $limit = [])
    {
        $whereClause = $this->addWhereToQuery($where);
        $orderClause = $this->addOrderToQuery($order);
        $limitClause = $this->addLimitToQuery($limit);

        $query = $this->db->prepare("SELECT * FROM " . $table . $whereClause . $orderClause . $limitClause);

        if ($whereClause) $this->bindArrayOfValues($query, $where);
        if ($limitClause) $this->bindArrayOfValues($query, $limit);

        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, '\Entity\\' . ucfirst($table));
        return $query->fetchAll();
    }


    /**
     * @param string $table table for FROM Clause
     * @param array $where keys : field Name<br />value : value to Find<br />example : ["userId" => 1, "postId" => 3]
     * @param array $order keys : fieldName<br />value : orderDirection<br />example : ["lastName" => "DESC", "firstName" => "ASC]
     * @return mixed return Entity or FALSE if no result
     */
    public function findOneBy(string $table, array $where, array $order = [])
    {
        $result = $this->findBy($table, $where, $order, ['count_row' => 1]);
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
        $query = $this->db->prepare('DELETE FROM ' . $this->getEntityTable($entity) . ' WHERE id = :id');
        $query->bindValue(':id', $entity->getId());

        $query->execute();
    }


    /**
     * @param Entity $entity
     */
    public function update(Entity $entity)
    {
        $fields = array_keys($entity->entityToArray());
        $setClause = $this->addSetClauseToQuery($entity, $fields);

        $query = $this->db->prepare("UPDATE " . $this->getEntityTable($entity) . $setClause . ' WHERE id = :id');
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
        $insertClause = $this->addInsertClauseToQuery($entity, $fields);

        $query = $this->db->prepare('INSERT INTO ' . $this->getEntityTable($entity) . $insertClause);
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
     * @param Entity $entity
     * @param $vars
     * @return string
     */
    protected function addInsertClauseToQuery(Entity $entity, $vars)
    {
        $fields = [];
        $values = [];
        foreach ($vars as $field) {
            if ($field != 'id') {
                $fields[] = $this->getEntityTable($entity) . '.' . $field;
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
            if (is_string($value)) {
                $preparedQuery->bindValue(':' . $field, $value, PDO::PARAM_STR);
            } elseif (is_int($value)) {
                $preparedQuery->bindValue(':' . $field, $value, PDO::PARAM_INT);
            } elseif (is_a($value, 'DateTime')) {
                $preparedQuery->bindValue(':' . $field, $value->format('Y-m-d H:i:s'), PDO::PARAM_STR);
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
        if (!empty($order)) {
            foreach ($order as $field => $sort) {
                if (in_array($sort, ['ASC', 'DESC'])) {
                    $params[] = $field . " " . $sort;
                }
            }
            return " ORDER BY " . implode(',', $params);
        }
        return '';
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
     * Get the corresponding table name from an entity
     * @param Entity $entity
     * @return string
     */
    protected function getEntityTable(Entity $entity)
    {
        return lcfirst(explode('Entity\\', get_class($entity))[1]);
    }


    /**
     * Construct the SET clause for UPDATE
     * @param array $set
     * @return string
     */
    protected function addSetClauseToQuery(Entity $entity, array $set)
    {
        $setClause = [];
        foreach ($set as $field) {
            if ($field != 'id')
                $setClause[] = $this->getEntityTable($entity) . '.' . $field . '=:' . $field;
        }
        return " SET " . implode(',', $setClause);
    }
}