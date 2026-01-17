import './bootstrap.js';
import './styles/app.css';
import jquery from 'jquery';

// Make jQuery globally available (ensure it's the function export)
window.$ = jquery;
window.jQuery = jquery;

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
console.log('jQuery loaded (type):', typeof window.$, window.$ && window.$.fn ? 'has .fn' : 'no .fn');
