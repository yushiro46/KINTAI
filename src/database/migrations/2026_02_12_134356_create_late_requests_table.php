<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('late_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained()->cascadeOnDelete();
            $table->foreignId('attendance_id')->constrained()->cascadeOnDelete();
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected'])
                ->default('pending');
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
        Schema::dropIfExists('late_requests');
    }
}
