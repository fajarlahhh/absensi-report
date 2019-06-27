@extends('pages.administrator.main')

@section('page')
	<li class="breadcrumb-item active">Shift Karyawan</li>
@endsection

@section('header')
	<h1 class="page-header">Shift Karyawan</h1>
@endsection

@section('subcontent')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<!-- begin panel-heading -->
		<div class="panel-heading">
			<div class="row">
                <div class="col-md-12 col-lg-3 col-xl-3 col-xs-12">
                	@role('user|administrator')
                    <div class="form-inline">
                        <a href="/shiftkaryawan/tambah" class="btn btn-primary">Tambah</a>
                    </div>
                    @endrole
                </div>
                <div class="col-md-12 col-lg-9 col-xl-9 col-xs-12">
                	<form id="frm-cari" action="/shiftkaryawan" method="GET">
                		@csrf
	                	<div class="form-inline pull-right">
	                		<div class="form-group">
								<select class="form-control selectpicker cari" data-live-search="true" id="shift" name="shift" data-style="btn-info" data-width="100%">
									@foreach($datashift as $shf)
									<option value="{{ $shf->shift_id }}" 
										@if($shift == $shf->shift_id)
											selected
										@endif
									>{{ $shf->shift_nama }}</option>
									@endforeach
								</select>
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
							<th>ID</th>
							<th>NIP</th>
							<th>Nama</th>
							<th>Unit</th>
							<th>Jabatan</th>
							<th>Bagian</th>
							<th>Seksi</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						@php $no = 0;
						@endphp
					    @foreach ($data as $index => $sk)
					    <tr>
					        <td>{{ $no+1 }}</td>
					        <td>{{ $sk->anggota->pegawai_id }}</td>
					        <td>{{ $sk->anggota->anggota_nip }}</td>
					        <td>{{ $sk->anggota->pegawai->nm_pegawai }}</td>
					        <td>{{ $sk->anggota->pegawai->unit->nm_unit }}</td>
					        <td>{{ $sk->anggota->pegawai->jabatan->nm_jabatan }}</td>
					        <td>{{ $sk->anggota->pegawai->bagian->nm_bagian }}</td>
					        <td>{{ $sk->anggota->pegawai->seksi->nm_seksi }}</td>
					        <td class="text-right"><a href='javascript:;' onclick="hapus({{ $sk->angota_id }})" id='btn-del' class='btn btn-danger btn-xs'><i class='fa fa-trash-alt'></i></a></td>
				      	</tr>
						@php $no++;
						@endphp
					    @endforeach
				    </tbody>
				</table>
			</div>
		</div>
		<div class="panel-footer form-inline">
            <div class="col-md-6 col-lg-10 col-xl-10 col-xs-12">
				&nbsp;
			</div>
			<div class="col-md-6 col-lg-2 col-xl-2 col-xs-12">
				<label class="pull-right">Jumlah Data : {{ $no }}</label>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	<script>
		$(".cari").change(function() {
		     $("#frm-cari").submit();
		});
		
		function hapus(id) {
			swal({
				title: 'Apakah anda yakin?',
				text: 'Anda akan menghapus shift karyawan ' + id + '',
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
	          		window.location.href = "/shiftkaryawan/hapus/" + id;
		      	}
		    });
		}
	</script>
@endpush