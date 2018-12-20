<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Hash;
use App\Mail\AccountCreated;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname','lastname','email','password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function createuser($context){

        $context->validate(request(),[
            'firstname' => 'required',
            'lastname' => 'required',
            'email'  => 'required',
            'role'=>'required',
        ]);

        $randomstr=str_random(8);
        $user=$this->create([
            'firstname'=>request('firstname'),
            'lastname'=>request('lastname'),
            'email'=>request('email'),   
            'password' => Hash::make($randomstr),
        ]);
        $user->assignRole(request('role'));
        $data=[
            'firstname'=>request('firstname'),
            'pass'=>$randomstr,
        ];
        \Mail::to(request('email'))->send(new AccountCreated($data));

    }

    public function edituser($context){

        $context->validate(request(),[
            'firstname' => 'required',
            'lastname' => 'required',
            'email'  => 'required',
            
        ]);
        $id=request('id');
        $user=$this->where('id',$id)->first();
        $user->firstname=request('firstname');
        $user->lastname=request('lastname');
        $user->email=request('email');
        $user->save();
        if(request('role')!=NULL ){
            $user->syncRoles(request('role'));
        }
       
    }

    public function editpass($context){

        $context->validate(request(),[
            'newpass' => 'required',
            'confirm' => 'required',    
        ]);
        if(request('newpass')==request('confirm')){
            $hashedpass=Hash::make(request('newpass'));
            $user=Auth::user();
            $user->password=$hashedpass;
            $user->save();
        }

    }

    public function deleteuser($context){
        $this->where('id', request('id'))->first()->delete();
    }
}
