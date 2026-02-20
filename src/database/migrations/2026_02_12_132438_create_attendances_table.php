<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained()->cascadeOnDelete();
            $table->date('work_date');
            $table->time('clock_in')->nullable();
            $table->time('break_start')->nullable();
            $table->time('break_end')->nullable();
            $table->time('clock_out')->nullable();
            $table->string('status')->default('normal');
            $table->text('late_reason')->nullable();
            $table->integer('worked_minutes')->nullable();
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
        Schema::dropIfExists('attendances');
    }
}
