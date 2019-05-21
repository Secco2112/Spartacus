$(document).ready(function() {

	handle_flash_messages();
	js_tree_menus();

	users_form();
});



function handle_flash_messages() {
	var success = $("meta[name='message-success']"),
		error = $("meta[name='message-error']"),
		warning = $("meta[name='message-warning']"),
		message = "",
		title = "",
		params = {};

	if(success.length > 0) {
		title = "Sucesso";
		message = success.attr("content");
		fname = "success";
	} else if(error.length > 0) {
		title = "Erro";
		message = error.attr("content");
		fname = "error";
	} else if(warning.length > 0) {
		title = "Cuidado";
		message = warning.attr("content");
		fname = "warning";
	}

	if(message != "") {
		params = {
			title: title,
			message: message,
			position: "topCenter"
		}
		iziToast[fname](params);
	}
}



function js_tree_menus() {
	$('#tree-menu').jstree({
		'core' : {
	        check_callback: true
	    }
	});

	var selected_node = null,
		node_text = null,
		node_id = null,
		is_leaf = null;


	$("#tree-menu").on("select_node.jstree", function(evt, data){
    	selected_node = data.node,
    	node_text = selected_node.text,
    	node_id = selected_node.data.id,
    	is_leaf = data.instance.is_leaf(selected_node);
    	

    	$(".card.right").show().find(".card-header > h4").text(node_text + (node_id == 0? "": " (" + node_id + ")"));

    	if(node_id == 0) {
    		$(".buttons-options > button").each(function(i, b) { $(b).hide(); });
    		$(".addModal").show();
    	} else if(is_leaf) {
    		$(".buttons-options > button").each(function(i, b) { $(b).show(); });
    	} else {
    		$(".buttons-options > button").each(function(i, b) { $(b).hide(); });
    		$(".addModal").show();
    		$(".editModal").show();
    	}
	});



	// Buttons events
	$(".addModal").on("click", function() {
		$("#formAddMenu input[name=menu_name]").val("");
		$("#formAddMenu input[name=menu_id]").val("");
	});

	$(".btn-add-menu").on("click", function() {
		var menu_name = $("#formAddMenu input[name='menu_name']").val(),
			menu_link = $("#formAddMenu input[name='menu_link']").val();

		if(menu_name && menu_link) {
			$.ajax({
				url: '/admin/menus/add',
				dataType: 'json',
				type: 'post',
				data: ({
					menu_name: menu_name,
					menu_link: menu_link,
					parent_id: node_id
				}),
				headers: {
    				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				success: function(data) {
					if(data.success == true) {
						$('#addModal').hide().removeClass('show');
						$('.modal-backdrop').remove();

						var inserted_id = data.inserted_id;

						var inserted_node_id = $('#tree-menu').jstree().create_node('menu_' + node_id, {"id": "menu_" + inserted_id, "text": menu_name}, "last", false, false);
						var inserted_node = $('#tree-menu').jstree(true).get_node(inserted_node_id);
						inserted_node.data = {
							id: inserted_id
						};
					}
				}
			});
		}
	});


	$(".editModal").on("click", function() {
		$("#formEditMenu input[name=menu_name]").val(node_text);
		$("#formEditMenu input[name=menu_id]").val(node_id);
	});

	$(".btn-edit-menu").on("click", function() {
		var menu_name = $("#formEditMenu input[name='menu_name']").val();

		if(menu_name) {
			$.ajax({
				url: '/admin/menus/edit',
				dataType: 'json',
				type: 'post',
				data: ({
					menu_id: node_id,
					menu_name: menu_name
				}),
				headers: {
    				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				success: function(data) {
					if(data.success == true) {
						$('#editModal').hide().removeClass('show');
						$('.modal-backdrop').remove();

						$('#tree-menu').jstree('rename_node', 'menu_' + node_id, menu_name);
					}
				}
			});
		}
	});


	$(".deleteModal").on("click", function() {
		$("#formDeleteMenu input[name=menu_id]").val(node_id);
	});

	$(".btn-delete-menu").on("click", function() {
		$.ajax({
			url: '/admin/menus/delete',
			dataType: 'json',
			type: 'post',
			data: ({
				menu_id: node_id
			}),
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function(data) {
				if(data.success == true) {
					$('#deleteModal').hide().removeClass('show');
					$('.modal-backdrop').remove();

				 	$("#tree-menu").jstree("delete_node", "menu_" + node_id);
				 	var parent_id = $('#tree-menu').jstree(true).get_node(selected_node.parent).data.id;
				 	$('#tree-menu').jstree('select_node', '#menu_' + parent_id);
				}
			}
		});
	});
}


function users_form() {
	$("select[name=user-type]").on("change", function() {
		var type = $(this).find("option:selected").val();

		$(".user-form").hide();
		$("#form_" + type).show();
	});

	$("select[name=state_id]").on("change", function() {
		var _this = $(this),
			state_id = $(_this).find("option:selected").val(),
			cities_select = $("select[name=city_id]");

			$.ajax({
				url: '/admin/estados/cidades',
				dataType: 'json',
				type: 'post',
				data: ({
					state_id: state_id
				}),
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				success: function(data) {
					if(data.success == true) {
						var cities = data.cities;

						$(cities_select).empty().append("<option value='0' selected disabled>Selecione uma cidade...</option>");

						$.each(cities, function(i, el) {
							var option = "<option value='" + el.id + "'>" + el.name + "</option>";
							$(cities_select).append(option);
						});

						$(cities_select).removeAttr("disabled").removeAttr("title");
					}
				}
			});
	});

	$("#date_of_birth").mask("99/99/9999");
	$("#mother_document, #father_document").mask("999.999.999-99");
	$("#zipcode").mask("99999-999");
	$("#phone").mask("(99) 9999-9999");
	$("#cellphone").mask("(99) 99999-9999");

	$("#zipcode").on("keyup", function() {
		var cep = $(this).val(),
			_size = cep.length;

		if(_size == 9) {
			$.ajax({
				url: "/admin/usuarios/cep",
				type: "POST",
				dataType: "json",
				data: ({
					cep: cep,
				}),
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				success: function(data) {
					if(data) {
						$("input#address").val(data.street).attr("readonly", true);
						$("input#neighborhood").val(data.neighborhood).attr("readonly", true);
						$("input#complement").val(data.complement).attr("readonly", true);
					} else {
						$("input#address").val("").removeAttr("readonly");
						$("input#neighborhood").val("").removeAttr("readonly");
						$("input#complement").val("").removeAttr("readonly");
					}
				}
			});
		} else {
			$("input#address").val("").removeAttr("readonly");
			$("input#neighborhood").val("").removeAttr("readonly");
			$("input#complement").val("").removeAttr("readonly");
		}
	});
}