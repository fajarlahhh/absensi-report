@extends('pages.laporan.main')

@push('css')
	<link href="/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item active">Rekap Absensi</li>
@endsection

@section('header')
	<h1 class="page-header">Rekap Absensi</h1>
@endsection

@section('subcontent')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<!-- begin panel-heading -->
		<div class="panel-heading">
			<div class="row">
                <div class="col-md-12 col-lg-4 col-xl-4 col-xs-12">
	            	<form action="/rekapabsensi/pdf" method="GET" target="_blank">
                		<input type="hidden" name="bag" value="{{ $bag }}">
                		<input type="hidden" name="tgl" value="{{ $tgl }}">
                		<input type="submit" class="btn btn-warning" value="Cetak">
                	</form>
                </div>
                <div class="col-md-12 col-lg-8 col-xl-8 col-xs-12">
	            	<form id="frm-cari" action="/rekapabsensi" method="GET">
	            		@csrf
	                	<div class="form-inline pull-right">
	                		<div class="form-group">
								<select class="form-control selectpicker" onchange="submit()" data-live-search="true" id="bag" name="bag"  data-width="100%">
									@foreach($bagian as $bg)
									<option value="{{ $bg->kd_bagian }}" 
										@if($bg->kd_bagian == $bag)
											selected
										@endif
									>{{ $bg->nm_bagian }}</option>
									@endforeach
								</select>
		                    </div>&nbsp;
							<div class="input-group" id="default-daterange">
								<input type="text" name="tgl" class="form-control" value="{{ $tgl }}" placeholder="Pilih Tanggal Izin" readonly onchange="submit()" />
								<span class="input-group-append">
								<span class="input-group-text"><i class="fa fa-calendar"></i></span>
								</span>
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
							<th>HK</th>
							<th>TK</th>
							<th>TL</th>
							<th>I</th>
							<th>S</th>
							<th>C</th>
							<th>TD</th>
							<th>Jml. Kehadirah</th>
							<th>% Kehadiran</th>
						</tr>
					</thead>
					<tbody>
					    @foreach($absensi as $index => $absen)
					    <tr>
					        <td>{{ $absen->pegawai->nip }}</td>
					        <td>{{ $absen->pegawai->nm_pegawai }}</td>
							<td class="text-right">{{ sizeof($absen->absen) > 0? $absen->absen[0]->hari: '0' }}</td>
							<td class="text-right">{{ sizeof($absen->absen) > 0? $absen->absen[0]->tanpaketerangan: '0' }}</td>
							<td class="text-right">{{ sizeof($absen->absen) > 0? $absen->absen[0]->telat: '0' }}</td>
							<td class="text-right">{{ sizeof($absen->absen) > 0? $absen->absen[0]->izin: '0' }}</td>
							<td class="text-right">{{ sizeof($absen->absen) > 0? $absen->absen[0]->sakit: '0' }}</td>
							<td class="text-right">{{ sizeof($absen->absen) > 0? $absen->absen[0]->cuti: '0' }}</td>
							<td class="text-right">{{ sizeof($absen->absen) > 0? $absen->absen[0]->dinas: '0' }}</td>
							<td class="text-right">{{ sizeof($absen->absen) > 0? $absen->absen[0]->masuk: '0' }}</td>
							<td class="text-right">{{ sizeof($absen->absen) > 0? number_format($absen->absen[0]->masuk/$absen->absen[0]->hari * 100, 2): '0' }}</td>
				      	</tr>
					    @endforeach
				    </tbody>
				</table>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	<script src="/assets/plugins/bootstrap-daterangepicker/moment.min.js"></script>	
	<script src="/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
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

		$('#default-daterange').daterangepicker({
			opens: 'right',
			format: 'DD MMMM YYYY',
			separator: ' s/d ',
			startDate: moment('{{ date('Y-m-d') }}'),
			endDate: moment('{{ date('Y-m-d') }}'),
	    	dateLimit: { days: 30 },
		}, function (start, end) {
			$('#default-daterange input').val(start.format('DD MMMM YYYY') + ' - ' + end.format('DD MMMM YYYY'));
		});

		$('#default-daterange').on('apply.daterangepicker', function(ev, picker) {
			$("#frm-cari").submit();
		});
	</script>
@endpush