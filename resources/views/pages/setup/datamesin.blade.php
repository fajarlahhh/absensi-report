@extends('pages.setup.index')

@section('page')
	<li class="breadcrumb-item active">Data Mesin</li>
@endsection

@section('header')
	<h1 class="page-header">Data Mesin</h1>
@endsection

@section('subcontent')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<!-- begin panel-heading -->
		<div class="panel-heading">
			<div class="row">
                <div class="col-md-8 col-lg-9 col-xs-12">
                    <div class="form-inline">
                        <a href="https://gudang.pdamgirimenang.com/pengadaan/tambah.html" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus"></span>Tambah</a>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3 col-xs-12">
                	<div class="input-group input-group-sm">
						<input type="text" class="form-control" placeholder="Pencarian" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
						<div class="input-group-append">
							<span class="input-group-text" id="inputGroup-sizing-sm"><i class="fa fa-search"></i></span>
						</div>
					</div>
                </div>
            </div>

		</div>
		<div class="panel-body">
			
		</div>
	</div>
@endsection
