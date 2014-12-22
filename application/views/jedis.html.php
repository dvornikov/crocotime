<html>
<head>
	<!-- В Production в конечно так не ок -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	<script type="text/javascript" charset="utf8" src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<script src="/js/jquery.typing-0.2.0.min.js"></script>
	<script src="/js/jquery.tmpl.min.js"></script>
	<title>Jedis</title>
</head>
<body>
	<div class="container-fluid">
		<input type="hidden" name="sortby">
		<table class="table">
			<thead>
				<tr>
				<th><input type="checkbox" name="select_all"></th>
				<th><a href="#" class="js-sort" data-sortby="name">Имя джедая</a></th>
				<th><a href="#" class="js-sort" data-sortby="strain">Раса</a></th>
				<th><a href="#" class="js-sort" data-sortby="rank">Ранг</a></th>
			</tr>
			</thead>
			<tbody>
				<tr>
					<th></th>
					<th><input name="filter_name"></th>
					<th><input name="filter_strain"></th>
					<th><input name="filter_rank"></th>
				</tr>
				<?php foreach ($jedis as $jedi): ?>
					<tr>
						<td>
							<input type="checkbox" value="<?php echo $jedi['id']; ?>" name="selected[]">
						</td>
						<td><?php echo $jedi['name']; ?></td>
						<td><?php echo $jedi['strain']; ?></td>
						<td><?php echo $jedi['rank']; ?></td>
					</tr>
				<?php endforeach ?>
			</tbody>

		</table>
		<button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#myModal">Добавить</button>
		<button type="button" class="btn btn-danger btn-lg btn-block js-delete-jedi" disabled="disabled">Удалить</button>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">Добавление джедая</h4>
				</div>
				<div class="modal-body">
					<?php echo Form::open('welcome/create', array('method' => 'post', 'class' => 'js-add-jedi-form')); ?>
					<!-- <form role="form" class="js-add-jedi-form" action="/welcome/create"> -->
						<div class="form-group">
							<label for="field-name">Имя</label>
							<input type="text" class="form-control" id="field-name" placeholder="" name="name">
						</div>
						<div class="form-group">
							<label for="field-strain">Раса</label>
							<input type="text" class="form-control" id="field-strain" placeholder="" name="strain">
						</div>
						<div class="form-group">
							<label for="field-rank">Ранг</label>
							<input type="text" class="form-control" id="field-rank" placeholder="" name="rank">
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
					<button type="button" class="btn btn-primary js-add-jedi-submit">Добавить</button>
				</div>
			</div>
		</div>
	</div>


<script id="jediTemplate" type="text/x-jquery-tmpl">
	<tr>
		<td>
			<input type="checkbox" value="${id}" name="selected[]">
		</td>
		<td>${name}</td>
		<td>${strain}</td>
		<td>${rank}</td>
	</tr>
</script>

	<script type="text/javascript">
		$(document).ready(function () {
			filter();
			
		    $('.js-add-jedi-submit').click(function(e){
		    	e.preventDefault();
		    	$('.js-add-jedi-form').submit();
		    });

		    $('.js-add-jedi-form').submit(function(e) {
		    	e.preventDefault();

		    	$.ajax({
		    		type: "POST",
		    		data: $(this).serialize(),
		    		url: $(this).attr('action'),
		    		complete: function() {
		    			$('#myModal').modal('toggle');
		    			filter();
		    		}
		    	});
		    });

		    $('.js-delete-jedi').click(function(e){
		    	e.preventDefault();

		    	var selected = $(':checked[name^=selected]');
		    	var $this = $(this);

		    	$.ajax({
		    		type: "POST",
		    		data: selected,
		    		url: '<?php echo URl::site('welcome/delete'); ?>',
		    		complete: function() {
						selected.parents('tr').remove();
						$this.attr('disabled','disabled');
					}
		    	});
		    });

		    $('.js-sort').click(function(e){
		    	e.preventDefault();
		    	$('[name=sortby]').val($(this).data('sortby'));
		    	filter();
		    });


		    if (!$(':checked[name^=selected]').length) {
		    	$('.js-delete-jedi').attr('disabled','disabled');
		    	$('[name^=select_all]').prop('checked', false);
		    } else {
		    	$('.js-delete-jedi').removeAttr('disabled');
		    	$('[name^=select_all]').prop('checked', true);
		    }

		    if ($(':checked[name^=selected]').length == $('[name^=selected]').length) {
		    	$('[name^=select_all]').prop('checked', true);
		    } else {
		    	$('[name^=select_all]').prop('checked', false);
		    }

		    $(document).on('click', '[name^=selected]', function(e){ // Не DRY :(
		    	if (!$(':checked[name^=selected]').length) { 
			    	$('.js-delete-jedi').attr('disabled','disabled');
			    } else {
			    	$('.js-delete-jedi').removeAttr('disabled');
			    }

			    if ($(':checked[name^=selected]').length == $('[name^=selected]').length) {
			    	$('[name^=select_all]').prop('checked', true);
			    } else {
			    	$('[name^=select_all]').prop('checked', false);
			    }
		    });

		    $('[name^=select_all]').click(function(e){
		    	if ($(':checked[name^=selected]').length < $('[name^=selected]').length) { 
			    	$('[name^=selected]').prop('checked', true);
			    	$('.js-delete-jedi').removeAttr('disabled');
			    } else {
			    	$('[name^=selected]').prop('checked', false);
			    	$('.js-delete-jedi').attr('disabled','disabled');
			    }
		    });

		    $('table :text').typing({
				stop: function (event, $elem) {
					filter();
				},
				delay: 1000
			});
		});

function filter() {
	console.log('test');
	var url = '<?php echo URL::site("welcome/index"); ?>';
	var params = {};


	var filter_name = $('table input[name=filter_name]').val();
	if (filter_name) {
		params.name = filter_name;
	}
	
	var filter_strain = $('table input[name=filter_strain]').val();
	if (filter_strain) {
		params.strain = filter_strain;
	}

	var filter_rank = $('table input[name=filter_rank]').val();
	if (filter_rank) {
		params.rank = filter_rank;
	}

	if ($('[name=sortby]').val()) {
		params.sortby = $('[name=sortby]').val();
	}

	if (Object.keys(params).length) {
		url += '?' + $.param(params);
	}

	$.ajax({
		url: url,
		dataType: 'json',
		success : function(json) {
			$('table tbody tr:gt(0)').remove();
			$('#jediTemplate').tmpl(json).appendTo('table tbody');
		}
	});
}
	</script>
</body>
</html>