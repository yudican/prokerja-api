<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_apply_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_vacancy_id');
            $table->foreignId('job_vacancy_test_id');
            $table->foreignUuid('user_id');
            $table->string('test_file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_apply_tests');
    }
};
