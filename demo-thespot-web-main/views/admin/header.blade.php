<!-- [ Header ] start -->
<?php use App\Http\Controllers\Controller; 
$getNotificationList=Controller::getNotificationList();
$getNotificationCount=Controller::getNotificationCount();



// echo '<pre>'; print_r($getNotificationList); die;
?>

<header class="navbar pcoded-header navbar-expand-lg headerpos-fixed header-dark">
    <div class="m-header">
        <a class="mobile-menu" id="mobile-collapse1" href="javascript:"><span></span></a>
        <a href="index.html" class="b-brand">
         <div class="b-bg">
             <i class="feather icon-trending-up"></i>
         </div>
         <span class="b-title">The Spot</span>
     </a>
 </div>
 <a class="mobile-menu" id="mobile-header" href="javascript:">
    <i class="feather icon-more-horizontal"></i>
</a>
<div class="collapse navbar-collapse">
    <ul class="navbar-nav mr-auto">
        <li><a href="javascript:" class="full-screen" onclick="javascript:toggleFullScreen()"><i class="feather icon-maximize"></i></a></li>
        {{-- <li class="nav-item">
            <div class="main-search">
                <div class="input-group">
                    <input type="text" id="m-search" class="form-control" placeholder="Search . . .">
                    <a href="javascript:" class="input-group-append search-close">
                        <i class="feather icon-x input-group-text"></i>
                    </a>
                    <span class="input-group-append search-btn btn btn-primary">
                        <i class="feather icon-search input-group-text"></i>
                    </span>
                </div>
            </div>
        </li> --}}
    </ul>
    <ul class="navbar-nav ml-auto">
        <li>
            <div class="dropdown">
                <a class="dropdown-toggle" href="javascript:" id="notification_click" data-toggle="dropdown"><i class="fa fa-bell bell-color"></i></a>
              
                <span class="badge" style="
    background-color: red;
    border-radius: 10px;
    background-color: red;
    position: absolute;
    top: 14px;
    right: 3px;
    font-size: 11px;
    " ><span id="notification_count">{{$getNotificationCount}}</span></span>
  
                <div class="dropdown-menu dropdown-menu-right notification">
                    <div class="noti-head">
                        <h6 class="d-inline-block m-b-0">Notifications</h6>
                        <!-- <div class="float-right">
                            <a href="javascript:" class="m-r-10">mark as read</a>
                            <a href="javascript:">clear all</a>
                        </div> -->
                    </div>
                    <div class="noti-body" style="overflow-y: scroll;height: 360px;">

                       <ul class="notificationData" >
                        <!-- <li class="n-title">
                            <p class="m-b-0">NEW</p>
                        </li> -->
                        <?php
                        if(count($getNotificationList)>0)
                        {
                            $color="black";
                            $color1="#F1F1F1";
                            foreach ($getNotificationList as $key => $value) { 
                                if($value->is_read == 0){$color="White";
                                $color1="#f66e27";
                            }else{
                                $color="black";
                                $color1="#F1F1F1";
                            }
                                ?>
                                <a href="{{url('/')}}/admin/chats/chat-messages/{{$value->receiver_id}}?chatid={{$value->id}}" style="color: {{$color}};">
                                    <li class="notification" style="background: {{$color1}};border-width: 2px;border-color: white;">
                            <div class="media">
                                <img class="img-radius" src="{{$value->profile_image}}" alt="Generic placeholder image" style="height: 40px;width:40px;border-radius:40px;">
                                <div class="media-body">
                                    <p><strong style="color: {{$color}};">{{$value->user_name}} : {{$value->receiver_name}}</strong><span class="n-time" style="color: {{$color}};"><i class="icon feather icon-clock m-r-10"></i>{{date("h:i A", strtotime($value->created_at))}}</span></p>
                                    <p>{{$value->message}}</p>
                                </div>
                            </div>
                        </li></a>
                           <?php }
                        }else{

                         ?>
                        <li class="notification">
                            <div class="media">
                                <div class="media-body">
                                    <p>No Notification Found</p>
                                </div>
                            </div>
                        </li>
                      <?php } ?> 
                        <li class="n-title">
                            <p class="m-b-0">EARLIER</p>
                        </li>
                    </ul> 


                    </div>
                    
                    <!-- <div class="noti-footer">
                        <a href="javascript:">show all</a>
                    </div> -->
                </div>
            </div>
        </li>
        <li>
            <div class="dropdown drp-user">
                <a href="javascript:" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon feather icon-settings"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right profile-notification">
                    <div class="pro-head">
                        <img src="{{ config('app.profile_url')}}{{ Auth::user()->profile_image }}" class="img-radius" alt="User-Profile-Image">
                        <span>{{ucfirst(Auth::user()->name)}}</span>
                    </div>
                    <ul class="pro-body">
                        <!-- <li><a href="{{ url('admin/config') }}" class="dropdown-item"><i class="feather icon-settings"></i> Settings</a></li> -->
                        <li><a href="{{url('admin/updateprofile')}}" class="dropdown-item"><i class="feather icon-user"></i> Profile</a></li>
                        <!-- <li><a href="{{url('admin/home-page-setting')}}" class="dropdown-item"><i class="feather icon-mail"></i> My Messages</a></li> -->
                        <li><a href="{{url('admin/logout')}}" class="dropdown-item" onclick="return confirm('Are you sure you want to logout?')"><i class="feather icon-lock"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </li>
    </ul>
</div>
</header>
<script>

var counterdata = 1;

      setInterval(function(){
        
            // counterdata++;
            // if(last_id != ""){
            //     getNotification();
            // }
           
            $(".notificationData").load(location.href + " .notificationData"); 
         $("#notification_count").load(location.href + " #notification_count"); 
      }, 3000);
   
</script>
    <!-- [ Header ] end
