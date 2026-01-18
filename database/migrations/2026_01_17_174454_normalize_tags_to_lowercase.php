<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $questions = DB::table("questions")->select("id", "Tags")->get();

        foreach ($questions as $question) {
            if (empty($question->Tags)) {
                continue;
            }

            $tags = json_decode($question->Tags, true);
            
            if (is_string($tags)) {
                $tags = json_decode($tags, true);
            }
            
            if (!is_array($tags)) {
                if (is_string($question->Tags) && !str_starts_with($question->Tags, "[")) {
                     $tags = array_map("trim", explode(",", $question->Tags));
                } else {
                     continue;
                }
            }
            
            if (is_array($tags)) {
                $uniqueLowerTags = array_unique(array_map("strtolower", $tags));
                $normalizedTags = array_values($uniqueLowerTags);

                DB::table("questions")
                    ->where("id", $question->id)
                    ->update(["Tags" => json_encode($normalizedTags)]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No simple reverse as we lost the original casing information.
    }
};
