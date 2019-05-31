@extends('pages.setup.main')

@push('css')
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item">Data Kehadiran</li>
	<li class="breadcrumb-item active">Download</li>
@endsection

@section('header')
	<h1 class="page-header">Data Kehadiran <small>Download</small></h1>
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
		<form action="/datakehadiran/download" method="post" data-parsley-validate="true" data-parsley-errors-messages-disabled="">
			@csrf
			<div class="panel-body">
				<div class="form-group input-group-sm">
					<label class="control-label">Lokasi</label>
					<select class="form-control selectpicker" data-live-search="true" name="mesin_id" data-style="btn-info" data-width="100%">
						@foreach($mesin as $msn)
						<option value="{{ $msn->mesin_id }}">{{ $msn->mesin_lokasi." - ".$msn->kantor->kantor_nama }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="panel-footer">
	            <input type="submit" value="Download" class="btn btn-sm btn-success"  />
	            <a href="/datakehadiran" class="btn btn-sm btn-danger">Batal</a>
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