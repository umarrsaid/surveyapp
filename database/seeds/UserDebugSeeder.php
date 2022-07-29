<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\User;

class UserDebugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_su = Role::findOrFail(2);
        // $role_su->name         = 'superuser';
        // $role_su->display_name = 'Super User'; // optional
        // $role_su->description  = 'Super User'; // optional
        // $role_su->save();

        $user_ica = new User();
        $user_ica->nama     = 'ica';
        $user_ica->nama_lengkap    = 'ica';
        $user_ica->password = bcrypt('ica@2019');
        $user_ica->save();
        $user_ica->attachRole($role_su);

        $user_n = new User();
        $user_n->nama     = 'nola';
        $user_n->nama_lengkap    = 'nola';
        $user_n->password = bcrypt('nola@2019');
        $user_n->save();
        $user_n->attachRole($role_su);

        $user_u = new User();
        $user_u->nama     = 'umar';
        $user_u->nama_lengkap    = 'umar';
        $user_u->password = bcrypt('umar@2019');
        $user_u->save();
        $user_u->attachRole($role_su);

        $user_e = new User();
        $user_e->nama     = 'egi';
        $user_e->nama_lengkap    = 'egi';
        $user_e->password = bcrypt('egi@2019');
        $user_e->save();
        $user_e->attachRole($role_su);

        $user_d = new User();
        $user_d->nama     = 'darul';
        $user_d->nama_lengkap    = 'darul';
        $user_d->password = bcrypt('darul@2019');
        $user_d->save();
        $user_d->attachRole($role_su);

        $user_i = new User();
        $user_i->nama     = 'imam';
        $user_i->nama_lengkap    = 'imam';
        $user_i->password = bcrypt('imam@2019');
        $user_i->save();
        $user_i->attachRole($role_su);

        $user_b = new User();
        $user_b->nama     = 'bagas';
        $user_b->nama_lengkap    = 'bagas';
        $user_b->password = bcrypt('bagas@2019');
        $user_b->save();
        $user_b->attachRole($role_su);

        $user_t = new User();
        $user_t->nama     = 'tantan';
        $user_t->nama_lengkap    = 'tantan';
        $user_t->password = bcrypt('tantan@2019');
        $user_t->save();
        $user_t->attachRole($role_su);

        $user_f = new User();
        $user_f->nama     = 'fajar';
        $user_f->nama_lengkap    = 'fajar';
        $user_f->password = bcrypt('fajar@2019');
        $user_f->save();
        $user_f->attachRole($role_su);
    }
}
