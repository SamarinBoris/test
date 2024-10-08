<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait QueryHelperTrait
{
    public static function filterQuery(Builder $class, array $fields): Builder
    {
        return $class->where(function ($query) use ($class, $fields) {
            $column = Schema::getColumnListing((clone $class)->getQuery()->from);

            foreach ($fields as $fieldName => $fieldValue) {
                $fieldName = Str::snake($fieldName);
                if (!in_array($fieldName, $column) || (!$fieldValue && $fieldValue !== 0)) {
                    continue;
                }
                if (is_array($fieldValue)) {
                    $fieldValue = array_change_key_case($fieldValue,CASE_LOWER);
                    if (isset($fieldValue['from']) || isset($fieldValue['to'])) {
                        if (isset($fieldValue['from'])) {
                            $query->where($fieldName, '>=', $fieldValue['from']);
                        }
                        if (isset($fieldValue['to'])) {
                            $query->where($fieldName, '<=', $fieldValue['to']);
                        }
                    } elseif (isset($fieldValue['like'])) {
                        $query->where(
                            $fieldName,
                            'like',
                            '%' . str_replace(' ', '%%', $fieldValue['like']) . '%');
                    } else {
                        $query->whereIn($fieldName, $fieldValue);
                    }
                } else {
                    $query->where($fieldName, $fieldValue);
                }
            }
        });
    }

    public static function sortQuery(Builder $class, array $fields): Builder
    {
        $column = Schema::getColumnListing((clone $class)->getQuery()->from);
        if (!empty($fields['sort'])) {
            foreach ($fields['sort'] as $fieldName => $type) {
                $fieldName = Str::snake($fieldName);
                if (!in_array($fieldName, $column)) {
                    continue;
                }
                $class->orderBy($fieldName, $type);
            }
        }

        return $class;
    }
}
