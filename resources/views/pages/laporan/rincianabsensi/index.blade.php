@extends('pages.laporan.main')

@push('css')
	<link href="/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item active">Rincian Kehadiran</li>
@endsection

@section('header')
	<h1 class="page-header">Rincian Kehadiran</h1>
@endsection

@section('subcontent')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<!-- begin panel-heading -->
		<div class="panel-heading">
			<div class="row">
                <div class="col-md-12 col-lg-3 col-xl-3 col-xs-12">
                	@role('user|administrator')
                	<a href="#" class="btn btn-warning" onclick="cetak()">Cetak</a>
                    @endrole
                </div>
                <div class="col-md-12 col-lg-9 col-xl-9 col-xs-12">
	            	<form id="frm-cari" action="/rinciankehadiran" method="GET">
	            		@csrf
	                	<div class="form-inline pull-right">
							<div class="form-group">
								<input type="text" readonly class="form-control cari" id="datepicker1" name="tgl1" placeholder="Tgl. Mulai" value="{{ date('d M Y', strtotime($tgl1)) }}"/>
							</div>
		                    &nbsp;s/d&nbsp;
							<div class="form-group">
								<input type="text" readonly class="form-control cari" id="datepicker2" name="tgl2" placeholder="Tgl. Akhir" value="{{ date('d M Y', strtotime($tgl2)) }}" data-date-end-date="0d"/>
		                    </div>
	                	</div>
					</form>
                </div>
			</div>
		</div>
		<div class="panel-body">
			<div class="table-responsive" >
				<table class="table table-bordered" id="laporan">
                    <thead>
						<tr>
							<th>NIP</th>
							<th width="300">Nama</th>
							@for($i=0; $i <= $diff; $i++)
							<th width="100">{{ date('d M Y', strtotime($tgl1. ' + '.$i.' days')) }}</th>
							@endfor
						</tr>
					</thead>
					<tbody>
					    @for($i = 0; $i < count($absensi); $i++)
					    <tr>
					        <td>{{ $absensi[$i][0] }}</td>
					        <td>{{ $absensi[$i][1] }}</td>
							@for($j=2; $j <= $diff + 2; $j++)
							<td class="text-center {{ $absensi[$i][$j]? ((int)str_replace(':','',$absensi[$i][$j]) < (int)str_replace(':','',$aturan->aturan_masuk)? '': 'bg-orange-transparent-3'): 'bg-red-transparent-3' }}">{{ $absensi[$i][$j] }}</td>
							@endfor
				      	</tr>
					    @endfor
				    </tbody>
				</table>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	<script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="/assets/plugins/print-this/printThis.js"></script>
	<script>
		function cetak(){
			$("#laporan").printThis({
				importCSS: true,
				importStyle: true,
				removeInline: false,
			 	copyTagClasses: true
			});
		}

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

		function fingerprint(id) {
			swal({
				title: 'Download fingerprint',
				text: 'Anda akan menimpa data fingerprint di kantor ini?',
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
		     		$("#frm-fingerprint").submit();
		      	}
		    });
		}	

		function hapus(id) {
			swal({
				title: 'Apakah anda yakin?',
				text: 'Anda akan menghapus anggota dengan NIP : ' + id + '',
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
	          		window.location.href = "/dataanggota/hapus/" + id;
		      	}
		    });
		}
	</script>
@endpush