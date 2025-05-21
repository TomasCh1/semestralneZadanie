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

        foreach ($items as $item) {
            // insert question
            if($item['type'] == "MCQ"){
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

                foreach($item['areas'] as $areaName){
                    $area = DB::table('areas')
                        ->where('name', $areaName['name'])
                        ->first(['area_id']);

                    if($area){
                        $areaId = $area->area_id;
                    }
                    else{
                        $areaId = DB::table('areas')->insertGetId([
                            'name' => $areaName['name'],
                        ]);
                    }
                    DB::table('question_areas')->insert([
                        'question_id' => $questionId,
                        'area_id' => $areaId,
                    ]);
                }



                foreach ($item['languages'] as $languageName) {
                    $languageExist = DB::table('languages')
                        ->where('lang_name', $languageName['lang_name'])
                        ->first(['lang_id']);

                    if($languageExist){
                        $languageId = $languageExist->lang_id;
                    }
                    else{
                        $languageId = DB::table('languages')->insertGetId([
                            'lang_code' => $languageName['lang_code'],
                            'lang_name' => $languageName['lang_name'],
                        ]);
                    }
                    DB::table('question_translations')->insert([
                        'question_id' => $questionId,
                        'lang_id' => $languageId,
                    ]);
                }


            } else{
                $questionId = DB::table('questions')->insertGetId([
                    'type' => $item['type'],
                    'question_text'=> $item['question_text'],
                    'correct_answer' => $item['correct_answer'],
                    'created_at' => $now,
                ]);

                foreach ($item['areas'] as $areaName) {
                    $area = DB::table('areas')
                        ->where('name', $areaName['name'])
                        ->first(['area_id']);
                }

                if ($area){
                    $areaId = $area->area_id;
                }
                else{
                    $areaId = DB::table('areas')->insertGetId([
                        'name' => $areaName['name'],
                    ]);
                }

                DB::table('question_areas')->insert([
                    'question_id' => $questionId,
                    'area_id' => $areaId,
                ]);

                foreach ($item['languages'] as $language) {
                    $languageExist = DB::table('languages')
                        ->where('lang_code', $language['lang_code'])
                        ->first(['lang_id']);

                    if($languageExist){
                        $languageId = $languageExist->lang_id;
                    }
                    else{
                        $languageId = DB::table('languages')->insertGetId([
                            'lang_code' => $language['lang_code'],
                            'lang_name' => $language['lang_name'],
                        ]);
                    }
                    DB::table('question_translations')->insert([
                        'question_id' => $questionId,
                        'lang_id' => $languageId,
                    ]);
                }
            }

            // assign to area_id = 1
            /*DB::table('question_areas')->insert([
                'question_id' => $questionId,
                'area_id'     => 1,
            ]);*/
        }
    }
}
