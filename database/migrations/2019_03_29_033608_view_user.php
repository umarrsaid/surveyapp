<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ViewUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement( 'CREATE OR REPLACE VIEW view_user AS 
                        SELECT 
                            role_user.*,
                            users.nama, users.nama_lengkap, users.status,
                            roles.display_name
                        FROM role_user
                        LEFT JOIN users
                        ON users.id = role_user.user_id
                        LEFT JOIN roles
                        ON roles.id = role_user.role_id
                    ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // DB::statement( 'DROP VIEW IF EXISTS view_user' );
    }
}
