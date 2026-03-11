<div class="col-lg-4 col-md-6">
	<div class="card" id="manifests-widget">
		<div class="card-header">
			<i class="fa fa-book"></i>
            <span data-i18n="munkireport.manifest.title"></span>
            <a href="/show/listing/munkireport/munki" class="pull-right"><i class="fa fa-list"></i></a>
		</div>
		<div class="list-group scroll-box"></div>
	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(){

	$.getJSON( appUrl + '/module/munkireport/get_manifest_stats', function( data ) {

        var list = $('#manifests-widget div.scroll-box').empty();

        if(data.length){

			// Sort on manifestname
			data.sort(function(a,b){
				return mr.naturalSort(a.manifestname, b.manifestname);
			});

            $.each(data, function(i,d){
                var badge = '<span class="badge pull-right">'+d.count+'</span>';
                list.append('<a href="'+appUrl+'/show/listing/munkireport/munki/#'+d.manifestname+'" class="list-group-item">'+d.manifestname+badge+'</a>')
            });
        }
        else{
            list.append('<span class="list-group-item">'+i18n.t('no_clients')+'</span>');
        }

    });
});
</script>
