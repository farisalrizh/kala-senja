@extends('layouts.app')

@section('content')
<main>
    <section class="hero" id="cerita">
        <div class="hero-copy">
            <span class="eyebrow"><span class="eyebrow-line"></span> Sebuah ruang untuk melambat</span>
            <h1>Hari boleh sibuk.<br><em>Singgah dulu.</em></h1>
            <p>Di antara riuhnya hari, selalu ada ruang untuk secangkir kopi yang baik dan obrolan yang terasa dekat.</p>
            <div class="hero-actions">
                <a class="button button-dark" href="#menu">Jelajahi menu <span aria-hidden="true">↗</span></a>
                <span class="hero-note">Dibuat dengan hati, disajikan hangat.</span>
            </div>
            <div class="hero-meta">
                <span><strong>08.00 — 22.00</strong><small>Setiap hari</small></span>
                <span class="meta-divider"></span>
                <span><strong>SMK 1 Garut</strong><small>Temukan kami di sini</small></span>
            </div>
        </div>
        <div class="hero-visual">
            <img src="https://images.unsplash.com/photo-1442512595331-e89e73853f31?auto=format&fit=crop&w=1400&q=90" alt="Suasana hangat di kedai kopi Kala Senja" fetchpriority="high">
            <div class="hero-stamp"><span>GOOD COFFEE</span><strong>good<br>company</strong><span>EST. WITH LOVE</span></div>
            <span class="image-caption">Lebih dari kopi, ini tentang rasa pulang.</span>
        </div>
        <span class="hero-index">01 — 03</span>
    </section>

    <section class="intro-strip" aria-label="Tentang Kala Senja">
        <span>Ruang untuk jeda</span><i>✳</i><span>Kopi dari hati</span><i>✳</i><span>Cerita yang tumbuh</span><i>✳</i><span>Setiap hari, selalu ada</span>
    </section>

    <section class="menu-section section-wrap" id="menu">
        <div class="section-heading">
            <div>
                <span class="eyebrow"><span class="eyebrow-line"></span> Yang kami sajikan</span>
                <h2>Teman baik<br>untuk <em>harimu.</em></h2>
            </div>
            <p>Dari tegukan pertama sampai suapan terakhir, semua dibuat agar kamu ingin tinggal sedikit lebih lama.</p>
        </div>

        @if ($menus->isNotEmpty())
            <form action="{{ route('orders.store') }}" method="POST" class="order-form">
                @csrf
                @if (session('success'))
                    <div class="alert alert-success" role="status">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-error" role="alert">Periksa pilihan menu dan data pemesanan kamu.</div>
                @endif
                <div class="menu-filters" role="group" aria-label="Filter menu">
                    <button class="filter-chip is-active" type="button" data-filter="Semua">Semua</button>
                    @foreach ($menuCategories as $category)
                        <button class="filter-chip" type="button" data-filter="{{ $category }}">{{ $category }}</button>
                    @endforeach
                </div>
                <div class="menu-grid">
                    @foreach ($menus as $index => $menu)
                        <article class="menu-card" data-category="{{ $menu->kategori }}">
                            <div class="menu-photo">
                                @if ($menu->gambar)
                                    <img src="{{ $menu->gambar }}" alt="{{ $menu->nama_menu }}" loading="lazy">
                                @else
                                    <div class="menu-photo-placeholder"><span>ks.</span></div>
                                @endif
                                @if ($menu->is_featured)<span class="menu-ribbon">Favorit</span>@endif
                                <span class="menu-number">0{{ $index + 1 }}</span>
                            </div>
                            <div class="menu-card-body">
                                <span class="menu-category">{{ $menu->kategori }}</span>
                                <div class="menu-title-row">
                                    <h3>{{ $menu->nama_menu }}</h3>
                                    <span class="menu-price">Rp{{ number_format($menu->harga, 0, ',', '.') }}</span>
                                </div>
                                <p>{{ $menu->deskripsi ?: 'Dibuat dengan bahan pilihan dan disajikan dengan sepenuh hati.' }}</p>
                                <label class="order-quantity">Jumlah
                                    <input type="number" name="items[{{ $menu->id }}]" value="{{ old('items.'.$menu->id, 0) }}" min="0" max="20" inputmode="numeric">
                                </label>
                                @error('items.'.$menu->id)<small class="field-error">{{ $message }}</small>@enderror
                            </div>
                        </article>
                    @endforeach
                </div>
                @error('items')<small class="field-error order-error">{{ $message }}</small>@enderror
                <div class="order-checkout">
                    <div class="order-checkout-heading">
                        <span class="eyebrow"><span class="eyebrow-line"></span> Pesan sekarang</span>
                        <h3>Pilih menu, kami siapkan.</h3>
                            <p>Pesanan bisa dinikmati di tempat atau dibungkus. Pembayaran dapat dilakukan dengan QRIS saat pengambilan.</p>
                    </div>
                        <div class="qris-payment" aria-label="Pembayaran QRIS">
                            <div class="qris-copy">
                                <strong>Bayar dengan QRIS</strong>
                                <span>Buka aplikasi mobile banking atau e-wallet, pilih QRIS, lalu pindai kode berikut.</span>
                            </div>
                            <img
                                src="{{ asset('qrcode-kala-senja.png') }}"
                                alt="Kode QRIS Kala Senja"
                                width="240"
                                height="240"
                                loading="lazy"
                            >
                        </div>
                    <div class="order-fields">
                        <label>Nama
                            <input type="text" name="nama" value="{{ old('nama') }}" autocomplete="name" required maxlength="100">
                            @error('nama')<small class="field-error">{{ $message }}</small>@enderror
                        </label>
                        <label>Nomor telepon
                            <input type="tel" name="telepon" value="{{ old('telepon') }}" autocomplete="tel" required maxlength="30">
                            @error('telepon')<small class="field-error">{{ $message }}</small>@enderror
                        </label>
                        <label>Penyajian
                            <select name="jenis_pesanan" required>
                                <option value="makan_di_tempat" @selected(old('jenis_pesanan', 'makan_di_tempat') === 'makan_di_tempat')>Makan di tempat</option>
                                <option value="dibungkus" @selected(old('jenis_pesanan') === 'dibungkus')>Dibungkus</option>
                            </select>
                            @error('jenis_pesanan')<small class="field-error">{{ $message }}</small>@enderror
                        </label>
                        <label>Catatan <span class="optional">(opsional)</span>
                            <textarea name="catatan" rows="2" maxlength="1000">{{ old('catatan') }}</textarea>
                            @error('catatan')<small class="field-error">{{ $message }}</small>@enderror
                        </label>
                    </div>
                    <button class="button button-dark form-submit" type="submit">Kirim pesanan <span aria-hidden="true">↗</span></button>
                </div>
            </form>
        @else
            <div class="empty-menu">
                <span>☕</span>
                <h3>Menu kami sedang disiapkan.</h3>
                <p>Singgah kembali sebentar lagi untuk melihat sajian Kala Senja.</p>
            </div>
        @endif
        <div class="menu-footnote"><span>Semua harga dalam rupiah.</span><span>Menu musiman dapat berubah sewaktu-waktu.</span></div>
    </section>

    <section class="quote-section">
        <span class="quote-mark">“</span>
        <blockquote>Tak semua hal perlu buru-buru.<br><em>Termasuk menikmati kopi.</em></blockquote>
        <span class="quote-credit">— Filosofi Kala Senja</span>
    </section>

    <section class="visit-section section-wrap" id="kunjungi">
        <div class="visit-photo">
            <img src="https://images.unsplash.com/photo-1445116572660-236099ec97a0?auto=format&fit=crop&w=1200&q=85" alt="Interior kedai kopi yang nyaman" loading="lazy">
            <span class="photo-tag">Tempat kecil, cerita besar.</span>
        </div>
        <div class="visit-copy">
            <span class="eyebrow"><span class="eyebrow-line"></span> Sampai jumpa di sini</span>
            <h2>Sudut kecil<br>untuk <em>merasa dekat.</em></h2>
            <p>Kami menyiapkan tempat yang nyaman untuk bekerja, bertemu, atau sekadar menikmati waktu sendiri.</p>
            <div class="visit-details">
                <div><span class="detail-icon">⌖</span><p><strong>Alamat</strong><br>SMK 1 Garut</p></div>
                <div><span class="detail-icon">◷</span><p><strong>Jam buka</strong><br>Setiap hari, 08.00 — 22.00</p></div>
                <div><span class="detail-icon">☎</span><p><strong>Hubungi kami</strong><br><a href="tel:+622155501018">+62 21 5550 1018</a></p></div>
            </div>
            <a class="text-link" href="#reservasi">Simpan tempatmu <span aria-hidden="true">→</span></a>
        </div>
    </section>

    <section class="reservation-section" id="reservasi">
        <div class="reservation-copy">
            <span class="eyebrow eyebrow-light"><span class="eyebrow-line"></span> Meja sudah menunggumu</span>
            <h2>Datang bersama,<br><em>lebih seru.</em></h2>
            <p>Rencanakan kunjunganmu. Isi detail di samping dan tim kami akan menghubungimu untuk mengonfirmasi meja.</p>
            <div class="reservation-hours"><span>BUKA SETIAP HARI</span><strong>08.00 — 22.00</strong></div>
        </div>
        <form class="reservation-form" action="{{ route('reservations.store') }}" method="POST">
            @csrf
            <div class="form-heading"><span>RESERVASI</span><span>01 / 01</span></div>
            @if (session('success'))
                <div class="alert alert-success" role="status">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-error" role="alert">Periksa kembali data reservasi kamu.</div>
            @endif
            <div class="form-row">
                <label>Nama lengkap
                    <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama kamu" autocomplete="name" required maxlength="100">
                    @error('nama')<small class="field-error">{{ $message }}</small>@enderror
                </label>
                <label>Nomor telepon
                    <input type="tel" name="telepon" value="{{ old('telepon') }}" placeholder="+62 812 ..." autocomplete="tel" required maxlength="30">
                    @error('telepon')<small class="field-error">{{ $message }}</small>@enderror
                </label>
            </div>
            <label>Email
                <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" autocomplete="email" required maxlength="150">
                @error('email')<small class="field-error">{{ $message }}</small>@enderror
            </label>
            <div class="form-row">
                <label>Tanggal
                    <input type="date" name="tanggal_reservasi" value="{{ old('tanggal_reservasi') }}" min="{{ now()->toDateString() }}" required>
                    @error('tanggal_reservasi')<small class="field-error">{{ $message }}</small>@enderror
                </label>
                <label>Waktu
                    <select name="waktu" required>
                        <option value="">Pilih waktu</option>
                        @foreach (['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00'] as $time)
                            <option value="{{ $time }}" @selected(old('waktu') === $time)>{{ $time }}</option>
                        @endforeach
                    </select>
                    @error('waktu')<small class="field-error">{{ $message }}</small>@enderror
                </label>
            </div>
            <label>Jumlah orang
                <select name="jumlah_orang" required>
                    <option value="">Pilih jumlah</option>
                    @foreach (range(1, 20) as $count)
                        <option value="{{ $count }}" @selected((string) old('jumlah_orang') === (string) $count)>{{ $count }} orang</option>
                    @endforeach
                </select>
                @error('jumlah_orang')<small class="field-error">{{ $message }}</small>@enderror
            </label>
            <label>Catatan <span class="optional">(opsional)</span>
                <textarea name="catatan" rows="2" placeholder="Ada permintaan khusus?">{{ old('catatan') }}</textarea>
                @error('catatan')<small class="field-error">{{ $message }}</small>@enderror
            </label>
            <button class="button button-dark form-submit" type="submit">Kirim permintaan <span aria-hidden="true">↗</span></button>
            <p class="form-note">Reservasi akan dikonfirmasi oleh tim kami melalui telepon atau email.</p>
        </form>
    </section>
</main>
@endsection
