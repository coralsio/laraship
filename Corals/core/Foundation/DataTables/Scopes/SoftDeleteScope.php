<?php

namespace Corals\Foundation\DataTables\Scopes;

use Yajra\DataTables\Contracts\DataTableScope;

class SoftDeleteScope implements DataTableScope
{

    public function apply($query)
    {
        $query->onlyTrashed();
    }
}
