<?php $url = Request::segments(); ?>
@extends('layouts.admin')
@section('content')
<?php //pr($userDetail);die;
?>
<div class="card tableCard code-table">
    <div class="card-header">
        <h5>Users Management</h5>
        <div class="card-header-right">
            <a href="{{ route('userList') }}" class="btn btn-theme"><i class="fa fa-list"> Users List</i></a>
        </div>
    </div>
</div>
<div class="row">
   <!--  <div class="col-md-3">
        <div class="card">
            <div class="card-block p-0">
                <div class="text-center project-main">
                    <img src="{{ config('app.profile_url')}}{{ $userDetail->profile_image }}" alt="User-Profile-Image" height="300" width="300" style="border-radius:20px">
                    <h5 class="mt-4">{{$userDetail->name}}</h5>
                    <span>{{$userDetail->email}}</span><br>
                    <span>{{$userDetail->mobile}}</span>
                </div>
                <div class="border-top"></div>
            </div>
        </div>
    </div> -->
    <div class="col-md-6">
        <div class="card tableCard code-table">
            <div class="card-header">
                <h5>User Detail</h5>
            </div>
            <div class="card-body box-profile">
                <ul class="list-group list-group-unbordered mb-3">
                      @if(!empty($userDetail->name))
                    <li class="list-group-item">
                        <b>Name</b> <a class="float-right">{{$userDetail->name}}</a>
                    </li>
                    @endif
                     @if(!empty($userDetail->email))
                    <li class="list-group-item">
                        <b>Email</b> <a class="float-right">{{$userDetail->email}}</a>
                    </li>
                    @endif
                    @if(!empty($userDetail->mobile))
                    <li class="list-group-item">
                        <b>Mobile</b> <a class="float-right">{{$userDetail->mobile}}</a>
                    </li>
                    @endif
                    @if(isset($userDetail->userDetail) && !empty($userDetail->userDetail))

                    @if(!empty($userDetail->userDetail->gender))
                    <li class="list-group-item">
                        <b>Gender</b> <a class="float-right">{{$userDetail->userDetail->gender}}</a>
                    </li>
                    @endif
                      @if(!empty($userDetail->userDetail->nationality))
                    <li class="list-group-item">
                        <b>Nationality</b> <a class="float-right">{{$userDetail->userDetail->nationality}}</a>
                    </li>
                    @endif
                    @if(!empty($userDetail->userDetail->intrested_in))
                    <li class="list-group-item">
                        <b>Intrested In</b> <a class="float-right">{{$userDetail->userDetail->intrested_in}}</a>
                    </li>
                    @endif
                    @if(!empty($userDetail->userDetail->dob))
                    <li class="list-group-item">
                        <b>Date of Birth</b> <a class="float-right">{{$userDetail->userDetail->dob}}</a>
                    </li>
                    @endif
                    @if(!empty($userDetail->userDetail->bio))
                    <li class="list-group-item">
                        <b>About User</b> <a class="float-right">{{$userDetail->userDetail->bio}}</a>
                    </li>
                    @endif
                    @if(!empty($userDetail->userDetail->about_business))
                    <li class="list-group-item" style="word-break: break-all;">
                        <b>About Business</b> <a class="float-right">{{$userDetail->userDetail->about_business}}</a>
                    </li>
                    @endif
                    @if(!empty($userDetail->userDetail->website))
                    <li class="list-group-item">
                        <b>Website</b> <a class="float-right">{{$userDetail->userDetail->website}}</a>
                    </li>
                    @endif
                    @if(!empty($userDetail->userDetail->social_media))
                    <li class="list-group-item">
                        <b>Social Media</b> <a class="float-right">{{$userDetail->userDetail->social_media}}</a>
                    </li>
                    @endif
                    @if(!empty($userDetail->userDetail->id_proof))
                    <li class="list-group-item">
                        <b>Id Proof</b>
                        <a class="float-right"><img src="{{assets('uploads/id_proof/'.$userDetail->userDetail->id_proof)}}"></a>
                    </li>
                    @endif
                    @if(!empty($userDetail->userDetail->license))
                    <li class="list-group-item">
                        <b>License</b>
                        <a class="float-right"><img src="{{assets('uploads/license/'.$userDetail->userDetail->license)}}"></a>
                    </li>
                    @endif
                    @endif
                    <li class="list-group-item">
                        <b>Register Date</b> <a class="float-right">{{$userDetail->created_at->format('d-M-Y')}}</a>
                    </li>
                  
                </ul>
            </div>
        </div>
    </div>
     <?php $profileImages = explode(',', $userDetail->images); 
                 $count=0;
                 ?>
                    @if(count($profileImages) > 0 && $userDetail->images != "")
    <div class="col-md-6">
        <div class="card">
             <div class="card-header">
                <h5>Images</h5>
            </div>
            <div class="card-block p-0">
                 <ul class="list-group list-group-unbordered mb-3">
                
                    <li class="list-group-item">
                        <div class="row">
                            
                            @foreach($profileImages as $key => $value)
                       
                            <div style="margin-top: 25px;" class="col-md-4"><a href="{{ config('app.profile_url')}}{{$value}}"><img src="{{ config('app.profile_url')}}{{$value}}" style="border-radius: 10px;" width="200" height="200"></a></div><br>
                      
                            @endforeach
                        </div>
                    </li>
                   
                </ul>
                <div class="border-top"></div>
            </div>
        </div>
    </div>
     @endif
</div>

<style>
    /* .widget-title, .modal-header, .table th, div.dataTables_wrapper .ui-widget-header{
        background:#2e363f;
    }
    .table-icon span { color:#fff; }
    .table-icon h5 { color:#fff; }*/
    table,
    th,
    td {
        border: 1px black;
    }

    .table-user-information tr td:first-child {
        font-weight: bold;
        width: 150px;
        position: relitive;
    }

    .table-user-information tr td {
        border-bottom: 1px solid #FFF;
        padding: 10px 0px;
        ;
    }
</style>
<script type="text/javascript">
    function deleteImage(id="",user_id="") {
         if (confirm("Are you sure you want to delete?")){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
        type: 'POST',
        data: {
            _token: CSRF_TOKEN,
            image_id: id,
            user_id: user_id
        },
        url: "{{url('/')}}/deleteImage",
        success: function(response) {
          window.location.reload();
        }
    });
    }
    }
</script>
@stop