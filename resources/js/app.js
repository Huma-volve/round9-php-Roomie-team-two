import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Log للتأكد من تحميل Echo
console.log('Echo loaded:', typeof Echo !== 'undefined');