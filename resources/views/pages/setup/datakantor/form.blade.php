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
				@if($data)
				<div class="form-group">
					<label class="control-label">ID</label>
					<input class="form-control" type="text" name="kantor_id" value="{{ $data? $data->kantor_id: '' }}" required readonly />
				</div>
				@endif
				<div class="form-group">
					<label class="control-label">Lokasi</label>
					<input class="form-control" type="text" name="kantor_nama" value="{{ $data? $data->kantor_nama: '' }}" required data-parsley-maxlength="250" autocomplete="off" />
				</div>
				<div class="form-group input-group-sm">
					<label class="control-label">Unit</label>
					<select class="form-control selectpicker" data-live-search="true" name="unit_id" data-style="btn-info" data-width="100%">
						@foreach($unit as $unt)
						<option value="{{ $unt->kd_unit }}" 
							@if($data && $data->unit_id == $unt->kd_unit)
								selected
							@endif
						>{{ $unt->nm_unit }}</option>
						@endforeach
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