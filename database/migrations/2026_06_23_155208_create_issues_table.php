<?php

use App\Enums\IssuePriority;
use App\Enums\IssueStatus;
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
            Schema::create('issues', function (Blueprint $table): void {
                $table->id();

                $table->foreignId('project_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->string('title');

                $table->text('description');

                $table->enum(
                    'status',
                    array_column(IssueStatus::cases(), 'value')
                )->default(IssueStatus::Open->value);

                $table->enum(
                    'priority',
                    array_column(IssuePriority::cases(), 'value')
                )->default(IssuePriority::Medium->value);

                $table->dateTime('due_date')->nullable();

                $table->timestamps();

                $table->index('status');
                $table->index('priority');
                $table->index('due_date');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};
