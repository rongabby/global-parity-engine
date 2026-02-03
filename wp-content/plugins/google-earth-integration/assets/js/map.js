(function($) {
    'use strict';
    
    // Initialize map when document is ready
    $(document).ready(function() {
        if (typeof google !== 'undefined' && typeof geiMarkers !== 'undefined') {
            initMap();
        }
    });
    
    function initMap() {
        // Center map on Japan
        var mapCenter = {lat: 35.0, lng: 139.0};
        
        var map = new google.maps.Map(document.getElementById('google-earth-map'), {
            zoom: 3,
            center: mapCenter,
            mapTypeId: 'terrain'
        });
        
        // Create info window
        var infoWindow = new google.maps.InfoWindow();
        
        // Add markers from PHP
        geiMarkers.forEach(function(markerData, index) {
            var marker = new google.maps.Marker({
                position: {lat: parseFloat(markerData.lat), lng: parseFloat(markerData.lng)},
                map: map,
                title: markerData.name,
                label: (index + 1).toString(),
                animation: google.maps.Animation.DROP
            });
            
            // Add click listener to show info
            marker.addListener('click', function() {
                var content = '<div class="marker-info-window">' +
                    '<h3>' + markerData.name + '</h3>' +
                    '<p><strong>Coordinates:</strong> ' + markerData.lat + '° N, ' + markerData.lng + '° E</p>' +
                    '<p>' + markerData.description + '</p>' +
                    '</div>';
                infoWindow.setContent(content);
                infoWindow.open(map, marker);
            });
        });
        
        // Draw polyline connecting markers
        if (geiMarkers.length > 1) {
            var path = geiMarkers.map(function(marker) {
                return {lat: parseFloat(marker.lat), lng: parseFloat(marker.lng)};
            });
            
            var polyline = new google.maps.Polyline({
                path: path,
                geodesic: true,
                strokeColor: '#FF0000',
                strokeOpacity: 0.7,
                strokeWeight: 2
            });
            
            polyline.setMap(map);
        }
    }
    
})(jQuery);
