@extends('pages.master.main')

@section('page')
	<li class="breadcrumb-item active">Data Pegawai</li>
@endsection

@push('css')
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
@endpush

@section('header')
	<h1 class="page-header">Data Pegawai</h1>
@endsection

@section('subcontent')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<!-- begin panel-heading -->
		<div class="panel-heading">
			<div class="row">
                <div class="col-md-2 col-lg-6 col-xl-6 col-xs-12 col-sm-12">
					&nbsp;
                </div>
                <div class="col-md-10 col-lg-6 col-xl-6 col-xs-12 col-sm-12">
					<form action="/datapegawai" method="GET" id="frm-kantor" class="pull-right">
	                    <div class="form-inline">
	                        <div class="form-group">
								<select class="form-control selectpicker" data-live-search="true" id="kantor" name="kantor" data-style="btn-info" data-width="100%">
									@foreach($kantor as $ktr)
									<option value="{{ $ktr->kantor_id }}" 
										@if($kantor_id == $ktr->kantor_id)
											selected
										@endif
									>{{ $ktr->kantor_nama }}</option>
									@endforeach
								</select>
		                    </div>&nbsp;
	                		<div class="input-group ">
								<input type="text" class="form-control" name="cari" placeholder="Pencarian" aria-label="Sizing example input" autocomplete="off"  value="{{ $cari }}">
								<div class="input-group-append">
									<button class="btn input-group-text" >
										<i class="fa fa-search"></i>
									</button>
								</div>
							</div>
						</div>
					</form>
            	</div>
            </div>
		</div>
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-hover">
                    <thead>
						<tr>
							<th>No.</th>
							<th>NIP</th>
							<th>Nama</th>
							<th>Golongan</th>
							<th>Jenis Kelamin</th>
						</tr>
					</thead>
					<tbody>
					    @foreach ($data as $index => $pegawai)
					    <tr>
					        <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
					        <td>{{ $pegawai->pegawai_nip }}</td>
					        <td>{{ $pegawai->pegawai_nama }}</td>
					        <td>{{ $pegawai->pegawai_golongan }}</td>
					        <td>{{ $pegawai->pegawai_jenis_kelamin }}</td>
				      	</tr>
					    @endforeach
				    </tbody>
				</table>
			</div>
		</div>
		<div class="panel-footer form-inline">
            <div class="col-md-6 col-lg-10 col-xl-10 col-xs-12">
				{{ $data->links() }}
			</div>
			<div class="col-md-6 col-lg-2 col-xl-2 col-xs-12">
				<label class="pull-right">Jumlah Data : {{ $data->total() }}</label>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	<script src="/assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
@endpush