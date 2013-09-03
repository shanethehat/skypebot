<?php

namespace Inviqa\Lunch;

use Guzzle\Service\Client;
use Guzzle\Common\Collection;
use Guzzle\Service\Description\ServiceDescription;

class LunchService extends Client implements LunchServiceInterface
{
    /**
     * Factory method to create a new MyServiceClient
     *
     * The following array keys and values are available options:
     *   - base_url: Base URL of web service
     *   - scheme: URI scheme: http or https
     *
     * @param array|Collection $config Configuration data
     *
     * @return self
     */
    public static function factory($config = array())
    {
        $default = array(
            'base_url' => '{scheme}://apps.inviqa.com/sheffield-lunch',
            'scheme' => 'http'
        );

        $required = array('base_url');

        $config = Collection::fromConfig($config, $default, $required);

        $client = new self($config->get('base_url'), $config);

        $description = ServiceDescription::factory(__DIR__ . '/lunch-service.json');
        $client->setDescription($description);

        return $client;
    }

    public function getCurrentShopper()
    {
        return $this->executeCommand('getCurrentShopper');
    }

    public function getCurrentWasher()
    {
        return $this->executeCommand('getCurrentWasher');
    }

    public function getNextShopper()
    {
        $this->executeCommand('getNextShopper');
        return $this->getCurrentShopper();
    }

    public function getNextWasher()
    {
        $this->executeCommand('getNextWasher');
        return $this->getCurrentWasher();
    }

    protected function executeCommand($commandName)
    {
        $command = $this->getCommand($commandName);
        return $this->execute($command);
    }
}
