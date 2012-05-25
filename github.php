<?php

$message = array();
if (isset($_REQUEST['payload'])) {
    $payload = json_decode($_REQUEST['payload']);

    if ($payload) {
        foreach ($payload->commits as $commit) {
            $lines = explode("\n", $commit->message);
            $message[] = '- ' . $lines[0] . ' ('.substr($commit->id, 0, 6).')';
        }

        $info = sprintf(
            "%s pushed to %s at %s/%s.\n%s",
            $payload->head_commit->committer->name,
            $payload->ref,
            $payload->repository->organization,
            $payload->repository->name,
            join("\n", $message)
        );
    }
} else {
    $info = "I received an invalid request from someone (not GitHub?). If this continues, something is broken!";
}

$n = (new Dbus( Dbus::BUS_SESSION, true ))->createProxy( "com.Skype.API", "/com/Skype", "com.Skype.API");
$n->Invoke( "NAME PHP" );
$n->Invoke( "PROTOCOL 7" );
$n->Invoke( "CHATMESSAGE {$_REQUEST['id']} $info");
