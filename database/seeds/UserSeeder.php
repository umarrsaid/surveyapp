<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_su = new Role();
        $role_su->name         = 'superuser';
        $role_su->display_name = 'Super User'; // optional
        $role_su->description  = 'Super User'; // optional
        $role_su->save();

        $user_su = new User();
        $user_su->nama     = 'SuperUserxyz';
        $user_su->nama_lengkap    = 'Super User';
        $user_su->password = bcrypt('Super_user@2019');
        $user_su->status    = 1;
        $user_su->save();
        $user_su->attachRole($role_su);


        $role_admin = new Role();
        $role_admin->name         = 'admin';
        $role_admin->display_name = 'Admin Developer'; // optional
        $role_admin->description  = 'Admin Developer'; // optional
        $role_admin->save();

        $user_admin = new User();
        $user_admin->nama     = 'admin';
        $user_admin->nama_lengkap    = 'Admin';
        $user_admin->password = bcrypt('Umang@2022');
        $user_admin->status    = 1;
        $user_admin->save();
        $user_admin->attachRole($role_admin);

        $role_user = new Role();
        $role_user->name         = 'user';
        $role_user->display_name = 'User'; // optional
        $role_user->description  = 'User'; // optional
        $role_user->save();

        $user_user = new User();
        $user_user->nama     = 'user';
        $user_user->nama_lengkap    = 'User';
        $user_user->password = bcrypt('User@2022');
        $user_user->status    = 1;
        $user_user->save();
        $user_user->attachRole($role_user);


     //    \DB::table('users')->insert([
     //    	'nama' => 'admin_dev',
     //    	'nama_lengkap' => 'Admin Developer',
     //    	'password' => app('hash')->make('Admin@2019'),
    	// ]);
    }
}
