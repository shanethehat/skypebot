<?php
require_once 'vendor/autoload.php';

# Hack to work around strange php issue...
parse_str(file_get_contents('php://input'), $_REQUEST);
if (isset($_REQUEST['payload'])) {
    $payload = json_decode($_REQUEST['payload']);

    if ($payload) {
        $message = array();
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

        Inviqa\SkypeEngine::getDbusProxy()->Invoke( "CHATMESSAGE {$_REQUEST['id']} $info");
    }
}

