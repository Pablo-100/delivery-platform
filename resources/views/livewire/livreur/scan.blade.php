<div class="max-w-xl mx-auto py-6 px-4">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Scanner un Colis</h2>
        <a href="{{ route('livreur.dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">
            &larr; Retour
        </a>
    </div>
    
    <!-- Zone d'erreur -->
    @if($error)
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm animate-pulse">
            <div class="flex items-center">
                <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="font-bold">{{ $error }}</p>
            </div>
        </div>
    @endif

    <!-- Scanner Container -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div id="reader" class="w-full"></div>
        <div class="p-4 text-center text-gray-500 text-sm bg-gray-50 border-t">
            Pointez votre caméra vers un QR Code de livraison.
        </div>
    </div>

    <!-- Script Scanner -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
             function onScanSuccess(decodedText, decodedResult) {
                // handle the scanned code as you like, for example:
                console.log(`Code matched = ${decodedText}`, decodedResult);
                
                // Stop scanning
                html5QrcodeScanner.clear();
                
                // Call Livewire component method
                @this.handleScan(decodedText);
            }

            function onScanFailure(error) {
                // handle scan failure, usually better to ignore and keep scanning.
                // for example:
                // console.warn(`Code scan error = ${error}`);
            }

            let html5QrcodeScanner = new Html5QrcodeScanner(
                "reader",
                { fps: 10, qrbox: {width: 250, height: 250} },
                /* verbose= */ false);
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        });
    </script>
</div>
