<?php

namespace App\Services;

use App\Models\Form;
use DB;
use Hash;
use Illuminate\Http\Request;

class FormService
{
    private CrmLogService $crmLogService;
 
    public function __construct(CrmLogService $crmLogService)
    {
        $this->crmLogService = $crmLogService;
    }

    public function formTB(Request $request)
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: *');
     
        // searchText
        $search = '';
        if (isset($request->search) && isset($request->search)) {
            $search = $request->search;
        }

        $forms = DB::table('forms')->selectRaw('
            id,
            form_name,
            file_template_url,
            data_set,
            CASE status WHEN 0 THEN "DISABLED" WHEN 1 THEN "ACTIVE" END as status,
            created_at
        ')->where('deleted','0');

        $forms = $forms->where(function ($query) use ($search) {
            return $query->where('id', 'like', '%' . $search . '%')
                ->orWhere('form_name', 'like', '%' . $search . '%')
                ->orWhere('file_template_url', 'like', '%' . $search . '%')
                ->orWhere('data_set', 'like', '%' . $search . '%')
                ->orWhere(DB::raw('CASE status WHEN 0 THEN "INACTIVE" WHEN 1 THEN "ACTIVE" END'), 'like', '%' . $search . '%')
                ->orWhere('created_at', 'like', '%' . $search . '%');
        });
        if ($request->sort) {
            $forms->orderBy($request->sort, $request->order);
        }

        if($request->paginate_display){
            $paginated = $forms->paginate($request->paginate_display);
        }else{
            $paginated = $forms->paginate(10);
        }
        

        $result = [
            'data'            => $paginated->items(),
            'pagination' => [
                'total' => $paginated->total(),
                'per_page' => $paginated->perPage(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),]
        ];

        return $result;
    }
    public function formAdd($validatedData): void
    {
        $form = new Form();
        $form->form_name = $validatedData['form_name'];
        $form->file_template_url = $validatedData['file_template_url'];
        $form->data_set = $validatedData['data_set'];
        $form->status = $validatedData['status'];
        $form->save();

        // $this->crmLogService->addCrmLog($form,"Manage Forms","formAdd");
    }
    public function formEdit($validatedData, Form $form): void
    {
        $form->form_name = $validatedData['form_name'];
        $form->file_template_url = $validatedData['file_template_url'];
        $form->data_set = $validatedData['data_set'];
        $form->status = $validatedData['status'];
        $form->save();

        $this->crmLogService->addCrmLog($form,"Manage Forms","formEdit");

    }
    public function formDelete(Form $form)
    {
        $form->deleted = "1";
        $form->save();

        $this->crmLogService->addCrmLog($form,"Manage Forms","formDelete");

    }
}
