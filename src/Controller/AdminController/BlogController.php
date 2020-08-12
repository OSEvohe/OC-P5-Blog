<?php


namespace Controller\AdminController;

use Core\Controller;
use Entity\User;
use Models\CommentManager;
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
        $this->templateVars['authors'] = (new UserManager())->findByRole(User::ROLE_ADMIN);
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
        $manager = new CommentManager();
        if ($this->params['postId'] == 'all') {
            $this->templateVars['comments'] = $manager->getComments();
        } else {
            $this->templateVars['comments'] = $manager->getComments(['postId' => $this->params['postId']]);
        }

        $this->render('@admin/comments_list.html.twig');
    }

    public function executeEditComment()
    {
        $this->setTemplateVarsForSingleComment();
        $this->render('@admin/comment_edit.html.twig');
    }

    public function executeDeleteComment()
    {
        $this->setTemplateVarsForSingleComment();
        $this->render('@admin/comment_delete.html.twig');
    }

    private function setTemplateVarsForSingleComment()
    {
        $this->templateVars['comment'] = $comment = (new CommentManager())->findOneBy('comment', ['id' => $this->params['id']]);
        $this->templateVars['author'] = (new UserManager())->findOneBy('user', ['id' => $comment->getUserId()]);
        $this->templateVars['post'] = (new PostManager())->findOneBy('post', ['id' => $comment->getPostId()]);

        return $comment;
    }
}