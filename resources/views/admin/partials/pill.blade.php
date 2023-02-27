<ul class="nav nav-pills mb-2">
    <li class="nav-item">
        <a class="nav-link {{ $active_link == 1 ? 'active' : '' }}" href="{{route('admin.fir.form-detail-b', $fir->fir_id)}}">
            <i data-feather='file-text'></i>
            <span class="fw-bold">Quality of Investigation</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active_link == 2 ? 'active' : '' }}" href="{{route('admin.fir.form-detail-c', $fir->fir_id)}}">
            <i data-feather='file-text'></i>
            <span class="fw-bold">Quality of Evidence</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active_link == 3 ? 'active' : '' }}" href="{{route('admin.fir.form-detail-d', $fir->fir_id)}}">
            <i data-feather='file-text'></i>
            <span class="fw-bold">Monitoring of Supervision</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active_link == 4 ? 'active' : '' }}" href="{{route('admin.fir.form-detail-e', $fir->fir_id)}}">
            <i data-feather='file-text'></i>
            <span class="fw-bold">Scruitny by Legal Branch</span>
        </a>
    </li>
</ul>
