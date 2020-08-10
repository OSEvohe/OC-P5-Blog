<?php


namespace Core;

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

        if ($whereClause) $this->bindWhereValues($query, $where);
        if ($limitClause) $this->bindLimitValues($query, $limit);

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

    public function delete(string $table, int $id)
    {

    }


    public function update($entity)
    {

    }

    public function create()
    {

    }


    /** ------------- HELPERS -------------- */


    protected function bindWhereValues(PDOStatement $preparedQuery, $where): void
    {
        foreach ($where as $field => $value) {
            $preparedQuery->bindValue(':' . $field, $value);
        }
    }

    protected function bindLimitValues(PDOStatement $preparedQuery, $limit): void
    {
        foreach ($limit as $param => $value) {
            $preparedQuery->bindValue(':' . $param, (int)$value, PDO::PARAM_INT);
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
                } else {
                    return '';
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
}