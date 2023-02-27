@include('includes.site.head')
<div class="site_content">
<!-- Menu Starts -->
<header>
   <div class="container">
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
         <a class="navbar-brand" href="{{ url('/') }}">
         <img src="{{ config('app.asset_url') }}/assets/img/site-logo.png"></a>
         <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
         </button>
         <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="ml-auto navbar-nav header-menu">
               <li class="nav-item @if (\Request::is('/'))  active @endif">
                  <a class="nav-link" href="{{ url('/') }}">Home <span class="sr-only">(current)</span></a>
               </li>
               <li class="nav-item @if (\Request::is('about-us'))  active @endif">
                  <a class="nav-link" href="{{ url('/') }}/about-us">About us</a>
               </li>
               <li class="nav-item @if (\Request::is('how-it-works'))  active @endif">
                  <a class="nav-link" href="{{ url('/') }}/how-it-works">How it works</a>
               </li>
               <li class="nav-item @if (\Request::is('contact-us'))  active @endif">
                  <a class="nav-link" href="{{ url('/') }}/contact-us">Contact us</a>
               </li>

              <?php 
            
              if(Auth::check()){
               $role=Auth::user()->role;
                 ?>

                  @if($role=='admin')
                  <li class="nav-item header-btn">
                     <a class="nav-link btn signin" href="{{ url('/') }}/admin/dashboard">Dashboard</a>
                  </li>
                  @endif
                  @if($role=='employer')
                  <li class="nav-item header-btn">
                     <a class="nav-link btn signin" href="{{ url('/') }}/account-setting">Dashboard</a>
                  </li>
                  @endif
                  @if($role=='recruiter' || $role=='sub-recruiter')
                  <li class="nav-item header-btn">
                     <a class="nav-link btn signin" href="{{ url('/') }}/account-setting">Dashboard</a>
                  </li>
                  @endif
                  
             
                 <?php
              }
              else{
                 ?>
                   <li class="nav-item header-btn">
                     <a class="nav-link btn signin" href="{{ route('login') }}">Sign in</a>
                     <!-- <a class="nav-link btn signup" href="{{ url('register/employer') }}">Sign up</a> -->
                     <div class="dropdown">
                        <a class="nav-link btn signup dropdown-toggle" role="button" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sign up</a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                           <a class="dropdown-item" href="{{ url('register/employer') }}">employer</a>
                           <a class="dropdown-item" href="{{ url('register/recruiter') }}">recruiter</a>
                        </div>
                     </div>
                  </li>
                 <?php

              }

              ?>
             
            </ul>
         </div>
      </nav>
   </div>
</header>