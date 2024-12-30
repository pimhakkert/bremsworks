/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import '../styles/app.css'
import './css/style.css';
import './css/cruip.css';
/* Additional styles */
import './css/additional-styles/utility-patterns.css';
import './css/additional-styles/flatpickr.css';

//Importmap imports
import {Alpine} from 'alpinejs';
import 'chart.js';
import 'moment';
import 'chartjs-adapter-moment';
import 'flatpickr'
import 'https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js';
import 'tom-select/dist/css/tom-select.default.min.css';

//Other
import './js/main.js'
// import './js/dashboard-charts.js'
import './js/flatpickr-init.js'

window.Alpine = Alpine
