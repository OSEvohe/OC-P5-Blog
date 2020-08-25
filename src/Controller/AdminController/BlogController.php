<?php


namespace Controller\AdminController;

use Core\Controller;
use Entity\Post;
use Entity\User;
use Core\DataValidator;
use Models\CommentManager;
use Models\PostManager;
use Models\UserManager;


class BlogController extends Controller
{

    public function executeShow()
    {
        $this->templateVars['posts'] = (new PostManager())->getPostsWithCommentsCountAndAuthorName(['dateCreated' => 'DESC'], [], true );
        $this->render('@admin/posts_list.html.twig');
    }

    public function executeNewPost()
    {
        $post = new Post(['userId' => 1]); // TODO : Get from logged user

        if ($this->isFormSubmit('post_newSubmit')) {
            $post->hydrate($_POST);

            if ($post->isValid()) {
                (new PostManager())->create($post);
                $this->redirect('/admin/blog');
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
                $this->redirect('/admin/blog');
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
            $this->redirect('/admin/blog');
        }

        if ($this->isFormSubmit('post_deleteSubmit')) {
            (new PostManager())->delete($post);
            $this->redirect('/admin/blog');
        }

        $this->templateVars['post'] = $post;
        $this->templateVars['author'] = (new UserManager())->findOneBy(['id' => $post->getUserId()]);
        $this->render('@admin/post_delete.html.twig');
    }

    public function executeListComments()
    {
        $manager = new CommentManager();
        if ($this->params['postId'] == 'all') {
            $this->templateVars['comments'] = $manager->getCommentsWithAuthorNameAndPostTitle();
        } else {
            $this->templateVars['comments'] = $manager->getCommentsWithAuthorNameAndPostTitle(['postId' => $this->params['postId']]);
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
                $this->redirect('/admin/comments/all');
            }
        }

        $this->templateVars['errors'] =$comment->getConstraintsErrors();
        $this->render('@admin/comment_edit.html.twig');
    }

    public function executeDeleteComment()
    {
        $comment = $this->setTemplateVarsForSingleComment();

        if ($this->isFormSubmit('comment_deleteCancel')) {
            $this->redirect('/admin/comments/all');
        }

        if ($this->isFormSubmit('comment_deleteSubmit')) {
            (new CommentManager())->delete($comment);
            $this->redirect('/admin/comments/all');
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