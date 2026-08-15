<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('dashboard', ['students' => Student::query()->latest()->get()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required', 'numeric', 'min:0'],
            'name' => ['required', 'string', 'max:100'],
            'course' => ['required', 'string', 'max:50'],
            'year_level' => ['required', 'string', 'max:10'],
            'email' => ['required', 'email', 'string', 'max:100'],
        ]);

        Student::create($data);
        return redirect()->route('dashboard');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->show();
        return redirect()->route('dashboard');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'student_id' => ['required', 'numeric', 'min:0'],
            'name' => ['required', 'string', 'max:100'],
            'course' => ['required', 'string', 'max:50'],
            'year_level' => ['required', 'string', 'max:10'],
            'email' => ['required', 'email', 'string', 'max:100'],
        ]);

        $student->update($data);
        return redirect()->route('dashboard');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('dashboard');
    }
}
