<?php

namespace App\Traits;

use App\Models\History;
use Illuminate\Database\Eloquent\Model;

trait HistoryTrait
{
    protected static function bootHistoryTrait()
    {
        static::saved(function (Model $model) {
            if ($model->wasRecentlyCreated) {
                static::logChange($model, 'CREATED');
            } else {
                if (!$model->getChanges()) {
                    return;
                }
                static::logChange($model, 'UPDATED');
            }
        });

        static::deleting(function (Model $model) {
            static::logChange($model, 'DELETED');
        });
    }

    public static function logChange(Model $model, string $action)
    {
            $history = new History();
            $history->model_id = $model->id;
            $history->model_name = $model::class;
            $history->action = $action;
            $history->before = $action !== 'CREATED' ? json_encode($model->getOriginal()) : [];
            $history->after = json_encode($model->getAttributes()) ?? [];
            $history->save();

    }
}
