import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.store('sidebar', {
        open: true,

        toggle() {
            this.open = !this.open;
        }
    });

    Alpine.store('theme', {
        dark: document.documentElement.classList.contains('dark'),

        toggle() {
            this.dark = !this.dark;
            document.documentElement.classList.toggle('dark', this.dark);
            localStorage.setItem('theme', this.dark ? 'dark' : 'light');
        }
    });

    Alpine.store('modal', {
        current: null,
        payload: {},

        open(id, payload = {}) {
            this.current = id;
            this.payload = payload;
        },

        close(){
            this.current = null;
            this.payload = {};
        }
    })
});

Alpine.start();