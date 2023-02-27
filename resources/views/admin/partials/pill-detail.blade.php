<ul class="nav nav-pills mb-2">
    <li class="nav-item">
        <a class="nav-link {{ $active_link == 1 ? 'active' : '' }}" href="{{route('admin.fir.show-form-detail', ['id' => $fir->fir_id, 'type' => 1])}}">
            <i data-feather='file-text'></i>
            <span class="fw-bold">Quality of Investigation</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active_link == 2 ? 'active' : '' }}" href="{{route('admin.fir.show-form-detail', ['id' => $fir->fir_id, 'type' => 2])}}">
            <i data-feather='file-text'></i>
            <span class="fw-bold">Quality of Evidence</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active_link == 3 ? 'active' : '' }}" href="{{route('admin.fir.show-form-detail', ['id' => $fir->fir_id, 'type' => 3])}}">
            <i data-feather='file-text'></i>
            <span class="fw-bold">Monitoring of Supervision</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active_link == 4 ? 'active' : '' }}" href="{{route('admin.fir.show-form-detail', ['id' => $fir->fir_id, 'type' => 4])}}">
            <i data-feather='file-text'></i>
            <span class="fw-bold">Scruitny by Legal Branch</span>
        </a>
    </li>
</ul>
