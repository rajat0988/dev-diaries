<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add indexes for better query performance on low-resource servers
     */
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // Index for ordering by created_at (most common query)
            $table->index('created_at');

            // Index for filtering reported items
            $table->index('reported');

            // Index for user queries
            $table->index('user_id');

            // Composite index for common filters
            $table->index(['Answered', 'created_at']);
        });

        Schema::table('replies', function (Blueprint $table) {
            // Index for question_id lookups (foreign key should already have this)
            $table->index('question_id');

            // Index for user queries
            $table->index('user_id');

            // Index for reported items
            $table->index('reported');

            // Index for ordering
            $table->index('created_at');
        });

        Schema::table('votes', function (Blueprint $table) {
            // Composite index for votable polymorphic relation
            $table->index(['votable_type', 'votable_id']);

            // Index for user lookups
            $table->index('user_id');

            // Composite index for checking user votes
            $table->index(['user_id', 'votable_type', 'votable_id'], 'votes_user_votable_index');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['reported']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['Answered', 'created_at']);
        });

        Schema::table('replies', function (Blueprint $table) {
            $table->dropIndex(['question_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['reported']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('votes', function (Blueprint $table) {
            $table->dropIndex(['votable_type', 'votable_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex('votes_user_votable_index');
        });
    }
};
