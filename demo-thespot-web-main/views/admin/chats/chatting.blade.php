@extends('layouts.admin')
@section('content')

<style>
    body {
        scrollbar-color: 'red blue';
    }

    .chatTabActive {
        border-bottom: 1px solid #dedfe0;
        padding-bottom: 10px;
        padding: 10px;
        background: #f66e27;
    }

    .chat-sanders .msg {
        padding: 5px 15px;
    }

    .send-span {
        font-size: 10px;
        color: aliceblue !important;
    }

    .receive-span {
        font-size: 10px;
    }
    #last_message{

    width: 180px;
   
    }
    .msg{
       position: relative;
    word-break: break-word;
    white-space: pre-wrap;
    padding: 10px 15px;
    border-radius: 5px;
    line-height: 1.5;
    font-size: 14px;
    font-weight: 500;
    }
</style>
<div class="card tableCard code-table">
    <div class="card-header">
        <h5>Chat Management</h5>
        <div class="card-header-right">
        </div>
    </div>
</div>
<div class="row" >
    <div class="col-xl-3 col-md-4" >
        <div class="card" id="chatListRef">
            <div class="card-header">
                <h5>Chat List</h5>
            </div>
            <div class="card-block">
                <div class="row">
                    @if(count($chatsList))
                    @foreach($chatsList as $key => $value)
                    @if(collect(request()->segments())->last() != $value['sender_id'])
                    <div class="col-sm-12 m-b-10" style="border-bottom: 1px solid #dedfe0;padding-bottom: 10px;">
                        <div class="widget-timeline">
                            <div class="media">
                                <div class="mr-3 photo-table">
                                    <!-- <i class="fas fa-circle text-c-green f-10 m-r-10"></i> -->
                                    <a onclick="startChat('<?= $value['room'] ?>','<?= $user_id ?>','<?= $value['user']->id ?>','{{$value['user']->name}}','{{$value['fake_user']->name}}')"><img class="rounded-circle" style="width:40px;height:40px" src="{{config('app.profile_url')}}/{{$value['user']->profile_image}}" alt="chat-user"></a>
                                </div>
                                <div class="media-body">
                                <div class="row" style="margin-left: 0px;" i>
                                    <h6 class="d-inline-block" onclick="startChat('<?= $value['room'] ?>','<?= $user_id ?>','<?= $value['user']->id ?>','{{$value['user']->name}}','{{$value['fake_user']->name}}')">{{$value['user']->name}}</h6>
                                   
                                    @if($value['is_liked'] == 1)
                                    <a onclick="updateLikeStatus({{$value['user']->id}},<?= $user_id ?>,'like-profile')" style="position: absolute;right: 54px;"><img src="{{config('app.public_url')}}admin/icon/doubleHeart.png" width="18" height="18" ></a>
                                    @endif
                                    @if($value['is_liked'] == 0)
                                    <a onclick="updateLikeStatus({{$value['user']->id}},<?= $user_id ?>,'like-profile')" style="position: absolute;right: 54px;"><img src="{{config('app.public_url')}}admin/icon/grayHeart.png" width="18" height="18" ></a>
                                    @endif
                                    @if($value['is_viewed'] == 0)
                                    <a style="position: absolute;right: 20px;" onclick="updateViewStatus({{$value['user']->id}},<?= $user_id ?>,'profile-visit')" target="_blank" class="mb-2 mr-2 " title="User Detail"><i style="font-size: 18px;" class="fas fa-eye"></i></a>
                                    @endif
                                    @if($value['is_viewed'] == 1)
                                    <a style="position: absolute;right: 20px;" onclick="updateViewStatus({{$value['user']->id}},<?= $user_id ?>,'profile-visit')" target="_blank" class="mb-2 mr-2 " title="User Detail"><i style="font-size: 18px;color: #f9b291;" class="fas fa-eye"></i></a>
                                    @endif
                                    </div>
                                    <p onclick="startChat('<?= $value['room'] ?>','<?= $user_id ?>','<?= $value['user']->id ?>','{{$value['user']->name}}','{{$value['fake_user']->name}}')" class="m-b-0 text-muted" id="last_message">{{$value['last_message']}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <hr />
                    @endforeach
                    @else
                    <div class="col-sm-12 m-b-30">
                        <div class="widget-timeline">
                            <div class="media">
                                <h5>No Data Found</h5>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-8 col-md-8">
        <div class="card chat-sanders">
            <div class="card-header  borderless" style="background: linear-gradient(-135deg, #f66e27 0%, #f66e27 100%);">
                <h5 class="text-white">Chat with <b class="chatter_name"></b></h5>
            </div>
            <div class="card-block m-t-30 p-0 ">
                <div class="scroll-div ps ps--active-y" id="chat-scroll" style="overflow-y: auto;height:500px;">
                    <div style="padding:0 30px 35px 30px;" class="messagesList">
                        <!-- <p class="text-center text-muted">JUN 23 3:46PM</p> -->
                        <div style="text-align: center;">
                            <img src="{{ asset('images/logo.png') }}" style="height: 200px;margin-top:100px;opacity: 0.1;">
                        </div>
                    </div>
                    <!-- <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                        <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                    </div>
                    <div class="ps__rail-y" style="top: 0px; height: 280px; right: 0px;">
                        <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 234px;"></div>
                    </div> -->
                </div>
            </div>
            <div class="right-icon-control border-top messageBox" style="display:none">
                <div class="input-group input-group-button p-10">
                    <input type="hidden" id="sender_id">
                    <input type="hidden" id="receiver_id">
                    <input type="hidden" id="room">
                    <textarea class="form-control border-0 text-muted" placeholder="Write your message" id="message"></textarea>
                   <!--  <input type="text" class="form-control border-0 text-muted" placeholder="Write your message" id="message"> -->
                    <div class="input-group-append">
                        <button class="btn" type="button" id="send"><i class="fas f-20 fa-paper-plane"></i></button>
                    </div>
                </div>
            </div>
            <div class="right-icon-control border-top blockBox" style="display:none">
                <h6 style="text-align: center;opacity: 0.5;color: #f66e27;padding: 20px;">You are blocked by this user</h6>
            </div>
        </div>
    </div>
</div>
@stop
@section('customJs')
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
<script src="https://code.jquery.com/jquery-1.11.1.js"></script>
<script>
   
    var socket;
    user_id = '{{$user_id}}';
    senderName = 'S';
    receiverName = 'R';
    blockStatus = 0;
    dateSeparator = '';
    $(function() {
        socket = io.connect('https://admin.thespotapplication.com:17301', {
    reconnection: true,
    reconnectionDelay: 500,
    jsonp: false,
    reconnectionAttempts: Infinity,
    transports: ['websocket']
});
            
        socket.on("connect", () => {
            console.log("Device Connected");
           
            <?php if($chatsDataById != ""){ ?>
               var sender_id={{$chatsDataById->sender_id}};
                var receiver_id={{$chatsDataById->receiver_id}};
                var room='';
                if(sender_id > receiver_id){
                    room=receiver_id+'_'+sender_id;
                }
                 if(receiver_id > sender_id){
                      room=sender_id+'_'+receiver_id;
                }
                
              startChat(room,{{$chatsDataById->receiver_id}},{{$chatsDataById->sender_id}},'{{$chatsDataById->sender_name}}','{{$chatsDataById->receiver_name}}');
               <?php } ?>
        });
        socket.on("newChatMessages", (result) => {
            //setChatMessages(result)
            console.log(result);
            sentTime = timeDuration(result.created_at);
            separator = dateSeparation(result.created_at);

            if (result.sender_id == user_id) {
                html = `<div class="row m-b-0 send-chat align-items-end"> <div class = "col text-right"><div class = "msg" ><h6 class = "m-b-0 text-white" > ${result.message}</h6><span class="send-span"> ${sentTime}</span> </div> </div> <div class = "col-auto p-l-0" ><h5 class = "text-white d-flex align-items-center theme-bg justify-content-center" > ${senderName} </h5></div> </div>`;
            } else {
                html = `<div class="row m-b-20 received-chat align-items-end"> <div class = "col-auto p-r-0" ><h5 class = "text-white d-flex align-items-center theme-bg2 justify-content-center" > ${receiverName} </h5> </div> <div class = "col" ><div class = "msg" ><h6 class = "m-b-0" > ${result.message} </h6><span class="receive-span">${sentTime} </span> </div> </div> </div>`;
            }
            // if (separator != dateSeparator) {
            //     dateSeparator = separator;
            //     html = `<p class="text-center text-muted">${dateSeparator}</p>${html}`;
            // }
            // console.log(html)
            $('.messagesList').append(html);
             // $('.messagesList .align-items-end').last().after(html);
          // startChat(result.room,result.sender_id,result.receiver_id,'asdf','sdf23');

            $("#chat-scroll").scrollTop($("#chat-scroll")[0].scrollHeight + 220);
            /*  setLi(result);
             $('.chat_message_list').animate({
                 scrollTop: $('.chat_message_list ul').height()
             }, 0); */
        });
        socket.on("getMessage", (result) => {
            setChatMessages(result)
        });
        socket.on("joinedroom", (status, room) => {
            if (status != 'Block') {
                $('.messageBox').show();
                $('.blockBox').hide();
            } else {
                $('.blockBox').show();
                $('.messageBox').hide();
            }
        });
    });
// <?php if($chatsDataById != ""){ ?>
    //alert("Fdsf");

 // <?php } ?>

    function startChat(room, senderId, receiverId, name, sender_name) {
        console.log(room, senderId, receiverId, name, sender_name);
        $('.loader').show();
        socket.emit("joinroom", senderId, receiverId, room);
        socket.emit("getMessage", room, senderId);
        $('#sender_id').val(senderId);
        $('#receiver_id').val(receiverId);
        $('#room').val(room);
        $('.chatter_name').html(name);

        senderName = getFirstLetter(sender_name)
        receiverName = getFirstLetter(name)

    }
    $('#send').click(function() {
         var msg = $('#message').val();
         msg = $.trim(msg);
        if (msg != '') {
            sender_id = $('#sender_id').val();
            receiver_id = $('#receiver_id').val();
            message = $('#message').val();
            room = $('#room').val();
            datetime = '<?= date('Y-m-d H:i:s') ?>';

            socket.emit('chatMessage', sender_id, receiver_id, room, message);
            $('#message').val('');
            $('#chat-scroll').stop().animate({
                scrollTop: $('#chat-scroll')[0].scrollHeight + 220
            }, 800);
             var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
             $.ajax({
                    url: '{{url('/')}}/updateChatStatus',
                    type: 'POST',
                     data: {_token: CSRF_TOKEN, room:room},
                    dataType: 'JSON',
                    /* remind that 'data' is the response of the AjaxController */
                    success: function (data) { 
                     console.log("Fds");
                        //$(".writeinfo").append(data.msg); 
                    }
                }); 
        }
    });
  
    function updateLikeStatus(like_user_id="",sender_id="",type="") {
       
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
                    url: '{{url('/')}}/api/like-unlike-user',
                    type: 'POST',
                     data: {_token: CSRF_TOKEN, user_id:sender_id,like_user_id:like_user_id},
                    dataType: 'JSON',
                    /* remind that 'data' is the response of the AjaxController */
                    success: function (data) { 
                        console.log(data);
                        $("#chatListRef").load(location.href + " #chatListRef");
                //         if(data.message == "Liked"){
                //             $.ajax({
                //     url: '{{url('/')}}/api/send_notification',
                //     type: 'POST',
                //      data: {_token: CSRF_TOKEN, user_id:like_user_id,sender_id:sender_id, type:type},
                //     dataType: 'JSON',
                //     /* remind that 'data' is the response of the AjaxController */
                //     success: function (data) { 
                //         // alert(data.message);
                //      console.log(data);
                //         //$(".writeinfo").append(data.msg); 
                //     }
                // }); 
                //         }
                       
                    }
                }); 
            
    }
    function updateViewStatus(like_user_id="",sender_id="",type="") {
       
       var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
                   url: '{{url('/')}}/api/like-view-user',
                   type: 'POST',
                    data: {_token: CSRF_TOKEN, user_id:sender_id,like_user_id:like_user_id},
                   dataType: 'JSON',
                   /* remind that 'data' is the response of the AjaxController */
                   success: function (data) { 
                       console.log(data);
                       $("#chatListRef").load(location.href + " #chatListRef");
            //            if(data.message == "Viewed"){
            //                $.ajax({
            //        url: '{{url('/')}}/api/send_notification',
            //        type: 'POST',
            //         data: {_token: CSRF_TOKEN, user_id:like_user_id,sender_id:sender_id, type:type},
            //        dataType: 'JSON',
            //        /* remind that 'data' is the response of the AjaxController */
            //        success: function (data) { 
            //            // alert(data.message);
            //         console.log(data);
            //            //$(".writeinfo").append(data.msg); 
            //        }
            //    }); 
            //            }
                      
                   }
               }); 
           
   }
 $("#message").keypress(function (e) {
    if(e.which === 13 && !e.shiftKey) {
        e.preventDefault();
         var msg = $('#message').val();
         msg = $.trim(msg);
        if (msg != '') {
            sender_id = $('#sender_id').val();
            receiver_id = $('#receiver_id').val();
            message = $('#message').val();
            room = $('#room').val();
            datetime = '<?= date('Y-m-d H:i:s') ?>';

            socket.emit('chatMessage', sender_id, receiver_id, room, message);
            $('#message').val('');
            $('#chat-scroll').stop().animate({
                scrollTop: $('#chat-scroll')[0].scrollHeight + 220
            }, 800);
             var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
             $.ajax({
                    url: '{{url('/')}}/updateChatStatus',
                    type: 'POST',
                     data: {_token: CSRF_TOKEN, room:room},
                    dataType: 'JSON',
                    /* remind that 'data' is the response of the AjaxController */
                    success: function (data) { 
                     console.log("Fds");
                        //$(".writeinfo").append(data.msg); 
                    }
                }); 
        }
    }
});
    function setChatMessages(result) {
        var li = '';
        var date = '';

        console.log('set Chat Messsages result----', result)
        var html = '';
        $(result).each(function(i, v) {
            sentTime = timeDuration(v.created_at);
            separator = dateSeparation(v.created_at);
            if (separator != dateSeparator) {
                dateSeparator = separator;
                html += `<p class="text-center text-muted">${dateSeparator}</p>`;
            }
            if (v.sender_id == user_id) {
                html += `<div class="row m-b-0 send-chat align-items-end"> <div class = "col text-right"><div class = "msg" ><h6 class = "m-b-0 text-white" > ${v.message} </h6> <span class="send-span">${sentTime}</span> </div> </div> <div class = "col-auto p-l-0" ><h5 class = "text-white d-flex align-items-center theme-bg justify-content-center" > ${senderName}</h5> </div> </div>`;
            } else {
                html += `<div class="row m-b-20 received-chat align-items-end"> <div class = "col-auto p-r-0" ><h5 class = "text-white d-flex align-items-center theme-bg2 justify-content-center" > ${receiverName} </h5> </div> <div class = "col" ><div class = "msg" ><h6 class = "m-b-0" > ${v.message}</h6><span class="receive-span">${sentTime}</span> </div> </div> </div>`;

            }
        });
        $('.messagesList').html(html);
        $('#chat-scroll').stop().animate({
            scrollTop: $('#chat-scroll')[0].scrollHeight + 220
        }, 800);
        //        $("#chat-scroll").scrollTop($("#chat-scroll")[0].scrollHeight + 220);
    }

    function timeDuration(messageDate, date) {
        var indiaTime = messageDate.toLocaleString("en-US", {
            timeZone: "Asia/Kolkata"
        });
        indiaTime = new Date(indiaTime);
        if (date == 'current') {
            indiaTime = new Date();
        }
        time = indiaTime.toLocaleString();
        var Hours = timeString = "" + ((indiaTime.getHours() > 12) ? indiaTime.getHours() - 12 : indiaTime.getHours());
        var Min = (indiaTime.getMinutes() > 9) ? indiaTime.getMinutes() : "0" + indiaTime.getMinutes();
        var tm = (indiaTime.getHours() >= 12) ? " P.M." : " A.M.";
        if (date == 'date') {
            var countdown_date = indiaTime.getDate() + "-" + (indiaTime.getMonth() + 1) + "-" + indiaTime.getFullYear();
        } else {
            //let countdown_date = indiaTime.getDate() + "-" + (indiaTime.getMonth() + 1) + "-" + indiaTime.getFullYear()+" "+timeString+':'+indiaTime.getMinutes()+" "+tm;
            var countdown_date = Hours + ':' + Min + " " + tm;
        }
        return countdown_date;
    }

    function dateSeparation(messageDate, date) {
        var indiaTime = messageDate.toLocaleString("en-US", {
            timeZone: "Asia/Kolkata"
        });
        indiaTime = new Date(indiaTime);
        if (date == 'current') {
            indiaTime = new Date();
        }
        const monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        time = indiaTime.toLocaleString();
        return monthNames[indiaTime.getMonth()] + ' ' + indiaTime.getDate() + ', ' + indiaTime.getFullYear();

    }

    function getFirstLetter(text) {
        return text.toUpperCase()[0];
    }
</script>
@stop