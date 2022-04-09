<?php
namespace App;

require __DIR__.'/vendor/autoload.php';

class Application {
    public function run() {
        echo "Hello world";
    }
}

$app = new Application();
$app->run();