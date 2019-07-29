@extends('pages.administrator.main')

@push('css')
	<link href="/assets/plugins/parsleyjs/src/parsley.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" />
@endpush

@section('page')
	<li class="breadcrumb-item active">Posting Absensi</li>
@endsection

@section('header')
	<h1 class="page-header">Posting Absensi</h1>
@endsection

@section('subcontent')
	<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
		<!-- begin panel-heading -->
		<form id="form-posting" method="POST">
			@csrf
			<div class="panel-body">
				<div class="form-group">
					<label class="control-label">Tanggal Kehadiran</label>
					<div class="input-group" id="default-daterange">
						<input type="text" name="tanggal" id="tanggal" class="form-control" value="{{ date('01 F Y').' - '.date('d F Y') }}" placeholder="Pilih Tanggal Izin" readonly />
						<span class="input-group-append">
						<span class="input-group-text"><i class="fa fa-calendar"></i></span>
						</span>
					</div>
				</div>
			</div>
			<div class="panel-footer">
	            <input type="submit" id="btn-posting" class="btn btn-sm btn-success" value="Posting" />
	        </div>
		</form>
	</div>
@endsection

@push('scripts')
	<script src="/assets/plugins/parsleyjs/dist/parsley.js"></script>
	<script src="/assets/plugins/bootstrap-daterangepicker/moment.min.js"></script>
	<script src="/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
	<script type="text/javascript">
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

	    	if ($('#btn-posting').val() == 'Posting') {
				$.ajaxSetup({
				    headers: {
				        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    }
				});
	      		$.ajax({
		            url : '/postingabsensi',
		            method : 'POST',
		            data : { "tanggal": $('#tanggal').val() },
		            beforeSend: function(data) {
		                waitingDialog.show('Mohon tunggu...');
		            },
		            success: function(data){
		            	console.log(data);
		                waitingDialog.hide();
		            	swal("Posting", data['pesan'], data['tipe']);
		            },			
		            error: function(jqXHR, textStatus, errorThrown) {
		                if(textStatus==="timeout") {
		                    alert("Call has timed out"); 
		                } else {
		                    alert("Another error was returned"); 
		                }
		            }
		        });
	    	}
	    });
	</script>
@endpush