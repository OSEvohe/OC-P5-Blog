<?php


namespace Services;


use Models\SocialNetworkManager;

trait SocialGenerator
{
    public function getSocialNetworks()
    {
        $this->templateVars['socialNetworks'] = (new SocialNetworkManager())->findAll(['name' => 'ASC']);
    }
}