<?php

namespace App\Services;

use App\Models\Form;
use DB;
use Hash;
use Illuminate\Http\Request;

class FormService
{
    public function userLevelTB(Request $request)
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: *');

        $tableColumns = array(
            'id',
            'name',
            'created_at',
            'updated_at',
        );

        // offset and limit
        $offset = 0;
        $limit = 10;
        if (isset($request->length)) {
            $offset = isset($request->start) ? $request->start : $offset;
            $limit = isset($request->length) ? $request->length : $limit;
        }

        // searchText
        $search = '';
        if (isset($request->search) && isset($request->search['value'])) {
            $search = $request->search['value'];
        }

        // ordering
        $sortIndex = 0;
        $sortOrder = 'desc';
        if (isset($request->order) && isset($request->order[0]) && isset($request->order[0]['column'])) {
            $sortIndex = $request->order[0]['column'];
        }
        if (isset($request->order) && isset($request->order[0]) && isset($request->order[0]['dir'])) {
            $sortOrder = $request->order[0]['dir'];
        }

        $userLevels = DB::table('user_levels')
            ->selectRaw('*')
            ->where('deleted', '0');
        // ->where('username', '!=', "root")
        // ->where('users.status', '1');
        $userLevels = $userLevels->where(function ($query) use ($search) {
            return $query->where('id', 'like', '%' . $search . '%')
                ->orWhere('name', 'like', '%' . $search . '%')
                ->orWhere('created_at', 'like', '%' . $search . '%')
                ->orWhere('updated_at', 'like', '%' . $search . '%');
        })
            ->orderBy($tableColumns[$sortIndex], $sortOrder);
        $userLevelCount = $userLevels->count();
        $userLevels = $userLevels->offset($offset)
            ->limit($limit)
            ->get();



        $result = [
            'recordsTotal'    => $userLevelCount,
            'recordsFiltered' => $userLevelCount,
            'data'            => $userLevels,
        ];

        return $result;
    }
    // public function userLevelAdd($validatedData): void
    // {

    //     //Check if email is in use
    //     $name = trim($validatedData['name']);
    //     $checkDupliateNameParameters = [
    //         "name" => $validatedData['name'],
    //         "user_level_id" => "",
    //         "type" => "add",
    //     ];

    //     $this->checkDuplicateName($checkDupliateNameParameters);
    //     $userLevel = new UserLevel();
    //     $userLevel->name = $validatedData['name'];
    //     $userLevel->n1_crm = $validatedData['n1_crm'] ?? 0;
    //     $userLevel->n1_tools = $validatedData['n1_tools'] ?? 0;
    //     $userLevel->n2_users = $validatedData['n2_users'] ?? 0;
    //     $userLevel->n2_user_roles = $validatedData['n2_user_roles'] ?? 0;
    //     $userLevel->n2_dashboard = $validatedData['n2_dashboard'] ?? 0;
    //     $userLevel->save();
    // }
    // public function userLevelEdit($validatedData, UserLevel $userLevel): void
    // {
    //     $checkDupliateNameParameters = [
    //         "name" => $validatedData['name'],
    //         "user_level_id" => $userLevel->id,
    //         "type" => "edit",
    //     ];

    //     $this->checkDuplicateName($checkDupliateNameParameters);
    //     $userLevel->name = $validatedData['name'];
    //     $userLevel->n1_crm = $validatedData['n1_crm'] ?? 0;
    //     $userLevel->n1_tools = $validatedData['n1_tools'] ?? 0;
    //     $userLevel->n2_users = $validatedData['n2_users'] ?? 0;
    //     $userLevel->n2_user_roles = $validatedData['n2_user_roles'] ?? 0;
    //     $userLevel->n2_dashboard = $validatedData['n2_dashboard'] ?? 0;
    //     $userLevel->save();
    // }
    // public function userLevelDelete(UserLevel $userLevel)
    // {
    //     $userLevel->deleted = "1";
    //     $userLevel->save();
    // }
    // private function checkDuplicateName($paramaterArray)
    // {
    //     switch ($paramaterArray['type']) {
    //         case 'add':
    //             # code...
    //             $existingUserLevel = UserLevel::where('name', $paramaterArray['name'])->where('deleted', '0')->count();
    //             break;
    //         case 'edit':
    //             # code...
    //             $existingUserLevel = UserLevel::where('name', $paramaterArray['name'])->where('id', '!=', $paramaterArray['user_level_id'])->where('deleted', '0')->count();
    //             break;
    //     }
    //     if ($existingUserLevel > 0) {
    //         throw new \Exception('User Level already in use.');
    //     }
    // }
}