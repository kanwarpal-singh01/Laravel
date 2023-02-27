<!DOCTYPE html>
<html>
<head> 
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Multiple Markers with Info Windows to Google Maps</title> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet"><script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  

<?php $storyAddedVal =[]; ?>
<style type="text/css">
  #mapCanvas .centerMarker{
  position:absolute;
  /*url of the marker*/
  background:url(https://admin.thespotapplication.com/public/Droppin.png) no-repeat;
  /*center the marker*/
  top:50%;left:50%;
  z-index:1;
  /*fix offset when needed*/
  margin-left:-10px;
  margin-top:-34px;
  /*size of the image*/
  height: 103px;
    width: 67px;
  cursor:pointer;
}
#mapCanvas {
    width: 100% ;
    height: 100vh;
}
.gmnoprint,.gm-fullscreen-control{
    display:none;¯
}
.droppinimage{
  z-index: 100000 !important;
}
.blurPage img{
  /* filter: blur(1px);
    -webkit-filter: blur(1px); */
}
.aDSManish{
  /* filter: blur(1.80px);
    -webkit-filter: blur(1.80px); */
}

#myMarker div{
 border-radius:50%;
}


#myMarker img{border-radius: 200px;}
#myMarker .activeStory img{border: 2px solid #ff5000 !important;}
/*#myMarker .activeStory:after{width: 15px; height: 15px; content: ""; background:url(https://admin.thespotapplication.com/public/uploads/user/medium/1645710651_Yara%20F.jpeg); position: absolute; right: 0; bottom: 0; border-radius: 50px; background-size: 100% 100%;}
*/
#myMarker .activeStoryOwn img{border: 2px solid #ff5000 !important;}
/*#myMarker .activeStoryOwn:after{width: 15px; height: 15px; content: ""; background:url(https://admin.thespotapplication.com/public/plusAdd.png); position: absolute; right: 0; bottom: 0; border-radius: 50px; background-size: 100% 100%;}*/

#myMarker .activeStoryOwnNot:after{width: 15px; height: 15px; content: ""; background:url(https://admin.thespotapplication.com/public/plusAdd.png); position: absolute; right: 0; bottom: 0; border-radius: 50px; background-size: 100% 100%;}
div img{border-width: 10px;border-style: solid;border-color: red}
body {font-family: "Times New Roman", Times, serif;}



.map-bg{position: relative;}
.cancel-btn{position:absolute; left:0; bottom:15px; text-align:center; z-index: 999; width:100%;}
.cancel-btn a{display:inline-block; padding:9px 30px; border:solid 2px #ff4c08; border-radius:12px; font-size:14px; background:white;  text-decoration: none;color:#FF5000; font-family: 'Montserrat', sans-serif; font-weight: 700;}
/* 
#mapCanvas .gm-style {
  transform: scale(0.8);
} */

.map-bg{
  zoom:0.75 !important;
}
</style>
</head>
<body>
    
<div class="map-bg">  
 <div id="mapCanvas"></div>
 <div class="cancel-btn"> 
   <a href="javascript:;" id="confirmBtn">Confirm</a>
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
    const myLatLng = { lat: Number(lat), lng: Number(lng) };
    const map = new google.maps.Map(document.getElementById("mapCanvas"), {
    zoom: 16,
    center: myLatLng,

  });
  
  $('<div/>').addClass('centerMarker').appendTo(map.getDiv())
  // getCircle(lat,lng);
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

function initMap() {
 
  var iconUser = {
      url: '{{url('/')}}/public/uploads/user/medium/'+login_user_data.profile_image, // url
      scaledSize: { height: 50, width: 50 },
      rotation: 20,
  };
   if(login_user_data.story != null){
    console.log("dsaas3333")
       storyOwnAddedVal.push('{{url('/')}}/public/uploads/user/medium/'+login_user_data.profile_image);
    }
    if(login_user_data.story == null){
storyOwnAddedNotVal.push('{{url('/')}}/public/uploads/user/medium/'+login_user_data.profile_image);
    }

  // var myMarker =  new google.maps.Marker({
  //   position: myLatLng,
    
  //   map,
  //     // icon: iconUser,
  //     icon:{
           
  //       anchor: new google.maps.Point(25, 25)
  //           },
  //     title: "myMarkerss",
  //     draggable: true,
  //     optimized: false,
  // });
  getCircle(lat,lng);
//   google.maps.event.addListener(map, "dragend", function() {
 
//    var lat_long = map.getCenter().toUrlValue().split(',');
//    console.log(lat_long);
//    getCircle(lat_long[0],lat_long[1]);
// });
// google.maps.event.addListener(map, "center_changed", function() {
//   var center = this.getCenter();
//   var latitude = center.lat();
//   var longitude = center.lng();
  
//   console.log("current latitude is: " + this.getZoom());
//   // console.log("current longitude is: " + longitude);
  
//   // if(this.getZoom() > 16){
//   //   map.setOptions({ zoom:this.getZoom() });
//   // }
//   // setTimeout(function(){
//     getCircle(latitude,longitude);
//   // },500)
 
  
// });
setInterval(function () {
  var center = map.getCenter();
  var latitude = center.lat();
  var longitude = center.lng();
  if(lat!= latitude){
    lat = latitude;
    lng = longitude;
    getCircle(latitude,longitude);
  }
 
}, 1000);
// google.maps.event.addListenerOnce(map, 'zoom_changed', function() {
//     var oldZoom = map.getZoom();
//     console.log(oldZoom);
//     map.setZoom(oldZoom - 1); //Or whatever
// });

  // google.maps.event.addListener(myMarker,'dragend',function(event) {
  //   // removeCircle()
  //   getCircle(this.position.lat(),this.position.lng());
   
   
  //   });
           
  // google.maps.event.addListener(myMarker, 'click', (function(markers) {
  //           return function() {
            
  //              window.location.href="?own"
  //           }
  //       })(myMarker));
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
        // console.log(this.getPanes());
        this.getPanes().markerLayer.id = 'myMarker';
        // $(this.getPanes().markerLayer).find('img').attr('data-story','asd');
        blurImage.forEach(element => {
         
       

         $(this.getPanes().markerLayer).find('img[src="'+element+'"]').addClass('aDSManish');

        
       });
       
    };
  myoverlay.setMap(map);


      
}
var cityCircle1 = null;
var cityCircle2 = null;
var cityCircle3 = null;
var cityCircle4 = null;
var cityCircle;

