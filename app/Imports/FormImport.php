<?php

namespace App\Imports;

use App\Models\Form;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;
use App\Rules\CommaSeperated;

class FormImport implements ToModel, WithValidation, WithHeadingRow, ShouldAutoSize,OnEachRow
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public $rowCount = 0;
    public function onRow(Row $row)
    {
        $this->rowCount++;
    }

    public function __construct()
    {
    }

    public function model(array $row)
    {
        return new Form([
            'form_name'     => $row['form_name'],
            'file_template_url'    => $row['file_template_url'],
            'data_set'    => $row['data_set'],
            'status'    => $row['status'],
        ]);
    }


    public function rules(): array
    {
        return [
            'form_name' => 'required|string',
            'file_template_url' => 'required|string',
            'data_set' => ['required', new CommaSeperated],
            'status' => 'required|in:1,0',
        ];
    }
}
