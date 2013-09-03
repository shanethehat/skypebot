<?php

use Inviqa\Lunch\Member;

class MemberTest extends \PHPUnit_Framework_TestCase
{
    public function testMemberImplementsResponseInterface()
    {
        $member = new Member(1, 'name', new \DateTime(), new \DateTime());
        $this->assertInstanceOf('Guzzle\Service\Command\ResponseClassInterface', $member);
    }

    public function testObjectReturnsId()
    {
        $member = new Member(1, 'name', new \DateTime(), new \DateTime());
        $this->assertEquals(1, $member->getId());
    }

    public function testObjectReturnsName()
    {
        $member = new Member(1, 'name', new \DateTime(), new \DateTime());
        $this->assertEquals('name', $member->getName());
    }

    public function testObjectReturnsShopDate()
    {
        $shopDate = new \DateTime();
        $member = new Member(1, 'name', $shopDate, new \DateTime());
        $this->assertSame($shopDate, $member->getLastShop());
    }

    public function testObjectReturnsWashDate()
    {
        $washDate = new \DateTime();
        $member = new Member(1, 'name', new \DateTime(), $washDate);
        $this->assertSame($washDate, $member->getLastWash());
    }
}
