<!DOCTYPE html>
<html>
<head>
  <title>Hospital Locations</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <style>
    body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
    #map { height: 500px; width: 100%; border-radius: 10px; margin-top: 15px; }
    .filters { margin: 15px; text-align: center; }
    select, button {
      padding: 8px;
      margin: 5px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    button {
      background: #e63946;
      color: #fff;
      cursor: pointer;
    }
    button:hover { background: #d62828; }
  </style>
</head>
<body>

  <h2 style="text-align:center;">Find Hospitals for Blood Requests</h2>

  <!-- Filter Section -->
  <div class="filters">
    <label>District: </label>
    <select id="district">
      <option value="">All</option>
      <option value="Trivandrum">Trivandrum</option>
      <option value="Kochi">Kochi</option>
      <option value="Kollam">Kollam</option>
      <option value="Pathanamttita">Pathanamttita</option>
      <option value="Alappuzha">Alappuzha</option>
      <option value="Kottayam">Kottayam</option>
      <option value="Idukki">Idukki</option>
      <option value="Ernakulam">Ernakulam</option>
      <option value="Thrissur">Thrissur</option>
      <option value="Palakkad">Palakkad</option>
      <option value="Malappuram">Malappuram</option>
      <option value="Kozhikode">Kozhikode</option>
      <option value="Wayanad">Wayanad</option>
      <option value="Kannur">Kannur</option>
      <option value="Kasaragod">Kasaragod</option>
      <!-- add more districts -->
    </select>

    <label>Blood Type: </label>
    <select id="blood_type">
      <option value="">All</option>
      <option value="A+">A+</option>
      <option value="A-">A-</option>
      <option value="B+">B+</option>
      <option value="O+">O+</option>
      <option value="O-">O-</option>

      <option value="AB+">AB+</option>
        <option value="AB-">AB-</option>
      <!-- add more types -->
    </select>   

    <button onclick="loadHospitals()">Search</button>
  </div>

  <!-- Map -->
  <div id="map"></div>

  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script>
    var map = L.map('map').setView([10.8505, 76.2711], 7); // Kerala center

    // Base map
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var markersLayer = L.layerGroup().addTo(map);

    function loadHospitals() {
      markersLayer.clearLayers();

      var district = document.getElementById("district").value;
      var blood_type = document.getElementById("blood_type").value;

      fetch("get_hospitals.php?district=" + district + "&blood_type=" + blood_type)
        .then(res => res.json())
        .then(data => {
          if (data.length === 0) {
            alert("No hospitals found for selected filters.");
          }
          data.forEach(hospital => {
            var marker = L.marker([hospital.latitude, hospital.longitude]).addTo(markersLayer);
            marker.bindPopup(`
              <b>${hospital.hospital_name}</b><br>
              District: ${hospital.district}<br>
              Blood Type: ${hospital.blood_type}<br>
              <button onclick="alert('Request sent to ${hospital.hospital_name}')">Request</button>
            `);
          });
        });
    }
    // Load all hospitals initially
    loadHospitals();
  </script>

</body>
</html>
