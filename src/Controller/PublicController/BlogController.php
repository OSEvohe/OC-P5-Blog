<?php


namespace Controller\PublicController;

use Core\Controller;
use Models\PostManager;


class BlogController extends Controller
{

    public function executeShow()
    {
        $posts = (new PostManager())->getPosts(['dateCreated' => 'DESC'], ['count_row' => 10]);
        if (!empty($posts)){
            $this->templateVars['posts'] = $posts;
        }
        $this->render('@public/blog.html.twig');
    }

    public function executeShowPost(){
        $this->render('@public/single-post.html.twig');
    }
}