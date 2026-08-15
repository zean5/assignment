<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;

class ExportStudents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:students {--path= : File path to write (storage/app/students_export.json)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export students table to a JSON file for Firestore import';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $path = $this->option('path') ?: storage_path('app/students_export.json');
        $students = Student::all()->map(function ($s) {
            return [
                'id' => $s->id,
                'student_id' => $s->student_id,
                'name' => $s->name,
                'course' => $s->course,
                'year_level' => $s->year_level,
                'email' => $s->email,
                'created_at' => optional($s->created_at)->toDateTimeString(),
                'updated_at' => optional($s->updated_at)->toDateTimeString(),
            ];
        });

        file_put_contents($path, $students->toJson(JSON_PRETTY_PRINT));

        $this->info("Exported {$students->count()} students to: {$path}");
        return 0;
    }
}
