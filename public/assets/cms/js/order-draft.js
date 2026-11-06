// Form tambah pesanan: kirim lewat fetch agar isian tidak hilang saat koneksi putus,
// dan simpan draft di localStorage agar bisa dipulihkan setelah reload atau tab tertutup.
// Bergantung pada jQuery dan fungsi global di view: createTableRow, updateTotals, updateQty.
(function () {
    const script = document.currentScript;
    const form = document.getElementById('orderForm');
    if (!form) return;

    const key = 'orderDraft:' + script.dataset.user + ':' + location.pathname;
    const successPath = new URL(script.dataset.success, location.href).pathname;
    const MAX_AGE = 24 * 60 * 60 * 1000; // draft lebih dari 1 hari dibuang, paket/harga bisa sudah berubah

    const store = {
        get() { try { return JSON.parse(localStorage.getItem(key)); } catch (e) { return null; } },
        set(v) { try { localStorage.setItem(key, JSON.stringify(v)); } catch (e) {} },
        clear() { try { localStorage.removeItem(key); } catch (e) {} },
    };

    function save() {
        const items = $('.transaksiItem tr').map(function () {
            const row = $(this);
            return {
                id: row.find('input[type=hidden]').first().val(),
                name: row.find('td').eq(0).text().trim(),
                price: parseInt(row.find('td').eq(1).text().replace(/[^0-9,-]/g, ''), 10),
                qty: row.find('.quantityInput').val(),
                sub: row.find('.sub_agent_item').val(),
            };
        }).get();

        if (!items.length) return store.clear();

        store.set({
            saved_at: Date.now(),
            order_number: form.elements.order_number.value,
            order_date: form.elements.order_date ? form.elements.order_date.value : null,
            agent_id: $('#agent_id').val(),
            items: items,
        });
    }

    function restore(d) {
        form.elements.order_number.value = d.order_number;
        if (d.order_date && form.elements.order_date) form.elements.order_date.value = d.order_date;

        const agent = document.getElementById('agent_id');
        if (agent && agent.tagName === 'SELECT' && d.agent_id) {
            agent.tomselect ? agent.tomselect.setValue(d.agent_id) : (agent.value = d.agent_id);
        }

        d.items.forEach(function (it) {
            const row = createTableRow(it.id, it.name, it.price, 1);
            $('.transaksiItem').append(row);
            updateTotals(it.price, 1);
            const input = row.find('.quantityInput').val(it.qty);
            updateQty(input[0]);
            // createTableRow mengisi opsi sub agent di setTimeout 0, jadi pilih sesudahnya
            setTimeout(function () { if (it.sub) row.find('.sub_agent_item').val(it.sub); }, 0);
        });
    }

    function showError(msg) {
        let box = document.getElementById('orderFormError');
        if (!box) {
            box = document.createElement('div');
            box.id = 'orderFormError';
            box.className = 'alert alert-danger mb-4';
            box.setAttribute('role', 'alert');
            form.prepend(box);
        }
        box.textContent = msg;
        box.scrollIntoView({ block: 'center' });
    }

    window.addEventListener('load', function () {
        const d = store.get();
        if (d && d.items && d.items.length && Date.now() - d.saved_at < MAX_AGE) {
            if (confirm('Ada isian pesanan yang belum tersimpan (' + d.items.length + ' item). Pulihkan?')) restore(d);
            else store.clear();
        } else {
            store.clear();
        }

        new MutationObserver(save).observe(document.querySelector('.transaksiItem'), { childList: true });
        form.addEventListener('change', save);
    });

    // simpan() di view memanggil $('#orderForm').submit(), lalu tombol submit ikut men-submit;
    // handler jQuery menangkap keduanya, flag `sending` mencegah kirim dua kali.
    let sending = false;
    $(form).on('submit', async function (e) {
        e.preventDefault();
        if (sending) return;
        sending = true;
        save();

        const btn = form.querySelector('button[type=submit]');
        btn.disabled = true;
        let msg;

        try {
            const res = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'X-Order-Fetch': '1' },
            });
            const url = new URL(res.url);

            if (res.redirected && url.pathname === successPath) {
                store.clear();
                location.href = res.url;
                return;
            }

            if (res.status === 419 || url.pathname === '/login') {
                msg = 'Sesi login habis. Muat ulang halaman, isian bisa dipulihkan setelahnya.';
            } else {
                const doc = new DOMParser().parseFromString(await res.text(), 'text/html');
                const el = doc.querySelector('.alert .text-danger, .error-page .text-lg');
                msg = el ? el.textContent.trim() : 'Pesanan gagal disimpan (kode ' + res.status + ').';
            }
        } catch (err) {
            msg = 'Gagal terhubung ke server. Isian masih ada, periksa koneksi lalu tekan Simpan lagi.';
        }

        sending = false;
        btn.disabled = false;
        showError(msg);
    });
})();
