<?php

use Inviqa\Command\ChatMessage\BurritoHandler;
use Inviqa\Command\ChatMessage\CreateHandler;
use Inviqa\Command\ChatMessage\DeployHandler;
use Inviqa\Command\ChatMessage\DogBoyHandler;
use Inviqa\Command\ChatMessage\InfoHandler;
use Inviqa\Command\ChatMessage\MagentoHandler;
use Inviqa\Command\ChatMessage\PingHandler;
use Inviqa\Command\ChatMessage\PlannerHandler;
use Inviqa\Command\ChatMessage\WikiHandler;
use Inviqa\Command\ChatMessageCommandHandler;
use Inviqa\Command\ChatMessage\LunchHandler;
use Inviqa\Command\UserCommandHandler;
use Inviqa\Lunch\LunchService;
use Guzzle\Http\Client;
use Inviqa\SkypeEngine;

global $n;

$engine = new SkypeEngine($n);

$engine->addCommandHandler(new UserCommandHandler());

$chatMessageHandler = new ChatMessageCommandHandler();
$engine->addCommandHandler($chatMessageHandler);

$chatMessageHandler->add(new DogBoyHandler())
    ->add(new CreateHandler())
    ->add(new PingHandler())
    ->add(new MagentoHandler())
    ->add(new BurritoHandler())
    ->add(new PlannerHandler())
    ->add(new WikiHandler())
    ->add(new DeployHandler())
    ->add(new InfoHandler())
    ->add(new LunchHandler(LunchService::factory()));

return $engine;
