<!DOCTYPE html>
<html>
<head> 
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Multiple Markers with Info Windows to Google Maps</title> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
<?php $storyAddedVal =[]; ?>
<style type="text/css">
#mapCanvas {
    width: 100% ;
    height: 100vh;
}
.gmnoprint,.gm-fullscreen-control{
    display:none;
}

.blurPage img{
  /* filter: blur(1px);
    -webkit-filter: blur(1px); */
}
.aDSManish{
  /* filter: blur(1.80px);
    -webkit-filter: blur(1.80px); */
}
#myMarker img{border-radius: 200px;}
#myMarker .activeStory img{border: 2px solid #ff5000 !important;}
/*#myMarker .activeStory:after{width: 15px; height: 15px; content: ""; background:url(https://admin.thespotapplication.com/public/uploads/user/medium/1645710651_Yara%20F.jpeg); position: absolute; right: 0; bottom: 0; border-radius: 50px; background-size: 100% 100%;}
*/
#myMarker .activeStoryOwn img{border: 2px solid #ff5000 !important;}
/*#myMarker .activeStoryOwn:after{width: 15px; height: 15px; content: ""; background:url(https://admin.thespotapplication.com/public/spyBlank.png); position: absolute; right: 0; bottom: 0; border-radius: 50px; background-size: 100% 100%;}*/

#myMarker .activeStoryOwnNot:after{width: 15px; height: 15px; content: ""; background:url(https://admin.thespotapplication.com/public/plusAdd.png); position: absolute; right: 0; bottom: 0; border-radius: 50px; background-size: 100% 100%;}



/* div img{border-width: 10px;border-style: solid;border-color: red} */
body {font-family: "Times New Roman", Times, serif;}

.cancel-btn{position:absolute;  bottom:15px; text-align:right; z-index: 999; width:100%;right: 15px;}
.cancel-btn a{display:inline-block; }
.cancel-btn a img{
  height:50px;
  width:50px;
  border-style: none;
}
/*.activeStoryOwnNot{top:-26px !important;}*/

.cancel-btn a.returnBtn{display:inline-block; padding:9px 30px; border:solid 2px #FF5000; border-radius:12px; font-size:16px; background:#fff; text-decoration: none;color:#FF5000; font-family: 'Montserrat', sans-serif; font-weight: 700; margin-right:10px;}

#myMarker div{
 /* border-radius:50%; */
}




</style>
</head>
<body>
<div class="map-bg">  
 <div id="mapCanvas"></div>
 <div class="cancel-btn"> 
  <a class="returnBtn" id="locationReturnButn" href="?returnlocation" style="display: none;">Return to your Location</a>  
  <a href="javascript:;" onclick="setCenter()" id="confirmBtn"><img src="https://admin.thespotapplication.com/public/locationss.png" /></a>
</div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCsXuVvJxS2YgluB3CSG0DW8hwSOm_cFuI"></script>
<script src="https://cdn.klokantech.com/maptilerlayer/v1/index.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    var markers = [];
    var storyAddedVal = [];
    var storyOwnAddedVal = [];
    var storyOwnAddedNotVal = [];
    var blurImage = [];
    var login_user_data = "";
    var lat = "{{$lat}}";
    var lng = "{{$lng}}";
    var user_id_val = "{{$user_id}}";
    getMarkerData()

function getMarkerData() {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
     $.ajax({
                  
                    url: '{{url('/')}}/api/dashboard',
                    type: 'POST',
                    data: {_token: CSRF_TOKEN, lat: Number(lat), lng: Number(lng), user_id: user_id_val},
                    dataType: 'JSON',
                    success: function (data) { 
                        markers = data.data;
                        login_user_data = data.login_user_data;
                        initMap();
                    }
                }); 
}
const myLatLng = { lat: Number(lat), lng: Number(lng) };
 
 const map = new google.maps.Map(document.getElementById("mapCanvas"), {
   zoom: 15.6,
   center: myLatLng,

 });
