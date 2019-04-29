<?php

namespace Lvqingan\Test\MockModels;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;

class Migrator {

    public function up() {
        DB::schema()->dropIfExists('users');

        DB::schema()->create('users', function(Blueprint $t) {
            $t->increments('id');

            $t->string('name');
            $t->char('shengshiqu', 6);

            $t->index(['shengshiqu']);

            $t->softDeletes();
        });

        DB::schema()->dropIfExists('contacts');

        DB::schema()->create('contacts', function(Blueprint $t) {
            $t->increments('id');

            $t->string('name');
            $t->char('region', 6);

            $t->index(['region']);

            $t->softDeletes();
        });
    }

    public function down() {
        DB::schema()->drop('users');
        DB::schema()->drop('contacts');
    }
}
