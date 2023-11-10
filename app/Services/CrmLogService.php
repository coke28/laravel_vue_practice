<?php

namespace App\Services;

use App\Models\CrmLog;
use DB;
use Illuminate\Http\Request;

class CrmLogService
{
    public function crmLogTB(Request $request)
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

        $crm_logs = DB::table('crm_logs')->selectRaw('
            id,
            module_name,
            action,
            user_name,
            affected_row_copy,
            created_at
        ')
        ->where('deleted','0');

        $crm_logs = $crm_logs->where(function ($query) use ($search) {
            return $query->where('id', 'like', '%' . $search . '%')
                ->orWhere('module_name', 'like', '%' . $search . '%')
                ->orWhere('action', 'like', '%' . $search . '%')
                ->orWhere('user_name', 'like', '%' . $search . '%')
                ->orWhere('affected_row_copy', 'like', '%' . $search . '%')
                ->orWhere('created_at', 'like', '%' . $search . '%');
        });
        if ($request->sort) {
            $crm_logs->orderBy($request->sort, $request->order);
        }
        

        if($request->paginate_display){
            $paginated = $crm_logs->paginate($request->paginate_display);
        }else{
            $paginated = $crm_logs->paginate(10);
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


    public function addCrmLog($model,$module_name,$action)
    {

        $crm_log = new CrmLog();
        $crm_log->module_name = $module_name;
        $crm_log->action = $action;
        // $crm_log->user_id = auth()->user()->id;
        // $crm_log->user_name = auth()->user()->first_name." ".auth()->user()->last_name;
        $crm_log->affected_row_copy = json_encode($model);
        $crm_log->save();
    }

    public function crmLogDelete(CrmLog $crm_log)
    {
        $crm_log->deleted = "1";
        $crm_log->save();
    }
}
