<?php

namespace Inviqa\Lunch;

use Guzzle\Service\Command\OperationCommand;
use Guzzle\Service\Command\ResponseClassInterface;

class Member implements ResponseClassInterface, MemberInterface
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

    /**
     * Create a response model object from a completed command
     *
     * @param OperationCommand $command That serialized the request
     *
     * @return self
     */
    public static function fromCommand(OperationCommand $command)
    {
        $responseJson = $command->getResponse()->json();

        return new self(
            $responseJson['id'],
            $responseJson['name'],
            \DateTime::createFromFormat('Ymd', $responseJson['last_shop']),
            \DateTime::createFromFormat('Ymd', $responseJson['last_wash'])
        );
    }
}
