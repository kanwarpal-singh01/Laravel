@extends('layouts.admin')
@section('content')

<!-- <link rel="stylesheet" href="{{url('/')}}/public/assets/multiselect/css/style.css"> -->

<script  src="{{url('/')}}/public/assets/multiselect/js/index.js"></script>
<div class="card tableCard code-table">
   <div class="card-header">
      <h5>Push Notification Templates</h5>
      <div class="card-header-right">
                <a class="btn btn-theme" style="color:white;" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo"><i class="fa fa-bell"> Send Custom Notification</i></a>
            </div>
   </div>
   @include('includes.flash')
   <div class="card-block pb-0">
      <div class="table-responsive">
         <table class="table table-hover table-list">
            <thead>
               <tr>
                  <th>S.No.</th>
                  <th>Title</th>
                  <th>Message</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
               <?php $i = 1;  if(count($notification)>0){
                  //$pagelimit = Helper::get_config('AdminPageLimit');
                  $page = $notification->currentPage() ;
                  $i = ($page * $pagelimit) - ($pagelimit);
                  ?>
                  @foreach ($notification as $row)
                  @if($row->status == 1 )
                  <?php $i++ ?>
                  <tr class="gradeY">
                     <td>{{$i}}</td>
                     <td>{{ucfirst($row->title)}}</td>
                     <td>{{($row->message)}}</td>
                     <td class="center">
                        <a  href="{{ route('template_update',['id'=>base64_encode($row->id)]) }}"  ><i class="fa fa-edit"></i></a>
                     </td>
                  </tr>
                  @endif
                  @endforeach
                  <?php } else { ?>
                     <tr>
                        <td colspan='5'> Record Not Found</td>
                     </tr>
                     <?php } ?>
                  </tbody>
               </table>
               <?php if(!empty($notification)) { ?>
                  <div class="pagination-parent">
                     <div class="left">
                        Total notification:<strong>{{$notification->total()}}</strong> Page of pages: <strong>{{$notification->currentPage()}}</strong> of <strong>{{$notification->lastPage()}}</strong>
                     </div>
                     <div class="pagination">
                        {{  $notification->appends(Request::only('q'))->links() }}
                     </div>
                  </div>
                  <?php } ?>
               </div>
            </div>
         </div>
         <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Send Custom notification</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" action="{{url('/')}}/admin/notification-template/sendPushNotification">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <div class="modal-body">
      <div class="form-group">
            <label for="recipient-name" class="col-form-label">Select Users:</label>
          
            <select class="form-control" name="user_type" id="user_type" onchange=showUsers();>
               <option value="">Select Users</option>
               <option value="paid">Paid User</option>
               <option value="unpaid">Unpaid Users</option>
               <option value="specific_users">Specific Users</option>
            </select>
          </div>
        <div class="form-group" id="users_list" style="display:none;">
       
            <label for="recipient-name" class="col-form-label">Select Users:</label>
            
            <!-- <select class="form-control" id="myMulti" name="users[]" multiple="multiple" onchange="checkCreditUsers()"> -->
               <div style="overflow-y: auto;
    height: 116px;
    background: #f5f2f2;">
            <?php 
                                    if(count($usersList)>0)
                                    {
                                        foreach($usersList as $key => $value)
                                        {        
                                    ?>
               <label class="container"><input type="checkbox" name="users[]" style="margin-right:10px;" value="{{$value->id}}"> <span>{{$value->name}}<span>
  <span class="checkmark"></span>
</label>
               <?php }} ?>
               </div>
           
            <span class="errorMsgCom" id="errCreditUsers"></span>
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Title:</label>
            <input type="text" name="title" class="form-control" id="recipient-name" required="required">
          </div>
          <div class="form-group">
            <label for="message-text" class="col-form-label">Message:</label>
            <textarea class="form-control" id="message-text" name="message" required="required"></textarea>
          </div>
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="nextSecond">Send message</button>
      </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
		$(document).ready(function() {
			$('#multiselect').selectpicker();
		});
	</script>
<script>
  function showUsers(){
    
     if($('#user_type').val() == "specific_users"){
      $('#users_list').show();
     }else{
      $('#users_list').hide();
     }
  }
function checkCreditUsers()
    {  
        var val = $("#multiselect").val();

        if(val != "")
        {  
            $("#errCreditUsers").html("").hide();
            $("#nextSecond").removeClass('com-disabled');
            $("#nextSecond").attr('disabled', false);

            return true;
        }else{
            $("#errCreditUsers").html("Please select at least one industry.").show();
            $("#nextSecond").addClass('com-disabled');
            $("#nextSecond").attr('disabled', true);

            return false;
        }
    }
</script>
         @stop
