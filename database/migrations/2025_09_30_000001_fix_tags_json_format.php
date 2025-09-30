<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Fix existing Tags data to be proper JSON arrays
     */
    public function up(): void
    {
        // Get all questions
        $questions = DB::table('questions')->select('id', 'Tags')->get();

        foreach ($questions as $question) {
            if (empty($question->Tags)) {
                continue;
            }

            $tags = $question->Tags;
            $fixedTags = null;

            // Check if it's already a valid JSON array
            $decoded = json_decode($tags, true);

            if (is_array($decoded)) {
                // Already valid JSON array
                $fixedTags = json_encode($decoded);
            } elseif (is_string($tags)) {
                // Might be comma-separated string, convert to JSON array
                $tagArray = array_map('trim', explode(',', $tags));
                $fixedTags = json_encode($tagArray);
            }

            // Update if we have fixed tags
            if ($fixedTags !== null && $fixedTags !== $tags) {
                DB::table('questions')
                    ->where('id', $question->id)
                    ->update(['Tags' => $fixedTags]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse - data is now in correct format
    }
};
