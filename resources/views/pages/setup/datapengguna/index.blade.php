@extends('pages.setup.main')

@section('page')
	<li class="breadcrumb-item active">Data Pengguna</li>
@endsection

@section('header')
	<h1 class="page-header">Data Pengguna</h1>
@endsection

@section('subcontent')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<!-- begin panel-heading -->
		<div class="panel-heading">
			<div class="row">
                <div class="col-md-6 col-lg-7 col-xl-9 col-xs-12">
                	@role('user|administrator')
                    <div class="form-inline">
                        <a href="/datapengguna/tambah" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus"></span>Tambah</a>
                    </div>
                    @endrole
                </div>
                <div class="col-md-6 col-lg-5 col-xl-3 col-xs-12">
                	<form action="/datapengguna" method="GET">
	                	<div class="input-group input-group-sm">
	                		@csrf
							<input type="text" class="form-control" name="cari" placeholder="Pencarian" aria-label="Sizing example input" autocomplete="off" aria-describedby="inputGroup-sizing-sm" value="{{ $cari }}">
							<div class="input-group-append">
								<button class="btn input-group-text" id="inputGroup-sizing-sm">
									<i class="fa fa-search"></i>
								</button>
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
							<th>Unit</th>
							<th>Jabatan</th>
							<th>Bagian</th>
							<th>Seksi</th>
							<th>Level</th>
							<th width="100"></th>
						</tr>
					</thead>
					<tbody>
					    @foreach ($data as $index => $pengguna)
					    <tr>
					        <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
					        <td>{{ $pengguna->pengguna_nip }}</td>
					        <td>{{ $pengguna->nm_pegawai }}</td>
					        <td>{{ $pengguna->nm_unit }}</td>
					        <td>{{ $pengguna->nm_jabatan }}</td>
					        <td>{{ $pengguna->nm_bagian }}</td>
					        <td>{{ $pengguna->nm_seksi }}</td>
					        <td>{{ ucFirst($pengguna->getRoleNames()[0]) }}</td>
					        <td class="text-right">
					        	@role('user|administrator')
					        	<form action="datapengguna/edit" method="get">
					        		@csrf
					        		<input type="hidden" name="id" value="{{ $pengguna->pengguna_nip }}">
					        		<button class='btn btn-grey btn-xs'>
					        			<i class='fa fa-pencil-alt'></i>
					        		</button>
						        	@if(!in_array($pengguna->pengguna_nip, config('admin.nip')))
		                            <a href="javascript:;" onclick="hapus('{{ $pengguna->anggota_id }}')" id='btn-del' class='btn btn-danger btn-xs'><i class='fa fa-trash-alt'></i></a>
		                            @endif
					        	</form>
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
		function hapus(id) {
			swal({
				title: 'Apakah anda yakin?',
				text: 'Anda akan menghapus pengguna dengan NIP : ' + id + '',
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
	          		window.location.href = "/datapengguna/hapus/" + id;
		      	}
		    });
		}		
	</script>
@endpush