@extends('pages.setup.main')

@push('css')
	<link href="/assets/plugins/password-indicator/css/password-indicator.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item">Data Kantor</li>
	<li class="breadcrumb-item active">{{ $aksi }} Data</li>
@endsection

@section('header')
	<h1 class="page-header">Data Kantor <small>{{ $aksi }} Data</small></h1>
@endsection

@section('subcontent')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<!-- begin panel-heading -->
		<div class="panel-heading">
			<div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            </div>
			<h4 class="panel-title">Form</h4>
		</div>
		<form action="/datakantor/{{ strtolower($aksi) }}" method="post" data-parsley-validate="true" data-parsley-errors-messages-disabled="">
			@csrf
			<div class="panel-body">
				<input type="hidden" name="redirect" value="{{ url()->previous() }}">
				<input type="hidden" name="kantor_id" value="{{ $data? $data->kantor_id: '' }}">
				<div class="form-group">
					<label class="control-label">Nama</label>
					<input class="form-control" type="text" name="kantor_nama" value="{{ $data? $data->kantor_nama: '' }}" required data-parsley-maxlength="250" autocomplete="off" />
				</div>
				<div class="form-group input-group-sm">
					<label class="control-label">Kab/Kota</label>
					<select class="form-control selectpicker" data-live-search="true" name="kantor_lokasi" data-style="btn-info" data-width="100%">
						<option value="Kab. Bima" 
							@if($data && $data->kantor_lokasi == "Kab. Bima")
								selected
							@endif
						>Kab. Bima</option>
						<option value="Kab. Dompu" 
							@if($data && $data->kantor_lokasi == "Kab. Dompu")
								selected
							@endif
						>Kab. Dompu</option>
						<option value="Kab. Lombok Barat" 
							@if($data && $data->kantor_lokasi == "Kab. Lombok Barat")
								selected
							@endif
						>Kab. Lombok Barat</option>
						<option value="Kab. Lombok Tengah" 
							@if($data && $data->kantor_lokasi == "Kab. Lombok Tengah")
								selected
							@endif
						>Kab. Lombok Tengah</option>
						<option value="Kab. Lombok Timur" 
							@if($data && $data->kantor_lokasi == "Kab. Lombok Timur")
								selected
							@endif
						>Kab. Lombok Timur</option>
						<option value="Kab. Lombok Utara" 
							@if($data && $data->kantor_lokasi == "Kab. Lombok Utara")
								selected
							@endif
						>Kab. Lombok Utara</option>
						<option value="Kab. Sumbawa" 
							@if($data && $data->kantor_lokasi == "Kab. Sumbawa")
								selected
							@endif
						>Kab. Sumbawa</option>
						<option value="Kab. Sumbawa Barat" 
							@if($data && $data->kantor_lokasi == "Kab. Sumbawa Barat")
								selected
							@endif
						>Kab. Sumbawa Barat</option>
						<option value="Kota Bima" 
							@if($data && $data->kantor_lokasi == "Kota Bima")
								selected
							@endif
						>Kota Bima</option>
						<option value="Kota Mataram" 
							@if($data && $data->kantor_lokasi == "Kota Mataram")
								selected
							@endif
						>Kota Mataram</option>
					</select>
				</div>
			</div>
			<div class="panel-footer">
	            <input type="submit" value="Simpan" class="btn btn-sm btn-success"  />
	            <a href="{{ url()->previous() }}" class="btn btn-sm btn-danger">Batal</a>
	        </div>
		</form>
	</div>	
    @if ($errors->any())
	<div class="alert alert-danger">
		<ul>
		    @foreach ($errors->all() as $error)
	      	<li>{{ $error }}</li>
		    @endforeach
		</ul>
	</div>
    @endif
@endsection

@push('scripts')
	<script src="/assets/plugins/parsleyjs/dist/parsley.js"></script>
	<script src="/assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
@endpush