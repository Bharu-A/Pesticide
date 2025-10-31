document.addEventListener("DOMContentLoaded", function () {
  const map = L.map("map").setView([12.9716, 77.5946], 11);

  // Base map
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "&copy; OpenStreetMap contributors",
  }).addTo(map);

  // Marker colors for categories
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

  // Plot store markers
  if (typeof storeData !== "undefined" && storeData.length > 0) {
    const groupedStores = {};

    // Group by store
    storeData.forEach(store => {
      const key = store.store_name;
      if (!groupedStores[key]) {
        groupedStores[key] = {
          name: store.store_name,
          address: store.address,
          lat: parseFloat(store.lat),
          lng: parseFloat(store.lng),
          pesticides: []
        };
      }
      groupedStores[key].pesticides.push({
        name: store.pesticide_name,
        price: store.price,
        category: store.category
      });
    });

    Object.values(groupedStores).forEach(store => {
      const pesticideList = store.pesticides
        .map(p => `<li>${p.name} — ₹${p.price} (${p.category})</li>`)
        .join("");

      const popupHTML = `
        <b>${store.name}</b><br>
        ${store.address}<br><br>
        <b>Available Pesticides:</b>
        <ul>${pesticideList}</ul>
      `;

      // Use red or green icon based on dominant type
      const organicCount = store.pesticides.filter(p => p.category === "Organic").length;
      const inorganicCount = store.pesticides.length - organicCount;
      const icon = organicCount >= inorganicCount ? iconGreen : iconRed;

      L.marker([store.lat, store.lng], { icon }).addTo(map).bindPopup(popupHTML);
    });
  }
});
