<?php


namespace Controller\AdminController;

use Core\Controller;
use Entity\User;
use Models\PostManager;
use Models\UserManager;


class BlogController extends Controller
{

    public function executeShow()
    {
        $this->templateVars['posts'] = (new PostManager())->getPosts(['dateCreated' => 'DESC']);
        $this->render('@admin/posts_list.html.twig');
    }

    public function executeNewPost()
    {
        $this->render('@admin/post_new.html.twig');
    }

    public function executeEditPost()
    {
        $post = (new PostManager())->findOneBy('post', ['id' => $this->params['id']]);
        $this->templateVars['post'] = $post;
        $this->templateVars['authors'] = (new UserManager())->findByRole(User::ROLE_GUEST);
        $this->render('@admin/post_edit.html.twig');
    }

    public function executeDeletePost()
    {
        $post = (new PostManager())->findOneBy('post', ['id' => $this->params['id']]);
        $this->templateVars['post'] = $post;
        $this->templateVars['author'] = (new UserManager())->findOneBy('user', ['id' => $post->getId()]);
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