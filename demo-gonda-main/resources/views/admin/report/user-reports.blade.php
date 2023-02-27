@extends('admin.include.layouts')
@section('content')
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="row">
                    <div class="col-md-2 grid-margin stretch-card">
                        <form action="{{route('admin.user.reports')}}" method="get">
                            <input type="hidden" name="user_export">
                            <button type="submit" class="btn btn-success btn-fw">
                                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Export
                            </button>
                        </form>
                    </div>
                    <div class="col-md-10 grid-margin stretch-card">
                        <form class="row" action="{{route('admin.user.reports')}}" method="get">
                            <div class="col-sm-4" style="padding: 8px">
                                <span>From Date</span>
                                <input type="date" id="from" name="from" value="{{request('from') ?? ''}}" placeholder="From Date">
                            </div>
                            <div class="col-sm-4" style="padding: 8px;margin-left: -72px">
                                <span>To Date</span>
                                <input type="date" id="to" name="to" value="{{request('to') ?? ''}}" placeholder="To Date">
                            </div>
                            <div class="col-sm-3" style="padding: 8px">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fa fa-filter" aria-hidden="true"></i>
                                    Filter
                                </button>
                                <a href="{{route('admin.user.reports')}}" type="submit" id="reset" class="btn btn-primary btn-sm">
                                    <i class="fa fa-refresh" aria-hidden="true"></i>
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="page-body">
                    <div class="card">
                        <div class="card-block">
                            <div class="table-responsive dt-responsive">
                                <table id="data-table" class="table table-striped table-bordered nowrap">
                                    <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Image</th>
                                        <th>Mobile Number</th>
                                        <th>Company Name</th>
                                        <th>Job Title</th>
                                        <th>Street Address</th>
                                        <th>Apartment Number</th>
                                        <th>Country Name</th>
                                        <th>State Name</th>
                                        <th>City Name</th>
                                        <th>Zip Code</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($user as $key => $data)
                                        <tr>
                                            <td>{{$user->firstItem() + $key}}</td>
                                            <td>{{ucfirst($data->first_name ?? 'No first name')}}</td>
                                            <td>{{ucfirst($data->last_name ?? 'No last name')}}</td>
                                            <td>{{$data->email ?? 'No email'}}</td>
                                            @if($data->image)
                                            <td><img src="{{asset('public/storage/uploads/user-profile/'.$data->profile_image ?? '')}}" style="width: 40px; height: 40px"></td>
                                            @else
                                                <td>No image</td>
                                            @endif
                                            <td>{{$data->mobile_number ?? 'No mobile number'}}</td>
                                            <td>{{$data->companyDetail->company_name ?? 'No company name'}}</td>
                                            <td>{{$data->companyDetail->job_title ?? 'No job name'}}</td>
                                            <td>{{$data->mailingAddress->street_address ?? 'No address'}}</td>
                                            <td>{{$data->mailingAddress->apartment_number ?? 'No apartment number'}}</td>
                                            <td>{{$data->mailingAddress->countries->name ?? 'No country name'}}</td>
                                            <td>{{$data->mailingAddress->states->name ?? 'No state name'}}</td>
                                            <td>{{$data->mailingAddress->city_name ?? 'No city name'}}</td>
                                            <td>{{$data->mailingAddress->zip_code ?? 'No zip code'}}</td>
                                            <td>
                                                <label class="label {{$data->status == 1 ? 'label-success' : 'label-danger'}}">{{$data->status == 1 ? 'Active' : 'Inactive'}}</label>
                                            </td>
                                            <td>{{date_format($data->created_at ?? '', 'Y/m/d')}}</td>

                                        </tr>
                                    @empty
                                    @endforelse
                                    </tbody>
                                    {{ $user->links() }}
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready( function () {
            $('#data-table').DataTable({
                "paging": false,
                columnDefs: [
                    { orderable: false, targets: 4 },
                    { orderable: false, targets: 6 }
                ],
            });
        });

    </script>
@endsection
@section('scripts')


{{--    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">--}}
{{--    <link rel="stylesheet" href="/resources/demos/style.css">--}}
{{--    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>--}}
{{--    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>--}}
{{--    <script type="text/javascript">--}}
{{--        $(document).ready( function () {--}}
{{--            $('#from').datepicker({--}}
{{--                uiLibrary: 'bootstrap4'--}}
{{--            });--}}

{{--            $('#to').datepicker({--}}
{{--                uiLibrary: 'bootstrap4'--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}
@endsection
