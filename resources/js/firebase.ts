import { initializeApp } from 'firebase/app';
import { getAnalytics } from 'firebase/analytics';
import { getFirestore } from 'firebase/firestore';

const firebaseConfig = {
  apiKey: import.meta.env.VITE_FIREBASE_API_KEY || 'AIzaSyCg-9-ukIi_ceLHvp5YgJVRnm5UHuu1PNM',
  authDomain: import.meta.env.VITE_FIREBASE_AUTH_DOMAIN || 'activity-64a57.firebaseapp.com',
  projectId: import.meta.env.VITE_FIREBASE_PROJECT_ID || 'activity-64a57',
  storageBucket: import.meta.env.VITE_FIREBASE_STORAGE_BUCKET || 'activity-64a57.firebasestorage.app',
  messagingSenderId: import.meta.env.VITE_FIREBASE_MESSAGING_SENDER_ID || '136989036158',
  appId: import.meta.env.VITE_FIREBASE_APP_ID || '1:136989036158:web:0c166f0adb9461ac8f522f',
  measurementId: import.meta.env.VITE_FIREBASE_MEASUREMENT_ID || 'G-0TNFK0SX71',
};

export const firebaseApp = initializeApp(firebaseConfig);
export const analytics = typeof window !== 'undefined' ? getAnalytics(firebaseApp) : null;
export const db = getFirestore(firebaseApp);

// Export other services as needed
// import { getAuth } from 'firebase/auth';
// export const auth = getAuth(firebaseApp);
