@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Attributs content Edit</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('admin/attribute')}}">Attributs content Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Attributs content Edit</li>
         </ol>
     </div>
      <div class="col-sm-3">
       <a href="{{url('admin/attributes')}}">
        <button type="button" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Back</button>
       </a>
     </div>
     </div>
       <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form id="add_attributes" action="{{ route('attribute_edit',$attribute->id)}}" enctype="multipart/form-data" method="post">
              @csrf
             
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Attribute(English)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" maxlength="50" id="name_en" name="name_en" value="{{$attribute->name_en}}">
                  </div>
                  @error('name_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                  <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Attribute(Arabic)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" maxlength="50" id="name_ar" name="name_ar" value="{{$attribute->name_ar}}">
                  </div>
                  @error('name_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">Status</label>
                  <div class="col-sm-10">
                   <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                        <option value="1"<?php if ($attribute->status=='1') {echo 'selected';}?>>Enabled</option>
                        <option value="0"<?php if ($attribute->status=='0') {echo 'selected';}?>>Disabled</option>
                    </select>
                  </div>
                </div>
           
              
                <div class="form-footer">
                    <a href ="{{ url('admin/attribute')}}" class="btn btn-danger"><i class="fa fa-times"></i> CANCEL</a>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check-square-o"></i> SAVE</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
  </div>
  </div>

@include('includes-file.footer')
@if(session()->has('success'))
  <script>
  round_success_noti_record_update();
  </script>
  @endif 
 </body>
</html>

      