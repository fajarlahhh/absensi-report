@extends('pages.setup.main')

@push('css')
	<link href="/assets/plugins/password-indicator/css/password-indicator.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item">Data Jenis Izin</li>
	<li class="breadcrumb-item active">{{ $aksi }} Data</li>
@endsection

@section('header')
	<h1 class="page-header">Data Jenis Izin <small>{{ $aksi }} Data</small></h1>
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
		<form action="/jenisizin/{{ strtolower($aksi) }}" method="post" data-parsley-validate="true" data-parsley-errors-messages-disabled="">
			@csrf
			<div class="panel-body">
				<input type="hidden" name="redirect" value="{{ url()->previous() }}">
				<div class="form-group">
					<label class="control-label">Keterangan</label>
					<input class="form-control" type="text" name="jenis_izin_keterangan" value="{{ $data? $data->jenis_izin_keterangan: '' }}" required data-parsley-maxlength="250" autocomplete="off" />
				</div>
				<div class="form-group">
					<label class="control-label">% Transport</label>
					<input class="form-control numbering" type="text" name="persen_transport" value="{{ $data? $data->persen_transport: '' }}" required data-parsley-maxlength="5" autocomplete="off" />
				</div>
				<div class="form-group">
					<label class="control-label">% Kinerja</label>
					<input class="form-control numbering" type="text" name="persen_kinerja" value="{{ $data? $data->persen_kinerja: '' }}" required data-parsley-maxlength="5" autocomplete="off" />
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
	<script src="/assets/plugins/autonumeric/autoNumeric.js"></script> 
	<script type="text/javascript">		
    	AutoNumeric.multiple('.numbering', {
    		modifyValueOnWheel : false
    	});
	</script>
@endpush