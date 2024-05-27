<?php

namespace App\Repositories\Schedule;

use App\Models\Schedule;
use App\Repositories\EloquentRepository;
use App\Services\Traits\TCache;
use App\Services\Traits\TUpload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ScheduleEloquentRepository extends EloquentRepository implements ScheduleRepositoryInterface
{
    use TUpload, TCache;

    public function getModel(): string
    {
        return Schedule::class;
    }

    public function create(array $attributes, $with = [])
    {
        $model = $this->newObj();

        $model->fill($attributes);

        $model->status = self::STATUS['scheduled']['value'];

        $model->created_by = auth()->id();

        $model->save();

        empty($attributes['recipients']) ?: $model->recipients()->createMany($attributes['recipients']);

        empty($attributes['attachments']) ?: $model->attachments()->createMany($this->createAttachments($attributes['attachments']));

        $model->load($with);

        if (Carbon::make($model->date)->isToday()) $this->cacheOneItem($model);

        DB::commit();

        return $model;
    }

    public function update($id, array $attributes, $with = [])
    {
        $model = $this->find($id);

        $model->fill($attributes);

        $model->status = self::STATUS['scheduled']['value'];

        $model->save();

        if (!empty($attributes['recipients'])) {
            $model->recipients()->delete();


            $model->recipients()->createMany($attributes['recipients']);
        }

        if (!empty($attributes['attachments'])) {
            $paths = $model->attachments->pluck('path')->toArray();

            if ($model->attachments()->delete()) $this->deleteMultipleFiles($paths);

            $model->attachments()->createMany($this->createAttachments($attributes['attachments']));
        }

        $model->load($with);

        if (Carbon::make($model->date)->isToday()) $this->cacheOneItem($model);

        return $model;
    }

    public function list(Request $request, $with = [])
    {
        return $this->queryIndex($request)->with($with)->paginate($request->get('limit', 10));
    }

    public function createAttachments($attachments)
    {
        return collect($attachments)->map(fn($attachment) => [
            'original_name' => $attachment->getClientOriginalName(),
            'path' => $this->uploadFile($attachment),
            'mime_type' => $attachment->getClientMimeType(),
        ])->toArray();
    }

    public function queryIndex(Request $request)
    {
        $query = $this->getModelQuery();

        $orderBys = [
            'id',
            'name',
            'date',
            'send_to',
            'status',
        ];

        if ($request->has('order_by') && in_array($request->get('order_by'), $orderBys)) {
            $query->orderBy($request->get('order_by'), $request->get('order_type', 'desc'));
        }

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }

        if ($request->has('date')) {
            $query->where('date', $request->get('date'));
        }

        if ($request->has('send_to')) {
            $query->where('send_to', 'like', '%' . $request->get('send_to') . '%');
        }

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('date', [$request->get('from_date'), $request->get('to_date')]);
        }

        return $query;
    }

    public function updateStatus(string|array $id, $status)
    {
        $query = $this->getModelQuery();

        $query = is_array($id) ? $query->whereIn('id', $id) : $query->where('id', $id);

        $query->update(['status' => self::STATUS[$status]['value']]);

        return true;
    }

    public function updateStatusToCache(string|array $id, $status)
    {
        $schedules = $this->getFromCache();

        $query = is_array($id) ? $schedules->whereIn('id', $id) : $schedules->where('id', $id);

        $query->each(fn($item) => $item->status = self::STATUS[$status]['value']);

        Log::info('Schedules updated to cache', $query->toArray());

        $this->cache($query);

        return true;
    }

    public function getFromCacheGroupByStatus($status = null)
    {
        $schedules = $this->getFromCache();

        if (is_string($status) && in_array($status, array_keys(self::STATUS))) {
            return $schedules->filter(fn($schedule) => $schedule->status === self::STATUS[$status]['value']);
        }

        $result = collect(self::STATUS)->map(fn($item) => $schedules->filter(fn($schedule) => $schedule->status === $item['value']));

        if (is_array($status)) {
            return $result->filter(fn($item, $key) => in_array($key, $status));
        }

        return $result;
    }
}
