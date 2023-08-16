<?php
namespace App\View\Components\Forms;

use Illuminate\View\Component;
use Illuminate\View\View;

class Input extends Component
{
    public $label;
    public $icons;
    /**
     *
     * @param string $label
     * @param array $icons
     */
    public function __construct($label = '', $icons = [], $icon = [])
    {
        $this->label = $label;
        $this->icons = $icons;
        //если одна иконка
        if (!empty($icon)) {
            $this->icons[] = $icon;
        }
    }

    /**
     *
     * @return View
     */
    public function render(): View
    {
        return view('components.forms.input');
    }
}
