<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ErrorLayout extends Component
{
    public $title;
    public $bodyClass;

    public function __construct($title = '', $bodyClass = '')
    {
        $this->title = $title;
        $this->bodyClass = $bodyClass;
    }

    public function render()
    {
        return view('layouts.error');
    }
} 