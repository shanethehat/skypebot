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
        $member = new Member(
            1,
            'Ben Longden',
            \DateTime::createFromFormat('Ymd', '20130820'),
            \DateTime::createFromFormat('Ymd', '20130817')
        );

        $service = LunchService::factory();
        $this->setMockResponse($service, 'mock/get-shopper-response');
        $result = $service->getCurrentShopper();
        $this->assertEquals($member, $result);
    }

    public function testServiceRespondsToGetWasher()
    {
        $member = new Member(
            2,
            'Shane Auckland',
            \DateTime::createFromFormat('Ymd', '20130811'),
            \DateTime::createFromFormat('Ymd', '20130809')
        );

        $service = LunchService::factory();
        $this->setMockResponse($service, 'mock/get-washer-response');
        $result = $service->getCurrentWasher();
        $this->assertEquals($member, $result);
    }

    public function testServiceRespondsToGetNextShopper()
    {
        $member = new Member(
            1,
            'Ben Longden',
            \DateTime::createFromFormat('Ymd', '20130820'),
            \DateTime::createFromFormat('Ymd', '20130817')
        );

        $service = LunchService::factory();
        $this->setMockResponse($service, array(
            'mock/next-shopper-response',
            'mock/get-shopper-response'
        ));
        $result = $service->getNextShopper();
        $this->assertEquals($member, $result);
    }

    public function testServiceRespondsToGetNextWasher()
    {
        $member = new Member(
            2,
            'Shane Auckland',
            \DateTime::createFromFormat('Ymd', '20130811'),
            \DateTime::createFromFormat('Ymd', '20130809')
        );

        $service = LunchService::factory();
        $this->setMockResponse($service, array(
            'mock/next-washer-response',
            'mock/get-washer-response'
        ));
        $result = $service->getNextWasher();
        $this->assertEquals($member, $result);
    }
}