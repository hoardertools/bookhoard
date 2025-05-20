<?php

namespace Database\Seeders;

use App\Setting;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class create_initial_admin extends Seeder
{
    public function run()
    {

        if(User::count() == 0){
            $user = new User();
            $user->name = "Admin";
            $user->email = "update@me.local";
            $user->password =  Hash::make("password");;
            $user->canRead = true;
            $user->canWrite = true;
            $user->canDownload = true;
            $user->save();
        }

    }
}
