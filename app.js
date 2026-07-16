function freshMartApp() {
    return {
        // --- State Autentikasi ---
        isLoggedIn: false,
        loginUsername: '',
        loginPassword: '',
        usernameAktif: 'vyan',
        passwordAktif: 'vyan2492',

        // Kontrol Tab Saat Ini
        currentTab: 'dashboard',
        
        // State Finansial Dashboard (Data Reaktif)
        totalOmset: 4120000,
        
        // Bank Data Barang Minimarket Modern (Mockup Terbaru)
        dataBarang: [
            { id: 101, nama: 'Roti Gandum Organik Whole Wheat', kategori: 'Bahan Pokok', harga: 24500, stok: 12, gambar: 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=400' },
            { id: 102, nama: 'Susu Almond Vanilla Drink 1L', kategori: 'Minuman', harga: 42000, stok: 18, gambar: 'https://images.unsplash.com/photo-1550583724-b2692b85b150?w=400' },
            { id: 103, nama: 'Granola Berry Crispy Pack', kategori: 'Cemilan', harga: 31500, stok: 3, gambar: 'https://images.unsplash.com/photo-1566478989037-eec170784d0b?w=400' },
            { id: 104, nama: 'Tisu Wajah Bamboo Eco-Friendly', kategori: 'Kebersihan', harga: 14000, stok: 25, gambar: 'https://images.unsplash.com/photo-1607006342456-ba275cd34840?w=400' },
            { id: 105, nama: 'Madu Alami Clover Honey Pure', kategori: 'Bahan Pokok', harga: 89000, stok: 7, gambar: 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=400' },
            { id: 106, nama: 'Keripik Tempe Oven Non-MSG', kategori: 'Cemilan', harga: 12500, stok: 40, gambar: 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=400' }
        ],
        
        // State Penyimpanan Keranjang Belanja
        cart: [],
        
        // Histori Laporan Transaksi Masuk
        riwayatPenjualan: [
            { id: 'INV/2026/0012', waktu: 'Hari ini, 07:15 WIB', deskripsi: 'Susu Almond Vanilla (x1), Roti Gandum Organik (x2)', jumlah: 91000 },
            { id: 'INV/2026/0013', waktu: 'Hari ini, 08:40 WIB', deskripsi: 'Tisu Wajah Bamboo (x3), Keripik Tempe Oven (x1)', jumlah: 54500 }
        ],

        // --- State Form Produk Baru ---
        showAddForm: false,
        newProduct: {
            nama: '',
            kategori: 'Bahan Pokok',
            harga: 0,
            stok: 0,
            gambar: ''
        },

        // --- Fungsi Autentikasi ---
        prosesLogin() {
            if (this.loginUsername === this.usernameAktif && this.loginPassword === this.passwordAktif) {
                this.isLoggedIn = true;
                this.currentTab = 'dashboard';
                // Reset input form login
                this.loginUsername = '';
                this.loginPassword = '';
            } else {
                alert('Username atau Password salah!');
            }
        },

        prosesLogout() {
            this.isLoggedIn = false;
            this.cart = []; // Opsional: Kosongkan keranjang saat logout
            alert('Anda telah keluar dari sistem.');
        },

        // --- Fungsi Tambah Produk Baru ---
        tambahProdukBaru() {
            if (!this.newProduct.nama || this.newProduct.harga <= 0 || this.newProduct.stok < 0) {
                alert('Harap isi nama, harga, dan stok dengan benar!');
                return;
            }

            // Fallback gambar jika kosong
            let gambarDefault = this.newProduct.gambar.trim() !== '' 
                ? this.newProduct.gambar 
                : 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=400';

            const produkBaru = {
                id: Date.now(), // Generate ID unik menggunakan timestamp
                nama: this.newProduct.nama,
                kategori: this.newProduct.kategori,
                harga: parseInt(this.newProduct.harga),
                stok: parseInt(this.newProduct.stok),
                gambar: gambarDefault
            };

            this.dataBarang.push(produkBaru);
            
            // Reset Form & Tutup Modal/Panel form
            this.newProduct = { nama: '', kategori: 'Bahan Pokok', harga: 0, stok: 0, gambar: '' };
            this.showAddForm = false;
            alert('Produk baru berhasil ditambahkan ke etalase!');
        },

        // --- Fungsi Restock Produk ---
        restockBarang(id) {
            let jumlahInput = prompt('Masukkan jumlah tambahan stok untuk produk ini:');
            if (jumlahInput === null) return; // Klik Batal

            let tambahan = parseInt(jumlahInput);
            if (isNaN(tambahan) || tambahan <= 0) {
                alert('Input jumlah tidak valid!');
                return;
            }

            let barang = this.dataBarang.find(b => b.id === id);
            if (barang) {
                barang.stok += tambahan;
                alert(`Stok ${barang.nama} berhasil ditambah sebanyak ${tambahan} unit.`);
            }
        },

        // Fungsi Memasukkan Produk ke Keranjang Belanja
        tambahKeBag(produkDipilih) {
            let itemDiKeranjang = this.cart.find(c => c.id === produkDipilih.id);
            if (itemDiKeranjang) {
                if (itemDiKeranjang.qty < produkDipilih.stok) {
                    itemDiKeranjang.qty++;
                } else {
                    alert('Maaf, kuantitas melebihi batas stok tersedia!');
                }
            } else {
                this.cart.push({ ...produkDipilih, qty: 1 });
            }
        },

        // Manipulasi Kuantitas Item di Keranjang Belanja (+ / -)
        ubahJumlah(id, arah) {
            let item = this.cart.find(c => c.id === id);
            let referensiBarang = this.dataBarang.find(b => b.id === id);
            
            if (item) {
                item.qty += arah;
                if (item.qty > referensiBarang.stok) {
                    item.qty = referensiBarang.stok;
                    alert('Stok maksimal tercapai.');
                }
                if (item.qty <= 0) {
                    this.hapusDariBag(id);
                }
            }
        },

        // Menghapus Item secara paksa dari Keranjang Belanja
        hapusDariBag(id) {
            this.cart = this.cart.filter(c => c.id !== id);
        },

        // Hitung Total Kotor Sebelum Ditambah Pajak
        hitungTotalKotor() {
            return this.cart.reduce((total, item) => total + (item.harga * item.qty), 0);
        },

        // Pemrosesan Bayar (Simulasi Penyimpanan Data POS)
        prosesBayar() {
            let totalBersih = Math.round(this.hitungTotalKotor() * 1.10); // Penghitungan Pajak 10%
            let ringkasanTeks = this.cart.map(i => `${i.nama} (x${i.qty})`).join(', ');
            let nomorInvoice = 'INV/2026/' + Math.floor(1000 + Math.random() * 9000);
            
            // Mengurangi stok fisik barang di daftar utama
            this.cart.forEach(itemCart => {
                let barangAsli = this.dataBarang.find(b => b.id === itemCart.id);
                if (barangAsli) barangAsli.stok -= itemCart.qty;
            });

            // Mendapatkan penanda waktu dinamis
            let jamSekarang = new Date();
            let teksWaktu = `Hari ini, ${jamSekarang.getHours().toString().padStart(2, '0')}:${jamSekarang.getMinutes().toString().padStart(2, '0')} WIB`;

            // Kirim data ke susunan Laporan
            this.riwayatPenjualan.unshift({
                id: nomorInvoice,
                waktu: teksWaktu,
                deskripsi: ringkasanTeks,
                jumlah: totalBersih
            });

            // Update Total Pendapatan di Dashboard Utama
            this.totalOmset += totalBersih;

            // Kosongkan Kantong & lempar halaman
            this.cart = [];
            alert(`Transaksi berhasil! Nomor Invoice: ${nomorInvoice}`);
            this.currentTab = 'laporan';
        }
    };
}