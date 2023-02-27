@extends('admin.include.layouts')
@section('content')
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="form-group row">
                    <div class="col-md-12" style="margin-right: 873px;">
                        <a class="btn waves-effect waves-light btn-grd-primary"
                            href="{{ url('Kobe/category-management/list?page=' . request('page')) }}" id="back-btn">
                            <i class="fa fa-backward"></i>Back
                        </a>
                    </div>
                </div>
                <!-- Page body start -->
                <div class="page-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-block">
                                    <form id="" method="post"
                                        action="{{ route('admin.category.edit', ['id' => base64_encode($edit->id), 'page' => request('page')]) }}"
                                        novalidate enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                    id="name" name="name" placeholder="Name"
                                                    value="{{ $edit->name ?? '' }}">
                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- <div class="form-group row"> --}}
                                        {{-- <label class="col-sm-2 col-form-label">Image</label> --}}
                                        {{-- <div class="col-sm-10"> --}}
                                        {{-- <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror"> --}}
                                        {{-- @error('image') --}}
                                        {{-- <span class="invalid-feedback" role="alert"> --}}
                                        {{-- <strong>{{ $message }}</strong> --}}
                                        {{-- </span> --}}
                                        {{-- @enderror --}}
                                        {{-- </div> --}}
                                        {{-- </div> --}}
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Content</label>
                                            <div class="col-sm-10">
                                                <textarea name="content"
                                                    class="form-control @error('content') is-invalid @enderror" id="content"
                                                    rows="10" cols="80">{{ $edit->content ?? '' }}</textarea>
                                                @error('content')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- <div class="form-group row"> --}}
                                        {{-- <label class="col-sm-2 col-form-label"></label> --}}
                                        {{-- <div class="col-sm-10"> --}}
                                        {{-- <img src="{{asset('public/storage/uploads/category/'.$edit->image ?? '')}}" style="width: 50px; height: 50px"> --}}
                                        {{-- </div> --}}
                                        {{-- </div> --}}
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Status</label>
                                            <div class="form-radio">
                                                <div class="col-sm-5 radio radio-outline radio-inline">
                                                    <label>
                                                        <input type="radio" name="status" id="active" value="1"
                                                            {{ $edit->status === 1 ? 'checked' : '' }}>
                                                        <i class="helper"></i>Active
                                                    </label>
                                                </div>
                                                <div class="col-sm-6 radio radio-outline radio-inline">
                                                    <label>
                                                        <input type="radio" name="status" id="inactive" value="0"
                                                            {{ $edit->status === 0 ? 'checked' : '' }}>
                                                        <i class="helper"></i>Inactive
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2"></label>
                                            <div class="col-sm-10">
                                                <button class="btn waves-effect waves-light btn-grd-success">Save
                                                    <i class="fa fa-refresh fa-spin" style="display: none" id="spinner"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src={{ asset('public/challenge-validation/validation.js') }}></script>
    {{-- <script> --}}

    {{-- //CKEDITOR.replace( 'content' ); --}}
    {{-- CKEDITOR.replace('content', { --}}
    {{-- height: 250, --}}
    {{-- extraPlugins: 'colorbutton' --}}
    {{-- }); --}}
    {{-- CKEDITOR.instances['content'].getData(); --}}
    {{-- CKEDITOR.config.allowedContent = true; --}}
    {{-- </script> --}}

    {{-- for back button confirmation (browser back button and admin panel back button) --}}
    <script>
        if (window.history && history.pushState) {
            addEventListener('load', function() {
                history.pushState(null, null, null); // creates new history entry with same URL
                addEventListener('popstate', function() {
                    var stayOnPage = confirm("Are you sure you want to leave this page?");
                    if (stayOnPage) {
                        history.back()
                    } else {
                        history.pushState(null, null, null);
                    }
                });
            });
        }

        $("#back-btn").on('click', function() {
            return confirm('Are you sure you want to leave this page?');
        });
    </script>
    {{-- for back button confirmation (browser back button and admin panel back button) --}}
@endsection
