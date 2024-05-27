<?php

namespace App\Repositories\Division;

use Illuminate\Http\Request;

interface DivisionRepositoryInterface
{
    public function list(Request $request);
}
