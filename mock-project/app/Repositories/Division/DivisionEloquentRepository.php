<?php

namespace App\Repositories\Division;

use App\Models\Division;
use App\Repositories\EloquentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DivisionEloquentRepository extends EloquentRepository implements DivisionRepositoryInterface
{
    public function getModel(): string
    {
        return Division::class;
    }

    public function list(Request $request)
    {
        return $this->queryIndex($request)->paginate($request->get('limit', 10));
    }

    public function queryIndex(Request $request)
    {
        $query = $this->getModelQuery();

        $orderBys = [
            'id',
            'name',
            'created_at',
            'updated_at',
        ];

        if ($request->has('order_by') && in_array($request->get('order_by'), $orderBys)) {
            $query->orderBy($request->get('order_by'), $request->get('order_type', 'desc'));
        }

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }

        if ($request->has('is_default')) {
            $query->where('is_default', $request->get('is_default'));
        }

        return $query;
    }
}