function initMap() {
 
 var imageOwnUrl = login_user_data.spy_mode == 1 ? 'https://admin.thespotapplication.com/public/spyBlank.png' : '{{url('/')}}/public/uploads/user/medium/'+login_user_data.profile_image;
  // var iconUser = {
  //     url: imageOwnUrl, // url
  //     scaledSize: { height: 50, width: 50 },
  //     anchor: new map.Point(30, 30)
  // };
  if(login_user_data.drop_pin_status == 1){
  	$('#locationReturnButn').show();
  }else{
  	$('#locationReturnButn').hide();
  }
   
   if(login_user_data.story != null){
    console.log("dsaas3333")
       storyOwnAddedVal.push(imageOwnUrl);
    }
    if(login_user_data.story == null){
storyOwnAddedNotVal.push(imageOwnUrl);
    }

  var myMarker =  new google.maps.Marker({
    position: myLatLng,
    
    map,
      icon: {
      url: imageOwnUrl, // url
      scaledSize: { height: 50, width: 50 },
      anchor: new google.maps.Point(25, 25)
  },
      title: "myMarkerss",
      optimized: false,
  });
  
  
  google.maps.event.addListener(myMarker, 'click', (function(markers) {
            return function() {
            
               window.location.href="?own"
            }
        })(myMarker));
let markerStatus = "";

  for( i = 0; i < markers.length; i++ ) {
    if(markers[i]['story'] != null){
storyAddedVal.push('{{url('/')}}/public/uploads/user/medium/'+markers[i]['profile_image']);
    }
    if(markers[i]['radius_status'] == 0){
      blurImage.push('{{url('/')}}/public/uploads/user/medium/'+markers[i]['profile_image']);
      }
    
    var position = new google.maps.LatLng(markers[i]['lat'], markers[i]['lng']);
    var icon = {
      url: '{{url('/')}}/public/uploads/user/medium/'+markers[i]['profile_image'], 
      scaledSize: { height: 50, width: 50},
      // shape:{coords:[17,17,18],type:'circle'},
    };

    

    marker =  new google.maps.Marker({
        position: position,
        map,
        icon: icon ,
        title: "myMarker"+i,

        optimized: false
    });

//  if(markers[i]['radius_status'] == 0){ marker.setOpacity(0.5); }
 if(markers[i]['story'] != null){
markerStatus = "Active";
 }else{
  markerStatus = "55";
 }
 console.log(markerStatus);
    
    

        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
              if(markers[i]['radius_status'] == 1){
                window.location.href="?"+i;
              }else{
                window.location.href="?out";
              }
            }
        })(marker, i));
  }
  var myoverlay = new google.maps.OverlayView();
    myoverlay.draw = function () {

        console.log(blurImage);
        this.getPanes().markerLayer.id = 'myMarker';
        // $(this.getPanes().markerLayer).find('img').attr('data-story','asd');
        blurImage.forEach(element => {
         
          $(this.getPanes().markerLayer).find('img[src="'+element+'"]').addClass('aDSManish');
        });
        // 
    };
  myoverlay.setMap(map);

      new google.maps.Circle({
      strokeColor: "rgba(252,157,110,255)",
     
      strokeWeight: 0,
      fillColor: "rgba(255, 80, 0, 0.4)",
      fillOpacity: 1,
      map,
      center: { lat: Number(lat), lng: Number(lng) },
      radius: 150,
    });
    new google.maps.Circle({
      strokeColor: "rgba(252,157,110,255)",
     
      strokeWeight: 0,
      fillColor: "rgba(255, 80, 0, 0.3)",
      fillOpacity: 1,
      map,
      center: { lat: Number(lat), lng: Number(lng) },
      radius: 300,
    });
    new google.maps.Circle({
      strokeColor: "rgba(252,157,110,255)",
     
      strokeWeight: 0,
      fillColor: "rgba(255, 80, 0, 0.2)",
      fillOpacity: 1,
      map,
      center: { lat: Number(lat), lng: Number(lng) },
      radius: 400,
    });
    new google.maps.Circle({
      strokeColor: "rgba(255, 80, 0, 0.5)",
     
      strokeWeight: 3,
      fillColor: "rgba(255, 80, 0, 0.2)",
      fillOpacity: 1,
      map,
      center: { lat: Number(lat), lng: Number(lng) },
      radius: 500,
    });
}

//https://admin.thespotapplication.com/public/uploads/user/medium/1645710651_Yara F.jpeg


setTimeout(function(){
  
  $( "#myMarker div" ).each(function( i ) {
    var data = $(this).find('img').attr('src');
    if(storyAddedVal.includes(data)){
     
      $(this).addClass('activeStory');
    }
     if(storyOwnAddedVal.includes(data) && storyOwnAddedNotVal.length == 0){
     
      $(this).addClass('activeStoryOwn');
    }
    if(storyOwnAddedNotVal.includes(data)){
      $(this).addClass('activeStoryOwnNot');
    }
    // if(blurImage.includes(data)){
    //   $(this).addClass('blurPage');
    // }
    setInterval(function () {
      $(this).addClass('aDSManish');
}, 1000);
  //  $('.activeStoryOwn').css('top','-30px');
  //   $('.activeStoryOwnNot').css('top','-30px');
   
  });
  
},1000);

function moveBus( map, marker ) {

console.log("444")
};
function setCenter() {
 
  map.setCenter(myLatLng);
  map.setZoom(15.6);
}



</script>

</body>
</html>