//variables
    var marker;
    var path;
    var length;
    var coords;
    var map;
    var poly; 
    var coordsArray = [];//for future saving of routes to database
    var markerArray =[]; //used to clear map markers
//Get current position    
    pos = navigator.geolocation;
    pos.getCurrentPosition(success, error);
 //if position attained   
    function success(position)
    {  
      var mylat = position.coords.latitude;
      var mylong = position.coords.longitude;     
      coords = new google.maps.LatLng(mylat, mylong);
      initialize();
    }
//if get position unsuccessful
    function error()
    {   
      alert("Please ensure your browser supports location tracking and that you have it enabled.");
    } 
//initialize map using current position coordinates
    function initialize() {
      var mapOptions = {zoom: 16,center: coords,mapTypeId: google.maps.MapTypeId.SATELLITE};
      map = new google.maps.Map(document.getElementById('map'), mapOptions);
      var image = 'images/pinpoint.png';
      marker = new google.maps.Marker({map: map,position: coords, icon: image});    
      var polyOptions = {
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2 
      };
//Create polyline and set map to it
      poly = new google.maps.Polyline(polyOptions);
      poly.setMap(map);
//Add Liseteners to map
      google.maps.event.addListener(map, 'click', addLatLng);
      google.maps.event.addListener(map, 'click', addMarkers);
    }
//Add marker on click event and store in array "markerArray"
    function addMarkers(event){ 
        var image = 'images/marker.png';      
        marker = new google.maps.Marker({
        position: event.latLng,
          icon: image,
        map: map
      });
      markerArray.push(marker);
    }
// Set the map on all markers 
    function setAllMap(map) {
      for (var i = 0; i < markerArray.length; i++) {
      markerArray[i].setMap(map);
      }
    }
//Add click coordinates to "coordsArray"
//Add click event coordinates to polyline MVC array
    function addLatLng(event) {
      var coord = event.latLng;
      coordsArray.push(coord);
      path = poly.getPath();
      path.push(event.latLng);
//Get length of path
      length = google.maps.geometry.spherical.computeLength(path);
      length = $("distKm").value = Math.round(length/1000 * 100) / 100;
      document.getElementById("routeDistance").innerHTML = "Total Distance Km :" +length;
    }
//Clear map of markers and polylines, Empty all arrays, set length to 0.
    function clearMap(){
       setAllMap(null);
       markerArray=[];
       coordsArray =[];
       for(i = 0; i = path.length; i++) {
        path.pop();
       } 
        length =0;
        document.getElementById("routeDistance").innerHTML = "Total Distance Km :" +length;           
    }
//clear the last marker and polyline added to the map
    function clearLastMarker(){
      if(markerArray.length >0){
           markerArray[markerArray.length -1].setMap(null)
           markerArray.pop();  
           coordsArray.pop();
           path.pop();
           length = google.maps.geometry.spherical.computeLength(path);
           length = $("distKm").value = Math.round(length/1000 * 100) / 100;
           document.getElementById("routeDistance").innerHTML = "Total Distance Km :" +length;
       }else{
          return;
       }      
    }

       
  
    
  