<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreshMart OS - Sistem Minimarket Modern</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="style.css">
    
    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body x-data="freshMartApp()">

    <!-- ================= SCENARIO 1: HALAMAN LOGIN ================= -->
    <template x-if="!isLoggedIn">
        <div class="login-wrapper">
            <div class="login-card animate-fade">
                <div class="login-header">
                    <div class="brand-icon" style="margin: 0 auto 16px auto;">
                        <i class="fa-solid fa-leaf"></i>
                    </div>
                    <h2>Masuk FreshMart OS</h2>
                    <p>Masukkan kredensial kasir Anda untuk mengakses dashboard.</p>
                </div>
                <form @submit.prevent="prosesLogin()">
                    <div class="form-group-login">
                        <label>Username</label>
                        <input type="text" x-model="loginUsername" placeholder="Masukkan username" required class="input-minimal" style="width: 100%; border-radius: 12px; margin-bottom: 16px;">
                    </div>
                    <div class="form-group-login">
                        <label>Password</label>
                        <input type="password" x-model="loginPassword" placeholder="Masukkan password" required class="input-minimal" style="width: 100%; border-radius: 12px; margin-bottom: 24px;">
                    </div>
                    <button type="submit" class="btn-checkout-block">Login Aplikasi</button>
                </form>
            </div>
        </div>
    </template>

    <!-- ================= SCENARIO 2: AREA WORKSPACE (SETELAH LOGIN) ================= -->
    <template x-if="isLoggedIn">
        <div class="workspace-wrapper">
            <!-- NAV BAR DENGAN TOMBOL LOGOUT -->
            <nav class="top-nav">
                <div class="nav-container">
                    <div class="nav-brand">
                        <div class="brand-icon">
                            <i class="fa-solid fa-leaf"></i>
                        </div>
                        <div class="brand-titles">
                            <span class="title-main">MetroMart</span>
                            <span class="title-sub">Smart POS System</span>
                        </div>
                    </div>
                    
                    <div class="nav-links">
                        <button @click="currentTab = 'dashboard'" :class="currentTab === 'dashboard' ? 'tab-link active' : 'tab-link'">
                            <i class="fa-solid fa-chart-simple"></i> Dashboard
                        </button>
                        <button @click="currentTab = 'katalog'" :class="currentTab === 'katalog' ? 'tab-link active' : 'tab-link'">
                            <i class="fa-solid fa-basket-shopping"></i> Etalase Barang
                        </button>
                        <button @click="currentTab = 'keranjang'" :class="currentTab === 'keranjang' ? 'tab-link active' : 'tab-link'">
                            <i class="fa-solid fa-bag-shopping"></i> Kantong Belanja
                            <span x-show="cart.length > 0" class="cart-dot" x-text="cart.reduce((sum, item) => sum + item.qty, 0)"></span>
                        </button>
                        <button @click="currentTab = 'laporan'" :class="currentTab === 'laporan' ? 'tab-link active' : 'tab-link'">
                            <i class="fa-solid fa-chart-line"></i> Rekap Penjualan
                        </button>
                    </div>

                    <div class="nav-user">
                        <div class="user-text">
                            <span class="u-name">Vyandra Ardhito Pratama</span>
                            <span class="u-status">Kasir Utama</span>
                        </div>
                        <div class="u-avatar">VA</div>
                        <!-- Logout Button -->
                        <button @click="prosesLogout()" class="btn-logout" title="Keluar dari Akun">
                            <i class="fa-solid fa-power-off"></i>
                        </button>
                    </div>
                </div>
            </nav>

            <main class="container-workspace">

                <!-- TAB DASHBOARD -->
                <div x-show="currentTab === 'dashboard'" class="tab-pane animate-fade">
                    <div class="dashboard-welcome">
                        <div class="welcome-left">
                            <h1>Ringkasan Toko Hari Ini</h1>
                            <p>Sistem diperbarui otomatis secara real-time pada jam operasi kasir.</p>
                        </div>
                        <div class="welcome-right">
                            <button @click="currentTab = 'katalog'" class="btn-action-primary"><i class="fa-solid fa-bolt"></i> Transaksi Baru</button>
                        </div>
                    </div>

                    <div class="grid-metrics">
                        <div class="metric-box box-mint">
                            <div class="box-head">
                                <span>Total Omset</span>
                                <i class="fa-solid fa-money-bill-trend-up"></i>
                            </div>
                            <h2 x-text="'Rp ' + totalOmset.toLocaleString()"></h2>
                            <p class="box-footer">Kenaikan +12% dibanding kemarin</p>
                        </div>
                        <div class="metric-box">
                            <div class="box-head">
                                <span>Nota Lunas</span>
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                            <h2 x-text="riwayatPenjualan.length + ' Nota'"></h2>
                            <p class="box-footer text-muted">Semua transaksi sukses dikunci</p>
                        </div>
                        <div class="metric-box">
                            <div class="box-head">
                                <span>Kategori Aktif</span>
                                <i class="fa-solid fa-tags"></i>
                            </div>
                            <h2>4 Grup</h2>
                            <p class="box-footer text-muted">Bahan pokok, Cemilan, Kebersihan, Minuman</p>
                        </div>
                    </div>
                </div>

                <!-- TAB ETALASE / KATALOG -->
                <div x-show="currentTab === 'katalog'" class="tab-pane animate-fade">
                    <div class="catalog-header-bar">
                        <h2>Pilih Produk Pelanggan</h2>
                        <div style="display: flex; gap: 16px; align-items: center;">
                            <button @click="showAddForm = !showAddForm" class="btn-action-primary" style="background-color: var(--slate-dark);">
                                <i class="fa-solid fa-plus"></i> Produk Baru
                            </button>
                            <div class="search-wrapper">
                                <input type="text" placeholder="Ketik nama atau scan barcode item..." class="input-minimal">
                                <i class="fa-solid fa-barcode"></i>
                            </div>
                        </div>
                    </div>

                    <!-- FORM INPUT PRODUK BARU -->
                    <div x-show="showAddForm" class="form-add-product animate-fade" style="background: white; padding: 24px; border-radius: var(--radius-super); margin-bottom: 32px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
                        <h3 style="margin-bottom: 16px; font-size: 16px;">Tambah Barang Baru ke Etalase</h3>
                        <form @submit.prevent="tambahProdukBaru()" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; align-items: flex-end;">
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: var(--slate-muted);">Nama Produk</label>
                                <input type="text" x-model="newProduct.nama" placeholder="Contoh: Apel Fuji Segar" required class="input-minimal" style="width: 100%; border-radius: 8px; margin-top: 4px;">
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: var(--slate-muted);">Kategori</label>
                                <select x-model="newProduct.kategori" class="select-minimal" style="width: 100%; border-radius: 8px; margin-top: 4px; height: 42px;">
                                    <option value="Bahan Pokok">Bahan Pokok</option>
                                    <option value="Minuman">Minuman</option>
                                    <option value="Cemilan">Cemilan</option>
                                    <option value="Kebersihan">Kebersihan</option>
                                </select>
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: var(--slate-muted);">Harga Jual (Rp)</label>
                                <input type="number" x-model="newProduct.harga" required min="1" class="input-minimal" style="width: 100%; border-radius: 8px; margin-top: 4px;">
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: var(--slate-muted);">Stok Awal</label>
                                <input type="number" x-model="newProduct.stok" required min="0" class="input-minimal" style="width: 100%; border-radius: 8px; margin-top: 4px;">
                            </div>
                            <div>
                                <label style="font-size: 12px; font-weight: 700; color: var(--slate-muted);">URL Gambar (Opsional)</label>
                                <input type="text" x-model="newProduct.gambar" placeholder="https://..." class="input-minimal" style="width: 100%; border-radius: 8px; margin-top: 4px;">
                            </div>
                            <div style="display: flex; gap: 8px;">
                                <button type="submit" class="btn-action-primary" style="flex: 1; border-radius: 8px;">Simpan</button>
                                <button type="button" @click="showAddForm = false" class="btn-action-primary" style="background: #e2e8f0; color: var(--slate-dark); border-radius: 8px;">Batal</button>
                            </div>
                        </form>
                    </div>

                    <!-- GRID PRODUK DENGAN FITUR RESTOCK -->
                    <div class="grid-products-new">
                        <template x-for="item in dataBarang" :key="item.id">
                            <div class="card-modern-product">
                                <div class="img-badge-container">
                                    <img :src="item.gambar" :alt="item.nama">
                                    <div class="badge-stock-pill" :class="item.stok < 5 ? 'danger' : ''" x-text="'Sisa: ' + item.stok"></div>
                                </div>
                                <div class="card-body-details">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <span class="item-tag" x-text="item.kategori"></span>
                                        <!-- Tombol Restock -->
                                        <button @click="restockBarang(item.id)" class="btn-restock" title="Restock Stok Barang">
                                            <i class="fa-solid fa-boxes-stacked"></i> Restock
                                        </button>
                                    </div>
                                    <h3 class="item-title" x-text="item.nama"></h3>
                                    <div class="item-footer-price">
                                        <span class="price-text" x-text="'Rp ' + item.harga.toLocaleString()"></span>
                                        <button @click="tambahKeBag(item)" :disabled="item.stok <= 0" class="btn-circle-add">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- TAB KERANJANG -->
                <div x-show="currentTab === 'keranjang'" class="tab-pane animate-fade">
                    <template x-if="cart.length === 0">
                        <div class="empty-state-card">
                            <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" alt="Kosong" class="empty-illustration">
                            <h2>Kantong belanja masih kosong</h2>
                            <p>Silakan menuju halaman etalase barang untuk memasukkan pesanan pembeli.</p>
                            <button @click="currentTab = 'katalog'" class="btn-action-primary">Buka Etalase</button>
                        </div>
                    </template>

                    <template x-if="cart.length > 0">
                        <div class="split-checkout-layout">
                            <div class="checkout-list-side">
                                <h3 class="section-title">Detail Belanjaan</h3>
                                <div class="list-wrapper-scroll">
                                    <template x-for="item in cart" :key="item.id">
                                        <div class="item-row-checkout">
                                            <img :src="item.gambar" class="checkout-thumb">
                                            <div class="checkout-info-text">
                                                <h4 x-text="item.nama"></h4>
                                                <p x-text="'Rp ' + item.harga.toLocaleString()"></p>
                                            </div>
                                            <div class="checkout-qty-controls">
                                                <button @click="ubahJumlah(item.id, -1)" class="control-btn-square"><i class="fa-solid fa-minus"></i></button>
                                                <span class="control-number" x-text="item.qty"></span>
                                                <button @click="ubahJumlah(item.id, 1)" class="control-btn-square"><i class="fa-solid fa-plus"></i></button>
                                            </div>
                                            <span class="row-subtotal" x-text="'Rp ' + (item.harga * item.qty).toLocaleString()"></span>
                                            <button @click="hapusDariBag(item.id)" class="btn-trash-icon"><i class="fa-solid fa-xmark"></i></button>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="checkout-invoice-side">
                                <h3 class="section-title">Ringkasan Kasir</h3>
                                <div class="invoice-box-sheet">
                                    <div class="invoice-line"><span>Subtotal Item</span><span x-text="'Rp ' + hitungTotalKotor().toLocaleString()"></span></div>
                                    <div class="invoice-line"><span>Pajak Restitusi (10%)</span><span x-text="'Rp ' + (hitungTotalKotor() * 0.10).toLocaleString()"></span></div>
                                    <hr class="invoice-divider">
                                    <div class="invoice-line total-line"><span>Total Tagihan</span><span x-text="'Rp ' + (hitungTotalKotor() * 1.10).toLocaleString()"></span></div>
                                    
                                    <div class="payment-method-select">
                                        <label>Metode Pembayaran</label>
                                        <select class="select-minimal">
                                            <option>Tunai (Cash)</option>
                                            <option>QRIS / E-Wallet</option>
                                            <option>Debit / Credit Card</option>
                                        </select>
                                    </div>

                                    <button @click="prosesBayar()" class="btn-checkout-block">Proses & Cetak Struk</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- TAB LAPORAN -->
                <div x-show="currentTab === 'laporan'" class="tab-pane animate-fade">
                    <div class="report-header-flex">
                        <div>
                            <h2>Arsip Penjualan Toko</h2>
                            <p>Daftar transaksi legal yang tercatat dalam system POS.</p>
                        </div>
                        <div class="report-filter">
                            <button class="btn-filter-pill active">Hari Ini</button>
                            <button class="btn-filter-pill">Minggu Ini</button>
                        </div>
                    </div>

                    <div class="report-cards-stack">
                        <template x-for="nota in riwayatPenjualan" :key="nota.id">
                            <div class="report-row-card">
                                <div class="rep-left">
                                    <div class="badge-icon-status"><i class="fa-solid fa-check"></i></div>
                                    <div>
                                        <h4 class="rep-id" x-text="nota.id"></h4>
                                        <p class="rep-date" x-text="nota.waktu"></p>
                                    </div>
                                </div>
                                <div class="rep-center">
                                    <p class="rep-items-label">Barang yang dibeli:</p>
                                    <p class="rep-items-value" x-text="nota.deskripsi"></p>
                                </div>
                                <div class="rep-right">
                                    <span class="rep-total" x-text="'Rp ' + nota.jumlah.toLocaleString()"></span>
                                    <span class="badge-status-pill">Lunas</span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </main>
        </div>
    </template>

    <script src="app.js"></script>
</body>
</html>