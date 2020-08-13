<?php


namespace Services;


use Models\SocialNetworkManager;

trait SocialGenerator
{
    public function getSocialNetworks()
    {
        $this->templateVars['socialNetworks'] = (new SocialNetworkManager())->findAll('socialNetwork',['name' => 'ASC']);
    }
}