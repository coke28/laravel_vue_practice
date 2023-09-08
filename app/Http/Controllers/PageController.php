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
}