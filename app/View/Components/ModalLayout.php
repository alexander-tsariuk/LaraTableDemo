<?php
namespace app\View\Components;

use Illuminate\View\Component;


class ModalLayout extends Component
{


    public $title;
    public $form;
    public $method;
    public $formbtn;
    public $close;
    public $footer;
    
    /**
     * Создать экземпляр компонента.
     *
     * @param  string  $type
     * @param  string  $message
     * @return void
     */
    public function __construct($title = null, $close = null, $form = null,  $footer = null)
    {
        $this->title = $title;
        if ($form) {
            $form['btn'] = !empty($form['btn']) ? $form['btn'] : __('APP.SAVE');
            $form['class'] = !empty($form['class']) ? $form['class'] : '';
            if (empty($form['method'])) {
                $form['method'] = 'post';
            }
        }
        $this->form = $form;
        $this->close = $close ? $close : __('APP.CLOSE');
        $this->footer = $footer;
    }
    
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('components.modal.main');
    }
}