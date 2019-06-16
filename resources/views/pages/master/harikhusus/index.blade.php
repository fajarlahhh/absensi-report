@extends('pages.absensi.main')

@push('css')
	<link href="/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item active">Hari Khusus</li>
@endsection

@section('header')
	<h1 class="page-header">Hari Khusus</h1>
@endsection

@section('subcontent')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<!-- begin panel-heading -->
		<div class="panel-heading">
			<div class="row">
                <div class="col-md-12 col-lg-3 col-xl-3 col-xs-12">
                	@role('user|administrator')
                    <div class="form-inline">
                        <a href="/harikhusus/tambah" class="btn btn-primary">Tambah</a>&nbsp;
                    </div>
                    @endrole
                </div>
                <div class="col-md-12 col-lg-9 col-xl-9 col-xs-12">
                	<form id="frm-cari" action="/harikhusus" method="GET">
                		@csrf
	                	<div class="form-inline pull-right">
							<div class="form-group">
								<select class="form-control selectpicker cari" data-live-search="true" name="tahun" data-style="btn-warning" data-width="100%" onchange="getnip()">
									@for($i = 2019; $i <= date('Y'); $i++)
									<option value="{{ $i }}" {{ $i == $tahun? 'selected': '' }}>{{ $i }}</option>
									@endfor
								</select>
							</div>&nbsp;
		                	<div class="input-group">
								<input type="text" class="form-control" name="cari" placeholder="Pencarian" aria-label="Sizing example input" autocomplete="off" aria-describedby="inputGroup-sizing-sm" value="{{ $cari }}">
								<div class="input-group-append">
									<button class="btn input-group-text" id="inputGroup-sizing-sm">
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
							<th>Tanggal</th>
							<th>Keterangan</th>
							<th>Operator</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					    @foreach ($data as $index => $khusus)
					    <tr>
					    	@php
					    		$tgl = date('d M Y', strtotime($khusus->tgl_khusus_waktu));
					    	@endphp
					        <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
					        <td>{{ $tgl }}</td>
					        <td>{{ $khusus->tgl_khusus_keterangan }}</td>
					        <td>{{ $khusus->operator }}</td>
					        <td class="text-right">
					        	<a href='javascript:;' onclick="hapus('{{ $tgl }}')" id='btn-del' class='btn btn-danger btn-xs'><i class='fa fa-trash-alt'></i></a>
					    	</td>
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
	<script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script>
		$(".cari").change(function() {
		     $("#frm-cari").submit();
		});

		$('#datepicker1').datepicker({
			todayHighlight: true,
			format: 'dd MM yyyy',
			autoclose: true
		});

		$('#datepicker2').datepicker({
			todayHighlight: true,
			format: 'dd MM yyyy',
			autoclose: true
		});

		function hapus(id) {
			swal({
				title: 'Apakah anda yakin?',
				text: 'Anda akan menghapus hari khusus ' + id + '',
				icon: 'warning',
				buttons: {
					cancel: {
						text: 'Batal',
						value: null,
						visible: true,
						className: 'btn btn-default',
						closeModal: true,
					},
					confirm: {
						text: 'Ya',
						value: true,
						visible: true,
						className: 'btn btn-danger',
						closeModal: true
					}
				}
			}).then(function(isConfirm) {
		      	if (isConfirm) {
	          		window.location.href = "/harikhusus/hapus/" + id;
		      	}
		    });
		}
	</script>
@endpush