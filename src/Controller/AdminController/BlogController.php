<?php


namespace Controller\AdminController;

use Core\Controller;
use Entity\Post;
use Entity\User;
use Models\CommentManager;
use Models\PostManager;
use Models\UserManager;


class BlogController extends Controller
{
    const ADMIN_BLOG = '/admin/blog';
    const ADMIN_COMMENTS_ALL = '/admin/comments/all';

    /**
     * Show the page displaying all posts
     */
    public function executeShow()
    {
        $this->templateVars['posts'] = (new PostManager())->getPostsWithCommentsCountAndAuthorName(['dateCreated' => 'DESC']);
        $this->render('@admin/posts_list.html.twig');
    }

    public function executeNewPost()
    {
        $post = new Post(['userId' => $this->user->getUser()->getId()]);

        if ($this->isFormSubmit('post_newSubmit')) {
            $post->hydrate($_POST);

            if ($post->isValid()) {
                (new PostManager())->create($post);
                $this->redirect(self::ADMIN_BLOG);
            }
        }

        $this->templateVars['errors'] = $post->getConstraintsErrors();
        $this->templateVars['post'] = $post->entityToArray();
        $this->render('@admin/post_new.html.twig');
    }

    public function executeEditPost()
    {
        $post = (new PostManager())->findOneBy(['id' => $this->params['id']]);

        if ($this->isFormSubmit('post_editSubmit')) {
            $post->hydrate($_POST);

            if ($post->isValid()) {
                (new PostManager())->update($post);
                $this->redirect(self::ADMIN_BLOG);
            }
        }

        $this->templateVars['errors'] =$post->getConstraintsErrors();
        $this->templateVars['post'] = $post->entityToArray();
        $this->templateVars['authors'] = (new UserManager())->findByRole(User::ROLE_ADMIN);
        $this->render('@admin/post_edit.html.twig');
    }

    public function executeDeletePost()
    {
        $post = (new PostManager())->findOneBy(['id' => $this->params['id']]);

        if ($this->isFormSubmit('post_deleteCancel')) {
            $this->redirect(self::ADMIN_BLOG);
        }

        if ($this->isFormSubmit('post_deleteSubmit')) {
            (new PostManager())->delete($post);
            $this->redirect(self::ADMIN_BLOG);
        }

        $this->templateVars['post'] = $post;
        $this->templateVars['author'] = (new UserManager())->findOneBy(['id' => $post->getUserId()]);
        $this->render('@admin/post_delete.html.twig');
    }

    public function executeListComments()
    {
        $manager = new CommentManager();
        if ($this->params['postId'] == 'all') {
            $this->templateVars['comments'] = $manager->getCommentsWithAuthorNameAndPostTitle([], ['dateCreated' => 'DESC']);
        } else {
            $this->templateVars['comments'] = $manager->getCommentsWithAuthorNameAndPostTitle(['postId' => $this->params['postId']], ['dateCreated' => 'DESC']);
        }

        $this->render('@admin/comments_list.html.twig');
    }

    public function executeEditComment()
    {
        $comment = $this->setTemplateVarsForSingleComment();

        if ($this->isFormSubmit('comment_editSubmit')) {
            $comment->hydrate($_POST);
            if ($comment->isValid()) {
                (new CommentManager())->update($comment);
                $this->redirect(self::ADMIN_COMMENTS_ALL);
            }
        }

        $this->templateVars['errors'] =$comment->getConstraintsErrors();
        $this->render('@admin/comment_edit.html.twig');
    }

    public function executeDeleteComment()
    {
        $comment = $this->setTemplateVarsForSingleComment();

        if ($this->isFormSubmit('comment_deleteCancel')) {
            $this->redirect(self::ADMIN_COMMENTS_ALL);
        }

        if ($this->isFormSubmit('comment_deleteSubmit')) {
            (new CommentManager())->delete($comment);
            $this->redirect(self::ADMIN_COMMENTS_ALL);
        }

        $this->render('@admin/comment_delete.html.twig');
    }

    private function setTemplateVarsForSingleComment()
    {
        $this->templateVars['comment'] = $comment = (new CommentManager())->findOneBy(['id' => $this->params['id']]);
        $this->templateVars['author'] = (new UserManager())->findOneBy(['id' => $comment->getUserId()]);
        $this->templateVars['post'] = (new PostManager())->findOneBy(['id' => $comment->getPostId()]);

        return $comment;
    }
}