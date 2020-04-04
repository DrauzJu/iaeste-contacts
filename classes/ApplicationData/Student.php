<?php
class Student {
    private $id;
    private $name;
    private $scores;

    public function __construct($id, $name) {
        $this->setId($id);
        $this->setName($name);

        $this->scores = array();
    }

    public function addScore(Score $score) {
        $this->scores["C".$score->getCriterion()->getId()] = $score;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getScores(): array
    {
        return $this->scores;
    }
}
