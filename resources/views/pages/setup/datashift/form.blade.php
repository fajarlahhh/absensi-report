@extends('pages.setup.main')

@push('css')
	<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
	<link href="/assets/plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item">Shift</li>
	<li class="breadcrumb-item active">{{ $aksi }} Data</li>
@endsection

@section('header')
	<h1 class="page-header">Shift <small>{{ $aksi }} Data</small></h1>
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
		<form action="/shift/{{ strtolower($aksi) }}" method="post" data-parsley-validate="true" data-parsley-errors-messages-disabled="">
			@csrf
			<div class="panel-body">
				<input type="hidden" name="redirect" value="{{ url()->previous() }}">
				@if($data)
				<div class="form-group">
					<label class="control-label">ID</label>
					<input class="form-control" type="text" name="shift_id" value="{{ $data? $data->shift_id: '' }}" required readonly />
				</div>
				@endif
				<div class="form-group">
					<label class="control-label">Nama Shift</label>
					<input class="form-control" type="text" name="shift_nama" value="{{ $data? $data->shift_nama: '' }}" required data-parsley-maxlength="250" autocomplete="off" />
				</div>
				<div class="form-group">
					<label class="control-label">Jam Masuk</label>
					<div class="input-group date">
						<input type="text" class="form-control datetimepicker" name="shift_jam_masuk" value="{{ $data? $data->shift_jam_masuk: '' }}" required/>
						<span class="input-group-addon">
						<i class="fa fa-clock"></i>
						</span>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label">Jam Masuk</label>
					<div class="input-group date">
						<input type="text" class="form-control datetimepicker" name="shift_jam_pulang" value="{{ $data? $data->shift_jam_pulang: '' }}" required/>
						<span class="input-group-addon">
						<i class="fa fa-clock"></i>
						</span>
					</div>
				</div>
			</div>
			<div class="panel-footer">
	            <input type="submit" value="Simpan" class="btn btn-sm btn-success"  />
	            <a href="{{ $kembali }}" class="btn btn-sm btn-danger">Batal</a>
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
	<script src="/assets/plugins/bootstrap-daterangepicker/moment.min.js"></script>
	<script src="/assets/plugins/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
	<script>
		$('.datetimepicker').datetimepicker({
			format: 'HH:mm:ss'
		});
	</script>
@endpush