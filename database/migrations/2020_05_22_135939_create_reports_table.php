<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("userID");
            $table->foreign("userID")->references("id")->on("users");
            $table->unsignedBigInteger("projectID");
            $table->foreign("projectID")->references("id")->on("projects");
            $table->dateTime("date");
            $table->text("main_task", 2000);
            $table->text("sub_task", 2000);
            $table->text("next_sub_task", 2000);
            $table->text("extra_notes", 5000)->nullable();
            $table->unsignedBigInteger("updatedBy")->nullable();
            $table->foreign("updatedBy")->references("id")->on("users");
            $table->unsignedBigInteger("deletedBy")->nullable();
            $table->foreign("deletedBy")->references("id")->on("users");
            $table->boolean("status")->default(1);
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
        Schema::dropIfExists('reports');
    }
}
