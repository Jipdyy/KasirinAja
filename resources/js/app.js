//
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('alpine:init', () => {
    Alpine.store('sidebar', {
        open: true, 

        toggle() {
            this.open = !this.open;
        }
    });
});