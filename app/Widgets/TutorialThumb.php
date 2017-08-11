<?php

namespace App\Widgets;

use App\Helpers\ImgHelper;
use Arrilot\Widgets\AbstractWidget;

class TutorialThumb extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'id' => null,
        'src' => null,
        'slug' => null
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $this->config['src'] = ImgHelper::link($this->config['src'], $this->config['id'], 540);

        return view('widgets.tutorial_thumb', [
            'config' => $this->config,
        ]);
    }
}
