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

        $items = [
            // ----- Test A questions -----
            [
                'type'        => 'MCQ',
                'question_text' => 'Je daná lineárna funkcia f(x) = 2x + 4. Nájdite jej nulové body.',
                'text_latex'  => 'Je daná lineárna funkcia $f(x) = 2x + 4$. Nájdite jej nulové body.',
                'choices'     => [
                    ['text' => 'Má jeden nulový bod: 4', 'text_latex' => 'Má jeden nulový bod: $4$', 'is_correct' => false],
                    ['text' => 'Žiadna z ostatných odpovedí nie je pravdivá.', 'text_latex' => 'Žiadna z ostatných odpovedí nie je pravdivá.', 'is_correct' => true],
                    ['text' => 'Má dva nulové body: 2 a 4', 'text_latex' => 'Má dva nulové body: $2$ a $4$', 'is_correct' => false],
                    ['text' => 'Má jeden nulový bod: 2', 'text_latex' => 'Má jeden nulový bod: $2$', 'is_correct' => false],
                    ['text' => 'Nemá žiadny nulový bod.', 'text_latex' => 'Nemá žiadny nulový bod.', 'is_correct' => false],
                ],
            ],
            // ... (ostatné otázky z test_A)

            // ----- Test B questions -----
            [
                'type'        => 'MCQ',
                'question_text' => 'O trojuholníku ABC je vyslovený výrok: Ak má trojuholník ABC zhodné uhly pri vrcholoch A a B, tak je rovnostranný. Negáciou tohto výroku je:',
                'text_latex'  => 'O trojuholníku $ABC$ je vyslovený výrok: Ak má trojuholník $ABC$ zhodné uhly pri vrcholoch $A$ a $B$, tak je rovnostranný. Negáciou tohto výroku je:',
                'choices'     => [
                    ['text' => 'Ak má trojuholník ABC zhodné uhly pri vrcholoch A a B, tak je rovnoramenný.', 'text_latex' => 'Ak má trojuholník $ABC$ zhodné uhly pri vrcholoch $A$ a $B$, tak je rovnoramenný.', 'is_correct' => false],
                    ['text' => 'Ak je trojuholník ABC rovnostranný, tak má zhodné uhly pri vrcholoch A a B.', 'text_latex' => 'Ak je trojuholník $ABC$ rovnostranný, tak má zhodné uhly pri vrcholoch $A$ a $B$.', 'is_correct' => false],
                    ['text' => 'Trojuholník ABC má zhodné uhly pri vrcholoch A a B, a nie je rovnostranný.', 'text_latex' => 'Trojuholník $ABC$ má zhodné uhly pri vrcholoch $A$ a $B$, a nie je rovnostranný.', 'is_correct' => true],
                    ['text' => 'Trojuholník ABC je rovnostranný a nemá zhodné uhly pri vrcholoch A a B.', 'text_latex' => 'Trojuholník $ABC$ je rovnostranný a nemá zhodné uhly pri vrcholoch $A$ a $B$.', 'is_correct' => false],
                    ['text' => 'Ak má trojuholník ABC zhodné uhly pri vrcholoch A, B a C, tak je rovnostranný.', 'text_latex' => 'Ak má trojuholník $ABC$ zhodné uhly pri vrcholoch $A, B$ a $C$, tak je rovnostranný.', 'is_correct' => false],
                ],
            ],
            [
                'type'        => 'MCQ',
                'question_text' => 'Je daná lineárna funkcia f(x) = 3x − 1. Nájdite jej nulové body.',
                'text_latex'  => 'Je daná lineárna funkcia $f(x) = 3x - 1$. Nájdite jej nulové body.',
                'choices'     => [
                    ['text' => 'Žiadna z ostatných odpovedí nie je pravdivá.', 'text_latex' => 'Žiadna z ostatných odpovedí nie je pravdivá.', 'is_correct' => false],
                    ['text' => 'Nemá žiaden nulový bod.', 'text_latex' => 'Nemá žiaden nulový bod.', 'is_correct' => false],
                    ['text' => 'Má dva nulové body: 1 a 3', 'text_latex' => 'Má dva nulové body: $1$ a $3$', 'is_correct' => false],
                    ['text' => 'Má jeden nulový bod: 1/3', 'text_latex' => 'Má jeden nulový bod: $\tfrac{1}{3}$', 'is_correct' => false],
                    ['text' => 'Má jeden nulový bod: 1', 'text_latex' => 'Má jeden nulový bod: $1$', 'is_correct' => true],
                ],
            ],
            // ... (ďalšie otázky z test_B)

            // ----- Test C questions -----
            [
                'type'        => 'MCQ',
                'question_text' => 'Množina všetkých riešení rovnice sin x = √3 cos x je:',
                'text_latex'  => 'Množina všetkých riešení rovnice $\sin x = \sqrt{3}\cos x$ je:',
                'choices'     => [
                    ['text' => '{kπ; k ∈ Z} ∪ {π/3 + kπ; k ∈ Z}', 'text_latex' => '$\{k\pi; k \in \mathbb{Z}\} \cup \{\tfrac{\pi}{3} + k\pi; k \in \mathbb{Z}\}$', 'is_correct' => false],
                    ['text' => '{π/3 + kπ; k ∈ Z}', 'text_latex' => '$\{\tfrac{\pi}{3} + k\pi; k \in \mathbb{Z}\}$', 'is_correct' => true],
                    ['text' => '{kπ; k ∈ Z}', 'text_latex' => '$\{k\pi; k \in \mathbb{Z}\}$', 'is_correct' => false],
                    ['text' => 'prázdna množina.', 'text_latex' => 'prázdna množina.', 'is_correct' => false],
                    ['text' => '{π/6 + kπ; k ∈ Z}', 'text_latex' => '$\{\tfrac{\pi}{6} + k\pi; k \in \mathbb{Z}\}$', 'is_correct' => false],
                ],
            ],
            [
                'type'        => 'MCQ',
                'question_text' => 'Kvadratická rovnica 2x^2 + 7x - 4 = 0 má:',
                'text_latex'  => 'Kvadratická rovnica $2x^2 + 7x - 4 = 0$ má:',
                'choices'     => [
                    ['text' => 'Práve dve riešenia, ktorých súčet je číslo -7.', 'text_latex' => 'Práve dve riešenia, ktorých súčet je číslo $-7$.', 'is_correct' => false],
                    ['text' => 'Práve dve záporné riešenia.', 'text_latex' => 'Práve dve záporné riešenia.', 'is_correct' => false],
                    ['text' => 'Práve dve riešenia, ktorých súčin je číslo -2.', 'text_latex' => 'Práve dve riešenia, ktorých súčin je číslo $-2$.', 'is_correct' => true],
                    ['text' => 'Práve dve riešenia, ktorých súčin je číslo -4.', 'text_latex' => 'Práve dve riešenia, ktorých súčin je číslo $-4$.', 'is_correct' => false],
                    ['text' => 'Práve dve kladné riešenia.', 'text_latex' => 'Práve dve kladné riešenia.', 'is_correct' => false],
                ],
            ],
            // ... (ďalšie otázky z test_C)
        ];

        foreach ($items as $item) {
            // insert question
            $questionId = DB::table('questions')->insertGetId([
                'type' => $item['type'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // insert translation
            DB::table('question_translations')->insert([
                'question_id' => $questionId,
                'locale'      => 'sk',
                'text'        => $item['question_text'],
                'text_latex'  => $item['text_latex'],
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);

            // insert choices
            foreach ($item['choices'] as $choice) {
                $choiceId = DB::table('choices')->insertGetId([
                    'question_id' => $questionId,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);

                DB::table('choice_translations')->insert([
                    'choice_id'   => $choiceId,
                    'locale'      => 'sk',
                    'text'        => $choice['text'],
                    'text_latex'  => $choice['text_latex'],
                    'is_correct'  => $choice['is_correct'],
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
            }

            // assign to area_id = 1
            DB::table('question_areas')->insert([
                'question_id' => $questionId,
                'area_id'     => 1,
            ]);
        }
    }
}
