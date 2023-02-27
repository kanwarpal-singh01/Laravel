    <?php $url = Request::segments(); ?>
    @extends('layouts.admin',['pageHeader'=>'Users Management'])
    @section('content')

    <div class="card tableCard code-table">
        <div class="card-header">
            <h5>Users Story Management</h5>
        </div>
        <!-- <div class="card-header">
            {{ Form::open(array('url' => 'admin/'.$url[1], 'class'=>"form-inline md-form mr-auto", 'method' => 'get')) }}
            <div class="input-group">
                <input class="form-control" value="<?php if (!empty($query)) {
                                                        echo $query;
                                                    } ?>" placeholder='Search' name="q" type="text">
                <div class="input-group-append">
                    <button class="input-group-text submitSearchForm float-left" type="submit"><i class="feather icon-search"></i></button>
                    <a href="{{ route('userList')}}" class=" input-group-text text-danger btn btn-danger float-left" type="submit">Reset</a>
                </div>
            </div>
            {{ Form::close() }}
        </div> -->
        @include('includes.flash')
        <div class="card-block pb-0">
            <div class="table-responsive">
                <table class="table table-hover table-list">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Name</th>
                            <th>Story Type</th>
                            <th>Story Upladed Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        if (count($stories) > 0) {
                            $page = $stories->currentPage();
                            $pagelimit = config("Settings.AdminPageLimit");
                            $i = ($page * $pagelimit) - ($pagelimit); ?>
                            @foreach ($stories as $row)
                            <?php $i++ ?>
                            <tr class="gradeY">
                                <td>{{$i}}.</td>
                                <td><a href="{{route('userDetail',['id'=>base64_encode($row->users->id)])}}">{{$row->users->name }}</a></td>
                                <td>{{$row->story_type}}</td>
                                <td>{{$row->created_at->format('d-M-Y')}}</td>
                                <td class="center">
                                    <a href="{{config('app.story_url').$row->story}}" target="_blank" class="mb-2 mr-2 " title="User Detail"><i class="fas fa-eye"></i></a>
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
                <?php if (!empty($stories)) { ?>
                    <div class="pagination-parent">
                        <div class="left">
                            Total Records:<strong>{{$stories->total()}}</strong> Page of pages: <strong>{{$stories->currentPage()}}</strong> of <strong>{{$stories->lastPage()}}</strong>
                        </div>
                        <div class="pagination">
                            {{ $stories->appends(Request::only('q'))->links() }}
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    @stop