@extends('portal.template.app')
@section('content')

<div class="card">
    <div class="card-body p-0">

        {{-- Search Bar --}}
        <div class="p-3 border-bottom d-flex align-items-center gap-2 flex-wrap">
            <input type="text" id="search_location" class="form-control-sm"
                   placeholder="Search for a location..."
                   style="max-width:380px;"
                   onkeydown="if(event.key==='Enter') searchLocation()">
            <button class="btn btn-sm btn-outline-primary" onclick="searchLocation()">
                <i class="bx bx-search me-1"></i> Search
            </button>
            <small class="text-muted ms-1">or click anywhere on the map to pin a location</small>
            
            <button class="btn btn-success px-4 me-0 ms-auto" id="save_btn" onclick="saveAll()">
                <i class="bx bx-save me-1"></i> Save All
            </button>
        </div>

        {{-- Map --}}
        <div id="map" style="height:62vh; width:100%;"></div>

    </div>

    {{-- Added Locations Tag List --}}
    <div class="card-footer d-none">
        <div class="mb-2">
            <span class="fw-semibold">Pinned Locations</span>
            <span class="badge bg-secondary ms-1" id="count">0</span>
        </div>
        <div id="location_tags" class="d-flex flex-wrap gap-2">
            <span class="text-muted fst-italic" id="no_msg">No locations pinned yet.</span>
        </div>
    </div>
</div>

{{-- Leaflet --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.js"></script>

<script>
var map, geocoder;
var locations   = {};   // { localId: { id, pincode, city, country, lat, lng, marker } }
var pinIdSeq    = 0;

// ── Existing saved locations passed from PHP ──────────────────────────
var existing = @json($locations);

document.addEventListener('DOMContentLoaded', function () {

    map = L.map('map').setView([20.5937, 78.9629], 5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    geocoder = L.Control.Geocoder.nominatim();

    // Load existing pins
    existing.forEach(function (loc) {
        if (loc.lat && loc.lng) {
            addPin(parseFloat(loc.lat), parseFloat(loc.lng),
                   loc.id, loc.city, loc.country, loc.pincode);
        }
    });

    // Click on map to add
    map.on('click', function (e) {
        reverseGeocodeAndAdd(e.latlng);
    });
});

// ── Core helpers ──────────────────────────────────────────────────────

function addPin(lat, lng, dbId, city, country, pincode) {
    var localId = 'p' + (++pinIdSeq);
    var marker  = L.marker([lat, lng]).addTo(map);
    var label   = makeLabel(city, country);

    locations[localId] = {
        localId : localId,
        id      : dbId    || null,
        pincode : pincode || '',
        city    : city    || '',
        country : country || '',
        lat     : lat,
        lng     : lng,
        marker  : marker
    };

    marker.bindPopup(buildPopup(localId, label)).openPopup();
    renderList();
}

function removePin(localId) {
    if (!locations[localId]) return;
    map.removeLayer(locations[localId].marker);
    delete locations[localId];
    renderList();
}

function makeLabel(city, country) {
    return [city, country].filter(Boolean).join(', ') || 'Unknown location';
}

function buildPopup(localId, label) {
    return '<div style="min-width:80px; line-height:1.6">'
         + '<b>' + label + '</b>'
         + '<br><button class="btn btn-sm btn-danger w-100 mt-2" '
         + 'onclick="removePin(\'' + localId + '\')">Remove</button>'
         + '</div>';
}

function renderList() {
    var keys     = Object.keys(locations);
    var countEl  = document.getElementById('count');
    var tagsEl   = document.getElementById('location_tags');
    var noMsg    = document.getElementById('no_msg');

    countEl.textContent = keys.length;

    if (keys.length === 0) {
        tagsEl.innerHTML = '';
        tagsEl.appendChild(noMsg);
        noMsg.style.display = '';
        return;
    }

    noMsg.style.display = 'none';
    tagsEl.innerHTML    = '';

    keys.forEach(function (localId) {
        var loc   = locations[localId];
        var label = makeLabel(loc.city, loc.country);

        var chip = document.createElement('span');
        chip.className = 'badge d-inline-flex align-items-center gap-1 px-3 py-2 bg-dark';
        chip.style.cssText = 'font-size:13px; font-weight:400; cursor:default;';
        chip.innerHTML =
            '<i class="bx bx-map-pin"></i> ' + label +
            ' <button type="button" onclick="removePin(\'' + localId + '\')" '
            + 'style="background:none;border:none;color:#fff;font-size:16px;line-height:1;'
            + 'padding:0 0 0 6px;cursor:pointer;" title="Remove">&times;</button>';

        tagsEl.appendChild(chip);
    });
}

// ── Geocoding ─────────────────────────────────────────────────────────

function reverseGeocodeAndAdd(latlng) {
    geocoder.reverse(latlng, 1, function (results) {
        var city = '', country = '', pincode = '';
        if (results && results.length > 0) {
            var addr = results[0].properties.address || {};
            country  = addr.country  || '';
            city     = addr.city || addr.town || addr.village || addr.state || addr.county || '';
            pincode  = addr.postcode || '';
        }
        addPin(latlng.lat, latlng.lng, null, city, country, pincode);
    });
}

function searchLocation() {
    var text = document.getElementById('search_location').value.trim();
    if (!text) return;

    geocoder.geocode(text, function (results) {
        if (!results || results.length === 0) {
            errorToast('Location not found. Try a different search.');
            return;
        }
        var result = results[0];
        var latlng  = result.center;
        var addr    = result.properties.address || {};
        var city    = addr.city || addr.town || addr.village || addr.state || addr.county || '';
        var country = addr.country  || '';
        var pincode = addr.postcode || '';

        map.setView(latlng, 13);
        addPin(latlng.lat, latlng.lng, null, city, country, pincode);
        document.getElementById('search_location').value = '';
    });
}

// ── Save ──────────────────────────────────────────────────────────────

function saveAll() {
    var btn = document.getElementById('save_btn');
    btn.disabled    = true;
    btn.innerHTML   = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';

    var payload = Object.values(locations).map(function (loc) {
        return {
            id               : loc.id,
            presence_pincode : loc.pincode,
            presence_city    : loc.city,
            presence_country : loc.country,
            presence_lat     : loc.lat,
            presence_long    : loc.lng,
        };
    });

    $.ajax({
        url    : '{{ route("presence.save") }}',
        method : 'POST',
        data   : {
            _token    : '{{ csrf_token() }}',
            locations : JSON.stringify(payload),
        },
        complete: function (xhr) {
            btn.disabled  = false;
            btn.innerHTML = '<i class="bx bx-save me-1"></i> Save All';

            try {
                var res = JSON.parse(xhr.responseText);
                if (res.status === 200) {
                    successToast(res.message);
                    // Reload to refresh DB ids on pins
                    setTimeout(function () { window.location.reload(); }, 1200);
                } else {
                    errorToast(res.message || 'Something went wrong.');
                }
            } catch (e) {
                errorToast('Unexpected error. Please try again.');
            }
        },
        error: function () {
            btn.disabled  = false;
            btn.innerHTML = '<i class="bx bx-save me-1"></i> Save All';
            errorToast('Request failed. Please try again.');
        }
    });
}
</script>

@endsection
