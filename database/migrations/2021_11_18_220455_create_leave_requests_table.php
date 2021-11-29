<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('days');
            $table->foreignId('leave_type_id')->constrained('leave_types');
            $table->enum('full_leave',[0,1]);
            $table->enum('half_leave',['first','second'])->nullable();
            $table->longText('reason');
            $table->enum('acceptance',['pending','accepted','rejected']);
            $table->foreignId('accepted_by')->constrained('employees')->nullable(); 
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
        Schema::dropIfExists('leave_requests');
    }
}