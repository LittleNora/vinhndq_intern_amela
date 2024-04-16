<?php

namespace App\Controllers;

use eftec\bladeone\BladeOne;

class BaseController
{
    private string $viewPath = 'views';

    private string $cachePath = 'storage/cache';

    protected $blade = null;

    protected function view($path, $data = [])
    {
        if (!$this->blade) {
            $this->setBlade();
        }

        echo $this->blade->run($path, $data);
    }

    private function setBlade()
    {
        $this->blade = new BladeOne($this->viewPath, $this->cachePath, BladeOne::MODE_DEBUG);
    }
}