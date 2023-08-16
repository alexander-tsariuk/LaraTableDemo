<?php

namespace App\Tables\BulkActions;

use Illuminate\Support\Collection;
use Livewire\Component;
use Okipa\LaravelTable\Abstracts\AbstractBulkAction;
use Illuminate\Contracts\View\View;

class PriceBulkAction extends AbstractBulkAction
{
    protected function identifier(): string
    {
        return 'table_bulkprice-' . uniqid();
    }

    protected function label(array $allowedModelKeys): string
    {
        return __('APP.CHANGE_PRICES');
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
        $action = route($model->getRoute() . '.showbulkprice', []);

        return view('laravel-table::' . config('laravel-table.ui') . '.price-bulk-action', [
            'bulkAction' => $this,
            'label' => $this->label($this->allowedModelKeys),
            'action' => $action
        ]);
    }
}
