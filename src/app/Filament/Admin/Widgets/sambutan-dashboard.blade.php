<x-filament-widgets::widget>

    <x-filament::section>

        <div class="flex items-center justify-between">

            <div>

                <h2 class="text-3xl font-bold">
                    👋 Selamat Datang,
                    <span class="text-primary-600">
                        {{ $nama }}
                    </span>
                </h2>

                <p class="mt-2 text-gray-500">

                    {{ $tanggal }}

                    •

                    {{ $jam }} WIB

                </p>

                <p class="mt-5 text-xl font-semibold">

                    {{ $greeting }}

                </p>

                <p class="text-gray-500">

                    Semoga harimu menyenangkan 😊

                </p>

            </div>

            <div class="hidden lg:block">
                <img src="{{ asset('images/placeholder.png') }}" class="h-32 w-32 rounded-2xl object-cover" alt="Tukang Print Dadakan">
            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">

            <div class="rounded-xl bg-blue-50 p-5">

                <div class="text-sm text-gray-500">

                    Pesanan Baru

                </div>

                <div class="text-3xl font-bold text-blue-600">

                    {{ $pesananBaru }}

                </div>

            </div>

            <div class="rounded-xl bg-yellow-50 p-5">

                <div class="text-sm text-gray-500">

                    Menunggu Pembayaran

                </div>

                <div class="text-3xl font-bold text-yellow-600">

                    {{ $menungguPembayaran }}

                </div>

            </div>

            <div class="rounded-xl bg-green-50 p-5">

                <div class="text-sm text-gray-500">

                    Pengambilan Besok

                </div>

                <div class="text-3xl font-bold text-green-600">

                    {{ $ambilBesok }}

                </div>

            </div>

        </div>

    </x-filament::section>

</x-filament-widgets::widget>