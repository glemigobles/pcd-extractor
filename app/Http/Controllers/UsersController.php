<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function createuser(User $user){
        $user->createuser($this);
        return $message="Użytkownik utworzony.";
    }

    public function edituser(User $user){
        $user->edituser($this);
        return $message="Edycja zakończona sukcesem.";
    }

    public function editpass(User $user){
        $user->editpass($this);
        return $message="Edycja hasła zakończona sukcesem.";
    }

    public function workers(User $user){
        $title='Użytkownicy';
        $users=$user->with('roles')->where('id','!=','1')->get();
        $roles=DB::table('roles')->where('name','!=','administrator')->get();

        return view('modules.workers.workers', compact('users','title','roles'));
    }

    public function users(User $user){
        $users=$user->with('roles')->where('id','!=','1')->get();

        return $users;
    }

    public function deleteuser(User $user){
        $user->deleteuser($this);
    }
}
