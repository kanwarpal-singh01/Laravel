@extends('layouts.admin')
@section('content')
<div class="card tableCard code-table">
  <div class="card-header">
    <h5>FAQ Management</h5>
    <div class="card-header-right">
    </div>
  </div>
</div>
<div class="card tableCard code-table">
   <div class="card-header">
      <h5>FAQ's</h5>
      <div class="card-header-right">
         <a  href="{{ url('/admin/faq/create') }}" class="btn btn-theme btn-sm"><i class="fa fa-user-plus"> Add FAQ</i></a>
      </div>
   </div>
   @include('includes.flash')
   <div class="card-block pb-0">
      <div class="table-responsive">
         <table class="table table-hover table-list">
            <thead>
               <tr>
                  <th>S.No.</th>
                  <th>Question</th>
                  <th>Answer</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
               <?php $i = 1;
               if(count($records)>0)
               {
                  $page = $records->currentPage();
                  $pagelimit = config("Settings.AdminPageLimit");
                  $pagelimit = Helper::get_config('AdminPageLimit');
                  $i    = ($page * $pagelimit) - ($pagelimit);
                  ?>
                  @foreach ($records as $row)
                  <?php $i++;?>
                  <tr class="gradeY">
                     <td>{{$i}}</td>
                     <td>{{ucfirst(substr($row->question_en,0,50))}}</td>
                     <td>{{($row->answer_en)}}</td>
                     <td class="center">
                        <a  href="{{ url('/admin/faq/view') }}/{{base64_encode($row->id)}}" class="badge badge-primary"><i class="fa fa-eye"></i></a>
                        <a  href="{{ url('/admin/faq/edit') }}/{{base64_encode($row->id)}}" class="badge badge-info"><i class="fa fa-edit"></i></a>
                        <a  href="{{ url('/admin/faq/delete') }}/{{base64_encode($row->id)}}" onclick="return confirm('Do you want to delete ?')" class="badge badge-danger"><i class="fa fa-trash"></i></a>
                     </td>
                  </tr>
                  @endforeach
               <?php } else { ?>
                  <tr>
                     <td colspan='5'> Record Not Found</td>
                  </tr>
               <?php } ?>
            </tbody>
         </table>
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
@stop
