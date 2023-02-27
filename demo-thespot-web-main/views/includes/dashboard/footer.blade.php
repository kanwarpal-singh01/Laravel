</div>

<!-- JS bundle -->

<script type="text/javascript" src="{{ config('app.asset_url')}}assets/js/popper.min.js"></script> 
<script type="text/javascript" src="{{ config('app.asset_url')}}assets/js/bootstrap.min.js"></script>  
<script type="text/javascript" src="{{ config('app.asset_url')}}assets/js/owl.carousel.min.js"></script>
<script type="text/javascript" src="{{ config('app.asset_url')}}assets/js/aos.js"></script>

<script type="text/javascript" src="{{ config('app.asset_url')}}assets/js/jquery.range.slider.js"></script>
<script type="text/javascript" src="{{ config('app.asset_url')}}assets/js/jquery.easing.min.js"></script>
<script type="text/javascript" src="{{ config('app.asset_url')}}assets/js/script.js"></script>
<script type="text/javascript" src="{{ config('app.asset_url')}}/assets/js/select2.min.js"></script>



<script type="text/javascript">
$(window).scroll(function(){
if($(this).scrollTop() > 50){
    $('body').addClass('fixed');
}
else {
    $('body').removeClass('fixed');}
});


function store_notifications(data){
 
 $.ajax(
         {
     type: "post",
     url: "{{ url('/') }}/store-notification", // need to create this route
     data: data,
     cache: false,
     success: function (data) {

     }
 });

}



    $(".noti_909").on('click',function(){
  
    get_notifications();


});



$(".msg_noti").on('click',function(){
    get_message();

});





    




function get_notifications(){
 
 $.ajax(
         {
     type: "get",
     url: "{{ url('/') }}/get-notification", // need to create this route
     data: {},
     cache: false,
     success: function (data) {
         $(".notification-box ul").html(data);

     }
 });

}



function get_message(){
 
 $.ajax(
         {
     type: "get",
     url: "{{ url('/') }}/get-message", // need to create this route
     data: {},
     cache: false,
     success: function (data) {
         $(".message-box909 ul").html(data);

     }
 });

}




</script>


<script src="https://js.pusher.com/5.0/pusher.min.js"></script>

<script>
// $(function () {
//     $('#skills').selectpicker();
// });


$(".js-select2").select2({
        closeOnSelect : false,
        placeholder : "Enter Keyword",
        allowHtml: false,
        allowClear: false,
        tags: false, 
    });




function site_loader(data){

if(data){
   $(".loader").show();
  $("#overlayer").show();
}
else{
  $(".loader").hide();
  $("#overlayer").hide();
}


}


function fadeout_loader(){

    $(".loader").show().delay(4000).fadeOut();
  $("#overlayer").show().delay(4000).fadeOut();
  
}



Pusher.logToConsole = true;

var pusher = new Pusher('6c1bbddbc4c29dfe0aa0', {
    cluster: 'ap2',
    forceTLS: true
});

var my_id="{{ Auth::id() }}";

var channel = pusher.subscribe('notification-channel');
channel.bind('notification-event', function (data) {

  
  if (my_id == data.to) {
 
            var pending = parseInt($('#main_' + data.to).find('.badge').html());
            if (pending) {
            
                $('#main_' + data.to).find('.badge').html(pending + 1);
            } else {
            
                $('#main_'+ data.to+' i').append('<span class="badge">1</span>');
            }



    }
});

var channel = pusher.subscribe('secure-channel');
        channel.bind('secure-event', function (data) {

          

            if (my_id == data.to) {
 
 var pending = parseInt($('#message_' + data.to).find('.badge').html());
 if (pending) {
 
     $('#message_' + data.to).find('.badge').html(pending + 1);
 } else {
 
     $('#message_'+ data.to+' i').append('<span class="badge">1</span>');
 }

}

        });





function showMore()
{
    if ($('#threedots').css('display')=='none')
    {
        //already showing, we hide
            $('#threedots').show();
            $('#full_desc').hide();
            $('.show_hide').text('Show more..');
    }
    else
    {
            //show more
            $('#threedots').hide();
            $('#full_desc').show();
            $('.show_hide').text('Hide');

    }


}

$(".close").on('click',function(){


    location.reload();

    site_loader(true);
});

</script>