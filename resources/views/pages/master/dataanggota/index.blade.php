@extends('pages.master.main')

@section('page')
	<li class="breadcrumb-item active">Data Anggota</li>
@endsection

@section('header')
	<h1 class="page-header">Data Anggota</h1>
@endsection

@section('subcontent')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<!-- begin panel-heading -->
		<div class="panel-heading">
			<div class="row">
                <div class="col-md-2 col-lg-6 col-xl-6 col-xs-12 col-sm-12">
            	@role('user|administrator')
                    <div class="form-inline">
		                <a href="/dataanggota/tambah" class="btn btn-primary">Tambah</a>&nbsp;
		                <form id="frm-fingerprint" action="/dataanggota/fingerprint" method="post">
	                		@csrf
		                	<input type="hidden" name="kantor_id" value="{{ $kantor_id }}">
		                </form>&nbsp; 
		                <a href="#" class="btn btn-success" onclick="fingerprint('{{ $kantor_id }}')">Download Fingerprint</a>&nbsp;
		                <a href="/dataanggota/face" class="btn btn-info">Download FaceID</a>
		            </div>
                @endrole
                </div>
                <div class="col-md-10 col-lg-6 col-xl-6 col-xs-12 col-sm-12">
					<form action="/dataanggota" method="GET" id="frm-kantor" class="pull-right">
	                    <div class="form-inline">
	                		@csrf
	                        <div class="form-group">
								<select class="form-control selectpicker" data-live-search="true" id="kantor" name="kantor" data-style="btn-info" data-width="100%">
									@foreach($kantor as $ktr)
									<option value="{{ $ktr->kantor_id }}" 
										@if($kantor_id == $ktr->kantor_id)
											selected
										@endif
									>{{ $ktr->kantor_nama }}</option>
									@endforeach
								</select>
		                    </div>&nbsp;
	                		<div class="input-group ">
								<input type="text" class="form-control" name="cari" placeholder="Pencarian" aria-label="Sizing example input" autocomplete="off"  value="{{ $cari }}">
								<div class="input-group-append">
									<button class="btn input-group-text" >
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
							<th>NIP</th>
							<th>Nama</th>
							<th>Kantor</th>
							<th>Unit</th>
							<th>Jabatan</th>
							<th>Bagian</th>
							<th>Seksi</th>
							<th>Hak Akses</th>
							<th>Fingerprint</th>
							<th>FaceID</th>
							<th width="100"></th>
						</tr>
					</thead>
					<tbody>
					    @foreach ($data as $index => $anggota)
					    <tr>
					        <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
					        <td>{{ $anggota->anggota_nip }}</td>
					        <td>{{ $anggota->nm_pegawai }}</td>
					        <td>{{ $anggota->kantor_nama }}</td>
					        <td>{{ $anggota->nm_unit }}</td>
					        <td>{{ $anggota->nm_jabatan }}</td>
					        <td>{{ $anggota->nm_bagian }}</td>
					        <td>{{ $anggota->nm_seksi }}</td>
					        <td>{{ $anggota->anggota_hak_akses == 14? 'Super Admin': 'User Biasa' }}</td>
					        <td class="text-center">{{ $anggota->fingerprint->count() }}</td>
					        <td class="text-center">0</td>
					        <td class="text-right">
					        	@role('user|administrator')
                            	<a href="javascript:;" onclick="hapus('{{ $anggota->pegawai_id }}')" id='btn-del' class='btn btn-danger btn-xs'><i class='fa fa-trash-alt'></i></a>
	                    		@endrole
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
	<script>
		$("#kantor").change(function() {
		     $("#frm-kantor").submit();
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