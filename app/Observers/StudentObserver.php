<?php

namespace App\Observers;

use App\Models\Student;
use App\Services\FirestoreService;

class StudentObserver
{
    protected $firestore;

    public function __construct()
    {
        $this->firestore = new FirestoreService();
    }

    public function created(Student $student)
    {
        $this->firestore->setDocument('students', (string) $student->id, $student->toArray());
    }

    public function updated(Student $student)
    {
        $this->firestore->setDocument('students', (string) $student->id, $student->toArray());
    }

    public function deleted(Student $student)
    {
        $this->firestore->deleteDocument('students', (string) $student->id);
    }
}
