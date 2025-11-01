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
  <script>
    let map;
    let markers = [];
    let userLocation = null;
    const cropSuggestions = [
      "Rice", "Cotton", "Vegetables", "Fruits", "Wheat", 
      "Maize", "Pulses", "Sugarcane", "Tomato", "Potato"
    ];

    // Initialize the map
    function initMap() {
      map = L.map('map').setView([12.9716, 77.5946], 11); // Default to Bengaluru
      
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 18,
      }).addTo(map);
      
      // Try to get user's location
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          position => {
            userLocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            map.setView([userLocation.lat, userLocation.lng], 13);
            
            // Add user location marker
            L.marker([userLocation.lat, userLocation.lng])
              .addTo(map)
              .bindPopup("<b>Your Location</b>")
              .openPopup();
          },
          error => {
            console.log("Geolocation error: ", error);
          }
        );
      }
    }

    // Clear all markers from the map
    function clearMarkers() {
      markers.forEach(marker => map.removeLayer(marker));
      markers = [];
    }

    // Search for pesticide stores based on crop
    async function searchPesticide() {
      const crop = document.getElementById("searchBox").value.trim();
      if (!crop) {
        alert("Please enter a crop name");
        return;
      }

      // Show loading state
      document.getElementById("loading").style.display = "block";
      document.getElementById("noResults").style.display = "none";
      document.getElementById("storeList").innerHTML = "";
      document.getElementById("resultsCount").textContent = "Searching...";
      
      try {
        const response = await fetch(`search.php?crop=${encodeURIComponent(crop)}`);
        
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();

        // Hide loading state
        document.getElementById("loading").style.display = "none";
        
        // Clear previous results
        clearMarkers();
        
        if (!data.success || data.stores.length === 0) {
          document.getElementById("noResults").style.display = "block";
          document.getElementById("resultsCount").textContent = "0 stores found";
          return;
        }

        // Update results count
        document.getElementById("resultsCount").textContent = `${data.stores.length} stores found for ${data.crop}`;
        
        // Display stores
        displayStores(data.stores);
        
        // Add markers to map
        addMarkersToMap(data.stores);
        
        // Fit map to show all markers
        if (markers.length > 0) {
          const group = L.featureGroup(markers);
          map.fitBounds(group.getBounds().pad(0.1));
        }
      } catch (error) {
        console.error("Error fetching stores:", error);
        document.getElementById("loading").style.display = "none";
        document.getElementById("noResults").style.display = "block";
        document.getElementById("noResults").innerHTML = `
          <i class="fas fa-exclamation-triangle"></i>
          <h3>Error loading stores</h3>
          <p>Please check if the server is running and try again</p>
          <p><small>Error details: ${error.message}</small></p>
        `;
      }
    }

    // Display stores in the results list
    function displayStores(stores) {
      const storeList = document.getElementById("storeList");
      storeList.innerHTML = "";
      
      stores.forEach((store, index) => {
        const { name, address, lat, lng, pesticides } = store;
        
        // Calculate distance if user location is available
        let distanceText = "";
        if (userLocation) {
          const distance = calculateDistance(
            userLocation.lat, userLocation.lng, 
            parseFloat(lat), parseFloat(lng)
          );
          distanceText = `<div class="store-distance"><i class="fas fa-location-arrow"></i> ${distance.toFixed(1)} km away</div>`;
        }
        
        // Create pesticide list HTML
        let pesticideHTML = "";
        if (pesticides && pesticides.length > 0) {
          pesticideHTML = `<div class="pesticide-list">`;
          pesticides.forEach(pesticide => {
            pesticideHTML += `
              <div class="pesticide-item">
                <div class="pesticide-info">
                  <div class="pesticide-main">
                    <div class="pesticide-name">${pesticide.name}</div>
                    <div class="pesticide-description">${pesticide.description || 'Description not available'}</div>
                  </div>
                  <div class="pesticide-meta">
                    <span class="pesticide-price">₹${pesticide.price}</span>
                    <span class="pesticide-category category-${pesticide.category.toLowerCase()}">${pesticide.category}</span>
                  </div>
                </div>
              </div>
            `;
          });
          pesticideHTML += `</div>`;
        }
        
        const storeCard = document.createElement("div");
        storeCard.className = "store-card";
        storeCard.innerHTML = `
          <div class="store-name"><i class="fas fa-store"></i> ${name}</div>
          <div class="store-address"><i class="fas fa-map-marker-alt"></i> ${address}</div>
          ${distanceText}
          ${pesticideHTML}
          <div class="store-actions">
                    <a href="https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(name + ', ' + address)}"
          target="_blank"
          class="map-link">
          <i class="fas fa-map-location-dot"></i> View on Google Maps
        </a>

          </div>
        `;

        storeCard.onclick = () => focusStore(index);
        storeList.appendChild(storeCard);
      });
    }

    // Add markers to the map
    function addMarkersToMap(stores) {
      stores.forEach((store, index) => {
        const { name, address, lat, lng, pesticides } = store;
        
        // Create pesticide list for popup
        let pesticideList = "";
        if (pesticides && pesticides.length > 0) {
          pesticideList = "<div style='margin: 10px 0; max-height: 200px; overflow-y: auto;'>";
          pesticides.forEach(p => {
            pesticideList += `
              <div style="margin-bottom: 8px; padding-bottom: 8px; border-bottom: 1px solid #f0f0f0;">
                <div style="font-weight: bold; color: #2e7d32;">${p.name}</div>
                <div style="font-size: 0.8rem; color: #666; margin: 2px 0;">${p.description || 'Description not available'}</div>
                <div style="display: flex; justify-content: space-between; font-size: 0.85rem;">
                  <span style="font-weight: bold;">₹${p.price}</span>
                  <span style="background: ${p.category === 'Organic' ? '#e8f5e9' : '#ffebee'}; 
                        color: ${p.category === 'Organic' ? '#2e7d32' : '#c62828'}; 
                        padding: 1px 6px; border-radius: 10px; font-size: 0.7rem;">
                    ${p.category}
                  </span>
                </div>
              </div>
            `;
          });
          pesticideList += "</div>";
        }
        
        const marker = L.marker([lat, lng])
          .bindPopup(`
            <div style="min-width: 280px; max-width: 350px;">
              <h3 style="margin: 0 0 8px; color: var(--primary)">${name}</h3>
              <p style="margin: 0 0 10px; color: #666; font-size: 0.9rem;">${address}</p>
              ${pesticides && pesticides.length > 0 ? 
                `<div style="margin: 15px 0 5px; font-weight: bold; color: #333;">Available Pesticides:</div>${pesticideList}` : 
                "<p style='color: #666; font-style: italic;'>No pesticides available</p>"}
              <button onclick="focusStore(${index})" style="background: var(--primary); color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; width: 100%; margin-top: 10px;">Available</button>
            </div>
          `)
          .addTo(map);
        
        markers.push(marker);
      });
    }

    // Focus on a specific store
    function focusStore(index) {
      const marker = markers[index];
      map.setView(marker.getLatLng(), 15);
      marker.openPopup();

      // Highlight the selected store card
      const cards = document.querySelectorAll(".store-card");
      cards.forEach((card, i) => {
        card.classList.toggle("active", i === index);
      });
    }

    // Calculate distance between two coordinates (Haversine formula)
    function calculateDistance(lat1, lon1, lat2, lon2) {
      const R = 6371; // Earth's radius in km
      const dLat = (lat2 - lat1) * Math.PI / 180;
      const dLon = (lon2 - lon1) * Math.PI / 180;
      const a = 
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
        Math.sin(dLon/2) * Math.sin(dLon/2);
      const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
      return R * c;
    }

    // Show crop suggestions
    function showSuggestions() {
      const input = document.getElementById("searchBox").value.toLowerCase();
      const suggestionsContainer = document.getElementById("suggestions");
      
      if (input.length < 2) {
        suggestionsContainer.style.display = "none";
        return;
      }
      
      const filteredSuggestions = cropSuggestions.filter(suggestion => 
        suggestion.toLowerCase().includes(input)
      );
      
      if (filteredSuggestions.length === 0) {
        suggestionsContainer.style.display = "none";
        return;
      }
      
      suggestionsContainer.innerHTML = filteredSuggestions
        .map(suggestion => `<div class="suggestion-item" onclick="selectSuggestion('${suggestion}')">${suggestion}</div>`)
        .join("");
      
      suggestionsContainer.style.display = "block";
    }

    // Select a suggestion
    function selectSuggestion(suggestion) {
      document.getElementById("searchBox").value = suggestion;
      document.getElementById("suggestions").style.display = "none";
      searchPesticide();
    }

    // Initialize the application
    window.onload = function() {
      initMap();
      
      // Add event listeners
      document.getElementById("searchBox").addEventListener("input", showSuggestions);
      document.getElementById("searchBox").addEventListener("keypress", function(e) {
        if (e.key === "Enter") {
          searchPesticide();
        }
      });
      
      // Close suggestions when clicking outside
      document.addEventListener("click", function(e) {
        if (!e.target.closest(".search-container")) {
          document.getElementById("suggestions").style.display = "none";
        }
      });
    };
  </script>
</body>
</html>