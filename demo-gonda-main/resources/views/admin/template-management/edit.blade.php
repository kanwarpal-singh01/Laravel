@extends('admin.include.layouts')
@section('content')
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="form-group row">
                    <div class="col-md-12" style="margin-right: 873px;">
                        <a class="btn waves-effect waves-light btn-grd-primary"
                            href="{{ url('Kobe/template-management/list?page=' . request('page')) }}" id="back-btn">
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
                                    <form id="email-template-management-edit-form" method="post"
                                        action="{{ route('admin.template.edit', ['id' => base64_encode($edit->id), 'page' => request('page')]) }}"
                                        novalidate>
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
                                            <label class="col-sm-2 col-form-label">Subject</label>
                                            <div class="col-sm-10">
                                                <input type="text"
                                                    class="form-control @error('subject') is-invalid @enderror" id="subject"
                                                    name="subject" placeholder="Subject"
                                                    value="{{ $edit->subject ?? '' }}">
                                                @error('subject')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Credit Points</label>
                                            <div class="col-sm-10">
                                                <input type="text"
                                                    class="form-control @error('credit_points') is-invalid @enderror"
                                                    id="credit_points" name="credit_points" placeholder="Credit Points"
                                                    value="{{ $edit->credit_points ?? '' }}">
                                                @error('credit_points')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Keyword</label>
                                            <div class="col-sm-10">
                                                <select
                                                    class="form-control js-example-basic-single @error('keyword_id') is-invalid @enderror"
                                                    name="keyword_id[]" multiple="multiple" id="keyword_id">
                                                    <option value="" disabled>Select Keyword</option>
                                                    @foreach ($keyword as $record)
                                                        <option value="{{ $record->id }}" <?php if (in_array($record->id, explode(',', $edit->keyword_id))) {
    echo 'selected';
} ?>>
                                                            {{ $record->keyword }}</option>
                                                    @endforeach
                                                </select>
                                                @error('keyword_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Content</label>
                                            <div class="col-sm-10">
                                                <textarea name="content"
                                                    class="form-control @error('content') is-invalid @enderror" id="content"
                                                    rows="10" cols="80">{!! $edit->content ?? '' !!}</textarea>
                                                @error('content')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2"></label>
                                            <div class="col-sm-10">
                                                <button type="button" class="btn waves-effect waves-light btn-grd-primary"
                                                    id="preview" onclick="templatePreview()">preview
                                                </button>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Document Summary</label>
                                            <div class="col-sm-10">
                                                <textarea name="nda_summary"
                                                    class="form-control @error('nda_summary') is-invalid @enderror"
                                                    id="nda_summary" rows="10" cols="80">{!! $edit->nda_summary ?? '' !!}</textarea>
                                                @error('nda_summary')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
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
                                                <button type="submit"
                                                    class="btn waves-effect waves-light btn-grd-success">Save
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
    <script src="{{ asset('public/challenge-validation/validation.js') }}"></script>
    <script>
        CKEDITOR.config.disableNativeSpellChecker = false;
        CKEDITOR.config.extraPlugins = 'scayt';

        CKEDITOR.replace('content', {
            height: 250,
            extraPlugins: 'colorbutton'
        });
        CKEDITOR.replace('nda_summary', {
            height: 250,
            extraPlugins: 'colorbutton'
        });
        CKEDITOR.config.allowedContent = true;

        //for select2 dropdown
        $('#keyword_id').select2();
        // $("#keyword_id").on("select2:select", function (evt) {
        //     var element = evt.params.data.element;
        //     var $element = $(element);
        //     $element.detach();
        //     $(this).append($element);
        //     $(this).trigger("change");
        // });

        //for template preview

        function templatePreview() {
            var content = CKEDITOR.instances['content'].getData();
            var title = $('#title').val();
            $.ajax({
                type: "post",
                url: "{{ route('admin.template.preview') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'content': content,
                    'title': title
                },
                success: function(data) {
                    window.open(data, '_blank');
                },
                error: function() {
                    console.log('something went wrong.')
                }
            });
        }
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
