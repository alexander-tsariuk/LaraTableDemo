<?php
namespace app\View\Components\Forms;

use Illuminate\View\Component;


class Radio extends Component
{


    public $label;
    public $checked;
    public $id;

    
    /**
     * Создать экземпляр компонента.
     *
     * @param  string  $type
     * @param  string  $message
     * @return void
     */
    public function __construct($label = null, $checked = false)
    {
        $this->label = $label;
        $this->checked = $checked;
        $this->id = uniqid('check');
    }
    
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('components.forms.radio');
    }
}
