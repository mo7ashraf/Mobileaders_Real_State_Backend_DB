@extends('web.layout')
@section('title','Map')

@push('head')
  <link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""/>
  <style>
    #map { height: 70vh; width: 100%; }
  </style>
@endpush

@section('content')
  <div class="bg-white rounded-2xl shadow p-4 mb-4">
    <div class="flex items-center gap-4">
      <div class="font-bold">Nearby Ads</div>
      <label class="text-sm text-gray700">Radius (km):
        <input id="radiusKm" type="number" value="25" min="1" max="200" class="border rounded-lg px-2 py-1 w-20">
      </label>
      <button id="useLocation" class="ml-auto px-3 py-2 rounded-xl border hover:bg-gray-50">Use My Location</button>
    </div>
  </div>

  <div id="map" class="rounded-2xl overflow-hidden shadow bg-gray-200"></div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
  const map = L.map('map').setView([24.7136, 46.6753], 11); // Default center (Riyadh)
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  const markers = L.layerGroup().addTo(map);
  let allListings = [];
  const kmInput = document.getElementById('radiusKm');

  function haversineKm(a, b) {
    const toRad = d => d * Math.PI / 180;
    const R = 6371;
    const dLat = toRad(b.lat - a.lat);
    const dLng = toRad(b.lng - a.lng);
    const lat1 = toRad(a.lat);
    const lat2 = toRad(b.lat);
    const h = Math.sin(dLat/2)**2 + Math.cos(lat1)*Math.cos(lat2)*Math.sin(dLng/2)**2;
    return 2 * R * Math.asin(Math.sqrt(h));
  }

  function render(center) {
    markers.clearLayers();
    const radius = Number(kmInput.value || 25);
    const items = allListings.filter(it => it.latitude !== null && it.longitude !== null);
    let shown = items;
    if (center) {
      shown = items.filter(it => haversineKm(center, {lat: it.latitude, lng: it.longitude}) <= radius);
    }
    shown.forEach(it => {
      const m = L.marker([it.latitude, it.longitude]).bindPopup(
        `<div style="min-width:200px">
           <div style="font-weight:600">${it.title ?? 'Listing'}</div>
           <div style="color:#666; font-size:12px">${it.address ?? ''}</div>
           <div style="margin-top:4px; font-size:12px">Price: ${new Intl.NumberFormat().format(it.price || 0)}</div>
           <div style="margin-top:6px"><a href="/listings/${it.id}" class="text-primary">View</a></div>
         </div>`
      );
      markers.addLayer(m);
    });
    if (center) {
      const circle = L.circle([center.lat, center.lng], {radius: radius * 1000, color: '#06B580', fillOpacity: 0.05});
      markers.addLayer(circle);
    }
  }

  async function load() {
    try {
      const res = await fetch('/api/listings');
      allListings = await res.json();
      // Try to center on the first listing if no geolocation yet
      const first = allListings.find(it => it.latitude !== null && it.longitude !== null);
      if (first) map.setView([first.latitude, first.longitude], 12);
      render(null);
    } catch (e) {
      console.error('Failed to load listings', e);
    }
  }

  load();

  document.getElementById('useLocation').addEventListener('click', () => {
    if (!navigator.geolocation) { alert('Geolocation not supported'); return; }
    navigator.geolocation.getCurrentPosition(pos => {
      const center = { lat: pos.coords.latitude, lng: pos.coords.longitude };
      map.setView([center.lat, center.lng], 13);
      render(center);
    }, err => {
      alert('Unable to get location');
    }, { enableHighAccuracy: true, timeout: 8000 });
  });

  kmInput.addEventListener('change', () => {
    const c = map.getCenter();
    render({lat: c.lat, lng: c.lng});
  });
</script>
@endpush

