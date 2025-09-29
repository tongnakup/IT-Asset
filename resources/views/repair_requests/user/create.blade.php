<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Submit a New Repair Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- เรียกใช้ Livewire Component ที่รวมทุกอย่างไว้แล้ว --}}
                    <livewire:repair-form />

                    {{-- ส่วนของ QR Reader ยังคงอยู่ที่นี่ เพราะต้องใช้ JavaScript --}}
                    <div id="qr-reader" class="w-full md:w-1/2 border rounded-md mt-4" style="display: none;"></div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const scanBtn = document.getElementById('scan-qr-btn');
                if (!scanBtn) return;

                const qrReaderDiv = document.getElementById('qr-reader');
                let isScannerRunning = false;
                const html5QrCode = new Html5Qrcode("qr-reader");

                const stopScanner = () => {
                    if (isScannerRunning) {
                        html5QrCode.stop().then(() => {
                            qrReaderDiv.style.display = 'none';
                            isScannerRunning = false;
                        }).catch(err => console.log('Failed to stop camera.'));
                    }
                };

                const onScanSuccess = (decodedText, decodedResult) => {
                    Livewire.dispatch('assetScanned', {
                        assetNumber: decodedText
                    });
                    stopScanner();
                    alert(`สแกนสำเร็จ: ${decodedText}`);
                };

                scanBtn.addEventListener('click', () => {
                    if (isScannerRunning) {
                        stopScanner();
                        return;
                    }
                    qrReaderDiv.style.display = 'block';
                    isScannerRunning = true;
                    html5QrCode.start({
                            facingMode: "environment"
                        }, {
                            fps: 10,
                            qrbox: {
                                width: 250,
                                height: 250
                            }
                        },
                        onScanSuccess,
                        (errorMessage) => {
                            /* do nothing */
                        }
                    ).catch((err) => {
                        alert('ไม่สามารถเปิดกล้องได้ กรุณาตรวจสอบการอนุญาต');
                        qrReaderDiv.style.display = 'none';
                        isScannerRunning = false;
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
