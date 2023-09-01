<?php
namespace App\Tables;

use App\Overrides\Okipa\Abstracts\AbstractTableConfiguration;
use App\Overrides\Okipa\Table;
use App\Traits\TablesColumnsTrait;
use App\Tables\HeadActions\CreateHeadAction;
use App\Tables\BulkActions\DeleteBulkAction;
use App\Tables\BulkActions\PriceBulkAction;
use App\ViewModels\Warehouse;

class Warehouses extends AbstractTableConfiguration
{
    use TablesColumnsTrait;

    protected function table(): Table
    {
        $class = Warehouse::class;

        return Table::make()->model($class)
            ->headAction(new CreateHeadAction($class))
            ->bulkActions(fn () => [
            (new DeleteBulkAction($class)),
        ]);
    }

    protected function results(): array
    {
        return [ // The table results configuration.
        // As results are optional on tables, you may delete this method if you do not use it.
        ];
    }
}
