<?php

namespace App\Imports;

use App\Models\Form;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Rules\CommaSeperated;

class FormImport implements ToCollection, WithValidation, WithHeadingRow, ShouldAutoSize
{
    use Importable;

    public $rowCount = 0;
    private $forms;

    public function __construct()
    {
        $this->forms = Form::where('deleted','0')->get();
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $this->rowCount++;
            // You can perform additional processing for each row here if needed
            $form = $this->forms->where('form_name', $row['form_name'])->first();
            if ($form) {
                throw new \Exception("Form name with '{$row['form_name']}' already exists.",419);
            }
            $form = new Form([
                'form_name' => $row['form_name'],
                'file_template_url' => $row['file_template_url'],
                'data_set' => $row['data_set'],
                'status' => $row['status'],
            ]);

            // Save the model or perform any other operations
            $form->save();
        }
    }

    public function rules(): array
    {
        return [
            '*.form_name' => 'required|string',
            '*.file_template_url' => 'required|string',
            '*.data_set' => ['required', new CommaSeperated()],
            '*.status' => 'required|in:1,0',
        ];
    }
}
