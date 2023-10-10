@extends('layouts.admin-lte.main')
@section('style')
    {{-- <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.2/dist/leaflet.css"
        integrity="sha256-sA+zWATbFveLLNqWO2gtiw3HL/lh1giY/Inf1BJ0z14=" crossorigin="" /> --}}
    <style>
        .title {
            font-style: normal;
            font-weight: 500;
            font-size: 25px;
            line-height: 30px;
        }

        .title-card {
            font-style: normal;
            font-weight: 500;
            font-size: 16px;
            line-height: 20px;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="row justify-content-between">
                                    <div class="col-auto align-self-center">
                                        <h3 class="mb-0 title">
                                            {{ $visitor }}
                                        </h3>
                                        <span>Visitor</span>
                                    </div>
                                    <div class="col-auto align-self-center">
                                        <img src="{{ asset('img/dashboard-1.svg') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="row justify-content-between">
                                    <div class="col-auto align-self-center">
                                        <h3 class="mb-0 title">
                                            @price($todayIncome)
                                        </h3>
                                        <span>Today income</span>
                                    </div>
                                    <div class="col-auto align-self-center">
                                        <img src="{{ asset('img/dashboard-2.svg') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="row justify-content-between">
                                    <div class="col-auto align-self-center">
                                        <span class="title-card">Today Visitor Chart</span>
                                    </div>
                                    {{-- <div class="col-auto align-self-center">
                                        <a href="">View Detail</a>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="card-body">
                                <canvas id="visitorChart" width="400"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="row justify-content-between">
                                    <div class="col-auto align-self-center">
                                        <span class="title-card">Total Beacon Visitors</span>
                                    </div>
                                    {{-- <div class="col-auto align-self-center">
                                        <a href="">View Detail</a>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="card-body">
                                <canvas id="beaconVisitorChart" width="400"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header">
                                <div class="row justify-content-between">
                                    <div class="col-auto align-self-center">
                                        <span class="title-card">Total Visitor Chart</span>
                                    </div>
                                    {{-- <div class="col-auto align-self-center">
                                        <a href="">View Detail</a>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="card-body">
                                <canvas id="genderVisitorChart" width="400"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-header">
                                <div class="row justify-content-between">
                                    <div class="col-auto align-self-center">
                                        <span class="title-card">Group Heatmap</span>
                                    </div>
                                    {{-- <div class="col-auto align-self-center">
                                        <a href="{{ route('group.index') }}">View Detail</a>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="card-body">
                                {{-- <canvas id="map" width="100%"></canvas> --}}
                                <div id="map" style="height: 311px" class="w-100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script src="https://unpkg.com/leaflet@1.9.2/dist/leaflet.js"
        integrity="sha256-o9N1jGDZrf5tS+Ft4gbIK7mYMipq9lqpVJ91xHSyKhg=" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script src="{{ asset('js/leaflet-heat.js') }}"></script>
    <script>
        const gender = <?= json_encode($gender) ?>;
        const displayedBeacon = <?= json_encode($displayedBeacon) ?>;
        const displayedTodayVisitor = <?= json_encode($displayedTodayVisitor) ?>;
        const ctx = document.getElementById('beaconVisitorChart');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: displayedBeacon.label,
                datasets: [{
                    label: "Beacon Visitor",
                    data: displayedBeacon.value,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const visitorChart = document.getElementById('visitorChart');
        var xValues = displayedTodayVisitor.label;
        var yValues = displayedTodayVisitor.value;
        const data = {
            labels: xValues,
            datasets: [{
                label: 'Visitor',
                fill: true,
                backgroundColor: "rgb(207, 235, 255)",
                borderColor: "rgba(0,0,0,0.1)",
                data: yValues
            }]
        };
        const myVisitorChart = new Chart(visitorChart, {
            type: "line",
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {}
                }
            },
        });


        var barColors = [
            "rgba(252, 209, 0, 1)",
            "rgba(237, 112, 99, 1)",
        ];
        const genderLabel = gender.labels;
        const genderValue = gender.values;
        new Chart("genderVisitorChart", {
            type: "pie",
            data: {
                labels: genderLabel,
                datasets: [{
                    backgroundColor: barColors,
                    data: genderValue
                }]
            },
            // plugins: [ChartDataLabels],
            options: {
                title: {
                    display: true,
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label;
                                const currentValue = context.raw;
                                const display = [`${label}: ${currentValue}%`]

                                return display;
                            }
                        }
                    },
                    // legend: {
                    //     position: 'bottom',
                    // },
                    // datalabels: {
                    //     formatter: function(value, context) {
                    //         const label = context.chart.data.labels[
                    //             context.dataIndex
                    //         ];
                    //         const display = [`${label} : ${value}%`];
                    //         return display;
                    //     },
                    //     font: {
                    //         // weight: 'bold',
                    //     },
                    //     color: 'black',
                    // }
                },
            }
        });


        $(document).ready(function() {

            var yellowIcon = new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-gold.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            // var map = L.map('map').setView([-4.3842, 119.89], 3);
            // var map = L.map('map').setView([-2.645306, 121.104301], 4);
            var map = L.map('map').setView([-2.087691, 120.697814], 4);
            const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 16,
                // maxNativeZoom: 19,
                // attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);
            var heatMapData = @json($heatMapData);
            var markerData = @json($markerData);
            // console.log(<?= $markerData ?>);
            var heat = L.heatLayer([
                <?= $heatMapData ?>
            ], {
                radius: 20
            }).addTo(map);

            // console.log(markerData[0][0]);
            $.each(markerData, function(indexes, values) {

                var markerLocation = new L.LatLng(markerData[indexes][0], markerData[indexes][1]);
                var marker = new L.Marker(markerLocation, {
                    icon: yellowIcon
                });
                map.addLayer(marker);
                marker.bindPopup(markerData[indexes][2].toString());

            });

        });
    </script>
@endsection
