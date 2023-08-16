<?php

namespace App\Tables\HeadActions;

use Livewire\Component;
use Okipa\LaravelTable\Abstracts\AbstractHeadAction;
use Illuminate\Contracts\View\View;

class CreateHeadAction extends AbstractHeadAction
{
    public string $link;
    
    
    public function __construct(public string $model)
    {
        $model = new $model();
        $this->link = route($model->getRoute().'.create');
    }
    protected function class(): array
    {
        return [];
    }

    protected function icon(): string
    {
       
        $icon = '<span class="glyphicon glyphicon-plus"></span>';
        return $icon;
    }

    protected function title(): string
    {
        return  __('APP.CREATE');
    }

    /** @return mixed|void */
    public function action(Component $livewire)
    {
        // The treatment that will be executed on click on the head action button.
        // Use the `$livewire` param to interact with the Livewire table component and emit events for example.
    }
    
    public function render(): View
    {
        return view('laravel-table::' . config('laravel-table.ui') . '.head-action', [
            'class' => $this->class(),
            'title' => $this->title(),
            'icon' => $this->icon(),
            'link' => $this->link
        ]);
    }
    
}
