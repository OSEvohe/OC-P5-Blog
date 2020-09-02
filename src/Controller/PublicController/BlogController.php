<?php


namespace Controller\PublicController;

use Core\Controller;
use Entity\Comment;
use Entity\User;
use Models\CommentManager;
use Models\PostManager;
use Models\UserManager;
use Services\SocialGenerator;


class BlogController extends Controller
{
    use SocialGenerator;

    const POSTS_PER_PAGE = 6;


    /**
     * Show the Blog page listing the lasts 10 posts
     */
    public function executeShow()
    {
        $pagination = $this->createPagination();

        $this->templateVars['posts'] = (new PostManager())->getPostsWithCommentsCountAndAuthorName(['dateCreated' => 'DESC'], $pagination);
        $this->getSocialNetworks();
        $this->render('@public/blog.html.twig');
    }


    /**
     * Show a single post with its comments
     */
    public function executeShowPost()
    {
        $post = (new PostManager())->findOneBy(['id' => $this->params['id']]);

        if ($this->isFormSubmit('comment_newSubmit')) {
            $this->templateVars['errors'] = $this->processNewCommentForm();
        }

        $this->templateVars['allowComment'] = (string)($this->user->getUser()->hasRole(User::ROLE_MEMBER) || $this->user->getUser()->hasRole(User::ROLE_ADMIN));

        $this->templateVars['post'] = $post;
        $this->templateVars['author'] = (new UserManager())->findOneBy(['id' => $post->getUserId()]);
        $this->templateVars['comments'] = (new CommentManager())->findBy(['postId' => $this->params['id'], 'visible' => 1]);
        $this->templateVars['lastPosts'] = (new PostManager())->findAll([], ['count_row' => 5]);

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
        $comment->hydrate(['userId' => $this->user->getUser()->getId(), 'postId' => $this->params['id'], 'visible' => 0]);

        if ($comment->isValid()) {
            (new CommentManager())->create($comment);
            $this->redirect('/blog/' . $this->params['id']);
        }

        return $comment->getConstraintsErrors();
    }

    /**
     * @return array|int[]
     */
    private function createPagination(): array
    {
        $pagination['count_row'] = self::POSTS_PER_PAGE;

        if (isset($this->params['page']) && $this->params['page']) {
            $offset = ((int)$this->params['page'] - 1) * self::POSTS_PER_PAGE;
            $this->templateVars['pageCurrent'] = $this->params['page'];
            $pagination['offset'] = $offset;
        }

        $nb_post = count((new PostManager())->findAll());
        $this->templateVars['pagesCount'] = (int)($nb_post / self::POSTS_PER_PAGE) + 1;
        return $pagination;
    }
}