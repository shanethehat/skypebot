<?php
use Inviqa\SkypeEngine;
global $n;

$engine = new SkypeEngine($n);

$engine->add(':create', function(SkypeEngine $engine, $chatname, $handle, $body) {
    $engine->cmd("CHATMESSAGE {$chatname['val']} Sure. Why not.");
});

$engine->add(':ping', function(SkypeEngine $engine, $chatname, $handle, $body) {
    $engine->cmd("CHATMESSAGE {$chatname['val']} Pong!");
});

$engine->add(':magento', function(SkypeEngine $engine, $chatname, $handle, $body) {
    $engine->cmd("CHATMESSAGE {$chatname['val']} Have you tried clearing the cache?");
});

$engine->add(':burritos?', function(SkypeEngine $engine, $chatname, $handle, $body) {
    $engine->cmd("CHATMESSAGE {$chatname['val']} https://docs.google.com/a/inviqa.com/spreadsheet/ccc?key=0AgaDiKrNejnsdHRGTEFIMGxlOVVxQXpkbExYQlk1N2c");
});

$engine->add(':badger', function(SkypeEngine $engine, $chatname, $handle, $body) {
        $badgers = array(
            'http://i.telegraph.co.uk/multimedia/archive/02414/badger_2414880b.jpg',
            'http://www.brianmay.com/brian/briannews/newspix/12/SCH954H12_Brian_May_Badgers_475x298.jpg',
            'http://i.telegraph.co.uk/multimedia/archive/02369/badger_2369182b.jpg',
            'http://www.bbc.co.uk/insideout/content/images/2007/10/15/badger_close470_470x303.jpg',
            'http://www.elveden.suffolk.sch.uk/images/badger.jpg',
            'https://www.lifeinthemix.info/wp-content/uploads/2013/02/badger.jpg',
            'http://livinorange.files.wordpress.com/2010/02/meanbadger1.png',
            'http://www.binocular-repairs.com/wp-content/uploads/2011/11/badger-7669.jpg',
            'http://www.wildlifeonline.me.uk/images/graphics/european_badger_04.jpg',
        );
        $engine->cmd(sprintf("CHATMESSAGE {$chatname['val']} %s", $badgers[array_rand($badgers)]));
});

return $engine;
