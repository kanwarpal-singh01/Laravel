@extends('admin.include.layouts')
@section('content')
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <!-- Page-body start -->
                <div class="page-body">
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-block">
                                    <div class="row align-items-center">
                                        <div class="col-8">
                                            <h4 class="text-c-purple">{{ count($total_user) ?? 0 }}</h4>
                                            <h6 class="text-muted m-b-0">Total Users</h6>
                                        </div>
                                        <div class="col-4 text-right">
                                            <i class="fa fa-users"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-c-purple">
                                    <div class="row align-items-center">
                                        <div class="col-9">
                                            <p class="text-white m-b-0">Users</p>
                                        </div>
                                        <div class="col-3 text-right">
                                            <i class="fa fa-users"></i>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-block">
                                    <div class="row align-items-center">
                                        <div class="col-8">
                                            <h4 class="text-c-green">{{ count($total_testimonial) ?? 0 }}</h4>
                                            <h6 class="text-muted m-b-0">Total Testimonial</h6>
                                        </div>
                                        <div class="col-4 text-right">
                                            <i class="fa fa-list-alt"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-c-green">
                                    <div class="row align-items-center">
                                        <div class="col-9">
                                            <p class="text-white m-b-0">Testimonial</p>
                                        </div>
                                        <div class="col-3 text-right">
                                            <i class="fa fa-list-alt"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-xl-3 col-md-6"> --}}
                        {{-- <div class="card"> --}}
                        {{-- <div class="card-block"> --}}
                        {{-- <div class="row align-items-center"> --}}
                        {{-- <div class="col-8"> --}}
                        {{-- <h4 class="text-c-blue">{{count($total_challenge) ?? 0}}</h4> --}}
                        {{-- <h6 class="text-muted m-b-0">Total Challenges</h6> --}}
                        {{-- </div> --}}
                        {{-- <div class="col-4 text-right"> --}}
                        {{-- <i class="fa fa-handshake-o"></i> --}}
                        {{-- </div> --}}
                        {{-- </div> --}}
                        {{-- </div> --}}
                        {{-- <div class="card-footer bg-c-blue"> --}}
                        {{-- <div class="row align-items-center"> --}}
                        {{-- <div class="col-9"> --}}
                        {{-- <p class="text-white m-b-0">Challenge</p> --}}
                        {{-- </div> --}}
                        {{-- <div class="col-3 text-right"> --}}
                        {{-- <i class="fa fa-handshake-o"></i> --}}
                        {{-- </div> --}}
                        {{-- </div> --}}
                        {{-- </div> --}}
                        {{-- </div> --}}
                        {{-- </div> --}}
                        {{-- <div class="col-xl-3 col-md-6"> --}}
                        {{-- <div class="card"> --}}
                        {{-- <div class="card-block"> --}}
                        {{-- <div class="row align-items-center"> --}}
                        {{-- <div class="col-8"> --}}
                        {{-- <h4 class="text-c-red">{{count($total_contact) ?? 0}}</h4> --}}
                        {{-- <h6 class="text-muted m-b-0">Total Enquires</h6> --}}
                        {{-- </div> --}}
                        {{-- <div class="col-4 text-right"> --}}
                        {{-- <i class="fa fa-question-circle"></i> --}}
                        {{-- </div> --}}
                        {{-- </div> --}}
                        {{-- </div> --}}
                        {{-- <div class="card-footer bg-c-red"> --}}
                        {{-- <div class="row align-items-center"> --}}
                        {{-- <div class="col-9"> --}}
                        {{-- <p class="text-white m-b-0">Enquiry</p> --}}
                        {{-- </div> --}}
                        {{-- <div class="col-3 text-right"> --}}
                        {{-- <i class="fa fa-question-circle"></i> --}}
                        {{-- </div> --}}
                        {{-- </div> --}}
                        {{-- </div> --}}
                        {{-- </div> --}}
                        {{-- </div> --}}
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="card">
                                <div class="card-block">
                                    <h4 class="card-title">Total Users</h4>
                                    <div class="flot-chart-container">
                                        <canvas id="userChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6">
                            <div class="card">
                                <div class="card-block">
                                    <h4 class="card-title">Total Document In Draft , Document Sent And Document Complete
                                    </h4>
                                    <div class="flot-chart-container">
                                        <canvas id="documentInDraftChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Page-body end -->
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        // Start User Chart
        var ctx = document.getElementById("userChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["{{ $userArr[1]['month'] }}", "{{ $userArr[2]['month'] }}",
                    "{{ $userArr[3]['month'] }}",
                    "{{ $userArr[4]['month'] }}", "{{ $userArr[5]['month'] }}",
                    "{{ $userArr[6]['month'] }}",
                    "{{ $userArr[7]['month'] }}", "{{ $userArr[8]['month'] }}",
                    "{{ $userArr[9]['month'] }}",
                    "{{ $userArr[10]['month'] }}", "{{ $userArr[11]['month'] }}",
                    "{{ $userArr[12]['month'] }}"
                ],
                datasets: [{
                    label: '# of Users',
                    data: [{{ $userArr[1]['count'] }}, {{ $userArr[2]['count'] }},
                        {{ $userArr[3]['count'] }}, {{ $userArr[4]['count'] }},
                        {{ $userArr[5]['count'] }}, {{ $userArr[6]['count'] }},
                        {{ $userArr[7]['count'] }}, {{ $userArr[8]['count'] }},
                        {{ $userArr[9]['count'] }}, {{ $userArr[10]['count'] }},
                        {{ $userArr[11]['count'] }}, {{ $userArr[12]['count'] }}
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
        // End User Chart

        // Start Document in draft, sent, complete Chart
        var ctxx = document.getElementById("documentInDraftChart").getContext('2d');
        var myChartt = new Chart(ctxx, {
            type: 'bar',
            data: {
                labels: ["{{ $documentArr[1]['month'] }}", "{{ $documentArr[2]['month'] }}",
                    "{{ $documentArr[3]['month'] }}", "{{ $documentArr[4]['month'] }}",
                    "{{ $documentArr[5]['month'] }}", "{{ $documentArr[6]['month'] }}",
                    "{{ $documentArr[7]['month'] }}", "{{ $documentArr[8]['month'] }}",
                    "{{ $documentArr[9]['month'] }}", "{{ $documentArr[10]['month'] }}",
                    "{{ $documentArr[11]['month'] }}", "{{ $documentArr[12]['month'] }}"
                ],


                datasets: [{
                        label: '# of Document in draft',
                        data: [{{ $documentArr[1]['count'] }}, {{ $documentArr[2]['count'] }},
                            {{ $documentArr[3]['count'] }}, {{ $documentArr[4]['count'] }},
                            {{ $documentArr[5]['count'] }}, {{ $documentArr[6]['count'] }},
                            {{ $documentArr[7]['count'] }}, {{ $documentArr[8]['count'] }},
                            {{ $documentArr[9]['count'] }}, {{ $documentArr[10]['count'] }},
                            {{ $documentArr[11]['count'] }}, {{ $documentArr[12]['count'] }}
                        ],
                        backgroundColor: [
                            'rgba(255, 91, 132, 0.2)',
                            'rgba(255, 91, 132, 0.2)',
                            'rgba(255, 91, 132, 0.2)',
                            'rgba(255, 91, 132, 0.2)',
                            'rgba(255, 91, 132, 0.2)',
                            'rgba(255, 91, 132, 0.2)',
                            'rgba(255, 91, 132, 0.2)',
                            'rgba(255, 91, 132, 0.2)',
                            'rgba(255, 91, 132, 0.2)',
                            'rgba(255, 91, 132, 0.2)',
                            'rgba(255, 91, 132, 0.2)',
                            'rgba(255, 91, 132, 0.2)',
                            'rgba(255, 91, 132, 0.2)',

                        ],
                        borderColor: [
                            'rgba(255,99,132,1)',
                            'rgba(255,99,132,1)',
                            'rgba(255,99,132,1)',
                            'rgba(255,99,132,1)',
                            'rgba(255,99,132,1)',
                            'rgba(255,99,132,1)',
                            'rgba(255,99,132,1)',
                            'rgba(255,99,132,1)',
                            'rgba(255,99,132,1)',
                            'rgba(255,99,132,1)',
                            'rgba(255,99,132,1)',
                            'rgba(255,99,132,1)',

                        ],
                        borderWidth: 1
                    },
                    {
                        label: '# of Document sent',

                        data: [{{ $documentSentArr[1]['count'] }}, {{ $documentSentArr[2]['count'] }},
                            {{ $documentSentArr[3]['count'] }}, {{ $documentSentArr[4]['count'] }},
                            {{ $documentSentArr[5]['count'] }}, {{ $documentSentArr[6]['count'] }},
                            {{ $documentSentArr[7]['count'] }}, {{ $documentSentArr[8]['count'] }},
                            {{ $documentSentArr[9]['count'] }}, {{ $documentSentArr[10]['count'] }},
                            {{ $documentSentArr[11]['count'] }}, {{ $documentSentArr[12]['count'] }}
                        ],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(54, 162, 235, 0.2)',

                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(54, 162, 235, 1)',

                        ],
                        borderWidth: 1
                    },
                    {
                        label: '# of Document complete',

                        data: [{{ $documentCompleteArr[1]['count'] }},
                            {{ $documentCompleteArr[2]['count'] }},
                            {{ $documentCompleteArr[3]['count'] }},
                            {{ $documentCompleteArr[4]['count'] }},
                            {{ $documentCompleteArr[5]['count'] }},
                            {{ $documentCompleteArr[6]['count'] }},
                            {{ $documentCompleteArr[7]['count'] }},
                            {{ $documentCompleteArr[8]['count'] }},
                            {{ $documentCompleteArr[9]['count'] }},
                            {{ $documentCompleteArr[10]['count'] }},
                            {{ $documentCompleteArr[11]['count'] }},
                            {{ $documentCompleteArr[12]['count'] }}
                        ],
                        backgroundColor: [
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(255, 206, 86, 0.2)',

                        ],
                        borderColor: [
                            'rgba(255, 206, 86, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(255, 206, 86, 1)',

                        ],
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
        // Start Document in draft, sent, complete Chart
    </script>
@endsection
