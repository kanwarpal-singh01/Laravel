<nav class="navbar navbar-expand navbar-light bg-white topbar shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-lg-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <div class="head-contact-deats mr-auto">
        <!-- <ul>
          <li>
              <a href="tel:+0502992709">Help: +050 2992 709</a>
          </li>
          <li class="nav-item dropdown ml-2 custom-d-arrow dash-user-options">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i><img class="img-profile rounded-circle" src="{{ config('app.asset_url')}}assets/img/flag-img.svg"></i>
                  <span>United States</span>
              </a>
              <div class="dropdown-list dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown">
                  <a class="dropdown-item d-flex align-items-center" href="#">
                      asdasd
                  </a>
              </div>
          </li>
      </ul> -->
    </div>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">


        <?php $employer_credit_status=Helper::employer_credit_status(); ?>
        @if($employer_credit_status)
        <li class="availb_credits_info">
            <p>Available Credits</p><i
                class="availb_credit_icon fas fa-dollar-sign"></i><span>{{ Helper::get_credit(Auth::id()) }}</span>
        </li>
        @endif

        @if(Auth::user()->role == 'recruiter' || Auth::user()->role == 'sub-recruiter' )
        <li class="nav-item h-cand-status-list dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="{{ url('/')}}/candidate-status">
                <!-- <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i> -->
                <div class="blink_svg">
                    <img src="{{ config('app.asset_url')}}assets/img/blue_pulse.gif" />
                </div>
            </a>
        </li>
        @endif

        <?php 
$message=Helper::get_notification_count(Auth::id(),'message');
?>
        <!-- Nav Item - Messages -->
        <li class="nav-item notif_dropdown dropdown no-arrow mx-1 ">

            @if(Auth::user()->role=='employer')
            <a class="nav-link dropdown-toggle badge_count_shell msg_noti" href="{{url('/jobs')}}"
                id="message_{{ Auth::id() }}">
                @else
                <a class="nav-link dropdown-toggle badge_count_shell msg_noti" href="{{url('/candidate-status')}}"
                    id="message_{{ Auth::id() }}">
                    @endif


                    <i><img src="{{ config('app.asset_url')}}assets/img/grey-mail-icon.png">@if($message)<span
                            class="badge">{{ $message }}</span>@endif</i>
                </a>
                <!-- Dropdown - Messages -->


                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow" aria-labelledby="messagesDropdown">
                    <div class="message-box909">
                        <h3>Messages</h3>
                        <ul>


                        </ul>
                    </div>
                </div>
        </li>

        <?php 
$notifications=Helper::get_notification_count(Auth::id(),'notification');
?>
        <!-- Nav Item - Alerts -->
        <li class="nav-item notif_dropdown dropdown no-arrow mx-1 ">
            <a class="nav-link dropdown-toggle badge_count_shell noti_909" href="#" id="main_{{ Auth::id() }}"
                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i><img src="{{ config('app.asset_url')}}assets/img/grey-bell-icon.png">@if($notifications)<span
                        class="badge">{{ $notifications }}</span>@endif</i>
            </a>
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow" aria-labelledby="messagesDropdown">
                <div class="notification-box">
                    <h3>Notifications</h3>
                    <ul>


                    </ul>
                </div>
            </div>
            <!-- <div class="dropdown-list dropdown-menu dropdown-menu-right shadow" aria-labelledby="messagesDropdown">
      <a class="dropdown-item d-flex align-items-center" href="#">
          Bell Notification 1
      </a>
    </div> -->
        </li>


        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown custom-d-arrow dash-user-options">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i>
                    <img class="img-profile rounded-circle"
                        src="{{ config('app.asset_url') }}assets/img/{{ Auth::user()->profile_image ?? 'default_user.png' }}">
                </i>
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{url('/')}}/account-setting">
                    <i><img src="{{ config('app.asset_url')}}assets/img/siderbar-9.png"></i>Profile
                </a>

                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i><img src="{{ config('app.asset_url')}}assets/img/siderbar-9.png"></i>Logout
                </a>
            </div>
        </li>

    </ul>

</nav>

<!--logout model-->


<div class="modal fade logoutmodal" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Are you sure you want to log out?</div>
            <div class="modal-footer">
                <a class="btn " href="{{ url('/') }}/logout">Yes</a>
                <button class="btn" type="button" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>



<div id="overlayer"></div>
<span class="loader">
    <span class="loader-inner"></span>
</span>