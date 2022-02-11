<% if $MapMode == 1 %>
  <div class="homepage__map-imagecontainer">
    <div class="homepage__map-imagemode">
      <div class="homepage__map-titlebox">
        <% if $MapTitle %>
          <div class="homepage__map-title">
            $MapTitle
          </div>
        <% end_if %>
      </div>
      $MapImage
    </div>
  </div>
<% else %>
  <div class="homepage__map-container">
    <div class="homepage__map-titlebox">
      <% if $MapTitle %>
        <div class="homepage__map-title">
          $MapTitle
        </div>
      <% end_if %>
    </div>
    <div class="homepage__map-legend">
      <div class="homepage__map-legendindex waikato">
        <h3 class="homepage__map-legendindex-title">Waikato Westpac Rescue Helicopter</h3>
      </div>
      <div class="homepage__map-legendindex tect">
        <h3 class="homepage__map-legendindex-title">TECT Rescue Helicopter</h3>
      </div>
      <div class="homepage__map-legendindex greenlea">
        <h3 class="homepage__map-legendindex-title">Greenlea Rescue Helicopter</h3>
      </div>
      <div class="homepage__map-legendindex palmerston">
        <h3 class="homepage__map-legendindex-title">Palmerston North Rescue Helicopter</h3>
      </div>
    </div>
    <div class="homepage__map" id="map"></div>
  </div>
