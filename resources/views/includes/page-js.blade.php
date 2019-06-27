<script src="/assets/js/bundle.js"></script>
<script src="/assets/js/theme/default.js"></script>
<script src="/assets/js/apps.min.js"></script>
<script src="/assets/plugins/sweetalert/sweetalert.min.js"></script>

<script>
	$(document).ready(function() {
		App.init();

		@if(Session::get('pesan'))
            swal("{{ Session::get('judul') }}", "{{ Session::get('pesan') }}", "{{ Session::get('tipe') }}");
        @endif
	});
</script>

@stack('scripts')