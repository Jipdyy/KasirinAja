import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.store('sidebar', {
        open: false,

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

    Alpine.data('posCart', (initial) => ({
        cart: initial,
        busy: false,
        error: null,
        method: 'tunai',
        cash: '',

        get total() { return this.cart.total_amount; },
        get change() { return Math.max(0, (parseInt(this.cash) || 0) - this.total); },
        get enough() { return this.method === 'qris' || (parseInt(this.cash) || 0) >= this.total; },

        rp(n) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(n); },

        async send(url, method = 'POST', body = null) {
            this.busy = true;
            this.error = null;

            try {
                const res = await fetch(url, {
                    method,
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: body ? JSON.stringify(body) : null,
                });

                const data = await res.json();

                if (!res.ok) {
                    this.error = data.message ?? 'Terjadi kesalahan.';
                    return;
                }

                this.cart = data;
            } catch (e) {
                this.error = 'Gagal memuat produk.';
            } finally {
                this.busy = false;
            }
        },

        add(url) { return this.send(url); },
        setQty(item, qty) { return this.send(item.update_url, 'PATCH', { qty }); },
        remove(item) { return this.send(item.destroy_url, 'DELETE'); },
    }))
});

Alpine.start();