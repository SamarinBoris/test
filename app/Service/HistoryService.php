<?php

namespace App\Service;

use App\Models\History;
use Illuminate\Database\Eloquent\Model;

class HistoryService
{
    /**
     * @param string $modelId
     * @param string $modelName
     * @param string $action
     * @param array|null $before
     * @param array|null $after
     * @return void
     */
    public static function create(string $modelId, Model $modelName, string $action, ?array $before, ?array $after): void
    {
        if(method_exists($action, $modelName)){
            $history = new History();
            $history->model_id = $modelId;
            $history->model_name = $modelName::class;
            $history->action = $action;
            $history->before = $action !== 'CREATED' ? $modelName->getOriginal() : null;
            $history->after = $modelName->getChanges();
            $history->save();
        }
    }
}
