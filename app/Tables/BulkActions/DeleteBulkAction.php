<?php

namespace App\Tables\BulkActions;

use Illuminate\Support\Collection;
use Livewire\Component;
use Okipa\LaravelTable\Abstracts\AbstractBulkAction;
use Illuminate\Contracts\View\View;

class DeleteBulkAction extends AbstractBulkAction
{
    protected function identifier(): string
    {
        return 'table_delete-'.uniqid();
    }

    protected function label(array $allowedModelKeys): string
    {
        return __('APP.DELETE');
    }

    protected function defaultConfirmationQuestion(array $allowedModelKeys, array $disallowedModelKeys): string|null
    {
        return '';
    }

    protected function defaultFeedbackMessage(array $allowedModelKeys, array $disallowedModelKeys): string|null
    {
        return '';
    }

    /** @return mixed|void */
    public function action(Collection $models, Component $livewire)
    {
        // The treatment that will be executed on click on the bulk action link.
        // Use the `$livewire` param to interact with the Livewire table component and emit events for example.
    }
    
    public function render(): View
    {
        $model = new $this->modelClass();
        $action = route($model->getRoute().'.destroy', 0);
        return view('laravel-table::' . config('laravel-table.ui') . '.delete-bulk-action', [
            'bulkAction' => $this,
            'label' => $this->label($this->allowedModelKeys),
            'action' => $action
        ]);
    }
}
