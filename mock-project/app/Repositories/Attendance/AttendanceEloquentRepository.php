<?php

namespace App\Repositories\Attendance;

use App\Enums\QueueChannel;
use App\Jobs\LogAttendance;
use App\Models\Attendance;
use App\Repositories\EloquentRepository;
use App\Services\Traits\TCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class AttendanceEloquentRepository extends EloquentRepository implements AttendanceRepositoryInterface
{
    use TCache;

    public function getModel(): string
    {
        return Attendance::class;
    }

    public function indexForAdmin(Request $request, $with = [])
    {
//        $request->has('date') ?: $request->merge(['date' => now()->format('Y-m-d')]);

        $query = $this->queryIndex($request)->with($with);

        if ($request->has('name')) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->get('name') . '%'));
        }

        if ($request->has('email')) {
            $query->whereHas('user', fn($q) => $q->where('email', 'like', '%' . $request->get('email') . '%'));
        }

        if ($request->has('username')) {
            $query->whereHas('user', fn($q) => $q->where('username', 'like', '%' . $request->get('username') . '%'));
        }

        if ($request->has('user_id')) {
            $query->where('user_id', 'like', '%' . $request->get('user_id') . '%');
        }

        return $query;
    }

    public function indexForStaff(Request $request)
    {
        $request->has('from_date') ?: $request->merge([
            'from_date' => now()->subDays(7)->format('Y-m-d'),
            'to_date' => now()->format('Y-m-d'),
        ]);

        $query = $this->queryIndex($request);

        $query->where('user_id', auth()->id());

        return $query;
    }

    public function log(Request $request)
    {
        $data = $request->all();

        $data['user_id'] = auth('api')->user()->id;

        $data['date'] = now()->format('Y-m-d');

        $data['time'] = now()->format('H:i:s');

        LogAttendance::dispatch($data)->onQueue(QueueChannel::ATTENDANCE->value);
    }

    public function queryIndex(Request $request)
    {
        $query = $this->getModelQuery()->orderByDesc('date');

        $orderBys = [
            'id',
            'user_id',
            'date',
        ];

        if ($request->has('order_by') && in_array($request->get('order_by'), $orderBys)) {
            $query->orderBy($request->get('order_by'), $request->get('order_type', 'desc'));
        }

        if ($request->has('date')) {
            $query->where('date', $request->get('date'));
        }

        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('date', [$request->get('from_date'), $request->get('to_date')]);
        }

        return $query;
    }
}
