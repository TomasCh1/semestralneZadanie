<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MSQSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $items = json_decode(
            file_get_contents(database_path('seeders/data/questions.json')),
            true
        );

        $items = json_decode(
            file_get_contents(database_path('seeders/data/questions.json')),
            true
        );

        foreach ($items as $item) {
            // insert question
            $questionId = DB::table('questions')->insertGetId([
                'type' => $item['type'],
                'question_text'=> $item['question_text'],
                'created_at' => $now,
            ]);

            // insert translation



            // insert choices
            foreach ($item['choices'] as $choice) {
                $choiceId = DB::table('choices')->insertGetId([
                    'question_id' => $questionId,
                    'text' => $choice['text'],
                    'is_correct' => $choice['is_correct'],
                ]);

            }

            // assign to area_id = 1
            /*DB::table('question_areas')->insert([
                'question_id' => $questionId,
                'area_id'     => 1,
            ]);*/
        }
    }
}
