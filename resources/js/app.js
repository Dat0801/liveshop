import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Ensure Alpine starts after Livewire has fully loaded
window.deferLoadingAlpine = (callback) => {
	document.addEventListener('livewire:init', () => {
		callback();
	});
};

Alpine.start();
