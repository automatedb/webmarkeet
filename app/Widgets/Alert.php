<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

class Alert extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'message' => null,
        'type' => 'success'
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        return view('widgets.alert', [
            'config' => $this->config,
        ]);
    }
}
