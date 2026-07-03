<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex-1">
                <h2 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white md:text-3xl">
                    Selamat Datang,
                    <span class="text-primary-600">
                        {{ $nama }} 👋 
                    </span>
                </h2>

                <div class="mt-3 space-y-1 text-sm text-gray-500 dark:text-gray-400 md:text-base">
                    <p>📅 {{ $tanggal }}</p>
                    <p>🕒 Pukul {{ $jam }} WIB</p>
                </div>

                <div class="mt-5">
                    <p class="text-lg font-semibold text-gray-950 dark:text-white">
                        {{ $greeting }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 md:text-base">
                        Semoga harimu menyenangkan
                    </p>
                </div>
            </div>

            <div class="flex shrink-0 items-center justify-center lg:w-36">
                @if ($logo)
                    <img
                        src="{{ Storage::url($logo) }}"
                        class="h-24 w-24 rounded-full object-contain md:h-28 md:w-28"
                        alt="{{ $namaWebsite ?? 'Tukang Print Dadakan' }}"
                    >
                @else
                    <img
                        src="{{ asset('images/placeholder.png') }}"
                        class="h-24 w-24 rounded-full object-contain md:h-28 md:w-28"
                        alt="Logo default"
                    >
                @endif
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="rounded-xl bg-blue-50 p-5 dark:bg-blue-950/30">
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Pesanan Hari Ini
                </div>

                <div class="mt-2 text-3xl font-bold text-blue-600 dark:text-blue-400">
                    {{ $pesananHariIni }}
                </div>
            </div>

            <div class="rounded-xl bg-yellow-50 p-5 dark:bg-yellow-950/30">
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Perlu Verifikasi
                </div>

                <div class="mt-2 text-3xl font-bold text-yellow-600 dark:text-yellow-400">
                    {{ $perluVerifikasi }}
                </div>
            </div>

            <div class="rounded-xl bg-green-50 p-5 dark:bg-green-950/30">
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Pengambilan Besok
                </div>

                <div class="mt-2 text-3xl font-bold text-green-600 dark:text-green-400">
                    {{ $pengambilanBesok }}
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>