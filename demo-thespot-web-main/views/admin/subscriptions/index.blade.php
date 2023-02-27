    <?php $url = Request::segments(); ?>
    @extends('layouts.admin',['pageHeader'=>'Users Management'])
    @section('content')
    <div class="card tableCard code-table">
        <div class="card-header">
            <h5>Subscription Management</h5>
            <div class="card-header-right">
                <a href="{{ route('subscriptions') }}" class="btn btn-primary"><i class="fa fa-list"> Subscription List</i></a>
            </div>
        </div>
    </div>
    @include('includes.flash')
    <div class="card tableCard code-table">
        <div class="card-header">
            <h5>Subscription List</h5>
        </div>
        <div class="card-block pb-0">
            <div class="table-responsive">
                <table class="table table-hover table-list">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Plan Name</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        if (count($Subscriptions) > 0) {
                            $page = $Subscriptions->currentPage();
                            $pagelimit = config("Settings.AdminPageLimit");
                            $i = ($page * $pagelimit) - ($pagelimit); ?>
                            @foreach ($Subscriptions as $row)
                            <?php $i++ ?>
                            <tr class="gradeY">
                                <td>{{$i}}.</td>
                                <td>{{$row->plan_name}}</td>
                                <td>{{$row->description}}</td>
                                <td>{{$row->amount}}</td>
                                <td class="center">
                                    @if($row->status == 0)
                                    <a class="btn btn-danger btn-sm tip-bottom btn-mini" role="button" href="{{ route('changePlanStatus',['id'=>base64_encode($row->id),'status'=>'1']) }}" title="Click to Active" onclick="return confirm('Do you want to active ?')">Inactive</a>
                                    @endif
                                    @if($row->status == 1)
                                    <a class="tip-bottom btn-mini label theme-bg f-12 text-white" role="button" href="{{ route('changePlanStatus',['id'=>base64_encode($row->id),'status'=>'0']) }}" class="tip-left" title="Click to inactive" onclick="return confirm('Do you want to inactive ?')">Active</a>
                                    @endif
                                </td>
                                <td class="center">
                                    <a href="{{ route('editPlan',['id'=>base64_encode($row->id)])}}" class="mb-2 mr-2 "><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('planDetail',['id' => base64_encode($row->id)])}}" class="mb-2 mr-2 "><i class="fas fa-eye"></i></a>
                                    {{-- <a  href="{{ url('/admin/'.$url[1].'/delete') }}/{{base64_encode($row->id)}}/{{base64_encode($url[1])}}" class="badge-danger mb-2 mr-2 "><i class="fas fa-trash"></i></a> --}}
                                </td>
                            </tr>
                            @endforeach
                        <?php } else { ?>
                            <tr>
                                <td colspan='10' style="text-align: center">No Result Found</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php if (!empty($Subscriptions)) { ?>
                    <div class="pagination-parent">
                        <div class="left">
                            Total Records:<strong>{{$Subscriptions->total()}}</strong> Page of pages: <strong>{{$Subscriptions->currentPage()}}</strong> of <strong>{{$Subscriptions->lastPage()}}</strong>
                        </div>
                        <div class="pagination">
                            {{ $Subscriptions->appends(Request::only('q'))->links() }}
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    @stop