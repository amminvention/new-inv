<!-- Goal Overview Card -->
<div class="col-lg-3 col-md-6 col-12">
    <div class="card bg-primary text-white">
        <div class="card-header d-flex justify-content-between align-items-center">
{{--            <h6 class="card-title">Quality of Investigation</h6>--}}
            <h6 class="text-white">Quality of Investigation</h6>
        </div>
        <div class="card-body p-0">
            <div id="goal-overview-radial-bar-chart-1" class="my-2"></div>
            <div class="row border-top text-center mx-0">
                <div class="col-6 border-end py-1">
                    <p class="card-text text-white mb-0">Total Feedback</p>
                    <h3 class="text-white fw-bolder mb-0">{{ $stat_b['total_b'] }}</h3>
                </div>
                <div class="col-6 py-1">
                    <p class="card-text text-white mb-0">Pending Feedback</p>
                    <h3 class="fw-bolder mb-0 text-white">{{ ($stat_b['pending_b'] + $stat_b['completed_b']) == 0 ? $stat_b['total_b'] : $stat_b['pending_b'] }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/ Goal Overview Card -->

<!-- Goal Overview Card -->
<div class="col-lg-3 col-md-6 col-12">
    <div class="card bg-danger text-white">
        <div class="card-header d-flex justify-content-between align-items-center">
{{--            <h6 class="card-title">Quality of Evidence</h6>--}}
            <h6 class="text-white">Quality of Evidence</h6>
        </div>
        <div class="card-body p-0">
            <div id="goal-overview-radial-bar-chart-2" class="my-2"></div>
            <div class="row border-top text-center mx-0">
                <div class="col-6 border-end py-1">
                    <p class="card-text text-white mb-0">Total Feedback</p>
                    <h3 class="fw-bolder mb-0 text-white">{{ $stat_c['total_c'] }}</h3>
                </div>
                <div class="col-6 py-1">
                    <p class="card-text text-white mb-0">Pending Feedback</p>
                    <h3 class="fw-bolder mb-0 text-white">{{ ($stat_c['pending_c'] + $stat_c['completed_c']) == 0 ? $stat_c['total_c'] : $stat_c['pending_c'] }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/ Goal Overview Card -->

<!-- Goal Overview Card -->
<div class="col-lg-3 col-md-6 col-12">
    <div class="card bg-success text-white">
        <div class="card-header d-flex justify-content-between align-items-center">
{{--            <h6 class="card-title">Monitoring of Supervision</h6>--}}
            <h6 class="text-white">Monitoring of Supervision</h6>
        </div>
        <div class="card-body p-0">
            <div id="goal-overview-radial-bar-chart-3" class="my-2"></div>
            <div class="row border-top text-center mx-0">
                <div class="col-6 border-end py-1">
                    <p class="card-text text-white mb-0">Total Feedback</p>
                    <h3 class="fw-bolder mb-0 text-white">{{ $stat_d['total_d'] }}</h3>
                </div>
                <div class="col-6 py-1">
                    <p class="card-text text-white mb-0">Pending Feedback</p>
                    <h3 class="fw-bolder mb-0 text-white">{{ ($stat_d['pending_d'] + $stat_d['completed_d']) == 0 ? $stat_d['total_d'] : $stat_d['pending_d'] }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/ Goal Overview Card -->

<!-- Goal Overview Card -->
<div class="col-lg-3 col-md-6 col-12">
    <div class="card bg-info text-white">
        <div class="card-header d-flex justify-content-between align-items-center">
{{--            <h6 class="card-title">Scruitny by Legal Branch</h6>--}}
            <h6 class="text-white">Scruitny by Legal Branch</h6>
        </div>
        <div class="card-body p-0">
            <div id="goal-overview-radial-bar-chart-4" class="my-2"></div>
            <div class="row border-top text-center mx-0">
                <div class="col-6 border-end py-1">
                    <p class="card-text text-white mb-0">Total Feedback</p>
                    <h3 class="fw-bolder mb-0 text-white">{{ $stat_e['total_e'] }}</h3>
                </div>
                <div class="col-6 py-1">
                    <p class="card-text text-white mb-0">Pending Feedback</p>
                    <h3 class="fw-bolder mb-0 text-white">{{ ($stat_e['pending_e'] + $stat_e['completed_e']) == 0 ? $stat_e['total_e'] : $stat_e['pending_e'] }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/ Goal Overview Card -->
