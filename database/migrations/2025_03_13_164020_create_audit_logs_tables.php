<?php

declare(strict_types=1);

namespace MNarushevich\AuditLogs\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table): void {
            $table->id();
            $table->string('auditable_type');
            $table->uuid('auditable_uuid')->nullable();
            $table->unsignedBigInteger('auditable_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->uuid('user_uuid')->nullable();
            $table->enum('event', ['created', 'updated', 'deleted', 'restored']);
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            // Indexes for fast lookup
            $table->index(['auditable_type', 'auditable_id']);
            $table->index(['user_id', 'event', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
