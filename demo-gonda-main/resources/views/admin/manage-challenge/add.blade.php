@extends('admin.include.layouts')
@section('content')
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="form-group row">
                    <div class="col-md-12" style="margin-right: 873px;">
                        <a class="btn waves-effect waves-light btn-grd-primary" href="{{route('admin.manage.challenge.list')}}">
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
                                    <form id="challenge-add-form" method="post" action="{{route('admin.manage.challenge.add')}}" novalidate enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Title</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Title" value="{{ old('title') }}">
                                                @error('title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Category</label>
                                            <div class="col-sm-10">
                                                <select class="js-example-basic-single col-sm-12 @error('category_id') is-invalid @enderror" name="category_id">
                                                    <option value="">--Select Category--</option>
                                                    @forelse($category as $category_data)
                                                        <option value="{{$category_data->id}}">{{$category_data->name}}</option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                                @error('category_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Date</label>
                                            <div class="col-sm-10">
                                                <input class="form-control @error('date') is-invalid @enderror" type="date" name="date" value="{{old('date')}}"/>
                                            </div>
                                            @error('date')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Start Time</label>
                                            <div class="col-sm-10">
                                                <input class="form-control @error('start_time') is-invalid @enderror" type="time" name="start_time" value="{{old('start_time')}}"/>
                                            </div>
                                            @error('start_time')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">End Time</label>
                                            <div class="col-sm-10">
                                                <input class="form-control @error('end_time') is-invalid @enderror" type="time" name="end_time" value="{{old('end_time')}}"/>
                                            </div>
                                            @error('end_time')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">No Of Participants</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control @error('no_of_participants') is-invalid @enderror" id="no_of_participants" name="no_of_participants" placeholder="No Of Participants" value="{{ old('no_of_participants') }}">
                                                @error('no_of_participants')
                                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Image</label>
                                            <div class="col-sm-10">
                                                <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror">
                                                @error('image')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Description</label>
                                            <div class="col-sm-10">
                                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" rows="10" cols="80">{{old('description')}}</textarea>
                                                @error('description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Challenge Type</label>
                                            <div class="form-radio" style="display:flex;">
                                                <div class="col-sm-6 radio radio-outline radio-inline">
                                                    <label>
                                                        <input type="radio" name="challenge_type" value="1" id="online" checked="checked">
                                                        <i class="helper"></i>Online
                                                    </label>
                                                </div>
                                                <div class="col-sm-6 radio radio-outline radio-inline">
                                                    <label>
                                                        <input type="radio" name="challenge_type" id="callout" value="0">
                                                        <i class="helper"></i>Callout
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Status</label>
                                            <div class="form-radio" style="display:flex;">
                                                <div class="col-sm-6 radio radio-outline radio-inline">
                                                    <label>
                                                        <input type="radio" name="status" value="1" id="active" checked="checked">
                                                        <i class="helper"></i>Active
                                                    </label>
                                                </div>
                                                <div class="col-sm-6 radio radio-outline radio-inline">
                                                    <label>
                                                        <input type="radio" name="status" id="inactive" value="0">
                                                        <i class="helper"></i>Inactive
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Badge Type</label>
                                            <div class="form-radio" style="display:flex;">
                                                <div class="col-sm-4 radio radio-outline radio-inline">
                                                    <label>
                                                        <input type="radio" name="badge_type" value="1" id="gold" checked="checked">
                                                        <i class="helper"></i>Gold
                                                    </label>
                                                </div>
                                                <div class="col-sm-4 radio radio-outline radio-inline">
                                                    <label>
                                                        <input type="radio" name="badge_type" id="silver" value="2">
                                                        <i class="helper"></i>Silver
                                                    </label>
                                                </div>
                                                <div class="col-sm-4 radio radio-outline radio-inline">
                                                    <label>
                                                        <input type="radio" name="badge_type" id="bronze" value="3">
                                                        <i class="helper"></i>Bronze
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2"></label>
                                            <div class="col-sm-10">
                                                <button class="btn waves-effect waves-light btn-grd-primary">Submit
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
    <script type="text/javascript">
        //CKEDITOR.replace( 'description' );
        CKEDITOR.replace('description', {
            height: 250,
            extraPlugins: 'colorbutton'
        });
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
    </script>
@endsection
