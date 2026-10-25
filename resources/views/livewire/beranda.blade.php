<div class="container mt-5">
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center">
            @auth
                <p class="text-muted mt-3">👤 Login sebagai: <strong>{{ auth()->user()->name }}</strong> ({{ ucfirst(auth()->user()->peran) }})</p>
            @endauth

            <img src="{{ asset('img/ChatGPT Image Apr 19, 2025, 10_38_21 PM.png') }}" alt="Logo SI-KATUN" class="mb-3" style="max-width: 300px;">
            <h1 class="display-6 mb-3">👋 Selamat Datang di <strong>SI-KATUN</strong></h1>
            <p class="lead text-muted">Sistem Informasi Kasir <strong>Batik Tenun Kodok Ngorek</strong></p>
            <hr class="my-4">

            @if(auth()->user()->peran == 'admin')
                <p class="mb-0">Sebagai <strong>Admin</strong>, Anda dapat mengelola pengguna, produk, dan melihat laporan keuangan.</p>
            @elseif(auth()->user()->peran == 'kasir')
                <p class="mb-0">Sebagai <strong>Kasir</strong>, Anda dapat mencatat transaksi penjualan dan melihat laporan harian.</p>
            @else
                <p class="mb-0">Gunakan menu di atas untuk mulai mengelola sistem.</p>
            @endif
        </div>
    </div>
</div>
