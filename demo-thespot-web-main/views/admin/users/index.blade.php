

    <?php $url = Request::segments(); //echo implode('&', Request::all());dd(Request::all()); ?>
    @extends('layouts.admin',['pageHeader'=>'Users Management'])
    @section('content')

    <div class="card tableCard code-table">
        <div class="card-header">
            <h5>{{ucwords(str_replace('_',' ',base64_decode($status)))}} Users Management</h5>
            <div class="card-header-right">
                <button type="submit"  onclick="return confirm('Are you sure, want to delete ?')" class="btn btn-theme delete_selected_users" id="delete_selected_users"><i class="fas fa-trash">Delete All</i></button>
                <a href="{{ route('newUser') }}" class="btn btn-theme" ><i class="fa fa-user-plus"> Add New User</i></a>
                
                <a href="{{ route('userExport').'?tus='.Request::get('tus').'&q='.Request::get('q') }}" class="btn btn-success"><i class="fa fa-file-excel-o"> Generate Excel Report</i></a>
            </div>
        </div>
        <div class="card-header">
            {{ Form::open(array('url' => 'admin/'.$url[1], 'class'=>"form-inline md-form mr-auto", 'method' => 'get')) }}
            <div class="input-group">
                <!-- <input type="hidden" name="tus" value="{{base64_encode($status)}}"> -->
                {!! Form::select('tus',[base64_encode('active')=>'Active Users',base64_encode('de-active')=>'DeActive Users',base64_encode('fake_user')=>'Fake Users',base64_encode('users')=>'Main Users',base64_encode('paid_users')=>'Paid Users',base64_encode('Not-Verified')=>'Not-Verified'],$status,['class'=>'form-control','placeholder'=>"Select Types of User"]) !!}


                <!-- {!! Form::select('nationality',$nationalities,$nationality,['class'=>'form-control','placeholder'=>"Select Nationality"]) !!} -->
                <!-- {!! Form::select('gender',['Male'=>'Male','Female'=>'Female'],$gender,['class'=>'form-control','placeholder'=>"Select Gender"]) !!}
                {!! Form::select('age',array_combine(range(18,60), range(18,60)),$age,['class'=>'form-control','placeholder'=>"Select Age Range"]) !!} -->
                <input class="form-control" value="<?php if (!empty($query)) {
                                                        echo $query;
                                                    } ?>" placeholder='Search' name="q" type="text">
                <div class="input-group-append">
                    <button class="input-group-text submitSearchForm float-left" type="submit"><i class="feather icon-search"></i></button>
                    <a href="{{ route('userList')}}" class=" input-group-text text-danger btn btn-danger float-left" type="submit">Reset</a>
                </div>
            </div>
            {{ Form::close() }}
        </div>
        @include('includes.flash')
        <div class="card-block pb-0">
            <div class="table-responsive">
                <table class="table table-hover table-list">
                    <thead>
                        <tr>
                            <th>  <input type="checkbox" id="checkAll"  class='check_box_all' >&nbsp;All</th>
                            <th>S.No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Nationality</th>
                            <th>R. Date</th>
                            <th>Subscription Status</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        if (count($model) > 0) {
                            $page = $model->currentPage();
                            //$pagelimit = config("Settings.AdminPageLimit");
                            $i = ($page * $pagelimit) - ($pagelimit); ?>
                            @foreach ($model as $row)
                            <?php $i++ ?>
                            <tr class="gradeY">
                               
                                <td> <input type="checkbox" name="user_ids[]" id="checkItem" value="{{$row->id}}"></td>
                                <td>{{$i}}.</td>
                                <td>{{$row->name }}</td>
                                <td>{{$row->email}}</td>
                                <td>{{$row->mobile}}</td>
                                <td>{{$row->age}}</td>
                                <td>{{isset($row->userDetail->gender)?$row->userDetail->gender:''}}</td>
                                <td>{{isset($row->userDetail->nationality)?$row->userDetail->nationality:''}}</td>
                                <td>{{$row->created_at->format('d-M-Y')}}</td>
                                <td><span class="text-danger">{{Helper::getSubscriptionStatuc($row->id)}}</span></td>
                                <td class="center">
                                    @if($row->status == 0)
                                    <a class="btn btn-danger btn-sm tip-bottom btn-mini" role="button" href="#" title="Click to Active" onclick="return false;">Not Verified</a>
                                    @endif
                                    @if($row->status == 2)
                                    <a class="btn btn-warning btn-sm tip-bottom btn-mini" role="button" href="{{ url('/admin/users/change_status/activate') }}/{{base64_encode($row->id)}}" title="Click to Active" onclick="return confirm('Do you want to active ?')">Deactivate</a>
                                    @endif
                                    @if($row->status == 1)
                                    <a class="tip-bottom btn-mini label theme-bg f-12 text-white" role="button" href="{{ url('/admin/users/change_status/deactivate') }}/{{base64_encode($row->id)}}" class="tip-left" title="Click to inactive" onclick="return confirm('Do you want to inactive ?')">Active</a>
                                    @endif
                                    @if($row->status == 5)
                                    <a class="tip-bottom btn-danger label f-12 text-white" role="button" href="{{ url('/admin/users/change_status/activate') }}/{{base64_encode($row->id)}}" class="tip-left" title="Click to inactive" onclick="return confirm('Do you want to Active this user ?')">Suspended</a>
                                    @endif
                                </td>
                                <td class="center">
                                    <a href="{{ url('/admin/'.$url[1].'/edit') }}/{{base64_encode($row->id)}}" class="mb-2 mr-2 " title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('userDetail',['id' => base64_encode($row->id)])}}" class="mb-2 mr-2 " title="User Detail"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('deleteUser',['id' => base64_encode($row->id)])}}" class="badge-danger mb-2 mr-2 " onclick="return confirm('Are you sure, want to delete this user ?')" title="Delete User"><i class="fas fa-trash"></i></a>
                                    @if($row->role == 'fake_user')
                                    <a class="btn btn-sm tip-bottom btn-mini" role="button" href="{{route('chatting',['id'=>$row->id])}}" title="Click to Active"><img src="{{config('app.public_url')}}admin/icon/chat.png" width="15" height="15"></a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        <?php } else { ?>
                            <tr>
                                <td colspan='10' style="text-align: center">No Result Found</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php if (!empty($model)) { ?>
                    <div class="pagination-parent">
                        <div class="left">
                            Total Records:<strong>{{$model->total()}}</strong> Page of pages: <strong>{{$model->currentPage()}}</strong> of <strong>{{$model->lastPage()}}</strong>
                        </div>
                        <div class="pagination">
                            {{ $model->appends(request()->input())->links() }}
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="https://code.jquery.com/jquery-1.7.1.min.js"></script>
    <script>
         
         $("#checkAll").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });


        $(document).ready(function() {
            var $submit = $("#delete_selected_users").hide(),
            $cbs = $('input[name="user_ids[]"]').click(function() {
                $submit.toggle( $cbs.is(":checked") );
            });
            
        });

        $(document).ready(function() {
            var $submit = $("#delete_selected_users").hide(),
            $cbs = $('.check_box_all').click(function() {
                $submit.toggle( $cbs.is(":checked") );
            });
            
        });
            


    //     $(document).ready(function() {
	//    $("#delete_selected_users").click(function(){
	//        var test = new Array();
	//        $("input[name='user_ids[]']:checked").each(function() {
	//            test.push($(this).val());
	//        });
	//    });
	// });
	
	
	
	$(document).on('click', '.delete_selected_users', function(e) {
        e.stopImmediatePropagation();
	    url = $(this).attr('href');
    
        var test = new Array();
        $("input[name='user_ids[]']:checked").each(function() {
            test.push($(this).val());
        });
        $.ajax({
            url: '{{ url("admin/users/delete-multiple-users") }}',
            type:'post',
            data: {
                "_token": "{{ csrf_token() }}",
                "data": test
                
            },
            success: function(r){
                
                error_array 	= 	JSON.stringify(r);
                data			=	JSON.parse(error_array);
                if(data['status'] == '2') {
                    toastr.error('Invalid Request.');
                }else{
                    toastr.success('Users has been deleted successfully');
                    location.reload();
                }
            }
        });
                
	   e.preventDefault();
	});

    </script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
      
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    @stop