<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\User;
use App\Models\UserLevel;


use DB;

class PageController extends Controller
{
  public function indexPage()
  {
    if (Auth::check()) {
      // The user is logged in...
      return redirect()->route('user.dash');
    } else {
      return redirect()->route('get.login');
    }
  }

  public function loginPage()
  {
    return view('auth.login');
  }

  public function dashboardPage()
  {

    return view('dashboard.view', array(
      'pageTitle' => 'Dashboard',
      'pageDescription' => 'Test description',
      'parameters' => [
        'tb_api' => 'api/form/formTB',
        'model' => 'Form',
        'customFormComponent' => 'CustomForm',
        'page_display' =>[5,10,25,50],
        'table_columns' =>[
          array('header_name' => 'ID', 'header_value'=> 'id', 'orderable' => true),
          array('header_name' => 'Form Name', 'header_value'=> 'form_name', 'orderable' => true),
          array('header_name' => 'Form Template URL', 'header_value'=> 'file_template_url', 'orderable' => true),
          array('header_name' => 'Data Set', 'header_value'=> 'data_set', 'orderable' => true),
          array('header_name' => 'Status', 'header_value'=> 'status', 'orderable' => false),
          array('header_name' => 'Created At', 'header_value'=> 'created_at', 'orderable' => true),
        ],
      ],
      'parameters2' => [
        'tb_api' => 'api/crmLog/table',
        'model' => 'CRM Log',
        'page_display' =>[5,10,25,50],
        'table_columns' =>[
          array('header_name' => 'ID', 'header_value'=> 'id', 'orderable' => true),
          array('header_name' => 'Module Name', 'header_value'=> 'module_name', 'orderable' => true),
          array('header_name' => 'action', 'header_value'=> 'action', 'orderable' => true),
          array('header_name' => 'Username', 'header_value'=> 'user_name', 'orderable' => true),
          array('header_name' => 'affected_row_copy', 'header_value'=> 'affected_row_copy', 'orderable' => false),
          array('header_name' => 'Created At', 'header_value'=> 'created_at', 'orderable' => true),
        ],
        //tool_name either delete or edit only
        'tools' =>[
          array('tool_name' => 'delete' ,'tool_api'=> 'api/crmLog/delete/'),
          array('tool_name' => 'edit', 'tool_api'=> 'api/crmLog/edit/'),
        ]
      ],
    ));
  }

  private function permissionCheck($userlevel)
  {
    return ($userlevel == 0) ? false : true;
  }

  public function manageUser()
  {
    //  $hasAccess = (auth()->user()->userlevel->n2_users == 0) ? false : true;
    $hasAccess = $this->permissionCheck(auth()->user()->userlevel->n2_users);
    if (!$hasAccess) {
      if (Auth::check()) {
        // The user is logged in...
        return redirect()->route('user.dash');
      } else {
        return redirect()->route('get.login');
      }
    }
    return view('tools.manageUsers.view', array(
      'pageTitle' => 'Manage User',
      'pageDescription' => '',
      'userLevels' => UserLevel::where('deleted', '0')->get()
    ));
  }
  //
  public function manageUserLevel()
  {
    $hasAccess = $this->permissionCheck(auth()->user()->userlevel->n2_user_roles);
    if (!$hasAccess) {
      if (Auth::check()) {
        // The user is logged in...
        return redirect()->route('user.dash');
      } else {
        return redirect()->route('get.login');
      }
    }
    return view('tools.manageUserLevels.view', array(
      'pageTitle' => 'Manage User Level',
      'pageDescription' => ''
    ));
  }

  public function manageForm()
  {
    $hasAccess = $this->permissionCheck(auth()->user()->userlevel->n2_forms);
    if (!$hasAccess) {
      if (Auth::check()) {
        // The user is logged in...
        return redirect()->route('user.dash');
      } else {
        return redirect()->route('get.login');
      }
    }
    return view('tools.manageForms.view', array(
      'pageTitle' => 'Manage Forms',
      'pageDescription' => '',
    ));
  }

  public function manageCrmLog()
  {
    $hasAccess = $this->permissionCheck(auth()->user()->userlevel->n2_forms);
    if (!$hasAccess) {
      if (Auth::check()) {
        // The user is logged in...
        return redirect()->route('user.dash');
      } else {
        return redirect()->route('get.login');
      }
    }
    return view('tools.manageCrmLogs.view', array(
      'pageTitle' => 'Manage CRM Logs',
      'pageDescription' => '',
    ));
  }


}
