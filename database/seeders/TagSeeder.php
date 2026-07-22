<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'Remembering', 'slug' => 'การจดจำ'],
            ['name' => 'Understanding', 'slug' => 'การทำความเข้าใจ'],
            ['name' => 'Applying', 'slug' => 'การประยุกต์'],
            ['name' => 'Analyzing', 'slug' => 'การวิเคราะห์'],
            ['name' => 'Evaluating', 'slug' => 'การประเมิน'],
            ['name' => 'Creating', 'slug' => 'การสร้างสรรค์'],
        ];
        foreach ($tags as $tag) {
            DB::table('tags')->insert([
                'name' => $tag['name'],
                'slug' => $tag['slug'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
