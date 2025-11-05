<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crop-Based Pesticide Store Locator</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary: #2e7d32;
      --primary-light: #4caf50;
      --primary-dark: #1b5e20;
      --secondary: #ff9800;
      --accent: #2196f3;
      --text: #333333;
      --text-light: #666666;
      --background: #f8f9fa;
      --card-bg: #ffffff;
      --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      --radius: 12px;
      --transition: all 0.3s ease;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background-color: var(--background);
      color: var(--text);
      line-height: 1.6;
    }

    .container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 0 20px;
    }

    header {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: white;
      padding: 1.5rem 0;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .header-content {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .logo i {
      font-size: 2rem;
    }

    .logo h1 {
      font-size: 1.8rem;
      font-weight: 700;
    }

    .tagline {
      font-size: 1rem;
      opacity: 0.9;
    }

    .search-section {
      padding: 2.5rem 0;
      background-color: white;
      border-radius: 0 0 var(--radius) var(--radius);
      box-shadow: var(--shadow);
      margin-bottom: 2rem;
    }

    .search-container {
      max-width: 700px;
      margin: 0 auto;
      position: relative;
    }

    .search-box {
      display: flex;
      box-shadow: var(--shadow);
      border-radius: 50px;
      overflow: hidden;
      transition: var(--transition);
    }

    .search-box:focus-within {
      box-shadow: 0 6px 20px rgba(46, 125, 50, 0.2);
    }

    .search-box input {
      flex: 1;
      padding: 18px 24px;
      border: none;
      font-size: 1.1rem;
      outline: none;
    }

    .search-box button {
      background: var(--primary);
      color: white;
      border: none;
      padding: 0 30px;
      cursor: pointer;
      transition: var(--transition);
      font-weight: 600;
      font-size: 1rem;
    }

    .search-box button:hover {
      background: var(--primary-dark);
    }

    .suggestions {
      position: absolute;
      top: 100%;
      left: 0;
      right: 0;
      background: white;
      border-radius: 0 0 var(--radius) var(--radius);
      box-shadow: var(--shadow);
      z-index: 10;
      display: none;
      max-height: 200px;
      overflow-y: auto;
    }

    .suggestion-item {
      padding: 12px 20px;
      cursor: pointer;
      transition: var(--transition);
      border-bottom: 1px solid #f0f0f0;
    }

    .suggestion-item:hover {
      background: #f5f5f5;
    }

    .suggestion-item:last-child {
      border-bottom: none;
    }

    .content {
      display: grid;
      grid-template-columns: 1fr 2fr;
      gap: 2rem;
      margin-bottom: 2rem;
    }

    @media (max-width: 992px) {
      .content {
        grid-template-columns: 1fr;
      }
    }

    .results-section {
      background: var(--card-bg);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 1.5rem;
      max-height: 600px;
      overflow-y: auto;
    }

    .results-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid #eee;
    }

    .results-count {
      font-weight: 600;
      color: var(--primary);
    }

    .sort-options select {
      padding: 8px 12px;
      border-radius: 6px;
      border: 1px solid #ddd;
      background: white;
      color: var(--text);
    }

    .store-list {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .store-card {
      background: white;
      border-radius: var(--radius);
      padding: 1.5rem;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
      transition: var(--transition);
      cursor: pointer;
      border-left: 4px solid transparent;
    }

    .store-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
      border-left-color: var(--primary);
    }

    .store-card.active {
      border-left-color: var(--primary);
      background: #f1f8e9;
    }

    .store-name {
      font-weight: 700;
      font-size: 1.2rem;
      color: var(--primary-dark);
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .store-address {
      color: var(--text-light);
      margin-bottom: 0.8rem;
      display: flex;
      align-items: flex-start;
      gap: 8px;
    }

    .store-distance {
      display: flex;
      align-items: center;
      gap: 5px;
      color: var(--secondary);
      font-weight: 600;
      font-size: 0.9rem;
    }

    .pesticide-list {
      margin-top: 10px;
    }

    .pesticide-item {
      padding: 8px 0;
      border-bottom: 1px solid #f0f0f0;
    }

    .pesticide-item:last-child {
      border-bottom: none;
    }

    .pesticide-info {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 10px;
    }

    .pesticide-main {
      flex: 1;
      min-width: 0;
    }

    .pesticide-name {
      font-weight: 600;
      color: var(--primary-dark);
    }

    .pesticide-description {
      color: var(--text-light);
      font-size: 0.85rem;
      line-height: 1.4;
      margin-top: 5px;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .pesticide-meta {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      gap: 5px;
      flex-shrink: 0;
    }

    .pesticide-price {
      color: var(--primary);
      font-weight: 600;
      font-size: 0.9rem;
    }

    .pesticide-category {
      padding: 2px 8px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
    }

    .category-organic {
      background: #e8f5e9;
      color: var(--primary);
    }

    .category-inorganic {
      background: #ffebee;
      color: #c62828;
    }

    .map-section {
      border-radius: var(--radius);
      overflow: hidden;
      box-shadow: var(--shadow);
      height: 600px;
    }

    #map {
      height: 100%;
      width: 100%;
    }

    .no-results {
      text-align: center;
      padding: 3rem 2rem;
      color: var(--text-light);
    }

    .no-results i {
      font-size: 3rem;
      margin-bottom: 1rem;
      color: #ddd;
    }

    .loading {
      display: none;
      text-align: center;
      padding: 2rem;
    }

    .loading-spinner {
      border: 4px solid rgba(0, 0, 0, 0.1);
      border-left-color: var(--primary);
      border-radius: 50%;
      width: 40px;
      height: 40px;
      animation: spin 1s linear infinite;
      margin: 0 auto 1rem;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    footer {
      background: var(--primary-dark);
      color: white;
      padding: 2rem 0;
      text-align: center;
      margin-top: 3rem;
    }

    .footer-content {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .footer-links {
      display: flex;
      gap: 1.5rem;
    }

    .footer-links a {
      color: white;
      text-decoration: none;
      transition: var(--transition);
    }

    .footer-links a:hover {
      color: var(--secondary);
    }

    .copyright {
      font-size: 0.9rem;
      opacity: 0.8;
    }
.map-link {
  display: inline-block;
  background: var(--primary);
  color: #fff;
  padding: 8px 14px;
  border-radius: 6px;
  text-decoration: none;
  font-weight: 600;
  margin-top: 10px;
  transition: 0.3s;
}

.map-link:hover {
  background: var(--primary-dark);
  transform: scale(1.05);
}

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .header-content {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
      }
      
      .search-box {
        flex-direction: column;
        border-radius: var(--radius);
      }
      
      .search-box input, .search-box button {
        width: 100%;
        border-radius: 0;
      }
      
      .search-box input {
        border-radius: var(--radius) var(--radius) 0 0;
      }
      
      .search-box button {
        border-radius: 0 0 var(--radius) var(--radius);
      }
      
      .footer-content {
        flex-direction: column;
        text-align: center;
      }
      
      .pesticide-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
      }
      
      .pesticide-meta {
        flex-direction: row;
        align-items: center;
        gap: 10px;
      }
    }
    
  .pesticide-list {
    margin-top: 8px;
    padding: 8px;
    border-top: 1px solid #eee;
    background: #fafafa;
    border-radius: 8px;
  }
  .pesticide-item {
    margin-bottom: 8px;
  }
  .pesticide-name {
    font-weight: 600;
    color: #2e7d32;
  }
  .pesticide-price {
    color: #1b5e20;
    font-weight: bold;
  }
  .pesticide-category {
    background: #e0f2f1;
    color: #004d40;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.75rem;
    margin-left: 4px;
  }
.show-map-btn {
  background: #4CAF50;
  color: white;
  border: none;
  padding: 6px 10px;
  margin-top: 6px;
  cursor: pointer;
  border-radius: 4px;
  font-size: 14px;
}

.show-map-btn:hover {
  background: #45a049;
}

.fert-section {
  margin-top: 8px;
  background: #f9f9f9;
  border-radius: 6px;
  padding: 8px;
}

.fert-section h4 {
  margin-bottom: 4px;
  color: #2e7d32;
}

.fert-section ul {
  padding-left: 18px;
  margin: 4px 0 8px;
}

  </style>
</head>
<body>
  <header>
    <div class="container">
      <div class="header-content">
        <div class="logo">
          <i class="fas fa-seedling"></i>
          <h1>Crop-Based Pesticide Store Locator</h1>
        </div>
        <div class="tagline">
          Find the right pesticides for your crop and nearby stores
        </div>
      </div>
    </div>
  </header>

  <div class="container">
    <section class="search-section">
      <div class="search-container">
        <div class="search-box">
          <input type="text" id="searchBox" placeholder="Enter crop name (e.g. Rice, Cotton, Vegetables)">
          <button onclick="searchPesticide()">
            <i class="fas fa-search"></i> Search
          </button>
        </div>
        <div class="suggestions" id="suggestions">
          <!-- Suggestions will appear here -->
        </div>
      </div>
    </section>

    <div class="content">
      <section class="results-section">
        <div class="results-header">
          <div class="results-count" id="resultsCount">Search for a crop to find stores</div>
          <div class="sort-options">
           
          </div>
        </div>
        
        <div class="loading" id="loading">
          <div class="loading-spinner"></div>
          <p>Searching for stores...</p>
        </div>
        
        <div class="store-list" id="storeList">
          <!-- Store cards will appear here -->
        </div>
        
        <div class="no-results" id="noResults" style="display: none;">
          <i class="fas fa-store-slash"></i>
          <h3>No stores found</h3>
          <p>Try searching for a different crop name</p>
        </div>
      </section>

      <section class="map-section">
        <div id="map"></div>
      </section>
    </div>
  </div>

  <footer>
    <div class="container">
      <div class="footer-content">
        <div class="copyright">
          &copy; 2025 Crop-Based Pesticide Store Locator. All rights reserved.
        </div>
        <div class="footer-links">
          
        </div>
      </div>
    </div>
  </footer>

  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <!-- keep the rest of your index.php HTML as-is; replace the <script> ... </script> block near the bottom with this -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
  let map, markers = [], userLocation = null;
  const cropSuggestions = ["Rice","Cotton","Vegetables","Fruits","Wheat","Maize","Pulses","Sugarcane","Tomato","Potato"];

  function initMap() {
    map = L.map('map').setView([12.9716, 77.5946], 11);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);

    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(pos => {
        userLocation = { lat: pos.coords.latitude, lng: pos.coords.longitude };
        map.setView([userLocation.lat, userLocation.lng], 13);
        L.marker([userLocation.lat, userLocation.lng]).addTo(map).bindPopup("Your Location");
      });
    }
  }

  function clearMarkers() {
    markers.forEach(m => map.removeLayer(m));
    markers = [];
  }

  async function searchPesticide() {
    const crop = document.getElementById("searchBox").value.trim();
    if (!crop) return alert("Please enter a crop name");

    document.getElementById("loading").style.display = "block";
    document.getElementById("storeList").innerHTML = "";
    document.getElementById("resultsCount").textContent = "Searching...";

    try {
      const res = await fetch(`search.php?crop=${encodeURIComponent(crop)}`);
      if (!res.ok) throw new Error('Network response was not ok: ' + res.status);
      const data = await res.json();
      document.getElementById("loading").style.display = "none";
      clearMarkers();

      if (!data.success || !data.stores || data.stores.length === 0) {
        document.getElementById("noResults").style.display = "block";
        document.getElementById("resultsCount").textContent = "0 stores found";
        return;
      }

      document.getElementById("noResults").style.display = "none";
      document.getElementById("resultsCount").textContent = `${data.stores.length} stores found for ${data.crop}`;
      displayStores(data.stores);
      addMarkersToMap(data.stores);
    } catch (err) {
      console.error("Error:", err);
      document.getElementById("loading").style.display = "none";
      alert("Failed to fetch results. Check console or server logs.");
    }
  }

  function displayStores(stores) {
    const list = document.getElementById("storeList");
    list.innerHTML = "";
    stores.forEach((store, i) => {
      const name = store.name || 'Unnamed';
      const address = store.address || '';
      const latitude = store.latitude ?? store.lat;
      const longitude = store.longitude ?? store.lng;

      let distanceText = "";
      if (userLocation && latitude && longitude) {
        const dist = calculateDistance(userLocation.lat, userLocation.lng, parseFloat(latitude), parseFloat(longitude));
        distanceText = `<div><i class="fas fa-location-arrow"></i> ${dist.toFixed(1)} km away</div>`;
      }

      const branded = (store.branded || []).map(f => `
        <li><b>${escapeHtml(f.name)}</b> — ${escapeHtml(f.price || '')}</li>
      `).join('') || '<li>No branded fertilizers</li>';

      const nonBranded = (store.non_branded || []).map(f => `
        <li><b>${escapeHtml(f.name)}</b> — ${escapeHtml(f.price || '')}</li>
      `).join('') || '<li>No non-branded fertilizers</li>';

      const storeCard = document.createElement("div");
      storeCard.className = "store-card";
      storeCard.innerHTML = `
        <h3><i class="fas fa-store"></i> ${escapeHtml(name)}</h3>
        <p><i class="fas fa-map-marker-alt"></i> ${escapeHtml(address)}</p>
        ${distanceText}
        <button class="show-map-btn" onclick="focusStore(${i})"><i class='fas fa-map'></i> Show on Map</button>
        <div class="fert-section">
          <h4>Branded Fertilizers</h4>
          <ul>${branded}</ul>
          <h4>Non-Branded Fertilizers</h4>
          <ul>${nonBranded}</ul>
        </div>
      `;
      list.appendChild(storeCard);
    });
  }

  function addMarkersToMap(stores) {
    clearMarkers();
    stores.forEach((s, i) => {
      const lat = parseFloat(s.latitude ?? s.lat ?? 0);
      const lng = parseFloat(s.longitude ?? s.lng ?? 0);
      if (isFinite(lat) && isFinite(lng) && lat !== 0 && lng !== 0) {
        const marker = L.marker([lat, lng]).addTo(map);
        const name = s.name || '';
        const address = s.address || '';

        // Combine both fertilizer types for popup
        const branded = (s.branded || []).map(p => `<li>${escapeHtml(p.name)} (${p.category})</li>`).join('');
        const nonBranded = (s.non_branded || []).map(p => `<li>${escapeHtml(p.name)} (${p.category})</li>`).join('');
        const popupHTML = `
          <b>${escapeHtml(name)}</b><br>${escapeHtml(address)}<br><b>Branded:</b><ul>${branded}</ul><b>Non-Branded:</b><ul>${nonBranded}</ul>
        `;
        marker.bindPopup(popupHTML);
        markers.push(marker);
      }
    });
  }

  function focusStore(i) {
    if (!markers[i]) return;
    map.setView(markers[i].getLatLng(), 15);
    markers[i].openPopup();
  }

  function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat/2)**2 + Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*Math.sin(dLon/2)**2;
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
  }

  function showSuggestions() {
    const input = document.getElementById("searchBox").value.toLowerCase();
    const box = document.getElementById("suggestions");
    if (input.length < 2) return box.style.display = "none";
    const matches = cropSuggestions.filter(s => s.toLowerCase().includes(input));
    if (matches.length === 0) { box.innerHTML = ""; box.style.display = "none"; return; }
    box.innerHTML = matches.map(m => `<div class="suggestion-item" onclick="selectSuggestion('${m}')">${m}</div>`).join("");
    box.style.display = "block";
  }

  function selectSuggestion(s) {
    document.getElementById("searchBox").value = s;
    document.getElementById("suggestions").style.display = "none";
    searchPesticide();
  }

  function escapeHtml(str) {
    return String(str || '').replace(/[&<>"'\/]/g, s => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;'}[s]));
  }

  window.onload = function() {
    initMap();
    document.getElementById("searchBox").addEventListener("input", showSuggestions);
    document.getElementById("searchBox").addEventListener("keypress", e => { if (e.key === "Enter") searchPesticide(); });
    document.addEventListener("click", e => { if (!e.target.closest(".search-container")) document.getElementById("suggestions").style.display = "none"; });
  };
</script>

</body>
</html> 