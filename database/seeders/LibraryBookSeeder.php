<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LibraryBookSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $books = [
            ['title' => 'Science Fundamentals',       'author' => 'John Doe',          'isbn' => '978-0-111111-11-1', 'quantity' => 15],
            ['title' => 'Mathematics Class 8',        'author' => 'Punjab Textbook',    'isbn' => '978-0-222222-22-2', 'quantity' => 20],
            ['title' => 'English Grammar & Comp.',    'author' => 'Wren & Martin',      'isbn' => '978-0-333333-33-3', 'quantity' => 10],
            ['title' => 'Pakistan Studies',           'author' => 'NCTB',               'isbn' => '978-0-444444-44-4', 'quantity' => 12],
            ['title' => 'Islamic Studies Grade 5',    'author' => 'Punjab Textbook',    'isbn' => '978-0-555555-55-5', 'quantity' => 18],
            ['title' => 'Urdu Ki Duniya',             'author' => 'Ikhlaaq Ahmed',      'isbn' => '978-0-666666-66-6', 'quantity' => 8],
            ['title' => 'Computer Science Basics',    'author' => 'Richard Dale',       'isbn' => '978-0-777777-77-7', 'quantity' => 7],
            ['title' => 'General Knowledge Book',     'author' => 'General Press',      'isbn' => '978-0-888888-88-8', 'quantity' => 25],
            ['title' => 'Stories for Children',       'author' => 'Roald Dahl',         'isbn' => '978-0-999999-99-9', 'quantity' => 5],
            ['title' => 'Drawing & Craft Guide',      'author' => 'Art Academy',        'isbn' => '978-1-000000-00-1', 'quantity' => 6],
        ];

        foreach ($books as $book) {
            DB::table('library_books')->insert([
                'title'      => $book['title'],
                'author'     => $book['author'],
                'isbn'       => $book['isbn'],
                'quantity'   => $book['quantity'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $this->command->info('✔ LibraryBookSeeder: ' . count($books) . ' books inserted.');
    }
}
