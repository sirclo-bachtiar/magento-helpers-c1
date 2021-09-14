<?php

namespace Bachtiar\Helper\CustomQueryGenerator\Test;

use Bachtiar\Helper\CustomQueryGenerator\Service\QueryBuilderService;

class QueryBuilderTest
{
    public function __invoke()
    {
        return QueryBuilderService::select([])->from('base_table')
            ->join('relation_table', 'baseColumnId', '=', 'relationColumnId')
            ->where('base_table.name', '=', 'test')
            ->andWhere('base_table.age', '=', 'age')
            ->orWhere('relation_table.address', 'like', '%ponorogo%')
            ->generate();
    }
}