function getCircle(lat,lng) {

  if (map.getZoom() == 16) {
    console.log(map.getZoom(),"333")
    // map.setZoom(15.6);
  }
  if (map.getZoom() == 16) {
   
    // map.setZoom(15.6);
  }
  
	$("#confirmBtn").attr("href", "?lat="+lat+"&lng="+lng+"");

  if (cityCircle && cityCircle.setMap)
    cityCircle.setMap(null);
  cityCircle = new google.maps.Circle({
    strokeColor: "rgba(252,157,110,255)",
     
      strokeWeight: 0,
      fillColor: "rgba(255, 80, 0, 0.4)",
      fillOpacity: 1,
      map,
      center: { lat: Number(lat), lng: Number(lng) },
      radius: 150,
  });


  if (cityCircle2 && cityCircle2.setMap)
    cityCircle2.setMap(null);
  cityCircle2 = new google.maps.Circle({
    strokeColor: "rgba(252,157,110,255)",
     
     strokeWeight: 0,
     fillColor: "rgba(255, 80, 0, 0.3)",
     fillOpacity: 1,
     map,
     center: { lat: Number(lat), lng: Number(lng) },
     radius: 300,
  });

  if (cityCircle3 && cityCircle3.setMap)
    cityCircle3.setMap(null);
  cityCircle3 = new google.maps.Circle({
    strokeColor: "rgba(252,157,110,255)",
     
     strokeWeight: 0,
     fillColor: "rgba(255, 80, 0, 0.2)",
     fillOpacity: 1,
     map,
     center: { lat: Number(lat), lng: Number(lng) },
     radius: 400,
  });

  if (cityCircle4 && cityCircle4.setMap)
    cityCircle4.setMap(null);
  cityCircle4 = new google.maps.Circle({
    strokeColor: "rgba(255, 80, 0, 0.5)",
     
     strokeWeight: 3,
     fillColor: "rgba(255, 80, 0, 0.2)",
     fillOpacity: 1,
     map,
     center: { lat: Number(lat), lng: Number(lng) },
     radius: 500,
  });

}


 

setTimeout(function(){
  $( "#myMarker div" ).each(function( i ) {
    $('#myMarker div').find('img[src="https://maps.gstatic.com/mapfiles/api-3/images/spotlight-poi2.png').attr('src','https://admin.thespotapplication.com/public/Droppin.png').parent().addClass("droppinimage");
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
    if(blurImage.includes(data)){
      $(this).addClass('blurPage');
    }
//     setInterval(function () {
//       $(this).addClass('aDSManish');
// }, 1000);
   
  });
  
},1000);

function moveBus( map, marker ) {

console.log("444")
};


</script>

</body>
</html>