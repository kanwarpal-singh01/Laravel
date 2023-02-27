@extends('admin.include.layouts')
@section('content')
<div class="pcoded-inner-content">
  <div class="main-body">
      <div class="page-wrapper">
         <div class="page-body">
            <div class="card">
                <div class="card-block">
                    <div class="table-responsive dt-responsive">
                        <table id="data-table" class="table table-striped table-bordered nowrap">
                            <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Title</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($list as $key => $data)
                                <tr>
                                    <td>{{config('gonda.intro_slug.'.$data->slug)}}</td>
                                    <td>{{$data->title ?? ''}}</td>
                                    @if($data->image)
                                        <td><img src="{{asset('public/storage/uploads/intro-screen/'.$data->image ?? '')}}" style="width: 40px; height: 40px"></td>
                                    @else
                                        <td><h6>No Image</h6></td>
                                    @endif
                                    <td>
                                        <a class="btn waves-effect waves-light btn-grd-info" href="{{route('admin.intro.screen.view',['id'=>base64_encode($data->id), 'page'=>request('page')])}}" style="padding: 4px;">
                                            <i class="fa fa-eye"></i>  View
                                        </a>
                                        <a class="btn waves-effect waves-light btn-grd-success" href="{{route('admin.intro.screen.edit',['id'=>base64_encode($data->id), 'page'=>request('page')])}}" style="padding: 4px;">
                                            <i class="fa fa-pencil-square-o"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                            {{ $list->links() }}
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
                    { orderable: false, targets: 2 },
                    { orderable: false, targets: 3 }
                ],
            });
        });

    </script>
@endsection
