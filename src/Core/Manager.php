<?php


namespace Core;

use PDO;

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
        $query = $this->db->prepare("SELECT * FROM " . $table . $this->addOrderToQuery($order) . $this->addLimitToQuery($limit));
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
        $query = $this->db->prepare("SELECT * FROM " . $table . $this->addWhereToQuery($where) . $this->addOrderToQuery($order) . $this->addLimitToQuery($limit));
        foreach ($where as $field => $value) {
            $query->bindValue(':' . $field, $value);
        }
        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, '\Entity\\' . ucfirst($table));

        return $query->fetchAll();
    }


    /**
     * @param string $table table for FROM Clause
     * @param array $where keys : field Name<br />value : value to Find<br />example : ["userId" => 1, "postId" => 3]
     * @param array $order keys : fieldName<br />value : orderDirection<br />example : ["lastName" => "DESC", "firstName" => "ASC]
     * @return Entity return Entity or FALSE if no result
     */
    public function findOneBy(string $table, array $where, array $order = [])
    {
        $result = $this->findBy($table, $where, $order, ['count_row' => 1]);
        if (!empty($result)){
            return $result[0];
        }
        return FALSE;
    }


    public function update(Entity $entity){
        $table = lcfirst(explode('Entity\\', get_class($entity))[1]);

        $query = "Update ".$table. " SET ";
        foreach ($entity as $attr => $value){
            if ($attr != 'id') {
                $query .= $attr . " = :" . $attr . " ";
            }
        }
        $query .= "Where id = :id";
        echo $query;
    }

    public function delete(){

    }

    public function create(){

    }


    /**
     * @param array $where
     * @return string
     */
    protected function addWhereToQuery(array $where)
    {
        $query = '';
        if (!empty($where)) {
            $query = ' WHERE 1 ';
            foreach (array_keys($where) as $field) {
                $query .= "AND " . $field . "=:" . "$field";
            }
        }
        return $query;
    }

    /**
     * @param array $order
     * @return string
     */
    protected function addOrderToQuery(array $order)
    {
        $query = '';
        if (!empty($order)) {
            $query .= " ORDER BY";
            foreach ($order as $field => $sort) {
                if (in_array($sort, ['ASC', 'DESC'])) {
                    $query .= " " . $field . " " . $sort;
                } else {
                    return '';
                }
            }
        }
        return $query;
    }


    /**
     * @param array $limit
     * @return string
     */
    protected function addLimitToQuery(array $limit)
    {
        $query = '';
        if (isset($limit['count_row']) && is_int($limit['count_row'])) {
            $query .= " LIMIT ";
            if (isset($limit['offset']) && is_int($limit['offset'])) {
                $query .= $limit['offset'] . ",";
            }
            $query .= $limit['count_row'];
        }
        return $query;
    }
}