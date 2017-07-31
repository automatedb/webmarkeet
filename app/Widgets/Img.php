<?php

namespace App\Widgets;

use App\Helpers\ImgHelper;
use Arrilot\Widgets\AbstractWidget;

class Img extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'id' => null,
        'src' => null,
        'title' => null,
        'type' => null,
        'options' => [
            'class' => 'img-fluid'
        ]
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $size = 730;

        if($this->config['type'] === 'post') {
            $size = 300;
        }

        $this->config['src'] = ImgHelper::link($this->config['src'], $this->config['id'], $size);

        return view('widgets.img', [
            'config' => $this->config,
        ]);
    }
}
