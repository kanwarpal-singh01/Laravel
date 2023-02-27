@extends('layouts.newadmin')
@section('content')

<?php  $btnStatus = [1=>'Active',2=>'Closed',3=>'Resolved'];
 ?>
 <br>
<div class="content">
  <section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Contact us</h1>     
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Dashboard</a></li>
          
                <li class="breadcrumb-item active">Contact us</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
 <div class="container-fluid">
<div class="container-fluid">
 
   @if (count($errors) > 0)
   <div class="alert alert-error alert-block">
      <a class="close" data-dismiss="alert" href="#">×</a>
      <h4 class="alert-heading">Error!</h4>
      <ul>
         @foreach ($errors->all() as $error)
         <li>{{ $error }}</li>
         @endforeach
      </ul>
   </div>
   @endif
   @if(session()->has('message'))
   <div class="alert alert-success  alert-block">
      <a class="close" data-dismiss="alert" href="#">×</a>
      <h4 class="alert-heading">Success!</h4>
      {{ session()->get('message') }}
   </div>
   @endif
   <div class="row-fluid">
      <div class="span12">
         <div class="widget-box">
            
            <div class="widget-content nopadding span8">
            
          </div>
            <div class="widget-content nopadding">
               <table class="table table-bordered data-table">
                  <thead>
                     <tr>
                        <th>S.No.</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Message</th>
                        <th>Action</th>
                        <th>Send Mail</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php $i = 1;  
                     if(count($records)>0)
                     {
                        $page = $records->currentPage();
                        $pagelimit = config("Settings.AdminPageLimit");
                        $i    = ($page * $pagelimit) - ($pagelimit);
                      ?>
                     @foreach ($records as $row)
                     <?php $i++;?>
                     <tr class="gradeY">
                        <td>{{$i}}</td>
                        <td>{{ucfirst($row->first_name)}}</td>
                        <td>{{($row->phone)}}</td>
                        <td>{{($row->message)}}</td>

                        <td> <select class=" btnStatus slecter" data-id="{{($row->id)}}">
                        <!-- <option>{{($row->status)}}</option>         -->
            <option value="1" <?php if($row->status==1){ ?>selected <?php } ?>>Active</option>
            <option value="2" <?php if($row->status==2){ ?>selected <?php } ?>>Closed</option>
            <option value="3" <?php if($row->status==3){ ?>selected <?php } ?>>Resolved</option>
       </select></td>
       <td><a href="{{url('/')}}/admin/send-mail"><img style="width:24px;" src="{{ config('app.asset_url')}}/admin/icon/email.png"/></a></td>
                        
                     </tr>
                     @endforeach
                     <?php } else { ?>
                     <tr>
                        <td colspan='6'> Record Not Found</td>
                     </tr>
                     <?php } ?>
                  </tbody>
               </table>
            </div>
         </div>
         <?php if(!empty($records)) { ?>
        <div class="pagination-parent">
          <div class="left">
          Total Records:<strong>{{$records->total()}}</strong> Page of pages: <strong>{{$records->currentPage()}}</strong> of <strong>{{$records->lastPage()}}</strong> 
          </div>
          <div class="pagination"> 
            {{  $records->appends(Request::only('q'))->links() }} 
          </div>
        </div>
      <?php } ?> 
      </div>
   </div>
</div>
</div>
<script>   
            $('.btnStatus').change(function(){
                if (confirm('Are you sure you want to change the status ?')) 
                {
                    var user_id = $(this).data('id');
                    var status = $(this).val();
                    console.log(user_id);
                    console.log(status);
                    
                    $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: "{{ url('/')}}/admin/changeStatus",
                    data: {'status': status, 'user_id': user_id},
                    success: function(data){
                     window.location.reload();
                    }
                });
                    
                }
            });
        </script>
@stop



