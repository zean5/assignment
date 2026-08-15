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
        return Inertia::render('dashboard',['students'=>Student::query()->latest()->get()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     //
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    public function store(Request $request)
    {
        $data=$request->validate([
            'student_id'=>'required|unique:students,student_id',
            'name'=>'required',
            'course'=>'required',
            'year_level'=>'required|integer',
            'email'=>'required|email|unique:students,email',
        ]);
        Student::create($data);
        return redirect()->route('database');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->show();
        return redirect()->route('database');
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Student $student)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    public function update(Request $request, Student $student)
    {
        $data=$request->validate([
            'student_id'=>'required|unique:students,student_id,'.$student->id,
            'name'=>'required',
            'course'=>'required',
            'year_level'=>'required|integer',
            'email'=>'required|email|unique:students,email,'.$student->id,
        ]);
        $student->update($data);
        return redirect()->route('database');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('database');
    }
}
