<?php

namespace Inviqa\Lunch;

class Member
{
    protected $id;
    protected $name;
    protected $lastShop;
    protected $lastWash;

    public function __construct($id, $name, \DateTime $lastShop, \DateTime $lastWash) {
        $this->id = $id;
        $this->name = $name;
        $this->lastShop = $lastShop;
        $this->lastWash = $lastWash;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getLastShop()
    {
        return $this->lastShop;
    }

    /**
     * @return \DateTime
     */
    public function getLastWash()
    {
        return $this->lastWash;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
