<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use PDO;

class UniqueExceptCurrent implements Rule
{
    protected $table;
    protected $column;
    protected $model;

    public function __construct($table, $column, $model)
    {
        $this->table = $table;
        $this->column = $column;
        $this->model = $model;
    }

    public function passes($attribute, $value)
    {
        $query = DB::table($this->table)
            ->where($this->column, $value)
            ->where('deleted', '0');
        if ($this->model) {
            $query = $query->where('id', '!=', $this->model->id);
        }
        $query = $query->count();
        if ($query > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function message()
    {
        return 'The :attribute is already in use.';
    }
}
