<?php

namespace App\Tables\HeadActions;

use Livewire\Component;
use Okipa\LaravelTable\Abstracts\AbstractHeadAction;
use Illuminate\Contracts\View\View;

class CreateHeadActionDocument extends AbstractHeadAction
{
    public string $link;


    public function __construct(public string $model)
    {

        $model = new $model();
        $params = [];
        if (request()->has('type')) {
            $params = ['type' => request()->type];
        }

        $this->link = route($model->getRoute() . '.create', $params);
    }

    protected function class (): array
    {
        return [];
    }

    protected function icon(): string
    {

        $icon = '<span class="svg-icon">
            		<svg class="svg-plus">
            			<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-plus"></use>
            		</svg>
            	 </span>';
        return $icon;
    }

    protected function title(): string
    {
        return __('APP.CREATE');
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
