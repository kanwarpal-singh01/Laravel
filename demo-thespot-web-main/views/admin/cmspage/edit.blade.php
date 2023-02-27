<?php $url = Request::segments(); 
//pr(asset('/plugins/ckeditor/ckeditor.js'));die;
?>
@extends('layouts.admin')
@section('content')
<div class="card tableCard code-table">
    <div class="card-header">
        <h5>Content Management</h5>
        <div class="card-header-right">
            <a  href="{{ route('cmspages') }}" class="btn btn-theme"><i class="fa fa-list"> Pages List</i></a>
        </div>
    </div>
</div>
@include('includes.flashMessage')
<div class="card tableCard code-table">
    <div class="card-header">
        <h5>Edit Page</h5>
    </div>
    <div class="card-body">
        {!! Form::model($record, ['method' => 'PATCH',     'id' => 'cmspageAddFrom','files' => true ,'class' => 'form-horizontal','url' => ['admin/cmspage/update', base64_encode($record->id)]]) !!}
        <div class="form-group">
            <label>Title English</label>
            {!! Form::text('title_en', old('title_en'), ['class' => 'form-control valid-text' ,'placeholder' => 'Enter English Title here','required']) !!}
        </div>
        <div class="form-group">
            <label>Title Arbic</label>
            {!! Form::text('title_ar', old('title_ar'), ['class' => 'form-control' ,'placeholder' => 'Enter Arbic Title here','required']) !!}
        </div>
        <div class="form-group">
            <label>Meta Description</label>
            {!! Form::textarea('meta_description',null, ['class' => 'form-control span3','colspan'=>3,'placeholder' => 'Enter Meta Description','required']) !!}
        </div>
        <div class="form-group">
            <label>Content English</label>
            {!! Form::textarea('content_en', null, ['class' => 'form-control span9','id'=>'edittextarea' ,'placeholder' => 'Enter Description','required']) !!}
        </div>
        <div class="form-group">
            <label>Content Arbic</label>
            {!! Form::textarea('content_ar', null, ['class' => 'form-control span9 ','id'=>'editarbicarea' ,'placeholder' => 'Enter Description']) !!}
        </div>
        <div class="control-group float-left">
            <div class="form-actions span6 text-center">
                <input type="submit" value="Update Page" class="btn btn-success">
            </div>
        </div>
        {!! Form::close() !!}

    </div>
</div>


<script src="{{asset('/plugins/ckeditor/ckeditor.js')}}"></script>
<script>
    CKEDITOR.config.height = 250;
    
    CKEDITOR.replace( 'edittextarea', {
        extraPlugins: 'imageuploader',
        width: '100%',
    });
    CKEDITOR.replace( 'editarbicarea', {
        extraPlugins: 'imageuploader',
        width: '100%',
    });
    </script>
    <script type="text/javascript">
        var ranges = [
'\ud83c[\udf00-\udfff]', // U+1F300 to U+1F3FF
'\ud83d[\udc00-\ude4f]', // U+1F400 to U+1F64F
'\ud83d[\ude80-\udeff]'  // U+1F680 to U+1F6FF
];

setTimeout(removeInvalidChars, 100);
function removeInvalidChars() {
    var str = $('.valid-text').val();

    str = str.replace(new RegExp(ranges.join('|'), 'g'), '');
    $(".valid-text").val(str);
    setTimeout(removeInvalidChars, 100);
}
</script>

@stop
