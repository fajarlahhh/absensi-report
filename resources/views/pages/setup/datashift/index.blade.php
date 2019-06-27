@extends('pages.setup.main')

@section('page')
	<li class="breadcrumb-item active">Shift</li>
@endsection

@section('header')
	<h1 class="page-header">Shift</h1>
@endsection

@section('subcontent')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<!-- begin panel-heading -->
		<div class="panel-heading">
			<div class="row">
                <div class="col-md-6 col-lg-7 col-xl-9 col-xs-12">
                	@role('user|administrator')
                    <div class="form-inline">
                        <a href="/shift/tambah" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus"></span>Tambah</a>
                    </div>
                    @endrole
                </div>
                <div class="col-md-6 col-lg-5 col-xl-3 col-xs-12">
                	<form action="/shift" method="GET">
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
							<th>Nama Shift</th>
							<th>Jam Masuk</th>
							<th>Jam Pulang</th>
							<th width="100"></th>
						</tr>
					</thead>
					<tbody>
					    @foreach ($data as $index => $mesin)
					    <tr>
					        <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
					        <td>{{ $mesin->shift_nama }}</td>
					        <td>{{ $mesin->shift_jam_masuk }}</td>
					        <td>{{ $mesin->shift_jam_pulang }}</td>
					        <td class="text-right">
					        	@role('user|administrator')
					        	<form action="shift/edit" method="get">
					        		@csrf
					        		<input type="hidden" name="id" value="{{ $mesin->shift_id }}">
					        		<button class='btn btn-grey btn-xs'>
					        			<i class='fa fa-pencil-alt'></i>
					        		</button>
	                            	<a href="javascript:;" onclick="hapus('{{ $mesin->shift_id }}, {{ $mesin->shift_nama }}')" id='btn-del' class='btn btn-danger btn-xs'><i class='fa fa-trash-alt'></i></a>
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
		function hapus(id, nama) {
			swal({
				title: 'Apakah anda yakin?',
				text: 'Anda akan menghapus shift : ' + nama + '',
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
	          		window.location.href = "/shift/hapus/" + id;
		      	}
		    });
		}		
	</script>
@endpush