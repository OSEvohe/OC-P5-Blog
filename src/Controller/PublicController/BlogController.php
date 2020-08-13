<?php


namespace Controller\PublicController;

use Core\Controller;
use Models\CommentManager;
use Models\PostManager;
use Models\UserManager;
use Services\SocialGenerator;


class BlogController extends Controller
{
    use SocialGenerator;

    public function executeShow()
    {
        $this->templateVars['posts'] = (new PostManager())->getPosts(['dateCreated' => 'DESC'], ['count_row' => 10]);

        $this->getSocialNetworks();
        $this->render('@public/blog.html.twig');
    }

    public function executeShowPost()
    {
        $post = (new PostManager())->findOneBy('post', ['id' => $this->params['id']]);
        $this->templateVars['post'] = $post;

        $this->templateVars['author'] = (new UserManager())->findOneBy('user', ['id' => $post->getUserId()]);
        $this->templateVars['comments'] = (new CommentManager())->findBy('comment', ['postId' => $this->params['id']]);
        $this->templateVars['lastPosts'] = (new CommentManager())->findAll('post', [], ['count_row' => 5]);

        $this->getSocialNetworks();
        $this->render('@public/single-post.html.twig');
    }
}