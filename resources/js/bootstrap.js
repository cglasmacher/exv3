import axios from 'axios';

// Sanctum: Cookies zulassen und CSRF-Cookie setzen
axios.defaults.withCredentials = true;
axios.get('/sanctum/csrf-cookie');

// Optional: weitere globale JS-Imports
// import 'bootstrap/dist/css/bootstrap.css';