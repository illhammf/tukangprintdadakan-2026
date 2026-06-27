<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-col gap-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold">
                        ⚡ Quick Action
                    </h2>

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Control center untuk aktivitas utama Tukang Print Dadakan.
                    </p>
                </div>

                <div class="rounded-2xl bg-primary-50 dark:bg-primary-950/40 px-5 py-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Login sebagai
                    </p>

                    <p class="font-bold text-primary-700 dark:text-primary-300">
                        {{ $userName }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="rounded-2xl border border-gray-200 dark:border-gray-700 p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pesanan Hari Ini</p>
                    <p class="text-3xl font-bold mt-1">{{ $pesananHariIni }}</p>
                </div>

                <div class="rounded-2xl border border-gray-200 dark:border-gray-700 p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pengambilan Besok</p>
                    <p class="text-3xl font-bold mt-1">{{ $pengambilanBesok }}</p>
                </div>

                <div class="rounded-2xl border border-gray-200 dark:border-gray-700 p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pembayaran Menunggu</p>
                    <p class="text-3xl font-bold mt-1">{{ $pembayaranMenunggu }}</p>
                </div>

                <div class="rounded-2xl border border-gray-200 dark:border-gray-700 p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pesan Baru</p>
                    <p class="text-3xl font-bold mt-1">{{ $pesanBaru }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('filament.admin.resources.pesanans.create') }}"
                   class="rounded-2xl border border-gray-200 dark:border-gray-700 p-5 hover:bg-primary-50 dark:hover:bg-primary-950/40 transition">
                    <div class="text-3xl mb-3">➕</div>
                    <div class="font-bold">Tambah Pesanan</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Buat order manual</div>
                </a>

                <a href="{{ route('filament.admin.resources.layanans.create') }}"
                   class="rounded-2xl border border-gray-200 dark:border-gray-700 p-5 hover:bg-primary-50 dark:hover:bg-primary-950/40 transition">
                    <div class="text-3xl mb-3">🖨️</div>
                    <div class="font-bold">Tambah Layanan</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Kelola layanan baru</div>
                </a>

                <a href="{{ route('filament.admin.resources.pembayarans.index') }}"
                   class="rounded-2xl border border-gray-200 dark:border-gray-700 p-5 hover:bg-primary-50 dark:hover:bg-primary-950/40 transition">
                    <div class="text-3xl mb-3">💳</div>
                    <div class="font-bold">Pembayaran</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Cek pembayaran</div>
                </a>

                <a href="{{ route('filament.admin.resources.kontak-masuks.index') }}"
                   class="rounded-2xl border border-gray-200 dark:border-gray-700 p-5 hover:bg-primary-50 dark:hover:bg-primary-950/40 transition">
                    <div class="text-3xl mb-3">📨</div>
                    <div class="font-bold">Pesan Masuk</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Lihat pertanyaan</div>
                </a>

                <a href="{{ route('filament.admin.resources.hari-liburs.index') }}"
                   class="rounded-2xl border border-gray-200 dark:border-gray-700 p-5 hover:bg-primary-50 dark:hover:bg-primary-950/40 transition">
                    <div class="text-3xl mb-3">📅</div>
                    <div class="font-bold">Hari Libur</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Atur tanggal tutup</div>
                </a>

                <a href="{{ route('filament.admin.resources.pengaturan-websites.index') }}"
                   class="rounded-2xl border border-gray-200 dark:border-gray-700 p-5 hover:bg-primary-50 dark:hover:bg-primary-950/40 transition">
                    <div class="text-3xl mb-3">🌐</div>
                    <div class="font-bold">Pengaturan Web</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Edit tampilan website</div>
                </a>

                <a href="{{ route('filament.admin.resources.pengaturan-bookings.index') }}"
                   class="rounded-2xl border border-gray-200 dark:border-gray-700 p-5 hover:bg-primary-50 dark:hover:bg-primary-950/40 transition">
                    <div class="text-3xl mb-3">⚙️</div>
                    <div class="font-bold">Aturan Booking</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Atur H-1 & biaya</div>
                </a>

                <a href="{{ route('filament.admin.resources.users.index') }}"
                   class="rounded-2xl border border-gray-200 dark:border-gray-700 p-5 hover:bg-primary-50 dark:hover:bg-primary-950/40 transition">
                    <div class="text-3xl mb-3">👥</div>
                    <div class="font-bold">Kelola User</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Data akun pengguna</div>
                </a>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>