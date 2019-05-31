@extends('pages.setup.main')

@section('page')
	<li class="breadcrumb-item active">Data Kantor</li>
@endsection

@section('header')
	<h1 class="page-header">Data Kantor</h1>
@endsection

@section('subcontent')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<!-- begin panel-heading -->
		<div class="panel-heading">
			<div class="row">
                <div class="col-md-6 col-lg-7 col-xl-9 col-xs-12">
                	@role('user|administrator')
                    <div class="form-inline">
                        <a href="/datakantor/tambah" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus"></span>Tambah</a>
                    </div>
                    @endrole
                </div>
                <div class="col-md-6 col-lg-5 col-xl-3 col-xs-12">
                	<form action="/datakantor" method="GET">
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
							<th>Lokasi</th>
							<th>Unit</th>
							<th width="120"></th>
						</tr>
					</thead>
					<tbody>
					    @foreach ($data as $index => $kantor)
					    <tr>
					        <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
					        <td>{{ $kantor->kantor_nama }}</td>
					        <td>{{ $kantor->unit->nm_unit }}</td>
					        <td class="text-right">
					        	@role('user|administrator')
					        	<form action="datakantor/edit" method="get">
					        		@csrf
					        		<input type="hidden" name="id" value="{{ $kantor->kantor_id }}">
					        		<button class='btn btn-grey btn-xs'>
					        			<i class='fa fa-pencil-alt'></i>
					        		</button>
	                            	<a href="javascript:;" onclick="hapus('{{ $kantor->kantor_id }}', '{{ $kantor->kantor_nama }}')" id='btn-del' class='btn btn-danger btn-xs'><i class='fa fa-trash-alt'></i></a>
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
		function hapus(id, lokasi) {
			swal({
				title: 'Apakah anda yakin?',
				text: 'Anda akan menghapus kantor ' + lokasi + '',
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
	          		window.location.href = "/datakantor/hapus/" + id;
		      	}
		    });
		}		
	</script>
@endpush