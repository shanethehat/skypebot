<?php

class SkypeEngineTest extends PHPUnit_Framework_TestCase
{
    private function expectSkype(DbusObject $dbus, $i, $req, $res='')
    {
        $dbus->expects($this->at($i))->method('Invoke')->with($req)->will($this->returnValue($res));
        return $dbus;
    }

    public function testWelcomeMessageIsSentAfterFriendRequestAccepted()
    {
        // mock the dbus object so we can simulate the linux skype client
        $dbus = $this->getMock('DbusObject', array('Invoke'));
        $this->expectSkype($dbus, 0, $this->equalTo('CHAT CREATE xyz,xyz'), 'CHAT xyz NAME');
        $this->expectSkype($dbus, 1, $this->stringStartsWith('CHATMESSAGE xyz'));
        $this->expectSkype($dbus, 2, $this->stringStartsWith('ALTER CHATMEMBER xyz SETROLETO MASTER'));

        $e = new \Inviqa\SkypeEngine($dbus);
        $e->parse('USER xyz BUDDYSTATUS 3'); // skype api friend status update to accepted
    }

    public function testChatRoomCreatedOnCommand()
    {
        $dbus = $this->getMock('DbusObject', array('Invoke'));
        $e = new \Inviqa\SkypeEngine($dbus);
        $e->parse('USER xyz BUDDYSTATUS 3'); // skype api friend status update to accepted
    }
}
