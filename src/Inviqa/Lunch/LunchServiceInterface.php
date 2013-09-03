<?php

namespace Inviqa\Lunch;

interface LunchServiceInterface
{
    public function getCurrentShopper();

    public function getCurrentWasher();

    public function getNextShopper();

    public function getNextWasher();
}
