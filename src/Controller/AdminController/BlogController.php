<?php


namespace Controller\AdminController;

use Core\Controller;


class BlogController extends Controller
{

    public function executeShow()
    {
        $this->render('@admin/posts_list.html.twig');
    }

    public function executeNewPost()
    {
        $this->render('@admin/post_new.html.twig');
    }

    public function executeEditPost()
    {
        $this->render('@admin/post_edit.html.twig');
    }

    public function executeDeletePost()
    {
        $this->render('@admin/post_delete.html.twig');
    }

    public function executeListComments()
    {
        $this->render('@admin/comments_list.html.twig');
    }

    public function executeEditComment(){
        $this->render('@admin/comment_edit.html.twig');
    }

    public function executeDeleteComment(){
        $this->render('@admin/comment_delete.html.twig');
    }
}