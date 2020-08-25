<?php


namespace Controller\PublicController;

use Core\Controller;
use Entity\Comment;
use Models\CommentManager;
use Models\PostManager;
use Models\UserManager;
use Services\SocialGenerator;


class BlogController extends Controller
{
    use SocialGenerator;

    public function executeShow()
    {
        $this->templateVars['posts'] = (new PostManager())->getPostsWithCommentsCountAndAuthorName(['dateCreated' => 'DESC'], ['count_row' => 10]);

        $this->getSocialNetworks();
        $this->render('@public/blog.html.twig');
    }

    public function executeShowPost()
    {
        $post = (new PostManager())->findOneBy(['id' => $this->params['id']]);

        if ($this->isFormSubmit('comment_newSubmit')) {
            $this->templateVars['errors'] = $this->processNewCommentForm();
        }

        $this->templateVars['post'] = $post;
        $this->templateVars['author'] = (new UserManager())->findOneBy(['id' => $post->getUserId()]);
        $this->templateVars['comments'] = (new CommentManager())->findBy(['postId' => $this->params['id'], 'visible' => 1]);
        $this->templateVars['lastPosts'] = (new CommentManager())->findAll([], ['count_row' => 5]);

        $this->getSocialNetworks();
        $this->render('@public/single-post.html.twig');
    }

    /**
     * Process the new comment form and redirect to the single post page if a new comment is created
     * @return array Return errors as an array if any
     */
    private function processNewCommentForm()
    {
        $comment = new Comment($_POST);
        $comment->hydrate(['userId' => 1, 'postId' => $this->params['id'], 'visible' => 0]); //TODO get userId from logged user

        if ($comment->isValid()) {
            (new CommentManager())->create($comment);
            $this->redirect('/blog/' . $this->params['id']);
        }

        return $comment->getConstraintsErrors();
    }
}