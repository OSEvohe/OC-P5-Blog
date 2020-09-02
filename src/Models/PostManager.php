<?php


namespace Models;


use PDO;

class PostManager extends \Core\Manager
{
    public function getPostsWithCommentsCountAndAuthorName(array $order = [], array $limit = [])
    {
        $orderClause = '';
        $limitClause = '';

        if (!empty($order)) {
            $orderClause = $this->addOrderToQuery($order);
        }
        if (!empty($limit)) {
            $limitClause = $this->addLimitToQuery($limit);
        }

        $queryStr = "   SELECT `post`.*, `user`.displayName,count(comment.id) AS comment_count
                        FROM `post` LEFT OUTER JOIN `comment` ON `post`.id = `comment`.postId
                        INNER JOIN `user` ON `post`.userId = `user`.id";

        $queryStr .= " GROUP BY post.id";

        $query = $this->db->prepare($queryStr . $orderClause . $limitClause);

        if ($limitClause) {
            $this->bindArrayOfValues($query, $limit);
        }

        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, '\Entity\\Post');

        return $query->fetchAll();
    }
}