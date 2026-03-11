<div class="col-lg-4 col-md-6">
	<div class="panel panel-default" id="munkireport-warnings-widget">
		<div class="panel-heading" data-container="body">
			<div class="panel-title"><i class="fa fa-warning"></i>
				<span data-i18n="munkireport.warnings"></span>
				<list-link data-url="/show/listing/munkireport/munki#warnings"></list-link>
			</div>
		</div>
		<div class="list-group scroll-box">
			<span class="list-group-item"><span data-i18n="machine.new_clients.no_new_clients"></span></span>
		</div>
	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(){
	$.getJSON( appUrl + '/module/munkireport/get_warnings/10', function( data ) {

		var scrollBox = $('#munkireport-warnings-widget .scroll-box').empty();

		$.each(data, function(index, obj){
			warning_json = JSON.parse(obj.warning_json)
			scrollBox
				.append($('<a>')
					.addClass('list-group-item')
					.attr('href', appUrl + '/show/listing/munkireport/munki#munkireport.warnings')
					.append($('<span>')
						.addClass('badge pull-right')
						.text(obj.count))
					.append(warning_json)
					)
		});

		if( ! data.length){
			scrollBox
				.append($('<span>')
					.addClass('list-group-item')
					.text(i18n.t('no_warnings')))
		}
	});
});
</script>
