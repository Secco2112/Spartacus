@include('layouts.header')
<body data-open="click" data-menu="vertical-menu" data-col="2-columns" class="vertical-layout vertical-menu 2-columns  fixed-navbar">
	@include('layouts.main-nav')
    @include('layouts.main-menu')

    <div class="app-content content container-fluid admin-menus">
		<div class="content-wrapper">
		    <div class="content-body">
				<section id="basic-form-layouts">
					<div class="row match-height">
						<div class="col-md-12" style="display: flex; justify-content: space-between;">
							<div class="card left">
								<div class="card-header">
									<h4 class="card-title" id="basic-layout-form-center">Menus</h4>
									<a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
								</div>
								<div class="card-body collapse in">
									<div class="card-block">

										<?php

											function getMenuForTree($menus) {
							                    $html = "";
							                    foreach ($menus as $key => $menu) {
						                            $html .= "<li class='jstree-open' data-id='{$menu->id}'>";
						                                $html .= $menu->name;
						                                if(count($menu->children) > 0) {
						                                    $html .= "<ul>";
						                                        $html .= getMenuForTree($menu->children);
						                                    $html .= "</ul>";
						                                }
						                            $html .= "</li>";
							                    }
							                    return $html;
							                }

							                echo "<div id='tree-menu'>";
							                	echo "<ul>";
								                    echo "<li class='jstree-open' data-id='0'>Base";
										                echo "<ul>";
										                	echo getMenuForTree($menus);
										                echo "</ul>";
									                echo "</li>";
									            echo "</ul>";
								            echo "</div>";

										?>

									</div>
								</div>
							</div>

							<div class="card right">
								<div class="card-header">
									<h4 class="card-title" id="basic-layout-form-center"></h4>
									<a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
								</div>
								<div class="card-body collapse in">
									<div class="card-block">
										<div class="buttons-options">
											<button class="addModal btn btn-primary" data-toggle="modal" data-target="#addModal">Adicionar</button>
											<button class="editModal btn btn-primary" data-toggle="modal" data-target="#editModal">Editar</button>
											<button class="deleteModal btn btn-primary" data-toggle="modal" data-target="#deleteModal">Excluir</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
		<div class="modal" id="addModal">
	  		<div class="modal-dialog modal-dialog-centered">
		    	<div class="modal-content">
		      		<div class="modal-header">
		        		<h4 class="modal-title">Adicionar Menu</h4>
		        		<button type="button" class="close" data-dismiss="modal">&times;</button>
		      		</div>

		      		<div class="modal-body">
		        		<form class="formModal" id="formAddMenu" action="" method="post">
		        			<ul>
			        			<li class="form-item">
			        				<label id="info">Nome: </label>
			        				<input class="form-control menu_name" type="text" name="menu_name">
			        			</li>
			        			<li class="form-item">
			        				<label id="info">Link: </label>
			        				<input class="form-control menu_link" type="text" name="menu_link">
			        			</li>
		        			</ul>
		        		</form>
		      		</div>

		      		<div class="modal-footer">
		        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
		        		<button type="button" class="btn btn-primary btn-add-menu">Salvar</button>
		      		</div>
		    	</div>
		  	</div>
		</div>
	</div>


	

    @include('layouts.footer')
</body>
</html>