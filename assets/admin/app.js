/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import '../styles/app.css'
import './css/style.css';
/* Additional styles */
import './css/additional-styles/utility-patterns.css';
import './css/additional-styles/flatpickr.css';

//Importmap imports
import {Alpine} from 'alpinejs';
import 'chart.js';
import 'moment';
import 'chartjs-adapter-moment';
import 'flatpickr'

//Other
import './js/main.js'
// import './js/dashboard-charts.js'
import './js/flatpickr-init.js'

window.Alpine = Alpine

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
