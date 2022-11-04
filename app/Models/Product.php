<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $table = "dummy";
    public const SEARCHABLE_COLUMNS = ["product", "category", "brand"];

    public function scopeSearch(Builder $query, $request)
    {
        if (isset($request->like))
            foreach (self::SEARCHABLE_COLUMNS as $column) {
                $query->orWhere($column, 'LIKE', '%' . $request->like . '%');
            }
        foreach ($request->all() as $key => $item) {
            if (in_array($key, self::SEARCHABLE_COLUMNS))
                $query->wherein($key, $item);
        }

    }

}
