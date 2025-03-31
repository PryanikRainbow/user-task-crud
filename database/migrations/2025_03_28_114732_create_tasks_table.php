<?php

use App\Models\Task;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->text('description');
            $table->enum('status', [
                Task::NEW_STATUS,
                Task::IN_PROGRESS_STATUS,
                Task::FAILED_STATUS,
                Task::FINISHED_STATUS
            ])
                ->default(Task::NEW_STATUS)
                ->index();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('start_date_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
