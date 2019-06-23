$(document).ready(function() {

	handle_flash_messages();
	js_tree_menus();

	users_form();

	students_subjects();

	grades();
	materials();
	student_grades();
	student_materials();

	$(".dropdown-toggle").on("click", function() {
		$(this).parent().toggleClass("open");
	});

	$(".logout-link").on("click", function(e) {
		e.preventDefault();
		$(this).closest("form").submit();
	})
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


function students_subjects() {
	$("#btn-add-subject").on("click", function(e) {
		e.preventDefault();

		var element_to_clone = $(".list-subjects .add-subject:first-child").clone();

		$(element_to_clone).find("select").each(function(i, select) {
			$(select).find("option").each(function(j, opt) {
				$(opt).removeAttr("selected");
			})
			$(select).find("option[value=0]").attr("selected", "selected");
		});

		$(".list-subjects").append($(element_to_clone).clone());
	});

	$(document).on("click", ".delete-subject", function() {
		$(this).parent().remove();
	});
}


function grades() {
	$(".form_notas #course").on("change", function() {
		var _this = this,
			value = $(this).find("option:selected").val();

		$(".form-group.school_years").remove();
		$(".form-group.subjects").remove();
		$(".list-students").empty();
		$(".form-actions").hide();

		$.ajax({
			url: "/notas/load_school_years",
			type: "POST",
			dataType: "json",
			data: ({
				course_id: value
			}),
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function(data) {
				var wrapper = $("<div />");
				wrapper.attr("class", "form-group school_years");
				wrapper.append("<label for='school_year'>Período letivo</label>");

				var select = $("<select />");
				select.attr({
					name: "school_year_id",
					id: "school_year",
					class: "form-control"
				});

				select.append("<option id='0' selected disabled>Selecione um período letivo</option>");
				$.each(data, function(i, el) {
					var option = $("<option />");
					option.attr("value", el.id).text(el.name);
					select.append(option);
				});

				wrapper.append(select);

				$(_this).parent().after(wrapper);
			}
		})
	});


	$(document).on("change", ".form_notas #school_year", function() {
		var _this = $(this),
			value = $(this).find("option:selected").val(),
			course = $("select#course").find("option:selected").val();

		$(".form-group.subjects").remove();
		$(".list-students").empty();
		$(".form-actions").hide();

		$.ajax({
			url: "/notas/load_subjects",
			type: "POST",
			dataType: "json",
			data: ({
				course_id: course,
				school_year_id: value
			}),
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function(data) {
				var wrapper = $("<div />");
				wrapper.attr("class", "form-group subjects");
				wrapper.append("<label for='subject'>Matéria</label>");

				var select = $("<select />");
				select.attr({
					name: "subject_id",
					id: "subject",
					class: "form-control"
				});

				select.append("<option id='0' selected disabled>Selecione uma matéria</option>");
				$.each(data, function(i, el) {
					var option = $("<option />");
					option.attr("value", el.id).text(el.name);
					select.append(option);
				});

				wrapper.append(select);

				$(_this).parent().after(wrapper);
			}
		})
	});


	$(document).on("change", ".form_notas #subject", function() {
		var _this = $(this),
			value = $(this).find("option:selected").val(),
			course = $("select#course").find("option:selected").val(),
			school_year = $("select#school_year").find("option:selected").val();

		$.ajax({
			url: "/notas/load_students",
			type: "POST",
			dataType: "json",
			data: ({
				course_id: course,
				school_year_id: school_year,
				subject_id: value
			}),
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function(data) {
				if(data) {
					$(".list-students").empty();
					$(".form-actions").show();

					$.each(data, function(i, student) {
						var student_el = $("<div />");
						student_el.attr("class", "student");
						student_el.attr("data-student-id", student.id);
						
						var student_header = $("<div />");
						student_header.attr("class", "student-header");
						student_header.append("<h5>" + student.name + "</h5>");

						var collapser = $("<div />");
						collapser.attr("class", "collapser");
						
						var collapser_wrapper = $("<div />");
						collapser_wrapper.attr("class", "wrapper");
						
						collapser.append(collapser_wrapper);
						student_el.append(student_header);
						student_el.append(collapser);

						var total_value = 0;

						if(student.grades.length > 0) {
							var sum_values = 0, sum_weights = 0;
							$.each(student.grades, function(j, grade) {
								var grade_wrapper = $("<div />");
								grade_wrapper.attr("class", "grade_wrapper");
	
								var value_wrapper = $("<div />");
								value_wrapper.attr("class", "value_wrapper");
								value_wrapper.append("<label>Nota " + (j+1) + "</label>");
								value_wrapper.append("<input class='form-control' value='" + grade.value + "' name='value[][" + student.id + "]' />");
								
								var weight_wrapper = $("<div />");
								weight_wrapper.attr("class", "weight_wrapper");
								weight_wrapper.append("<label>Peso " + (j+1) + "</label>");
								weight_wrapper.append("<input class='form-control' value='" + grade.weight + "' name='weight[][" + student.id + "]' />");
	
								grade_wrapper.append(value_wrapper);
								grade_wrapper.append(weight_wrapper);
								grade_wrapper.append("<i class='icon-cross'></i>");
								collapser_wrapper.append(grade_wrapper);

								sum_values += (grade.value * grade.weight);
								sum_weights += grade.weight;
							});

							var plus_wrapper = $("<div />");
							plus_wrapper.attr("class", "plus_wrapper add-grade");
							plus_wrapper.css("display", (student.grades.length < 4)? "flex": "none");
							plus_wrapper.append("<i class='icon-plus'></i>");
							plus_wrapper.append("<span>Adicionar nota</span>");
							collapser_wrapper.append(plus_wrapper);

							total_value = sum_values / sum_weights;
						} else {
							var grade_wrapper = $("<div />");
							grade_wrapper.attr("class", "grade_wrapper");

							var value_wrapper = $("<div />");
							value_wrapper.attr("class", "value_wrapper");
							value_wrapper.append("<label>Nota 1</label>");
							value_wrapper.append("<input class='form-control' value='' name='value[][" + student.id + "]' />");
							
							var weight_wrapper = $("<div />");
							weight_wrapper.attr("class", "weight_wrapper");
							weight_wrapper.append("<label>Peso 1</label>");
							weight_wrapper.append("<input class='form-control' value='' name='weight[][" + student.id + "]' />");

							var plus_wrapper = $("<div />");
							plus_wrapper.attr("class", "plus_wrapper add-grade");
							plus_wrapper.append("<i class='icon-plus'></i>");
							plus_wrapper.append("<span>Adicionar nota</span>");

							grade_wrapper.append(value_wrapper);
							grade_wrapper.append(weight_wrapper);
							grade_wrapper.append("<i class='icon-cross'></i>");
							collapser_wrapper.append(grade_wrapper);
							collapser_wrapper.append(plus_wrapper);
						}
						
						var total_grades = $("<div />");
						total_grades.attr("class", "total");
						total_grades.append("<labe>Média total</label>");
						total_grades.append("<div class='form-control'>" + total_value.toFixed(1) + "</div>");

						collapser_wrapper.append(total_grades);

						$(".list-students").append(student_el);
					});
				}
			}
		})
	});


	$(document).on("click", ".form_notas .student-header", function() {
		$(this).parent().find(".collapser").slideToggle();
	});


	$(document).on("click", ".form_notas .plus_wrapper", function() {
		var current_count = $(this).parent().find(".grade_wrapper").length,
			student_id = $(this).closest(".student").data("student-id");
		
		var value_wrapper = $("<div />");
		value_wrapper.attr("class", "value_wrapper");
		value_wrapper.append("<label>Nota " + (current_count+1) + "</label>");
		value_wrapper.append("<input class='form-control' value='' name='value[][" + student_id + "]' />");
		
		var weight_wrapper = $("<div />");
		weight_wrapper.attr("class", "weight_wrapper");
		weight_wrapper.append("<label>Peso " + (current_count+1) + "</label>");
		weight_wrapper.append("<input class='form-control' value='' name='weight[][" + student_id + "]' />");

		var grade_wrapper = $("<div />");
		grade_wrapper.attr("class", "grade_wrapper");
		grade_wrapper.append(value_wrapper);
		grade_wrapper.append(weight_wrapper);
		grade_wrapper.append("<i class='icon-cross'></i>");

		$(this).before(grade_wrapper);

		if($(this).parent().find(".grade_wrapper").length == 4) {
			$(this).hide();
		}
	});


	$(document).on("click", ".form_notas .grade_wrapper > i", function() {
		$(this).closest(".wrapper").find(".plus_wrapper").show();
		
		var wrapper = $(this).closest(".wrapper");

		$(this).parent().remove();

		var current_count = $(wrapper).find(".grade_wrapper").length,
			grades = $(wrapper).find(".grade_wrapper");

		if(current_count > 0) {
			var it = 1;
			grades.each(function(i, el) {
				$(el).find(".value_wrapper label").text("Nota " + it);
				$(el).find(".weight_wrapper label").text("Peso " + it);
				it++;
			});
		}
	});
}



function materials() {
	// $.fn.filepond.registerPlugin(FilePondPluginImagePreview);
	$.fn.filepond.registerPlugin(FilePondPluginFileMetadata);

	$(".form_materiais #course").on("change", function() {
		var _this = this,
			value = $(this).find("option:selected").val();

		$(".form-group.school_years").remove();
		$(".form-group.subjects").remove();
		$(".list-students").empty();
		$(".form-actions").hide();

		$.ajax({
			url: "/materiais/load_school_years",
			type: "POST",
			dataType: "json",
			data: ({
				course_id: value
			}),
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function(data) {
				var wrapper = $("<div />");
				wrapper.attr("class", "form-group school_years");
				wrapper.append("<label for='school_year'>Período letivo</label>");

				var select = $("<select />");
				select.attr({
					name: "school_year_id",
					id: "school_year",
					class: "form-control"
				});

				select.append("<option id='0' selected disabled>Selecione um período letivo</option>");
				$.each(data, function(i, el) {
					var option = $("<option />");
					option.attr("value", el.id).text(el.name);
					select.append(option);
				});

				wrapper.append(select);

				$(_this).parent().after(wrapper);
			}
		})
	});


	$(document).on("change", ".form_materiais #school_year", function() {
		var _this = $(this),
			value = $(this).find("option:selected").val(),
			course = $("select#course").find("option:selected").val();

		$(".form-group.subjects").remove();
		$(".list-students").empty();
		$(".form-actions").hide();

		$.ajax({
			url: "/materiais/load_subjects",
			type: "POST",
			dataType: "json",
			data: ({
				course_id: course,
				school_year_id: value,
				student: false
			}),
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function(data) {
				if(data) {
					$(".list-subjects").empty();

					$.each(data, function(i, subject) {
						var subject_el = $("<div />");
						subject_el.attr("class", "subject");
						subject_el.attr("data-subject-id", subject.id);

						var subject_header = $("<div />");
						subject_header.attr("class", "subject-header");
						subject_header.append("<h5>" + subject.name + "</h5>");

						var collapser = $("<div />");
						collapser.attr("class", "collapser");
						
						var collapser_wrapper = $("<div />");
						collapser_wrapper.attr("class", "wrapper");

						collapser.append(collapser_wrapper);

						var file_upload_input = $("<input />");
						file_upload_input.attr("type", "file");
						file_upload_input.attr("class", "input_file_material_" + subject.id);

						collapser_wrapper.append(file_upload_input);

						subject_el.append(subject_header);
						subject_el.append(collapser);

						$(".list-subjects").append(subject_el);


						// File upload
						var school_year_id = $(".form_materiais #school_year").val();

						var files = [];
						if(subject.files.length > 0) {
							$.each(subject.files_source, function(i, file) {
								files.push({
									source: file.source,
									options: {
										type: 'local',
										load: true,
										file: {
											id: file.id,
											name: file.name,
											size: file.size
										}
									}
								});
							});
						}

						$(".input_file_material_" + subject.id).filepond({
							allowMultiple: true,
							instantUpload: false,
							labelIdle: "Arraste e solte o(s) arquivo(s) ou <span class='filepond--label-action'>Procure</span>",
							fileMetadataObject: {
								school_year_id: school_year_id,
								subject_id: subject.id
							},
							files: files,
							server: {
								process: {
									url: "/materiais/upload_file",
									headers: {
										'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
									}
								}
							}
						});


						$(".input_file_material_" + subject.id).on("FilePond:removefile", function(e) {
							var file = e.detail.file,
								id = file.file.id,
								source = file.source;

							$.ajax({
								url: "/materiais/delete_file",
								type: "POST",
								dataType: "json",
								data: ({
									id: id,
									path: source
								}),
								headers: {
									'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
								}
							});
						});
					});
				}
			}
		})
	});


	$(document).on("click", ".form_materiais .subject-header", function() {
		$(this).parent().find(".collapser").slideToggle();
	});
}



function student_materials() {
	$(".form_materiais_estudante #school_year").on("change", function() {
		var _this = this,
			value = $(this).find("option:selected").val();

		$.ajax({
			url: "/materiais/load_subjects",
			type: "POST",
			dataType: "json",
			data: ({
				school_year_id: value,
				student: true
			}),
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function(data) {
				if(data) {
					$(".list-subjects").empty();

					$.each(data, function(i, subject) {
						var subject_el = $("<div />");
						subject_el.attr("class", "subject");
						subject_el.attr("data-subject-id", subject.id);

						var subject_header = $("<div />");
						subject_header.attr("class", "subject-header");
						subject_header.append("<h5>" + subject.name + "</h5>");

						var collapser = $("<div />");
						collapser.attr("class", "collapser");
						
						var collapser_wrapper = $("<div />");
						collapser_wrapper.attr("class", "wrapper");

						collapser.append(collapser_wrapper);

						subject_el.append(subject_header);
						subject_el.append(collapser);


						if(subject.files.length > 0) {
							var table = $("<table />");
							table.attr("class", "table");

							var thead = $("<thead />");
							var tr = $("<tr />");
							tr.append("<th scope='col'>Código</th>");
							tr.append("<th scope='col'>Nome do arquivo</th>");
							tr.append("<th scope='col'>Tamanho do arquivo</th>");
							tr.append("<th scope='col'>Data de inclusão</th>");
							tr.append("<th scope='col'></th>");
							thead.append(tr);
							thead.append(thead);

							var tbody = $("<tbody />");

							$.each(subject.files, function(i, file) {
								var tr_file = $("<tr />");
								tr_file.append("<td>" + file.id + "</td>");
								tr_file.append("<td>" + file.filename + "</td>");
								tr_file.append("<td>" + file.size + "</td>");
								tr_file.append("<td>" + file.date + "</td>");
								tr_file.append("<td><a style='color: inherit;' href='" + file.path + "' download><i class='icon-download'></i> Download</td>");
								tbody.append(tr_file);
							});

							table.append(thead);
							table.append(tbody);
							collapser_wrapper.append(table);
						}


						$(".list-subjects").append(subject_el);
					});
				}
			}
		});
	});

	$(document).on("click", ".form_materiais_estudante .subject .subject-header", function() {
		$(this).parent().find(".collapser").slideToggle();
	});
}


function student_grades() {
	$(".form_notas_estudante #school_year").on("change", function() {
		var _this = this,
			value = $(this).find("option:selected").val();

		$.ajax({
			url: "/notas/load_subjects",
			type: "POST",
			dataType: "json",
			data: ({
				school_year_id: value,
				student: true
			}),
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function(data) {
				if(data) {
					$(".list-subjects").empty();

					$.each(data, function(i, subject) {
						
					});
				}
			}
		});
	});
}