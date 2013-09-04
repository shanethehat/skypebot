<?php

namespace Inviqa\Lunch;

interface MemberInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return \DateTime
     */
    public function getLastShop();

    /**
     * @return \DateTime
     */
    public function getLastWash();

    /**
     * @return string
     */
    public function getName();
}
