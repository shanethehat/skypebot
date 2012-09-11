<?php
require_once 'vendor/autoload.php';

$payload = json_decode(file_get_contents('php://input'));
$info = "";

switch($payload->build->phase) {
    case 'STARTED':
        $info = sprintf(
            "%s build %s has started at %s.",
            $payload->name,
            $payload->build->number,
            $payload->build->full_url
        );
        break;
    case 'FINISHED':
        $info = sprintf(
            "%s build number %s finished with status %s at %s.",
            $payload->name,
            $payload->build->number,
            $payload->build->status,
            $payload->build->full_url
        );
        break;
    default:
        die('Not interested');
}

Inviqa\SkypeEngine::getDbusProxy()->Invoke( "CHATMESSAGE {$_REQUEST['id']} $info");
