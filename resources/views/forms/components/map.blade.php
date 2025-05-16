<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
    wire:ignore
    x-data="{
    state: $wire.$entangle('{{ $getStatePath() }}'),
    initMap() {
            // Check if map already exists
            if (window.mapInstance) {
                window.mapInstance.invalidateSize();
                return;
            }

            const map = L.map('map');
            window.mapInstance = map; // Store map instance globally

            L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png').addTo(map);
            if (this.state && this.state.lat && this.state.lng) {
                map.setView([this.state.lat, this.state.lng], 8);
                window.currentMarker = L.marker([this.state.lat, this.state.lng]).addTo(map);
            } else {
                map.locate({
                    setView: true,
                    maxZoom: 8
                });
            }

            map.on('locationfound', function(e) {
                if (!window.currentMarker) {
                    window.currentMarker = L.marker(e.latlng).addTo(map);
                }
            });

            map.on('mousedown', function(e) {
                if (window.currentMarker) {
                    map.removeLayer(window.currentMarker);
                }
                window.currentMarker = L.marker(e.latlng).addTo(map);
                $wire.$set('{{ $getStatePath() }}', {
                    lat: e.latlng.lat,
                    lng: e.latlng.lng
                });
            });

            map.on('locationerror', function() {
                new FilamentNotification()
                    .title('مشكلة في جلب الموقع')
                    .body('يرجى إعطاء الإذن للموقع')
                    .danger()
                    .send();
            });
        },
        initLivewireHook() {
            this.initMap(); // initialize at first load
            console.log(this.state);
            if (window.Livewire) {
                Livewire.hook('commit', ({ component, succeed, fail, respond }) => {
                    succeed(() => {
                        this.initMap(); // reinitialize after Livewire DOM updates
                    });
                });
            }
        }
    }" x-init="initMap()" x-on:filament:init="initLivewireHook()" x-effect="initLivewireHook()"
    >
        <div x-ref="mapContainer" id="map" style="width: 100%; height: 400px;" x-ref="mapContainer"></div>
    </div>
</x-dynamic-component>