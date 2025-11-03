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
<style>
  body {
    font-family: Arial, sans-serif;
    background: #f4f6f7;
    margin: 0;
    padding: 0;
  }
  .container {
    max-width: 1100px;
    margin: 30px auto;
    padding: 20px;
  }
  .store-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    padding: 16px;
    margin-bottom: 16px;
  }
  .store-name {
    font-size: 1.2rem;
    font-weight: 700;
    color: #1b5e20;
  }
  .store-address {
    color: #555;
    margin-bottom: 8px;
  }
  .toggle-btn {
    background: #e8f5e9;
    border: none;
    padding: 6px 10px;
    border-radius: 6px;
    color: #1b5e20;
    cursor: pointer;
    font-size: 0.9rem;
    margin-top: 8px;
  }
  .toggle-btn:hover {
    background: #c8e6c9;
  }
  .pesticide-list {
    display: none;
    margin-top: 10px;
    padding: 10px;
    border-top: 1px solid #ddd;
    background: #fafafa;
    border-radius: 8px;
  }
  .pesticide-item {
    margin-bottom: 8px;
    display: flex;
    justify-content: space-between;
  }
  .pesticide-info {
    flex: 1;
  }
  .pesticide-name {
    font-weight: 600;
    color: #2e7d32;
    text-decoration: none;
  }
  .pesticide-name:hover {
    text-decoration: underline;
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
  </style>
</head>
<body>
<div class="container">
  <h1>Fertilizer & Shop Finder</h1>
  <input type="text" id="crop" placeholder="Enter crop name (e.g., Rice)" />
  <button id="searchBtn">Search</button>

  <div id="storeList"></div>
</div>

<script>
document.getElementById('searchBtn').addEventListener('click', () => {
  const crop = document.getElementById('crop').value.trim();
  if (!crop) {
    alert("Please enter a crop name");
    return;
  }
  fetch(`search.php?crop=${encodeURIComponent(crop)}`)
    .then(res => res.json())
    .then(data => {
      const storeList = document.getElementById('storeList');
      storeList.innerHTML = "";

      if (!data.success || !data.stores.length) {
        storeList.innerHTML = `<p>No shops found for ${crop}.</p>`;
        return;
      }

      data.stores.forEach((store, idx) => {
        const div = document.createElement('div');
        div.className = 'store-card';
        div.innerHTML = `
          <div class="store-name">${store.name}</div>
          <div class="store-address">📍 ${store.address}</div>
          <button class="toggle-btn" data-target="fertilizer-${idx}">Show Fertilizers ▼</button>
          <div id="fertilizer-${idx}" class="pesticide-list">
            ${store.pesticides.map(f => `
              <div class="pesticide-item">
                <div class="pesticide-info">
                  <a href="fertilizer.html?name=${encodeURIComponent(f.name)}" class="pesticide-name">${f.name}</a>
                  <div class="pesticide-description">${f.description || 'No description available'}</div>
                </div>
                <div>
                  <span class="pesticide-price">₹${f.price}</span>
                  <span class="pesticide-category">${f.category}</span>
                </div>
              </div>
            `).join('')}
          </div>
        `;
        storeList.appendChild(div);
      });

      // Add toggle functionality
      document.querySelectorAll('.toggle-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          const target = document.getElementById(btn.dataset.target);
          if (target.style.display === 'block') {
            target.style.display = 'none';
            btn.innerText = 'Show Fertilizers ▼';
          } else {
            target.style.display = 'block';
            btn.innerText = 'Hide Fertilizers ▲';
          }
        });
      });
    })
    .catch(err => {
      console.error(err);
      alert("Error fetching data");
    });
});
</script>
</body>
</html>