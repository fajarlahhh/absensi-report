@extends('pages.setup.main')

@push('css')
	<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
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
				<div class="table-responsive">
					<table class="table table-hover">
	                    <thead>
							<tr>
								<th>Hari</th>
								<th>Hari Kerja</th>
								<th>Masuk</th>
								<th>Pulang</th>
								<th>Masuk Khusus</th>
								<th>Pulang Khusus</th>
							</tr>
						</thead>
						<tbody>
							@for($i=1;$i <= sizeof($hari); $i++)
						    <tr>
								<td><input type="hidden" name="aturan_hari[]" value="{{ $i }}">{{ $hari[$i-1] }}</td>
								<td>
									@php
										$aturan = $data->first(function($q) use($i){
											return $q->aturan_hari == $i;
										});
									@endphp
									<select class="form-control selectpicker" data-live-search="true" name="aturan_kerja[]" data-style="btn-info" data-width="100%">
										<option value="Masuk" {{ $aturan && $aturan->aturan_kerja == 'Masuk'? 'selected': '' }}>Ya</option>
										<option value="Libur" {{ $aturan && $aturan->aturan_kerja == 'Libur'? 'selected': '' }}>Tidak</option>
									</select>
								</td>
								<td>
									<div class="input-group date" >
										<input type="text" class="form-control datetimepicker" name="aturan_masuk[]" value="{{ $aturan? $aturan->aturan_masuk: '' }}" required/>
										<span class="input-group-addon">
										<i class="fa fa-clock"></i>
										</span>
									</div>
								</td>
								<td>
									<div class="input-group date" >
										<input type="text" class="form-control datetimepicker" name="aturan_pulang[]" value="{{ $aturan? $aturan->aturan_pulang: '' }}" required/>
										<span class="input-group-addon">
										<i class="fa fa-clock"></i>
										</span>
									</div>
								</td>
								<td>
									<div class="input-group date" >
										<input type="text" class="form-control datetimepicker" name="aturan_masuk_khusus[]" value="{{ $aturan? $aturan->aturan_masuk_khusus: '' }}" required/>
										<span class="input-group-addon">
										<i class="fa fa-clock"></i>
										</span>
									</div>
								</td>
								<td>
									<div class="input-group date" >
										<input type="text" class="form-control datetimepicker" name="aturan_pulang_khusus[]" value="{{ $aturan? $aturan->aturan_pulang_khusus: '' }}" required/>
										<span class="input-group-addon">
										<i class="fa fa-clock"></i>
										</span>
									</div>
								</td>
					      	</tr>
					      	@endfor
					    </tbody>
					</table>
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
