<?php

class SkypeEngineTest extends PHPUnit_Framework_TestCase
{
	private function expectSkype(DbusObject $dbus, $i, $req, $res)
	{
		$dbus->expects($this->at($i))->method('Invoke')->with($req)->will($this->returnValue($res));
		return $dbus;
	}

    public function testWelcomeMessageIsSentAfterFriendRequestAccepted()
    {
    	// mock the dbus object so we can simulate the linux skype client
    	$dbus = $this->getMock('DbusObject', array('Invoke'));
    	$this->expectSkype($dbus, 0, $this->equalTo('CHAT CREATE xyz,xyz'), 'CHAT xyz NAME');
        $this->expectSkype($dbus, 1, $this->stringStartsWith('CHATMESSAGE xyz'), 'CHATMESSAGE 1234 STATUS RECEIVED');

       	// the engine is not expected to parse the received message so we're expecting an exception
		$this->setExpectedException('\Inviqa\Exception\UnexpectedCommand', 'CHATMESSAGE 1234 STATUS RECEIVED');

        $e = new \Inviqa\SkypeEngine($dbus);
        $e->parse('USER xyz BUDDYSTATUS 3'); // skype api friend status update
    }
}
