<?php

namespace Inviqa\Command\ChatMessage;

use Inviqa\SkypeCommandInterface;
use Inviqa\Lunch\LunchServiceInterface;

class LunchHandler extends AbstractHandler implements ChatMessageHandlerInterface
{
    /**
     * @var \Inviqa\Lunch\LunchServiceInterface
     */
    protected $lunchService;

    /**
     * @var \Inviqa\Lunch\Member
     */
    protected $shopper;

    /**
     * @var \Inviqa\Lunch\Member
     */
    protected $washer;

    public function __construct(LunchServiceInterface $lunchService)
    {
        $this->lunchService = $lunchService;
    }

    public function handle(SkypeCommandInterface $chatname, SkypeCommandInterface $handle, SkypeCommandInterface $body)
    {
        if (!$this->firstWordIs(':lunch', $body->getValue())) {
            return;
        }

        if (!$this->isLunchChannel($chatname->getValue())) {
            $this->engine->invoke("CHATMESSAGE {$chatname->getValue()} This is not a lunch channel.");
            return true;
        }

        $args = explode(' ', $body->getValue());

        if (count($args) === 1) {
            $this->engine->invoke("CHATMESSAGE {$chatname->getValue()} " . $this->buildFullMessage());
            return true;
        }

        if (count($args) === 2) {
            if ('help' === $args[1]) {
                $this->engine->invoke("CHATMESSAGE {$chatname->getValue()} " . $this->buildHelpMessage());
                return true;
            }
        }

        if (count($args) === 3) {
            if ('next' === $args[1]) {
                switch ($arg[2]) {
                    case 'washer':
                        $this->getNextWasher();
                        $this->engine->invoke("CHATMESSAGE {$chatname->getValue()} " . $this->buildFullMessage());
                        return true;
                    case 'shopper':
                        $this->getNextShopper();
                        $this->engine->invoke("CHATMESSAGE {$chatname->getValue()} " . $this->buildFullMessage());
                        return true;
                }
            }
        }
    }

    protected function buildFullMessage()
    {
        return sprintf(
            "\nShopper: %s\nWasher: %s",
            $this->getShopper()->getName(),
            $this->getWasher()->getName()
        );
    }

    protected function buildHelpMessage()
    {
        return "\n"
            . ":lunch - displays the current shopper and washer\n"
            . ":lunch help - displays this message\n"
            . ":lunch next shopper - gets the next shopper\n"
            . ":lunch next washer - gets the next washer";
    }

    protected function getShopper()
    {
        if (!$this->shopper) {
            $this->shopper = $this->lunchService->getCurrentShopper();
        }
        return $this->shopper;
    }

    protected function getWasher()
    {
        if (!$this->washer) {
            $this->washer = $this->lunchService->getCurrentWasher();
        }
        return $this->washer;
    }

    protected function getNextShopper()
    {
        $this->shopper = $this->lunchService->getNextShopper();
    }

    protected function getNextWasher()
    {
        $this->washer = $this->lunchService->getNextWasher();
    }
}
