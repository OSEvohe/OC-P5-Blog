<?php


namespace Entity;

use Core\ConstraintableEntity;
use Core\Entity;
use Core\DataValidator;

class Post extends Entity
{
    use ConstraintableEntity;

    /** @var string */
    protected $content;
    /** @var string */
    protected $title;
    /** @var string */
    protected $lead;
    /** @var int */
    protected $userId;


    /**
     * Profile constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->addConstraints([
            'title' => [[
                'filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => '/^.{4,60}$/u'],
                'msg' => 'Titre invalide, 4 à 60 caractère autorisés'
            ]],
            'content' => [[
                'filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => '/^.{10,}$/u'],
                'msg' => 'Texte du contenu trop court, 10 caractères minimum'
            ]],
            'lead' => [[
                'filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => '/^.{10,200}$/u'],
                'msg' => 'Chapô invalide, 10 à 200 caractères alphanumériques autorisés'
            ]],
            'userId' => [[
                'filter' => FILTER_VALIDATE_INT,
                'msg' => 'Identifiant de l\'utilisateur invalide'
            ]]
        ]);
    }


    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getLead(): string
    {
        return $this->lead;
    }

    /**
     * @param string $lead
     */
    public function setLead(string $lead): void
    {
        $this->lead = $lead;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }
}