<% end_if %>
<script>
  function returnColor(subsite, theme, override) {
    if (subsite === 'Waikato') {
      if (theme == 'Waikato' || override == true)
        return '#ee3124';
      return '#ee312488';
    } else if (subsite === 'TECT') {
      if (theme == 'TECT' || override == true)
        return '#3cb4e7';
      return '#3cb4e788';
    } else if (subsite === 'Greenlea') {
      if (theme == 'Greenlea' || override == true)
        return '#8dc63f';
      return '#8dc63f88';
    } else if (subsite === 'Palmerston') {
      if (theme == 'Palmerston' || override == true)
        return '#00457c';
      return '#00457c88';
    } else {
      if (theme == 'Westpac' || override == true)
        return '#ee3124';
      return '#ee312488';
    }
  }

  function returnBase(subsite) {
    if (subsite === 'Waikato' || subsite === 'Westpac') {
      return window.location.protocol + "//www." + window.location.hostname + '/assets/Uploads/icon_base-waikato.svg';
    } else if (subsite === 'TECT') {
      return window.location.protocol + "//www." + window.location.hostname + '/assets/Uploads/icon_base-tect.svg';
    } else if (subsite === 'Greenlea') {
      return window.location.protocol + "//www." + window.location.hostname + '/assets/Uploads/icon_base-greenlea.svg';
    } else if (subsite === 'Palmerston') {
      return window.location.protocol + "//www." + window.location.hostname + '/assets/Uploads/icon_base-palmerston.svg';
    } else {
      return window.location.protocol + "//www." + window.location.hostname + '/assets/Uploads/icon_base-waikato.svg';
    }
  }

  function CreateMapBase(lat = 0, long = 0, subsite = 'test', map) {
    var image = returnBase(subsite);
    var mark = {
      path: "M 0 -5 z q 5 0 5 5 t -5 5 t -5 -5 Q -5 -5 0 -5",
      fillColor: returnColor(subsite, '$Theme', true),
      fillOpacity: 1.0,
      strokeWeight: 0,
      rotation: 0,
      scale: 2,
      anchor: new google.maps.Point(0, 0),
    }
    var marker = new google.maps.Marker({
      position: {lat: Number(lat), lng: Number(long)},
      icon: image,
      map,
    });
    marker.setMap(map);
  }

  function CreateMapIcon(lat = 0, long = 0, subsite = 'test', map) {
    var mark = {
      path: "M224.566,87.9260354 L224.273,103.622035 L224.273,103.658035 L186.896,103.634035 L186.896,87.1080354 C195.381,87.4880354 209.649,88.1670354 224.566,87.9260354 L224.566,87.9260354 Z M233.482,103.558035 L233.851,87.6480354 L233.832,87.6480354 C237.424,87.4830354 240.992,87.2660354 244.477,86.9500354 C245.417,92.6190354 244.771,96.9420354 242.378,99.6340354 C240.185,102.108035 236.65,103.158035 233.482,103.567035 L233.482,103.558035 Z M276.755,68.2910354 C276.755,68.2910354 266.139,82.7190354 258.71,73.7430354 L245.974998,47.1740354 C245.966,47.1740354 270.382,48.7580354 276.755,68.2910354 L276.755,68.2910354 Z M280.342,19.5190354 C282.925,19.5190354 285.015,17.4290354 285.015,14.8460354 C285.015,12.2640354 282.926,10.1740354 280.343,10.1740354 L182.223,10.1740354 L182.223,0.830035376 L163.534,0.830035376 L163.534,10.1740354 L56.069,10.1740354 C53.483,10.1740354 51.396,12.2650354 51.396,14.8470354 C51.396,17.4300354 53.483,19.5200354 56.069,19.5200354 L163.534,19.5200354 L163.534,24.8220354 C157.208,25.2040354 150.94,26.0610354 145.233,27.6970354 C145.233,27.6970354 143.284,29.0610354 146.787,30.2290354 L140.171,42.6850354 L46.337,48.1380354 C46.337,48.1380354 25.698,13.4830354 19.083,4.33403538 C12.461,-4.81396462 0,3.36203538 0,3.36203538 L10.9,36.8490354 L2.92,50.6700354 L2.724,55.3430354 L10.9,62.7390354 C10.9,62.7390354 10.12,69.9450354 9.148,73.0550354 C8.176,76.1720354 17.521,77.7280354 23.945,78.5070354 L44.774,59.2360354 L129.658,63.1280354 C129.658,63.1280354 162.099,83.5640354 177.551,86.5070354 L177.551,103.628035 L168.315,103.622035 C165.732,103.622035 163.588,105.712035 163.588,108.295035 C163.588,110.878035 165.628,112.968035 168.211,112.968035 L226.73,112.968035 C227.305,113.030035 228.5,113.132035 230.09,113.132035 C235.02,113.132035 243.726,112.145035 249.323,105.886035 C253.65,101.054035 255.145,94.3090354 253.826,85.8360354 C269.518,83.5140354 281.792,78.9050354 283.07,70.1350354 C283.07,70.1350354 278.398,41.1290354 226.615,37.4290354 L210.255,28.6670354 C210.255,28.6670354 197.815,25.9120354 182.223,24.9180354 L182.223,19.5190354 L280.342,19.5190354 Z",
      scale: 0.2,
      fillColor: returnColor(subsite, '$Theme', true),
      fillOpacity: 1.0,
      strokeWeight: 0,
      rotation: 0,
      anchor: new google.maps.Point(0, 0),
    }
    var marker = new google.maps.Marker({
      position: {lat: Number(lat), lng: Number(long)},
      icon: mark,
      map,
      title: "$Title",
    });
    marker.setMap(map);
  }

  function initMap() {
    const map = new google.maps.Map(document.getElementById("map"), {
      center: {lat: -38.9063924, lng: 175.0083781},
      zoom: 7.96,
      mapId: 'a2ccb782b858912',
      disableDefaultUI: true,
    });
    <% loop $getLinkedMissions %>
      var mark = {
        path: "M 0 -3 z q 3 0 3 3 t -3 3 t -3 -3 Q -3 -3 0 -3",
        fillColor: returnColor('$Subsite', '$Top.Theme', false),
        fillOpacity: 1.0,
        strokeWeight: 0,
        rotation: 0,
        scale: 3,
        anchor: new google.maps.Point(0, 0),
      }

      var marker = new google.maps.Marker({
        position: {lat: Number($Latitude), lng: Number($Longitude)},
        icon: mark,
        map,
        title: "$Title",
      });

      var contentString =
        '<div class="map__tooltip">' +
        '<a href="' + '$Link' + '" class="map__tooltip-title">' + '$Title' + '</a>' +
        '<div class="map__tooltip-contentupper">' +
        '<div class="map__tooltip-flight">' + '$FlightTime' + '</div>' +
        '<div class="map__tooltip-car">' + '$CarTime' + '</div>' +
        <% if $Stories %>
          '</div>' +
          '<div class="map__tooltip-contentlower">' +
          '<h3 class="map__tooltip-missionstitle">' + 'Stories' + '</h3>' +
          '<div class="map__tooltip-missions">' +
          <% loop $Stories %>
            '<a class="' + 'map__tooltip-mission" ' + 'href='
            + '"' + '$Link' + '"' + 'target="_blank">' + '$Title' + '</a>'
            <% if not $Last %> + '<br><br>' +
            <% end_if %>
          <% end_loop %>
          "</div>" +
          "</div>" +
          "<br><br>" +
        <% end_if %>
        "</div>";
      var infowindow = new google.maps.InfoWindow({
        content: contentString,
        maxWidth: 200,
      });

      marker.infowindow = infowindow;

      marker.addListener("click", function () {
        return this.infowindow.open(map, this);
      });
      marker.setMap(map);
    <% end_loop %>
    <% loop $getThis %>
      CreateMapIcon('$LatitudeIcon', '$LongitudeIcon', '$Theme', map)
      CreateMapBase('$Latitude', '$Longitude', '$Theme', map)
    <% end_loop %>
  }
</script>