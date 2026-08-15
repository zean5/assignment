/*
  Usage:
    - Place your service account JSON at the project root (serviceAccountKey.json)
    - Or set GOOGLE_APPLICATION_CREDENTIALS to the path
    - Run: node scripts/import-students-to-firestore.js storage/app/students_export.json

  This script reads the exported JSON and writes each student into Firestore
  in the `students` collection using the student id as document id.
*/

const fs = require('fs');
const path = require('path');
const admin = require('firebase-admin');

const keyPath = process.env.GOOGLE_APPLICATION_CREDENTIALS || path.join(process.cwd(), 'serviceAccountKey.json');
if (!fs.existsSync(keyPath)) {
  console.error('Service account JSON not found at', keyPath);
  process.exit(1);
}

admin.initializeApp({
  credential: admin.credential.cert(require(keyPath)),
});

const db = admin.firestore();

const input = process.argv[2] || path.join('storage', 'app', 'students_export.json');
if (!fs.existsSync(input)) {
  console.error('Export file not found:', input);
  process.exit(1);
}

const data = JSON.parse(fs.readFileSync(input, 'utf8'));

(async () => {
  for (const s of data) {
    const id = String(s.id || s.student_id || Date.now());
    const docRef = db.collection('students').doc(id);
    const payload = {
      student_id: Number(s.student_id),
      name: s.name,
      course: s.course,
      year_level: s.year_level,
      email: s.email,
      created_at: s.created_at || new Date().toISOString(),
      updated_at: s.updated_at || new Date().toISOString(),
    };
    await docRef.set(payload);
    console.log('Wrote student', id);
  }
  console.log('Import complete.');
})();
