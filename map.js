document.addEventListener("DOMContentLoaded", function () {
  const map = L.map("map").setView([12.9716, 77.5946], 11);

  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "&copy; OpenStreetMap contributors",
  }).addTo(map);

  const iconGreen = L.icon({
    iconUrl: "https://maps.google.com/mapfiles/ms/icons/green-dot.png",
    iconSize: [32, 32],
    iconAnchor: [16, 32],
    popupAnchor: [0, -30],
  });

  const iconRed = L.icon({
    iconUrl: "https://maps.google.com/mapfiles/ms/icons/red-dot.png",
    iconSize: [32, 32],
    iconAnchor: [16, 32],
    popupAnchor: [0, -30],
  });

  if (typeof storeData !== "undefined" && Array.isArray(storeData) && storeData.length > 0) {
    const groupedStores = {};

    storeData.forEach(store => {
      const key = store.store_name || store.name || '';
      const lat = (typeof store.lat !== 'undefined') ? parseFloat(store.lat) : (typeof store.latitude !== 'undefined' ? parseFloat(store.latitude) : NaN);
      const lng = (typeof store.lng !== 'undefined') ? parseFloat(store.lng) : (typeof store.longitude !== 'undefined' ? parseFloat(store.longitude) : NaN);

      if (!isFinite(lat) || !isFinite(lng)) {
        // skip invalid coords
        return;
      }

      if (!groupedStores[key]) {
        groupedStores[key] = {
          name: store.store_name || store.name || 'Store',
          address: store.address || '',
          lat: lat,
          lng: lng,
          pesticides: []
        };
      }
      groupedStores[key].pesticides.push({
        name: store.pesticide_name || store.name || '',
        price: store.price || '',
        category: store.category || ''
      });
    });

    Object.values(groupedStores).forEach(store => {
      const pesticideList = store.pesticides.map(p => `<li>${p.name} ${p.price ? '— ₹' + p.price : ''} (${p.category})</li>`).join("");
      const popupHTML = `<b>${store.name}</b><br>${store.address}<br><br><b>Available:</b><ul>${pesticideList}</ul>`;
      const organicCount = store.pesticides.filter(p => p.category && p.category.toLowerCase() === "organic").length;
      const inorganicCount = store.pesticides.length - organicCount;
      const icon = organicCount >= inorganicCount ? iconGreen : iconRed;
      L.marker([store.lat, store.lng], { icon }).addTo(map).bindPopup(popupHTML);
    });
  }
});
