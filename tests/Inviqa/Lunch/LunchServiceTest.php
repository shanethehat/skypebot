<?php

use Inviqa\Lunch\LunchService;
use Inviqa\Lunch\Member;
use Guzzle\Tests\GuzzleTestCase;

class LunchServiceTest extends GuzzleTestCase
{
    public function setUp()
    {
        $this->setMockBasePath(__DIR__);
    }

    public function testServiceRespondsToGetShopper()
    {
        $service = LunchService::factory();
        $this->setMockResponse($service, array(
            'mock/get-shopper-redirect-response',
            'mock/get-shopper-response'
        ));
        $result = $service->getCurrentShopper();
        $this->assertEquals(
            new Member(
                1,
                'Ben Longden',
                \DateTime::createFromFormat('Ymd', '20130820'),
                \DateTime::createFromFormat('Ymd', '20130817')
            ),
            $result
        );
    }

    public function testServiceRespondsToGetWasher()
    {
        $service = LunchService::factory();
        $this->setMockResponse($service, array(
            'mock/get-washer-response',
            'mock/get-washer-redirect-response',
        ));
        $result = $service->getCurrentWasher();
        $this->assertEquals(
            new Member(
                2,
                'Shane Auckland',
                \DateTime::createFromFormat('Ymd', '20130811'),
                \DateTime::createFromFormat('Ymd', '20130809')
            ),
            $result
        );
    }

    public function testServiceRespondsToGetNextShopper()
    {
        $service = LunchService::factory();
        $this->setMockResponse($service, array(
            'mock/next-shopper-response',
            'mock/get-shopper-response'
        ));
        $result = $service->getNextShopper();
        $this->assertEquals(
            new Member(
                1,
                'Ben Longden',
                \DateTime::createFromFormat('Ymd', '20130820'),
                \DateTime::createFromFormat('Ymd', '20130817')
            ),
            $result
        );
    }

    public function testServiceRespondsToGetNextWasher()
    {
        $service = LunchService::factory();
        $this->setMockResponse($service, array(
            'mock/next-washer-response',
            'mock/get-washer-response'
        ));
        $result = $service->getNextWasher();
        $this->assertEquals(
            new Member(
                2,
                'Shane Auckland',
                \DateTime::createFromFormat('Ymd', '20130811'),
                \DateTime::createFromFormat('Ymd', '20130809')
            ),
            $result
        );
    }
}
