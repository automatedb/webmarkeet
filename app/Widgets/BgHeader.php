<?php

namespace App\Widgets;

use App\Helpers\ImgHelper;
use Arrilot\Widgets\AbstractWidget;

class BgHeader extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'id' => null,
        'src' => null,
        'title' => null
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $this->config['src'] = ImgHelper::link($this->config['src'], $this->config['id'], 300);

        return view('widgets.bg_header', [
            'config' => $this->config,
        ]);
    }
}
