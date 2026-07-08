{{-- resources/views/backend/ranking-saw/compare.blade.php --}}
@extends('backend.layouts.main')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="fs-18">Perbandingan Alternatif SAW</h3>
    <a href="{{ route('ranking-saw.index') }}" class="btn btn-secondary btn-sm">
        <i data-feather="arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-12">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Perbandingan Nilai Preferensi</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Kriteria</th>
                                <th>Bobot</th>
                                <th>Atribut</th>
                                <th class="text-center bg-primary text-white">
                                    {{ $alt1->lokasi->nama_jalan }}
                                    <br>
                                    <small>Nilai: {{ number_format($alt1->nilai_preferensi, 4) }}</small>
                                </th>
                                <th class="text-center bg-danger text-white">
                                    {{ $alt2->lokasi->nama_jalan }}
                                    <br>
                                    <small>Nilai: {{ number_format($alt2->nilai_preferensi, 4) }}</small>
                                </th>
                                <th class="text-center">Selisih</th>
                                <th class="text-center">Pemenang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kriteria as $index => $k)
                                @php
                                    $field = $k->nama_kriteria;
                                    $nilai1 = $alt1->{$field};
                                    $nilai2 = $alt2->{$field};
                                    $selisih = $nilai1 - $nilai2;
                                    
                                    $ranking1 = $alt1->rankingSaw->where('kriteria_id', $k->id)->first();
                                    $ranking2 = $alt2->rankingSaw->where('kriteria_id', $k->id)->first();
                                @endphp
                                <tr>
                                    <td><strong>C{{ $index + 1 }}</strong><br>
                                        <small>{{ ucfirst(str_replace('_', ' ', $k->nama_kriteria)) }}</small>
                                    </td>
                                    <td>{{ $k->bobot }}%</td>
                                    <td>
                                        <span class="badge bg-{{ $k->atribut == 'benefit' ? 'success' : 'danger' }}">
                                            {{ ucfirst($k->atribut) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div><strong>{{ $nilai1 }}</strong></div>
                                        <small class="text-muted">Norm: {{ number_format($ranking1->nilai_normalisasi ?? 0, 4) }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div><strong>{{ $nilai2 }}</strong></div>
                                        <small class="text-muted">Norm: {{ number_format($ranking2->nilai_normalisasi ?? 0, 4) }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $selisih > 0 ? 'success' : ($selisih < 0 ? 'danger' : 'secondary') }}">
                                            {{ number_format($selisih, 0) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($selisih > 0)
                                            <span class="badge bg-success">
                                                <i data-feather="check-circle"></i> {{ $alt1->lokasi->nama_jalan }}
                                            </span>
                                        @elseif($selisih < 0)
                                            <span class="badge bg-danger">
                                                <i data-feather="check-circle"></i> {{ $alt2->lokasi->nama_jalan }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Sama</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-success">
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total Nilai Preferensi</strong></td>
                                <td class="text-center">
                                    <strong class="text-primary">
                                        {{ number_format($alt1->nilai_preferensi, 4) }}
                                    </strong>
                                </td>
                                <td class="text-center">
                                    <strong class="text-danger">
                                        {{ number_format($alt2->nilai_preferensi, 4) }}
                                    </strong>
                                </td>
                                <td class="text-center">
                                    <strong>
                                        {{ number_format($alt1->nilai_preferensi - $alt2->nilai_preferensi, 4) }}
                                    </strong>
                                </td>
                                <td class="text-center">
                                    @if($alt1->nilai_preferensi > $alt2->nilai_preferensi)
                                        <span class="badge bg-success fs-6">
                                            <i data-feather="award"></i> {{ $alt1->lokasi->nama_jalan }}
                                        </span>
                                    @elseif($alt1->nilai_preferensi < $alt2->nilai_preferensi)
                                        <span class="badge bg-danger fs-6">
                                            <i data-feather="award"></i> {{ $alt2->lokasi->nama_jalan }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary fs-6">Seri</span>
                                    @endif
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection