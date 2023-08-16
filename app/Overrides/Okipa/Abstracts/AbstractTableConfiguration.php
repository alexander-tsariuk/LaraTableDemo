<?php

namespace  App\Overrides\Okipa\Abstracts;

use  App\Overrides\Okipa\Table;

abstract class AbstractTableConfiguration
{
    public function setup(): Table
    {
        $table = $this->table();
        $table->columns($this->columns());
        $table->results($this->results());
        $table->addFilters();

        return $table;
    }

    abstract protected function table(): Table;

    abstract protected function columns(): array;

    protected function results(): array
    {
        return [];
    }
}
