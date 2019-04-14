$(document).ready(function() {

	js_tree_menus();

});



function js_tree_menus() {
	$('#tree-menu').jstree();


	$("#tree-menu").on("select_node.jstree", function(evt, data){
    	var selected_node = data.node,
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


    	// Buttons events
    	$(".btn-add-menu").on("click", function() {
    		var menu_name = $("input[name='menu_name']").val(),
    			menu_link = $("input[name='menu_link']").val();

    		console.log(true);

    		if(menu_name && menu_link) {
    			$.ajax({
    				url: '/admin/menus/add',
    				dataType: 'json',
    				type: 'post',
    				data: ({
    					menu_name: menu_name,
    					parent_id: node_id,
    					menu_link: menu_link
    				}),
    				headers: {
        				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    				},
    				success: function(data) {
    					$('#addModal').modal('hide');
    				}
    			});
    		}
    	});
	});
}