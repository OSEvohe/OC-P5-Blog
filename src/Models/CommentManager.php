<?php


namespace Models;


use PDO;

class CommentManager extends \Core\Manager
{

    public function getCommentsWithAuthorNameAndPostTitle(array $where = [], array $order = [], array $limit = [])
    {
        $orderClause = '';
        $limitClause = '';
        $whereClause = '';

        if (!empty($where)) {
            $whereClause = $this->addWhereToQuery($where);
        }
        if (!empty($order)) {
            $orderClause = $this->addOrderToQuery($order);
        }
        if (!empty($limit)) {
            $limitClause = $this->addLimitToQuery($limit);
        }

        $queryStr = "   SELECT comment.*, `user`.id, `user`.displayName, post.title as postTitle FROM comment 
                        INNER JOIN post ON post.id = comment.postId INNER JOIN `user` ON `user`.id = comment.userId";
        $query = $this->db->prepare($queryStr . $whereClause . $orderClause . $limitClause);

        if ($whereClause) {
            $this->bindArrayOfValues($query, $where);
        }
        if ($limitClause) {
            $this->bindArrayOfValues($query, $limit);
        }

        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, '\Entity\\Comment');
        return $query->fetchAll();
    }
}