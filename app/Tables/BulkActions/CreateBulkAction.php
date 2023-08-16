<?php

namespace App\Tables\BulkActions;

use Illuminate\Support\Collection;
use Livewire\Component;
use Okipa\LaravelTable\Abstracts\AbstractBulkAction;

class CreateBulkAction extends AbstractBulkAction
{
    public $link = '';
    
    protected function identifier(): string
    {
        return 'bulk_action_create';
    }

    protected function label(array $allowedModelKeys): string
    {
        $model = new $this->modelClass();
        $this->link = route($model->getRoute().'.create');
        $label = '<span class="svg-icon">
            		<svg class="svg-plus">
            			<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-plus"></use>
            		</svg>
            	 </span>';
        return $label;
    }

    protected function defaultConfirmationQuestion(array $allowedModelKeys, array $disallowedModelKeys): string
    {
      
        // The default bulk action confirmation question that will be asked before execution.
        // Return `null` if you do not want any confirmation question to be asked by default.
        return '';
    }

    protected function defaultFeedbackMessage(array $allowedModelKeys, array $disallowedModelKeys): string
    {
        // The default bulk action feedback message that will be triggered on execution.
        // Return `null` if you do not want any feedback message to be triggered by default.
        return '';
    }

    /** @return mixed|void */
    public function action(Collection $models, Component $livewire)
    {
        dd($models);
        // The treatment that will be executed on click on the bulk action link.
        // Use the `$livewire` param to interact with the Livewire table component and emit events for example.
    }
}
