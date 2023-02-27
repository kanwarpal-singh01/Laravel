@extends('admin.include.layouts')
@section('content')
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="form-group row">
                    <div class="col-md-12" style="margin-right: 873px;">
                        <a class="btn waves-effect waves-light btn-grd-primary"
                            href="{{ url('Kobe/knowledge-center/list?page=' . request('page')) }}" id="back-btn">
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
                                    <form id="banner-management-edit-form" method="post"
                                        action="{{ route('admin.knowledge.center.edit', ['id' => base64_encode($edit->id), 'page' => request('page')]) }}"
                                        novalidate enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Title</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                                    id="title" name="title" placeholder="Title"
                                                    value="{{ $edit->title ?? '' }}">
                                                @error('title')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Document</label>
                                            <div class="col-sm-10">
                                                <input type="file" name="document" id="document"
                                                    class="form-control @error('document') is-invalid @enderror">
                                                @error('document')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Category</label>
                                            <div class="col-sm-10">
                                                <select class="js-example-basic-single" name="category_id[]"
                                                    multiple="multiple">
                                                    <option value="" disabled>Select Category</option>
                                                    @foreach ($category as $record)
                                                        <option value="{{ $record->id }}" <?php if (in_array($record->id, explode(',', $edit->category_id))) {
    echo 'selected';
} ?>>
                                                            {{ ucfirst($record->name) }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Description</label>
                                            <div class="col-sm-10">
                                                <textarea name="description"
                                                    class="form-control @error('description') is-invalid @enderror"
                                                    id="description" rows="10"
                                                    cols="80">{{ $edit->description }}</textarea>
                                                @error('description')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- <div class="form-group row"> --}}
                                        {{-- <label class="col-sm-2 col-form-label"></label> --}}
                                        {{-- <div class="col-sm-10"> --}}
                                        {{-- <img src="{{asset('public/storage/uploads/knowledge-center/'.$edit->document ?? '')}}" style="width: 50px; height: 50px"> --}}
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
    <script>
        //CKEDITOR.replace( 'description' );
        CKEDITOR.replace('description', {
            filebrowserUploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form',
            height: 250,
            extraPlugins: 'colorbutton'
        });

        CKEDITOR.instances['description'].getData();
        CKEDITOR.config.allowedContent = true;

        //for select2 dropdown
        $('.js-example-basic-single').select2();
    </script>
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
