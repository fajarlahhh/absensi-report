@extends('pages.absensi.main')

@push('css')
	<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
	<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item active">Download Kehadiran</li>
@endsection

@section('header')
	<h1 class="page-header">Download Kehadiran</h1>
@endsection

@section('subcontent')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<!-- begin panel-heading -->
		<div class="panel-heading">
			<div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            </div>
			<h4 class="panel-title">Form</h4>
		</div>
		<form id="form-posting" method="POST">
			@csrf
			<div class="panel-body">
				<div class="form-group input-group-sm">
					<label class="control-label">Kantor</label>
					<select class="form-control selectpicker" data-live-search="true" name="kantor_id" id="kantor_id" data-style="btn-info" data-width="100%">
						@foreach($kantor as $ktr)
						<option value="{{ $ktr->kantor_id }}">{{ $ktr->kantor_nama }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="panel-footer">
	            <input type="submit" id="btn-posting" class="btn btn-sm btn-success" value="Download" />
	        </div>
		</form>
	</div>	
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

	<script type="text/javascript">
	    var waitingDialog = waitingDialog || (function ($) {
	        'use strict';

	  		var $dialog = $(
	        '<div class="modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
	        	'<div class="modal-dialog modal-m">' +
	        		'<div class="modal-content">' +
	          			'<div class="modal-header"><h3 style="margin:0;"></h3></div>' +
	          			'<div class="modal-body">' +
	            			'<center><img src="/assets/img/loading.svg" width="200"></center>' +
						'</div>' +
					'</div>' +
				'</div>' +
	        '</div>');

	  		return {
		        show: function (message, options) {
		          	if (typeof options === 'undefined') {
		            	options = {};
		          	}
		          	if (typeof message === 'undefined') {
		            	message = 'Loading';
		          	}
		          	var settings = $.extend({
			            dialogSize: 'm',
			            progressType: '',
			            onHide: null 
		          	}, options);

		          	$dialog.find('.modal-dialog').attr('class', 'modal-dialog').addClass('modal-' + settings.dialogSize);
		          	$dialog.find('.progress-bar').attr('class', 'progress-bar');
		          	if (settings.progressType) {
		            	$dialog.find('.progress-bar').addClass('progress-bar-' + settings.progressType);
		          	}
		          	$dialog.find('h3').text(message);
		          	if (typeof settings.onHide === 'function') {
		            	$dialog.off('hidden.bs.modal').on('hidden.bs.modal', function (e) {
		              		settings.onHide.call($dialog);
		            	});
		          	}
		          	$dialog.modal('show');
		        },
		        hide: function () {
					$dialog.modal('hide');
		        }
		    };

	    })(jQuery);

	    $('#form-posting').on('submit', function(e){
	    	e.preventDefault();

	    	if ($('#btn-posting').val() == 'Download') {
				$.ajaxSetup({
				    headers: {
				        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    }
				});
	      		$.ajax({
		            url : '/downloadkehadiran',
		            method : 'POST',
		            data : { "kantor_id": $('#kantor_id').val() },
		            beforeSend: function(data) {
		                waitingDialog.show('Mohon tunggu...');
		            },
		            success: function(data){
		                waitingDialog.hide();
		            	swal("Posting", data['pesan'], data['tipe']);
		            },
		            error: function(jqXHR, textStatus, errorThrown) {
		                waitingDialog.hide();
		            	swal("Posting", jqXHR.responseText, 'error');
		            }
		        });
	    	}
	    });
	</script>
@endpush