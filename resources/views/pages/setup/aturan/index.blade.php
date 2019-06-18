@extends('pages.setup.main')

@push('css')
	<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
	<link href="/assets/plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item active">Aturan</li>
@endsection

@section('header')
	<h1 class="page-header">Aturan</h1>
@endsection

@section('subcontent')
	<form action="/aturan" method="post">
		@csrf
		<div class="panel panel-inverse">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6">
						<div class="panel panel-inverse">
							<div class="panel-heading">
								<h4 class="panel-title">Hari Biasa</h4>
							</div>
							<div class="panel-body">
								<div class="form-group">
									<label class="control-label">Jam Masuk</label>
									<div class="input-group date" >
										<input type="text" class="form-control datetimepicker" name="aturan_masuk" value="{{ $data? $data->aturan_masuk: '' }}" />
										<span class="input-group-addon">
										<i class="fa fa-clock"></i>
										</span>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Jam Pulang</label>
									<div class="input-group date" >
										<input type="text" class="form-control datetimepicker" name="aturan_pulang" value="{{ $data? $data->aturan_pulang: '' }}" />
										<span class="input-group-addon">
										<i class="fa fa-clock"></i>
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="panel panel-inverse">
							<div class="panel-heading">
								<h4 class="panel-title">Hari Khusus</h4>
							</div>
							<div class="panel-body">
								<div class="form-group">
									<label class="control-label">Jam Masuk</label>
									<div class="input-group date">
										<input type="text" class="form-control datetimepicker" name="aturan_masuk_khusus" value="{{ $data? $data->aturan_masuk_khusus: '' }}" />
										<span class="input-group-addon">
										<i class="fa fa-clock"></i>
										</span>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Jam Pulang</label>
									<div class="input-group date">
										<input type="text" class="form-control datetimepicker" name="aturan_pulang_khusus" value="{{ $data? $data->aturan_pulang_khusus: '' }}" />
										<span class="input-group-addon">
										<i class="fa fa-clock"></i>
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12 m-l-5">
						<h4>Hari Libur</h4>
					</div>
					<div class="col-md-1">
						&nbsp;
					</div>
					<div class="col-md-1">
						<div class="checkbox checkbox-css">
							<input type="checkbox" id="Senin"  value="1" name="aturan_hari_libur[]" {{ $data && strpos($data->aturan_hari_libur, '2') !== false? 'checked': '' }} />
							<label for="Senin">Senin</label>
						</div>
					</div>
					<div class="col-md-1">
						<div class="checkbox checkbox-css">
							<input type="checkbox" id="Selasa"  value="2" name="aturan_hari_libur[]" {{ $data && strpos($data->aturan_hari_libur, '2') !== false? 'checked': '' }} />
							<label for="Selasa">Selasa</label>
						</div>
					</div>
					<div class="col-md-1">
						<div class="checkbox checkbox-css">
							<input type="checkbox" id="Rabu"  value="3" name="aturan_hari_libur[]" {{ $data && strpos($data->aturan_hari_libur, '3') !== false? 'checked': '' }} />
							<label for="Rabu">Rabu</label>
						</div>
					</div>
					<div class="col-md-1">
						<div class="checkbox checkbox-css">
							<input type="checkbox" id="Kamis"  value="4" name="aturan_hari_libur[]" {{ $data && strpos($data->aturan_hari_libur, '4') !== false? 'checked': '' }} />
							<label for="Kamis">Kamis</label>
						</div>
					</div>
					<div class="col-md-1">
						<div class="checkbox checkbox-css">
							<input type="checkbox" id="Jumat"  value="5" name="aturan_hari_libur[]" {{ $data && strpos($data->aturan_hari_libur, '5') !== false? 'checked': '' }} />
							<label for="Jumat">Jum'at</label>
						</div>
					</div>
					<div class="col-md-1">
						<div class="checkbox checkbox-css">
							<input type="checkbox" id="Sabtu" value="6" name="aturan_hari_libur[]" {{ $data && strpos($data->aturan_hari_libur, '6') !== false? 'checked': '' }} />
							<label for="Sabtu">Sabtu</label>
						</div>
					</div>
					<div class="col-md-1">
						<div class="checkbox checkbox-css">
							<input type="checkbox" id="Minggu" value="7" name="aturan_hari_libur[]" {{ $data && strpos($data->aturan_hari_libur, '7') !== false? 'checked': '' }} />
							<label for="Minggu">Minggu</label>
						</div>
					</div>
				</div>
			</div>
			<div class="panel-footer form-inline">
	            <input type="submit" value="Simpan" class="btn btn-sm btn-success"  />
			</div>
		</div>
	</form>
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
	<script src="/assets/plugins/bootstrap-daterangepicker/moment.min.js"></script>
	<script src="/assets/plugins/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
	<script>
		$('.datetimepicker').datetimepicker({
			format: 'HH:mm:ss'
		});
	</script>
@endpush
