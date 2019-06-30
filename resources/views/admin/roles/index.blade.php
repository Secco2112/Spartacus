@include('layouts.header')
<body data-open="click" data-menu="vertical-menu" data-col="2-columns" class="vertical-layout vertical-menu 2-columns  fixed-navbar">
	@include('layouts.main-nav')
    @include('layouts.main-menu')

    <div class="app-content content container-fluid admin-roles">
		<div class="content-wrapper">
		    <div class="content-body">
				<section id="basic-form-layouts">
					<div class="row match-height">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<h4 class="card-title" id="basic-layout-form-center"><?= Route::currentRouteName(); ?></h4>
									<div class="options-header">
										<?php
											if($buttons["create"]) { ?>
												<a href="usuarios-regras/inserir">Inserir</a>
											<? }
										?>
									</div>
								</div>
								<div class="card-body collapse in">
									<div class="card-block">

										<table class="table table-striped">
											<thead>
												<th scope="col">Código</th>
												<?php
													foreach ($fields as $key => $value) { ?>
														<th scope="col"><?= $value ?></th>
													<? }
												?>
												<th class="th_options" scope="col">Opções</th>
											</thead>
											<tbody>
												<?php
													foreach ($dados as $key => $dado) { ?>
														<tr>
															<td><?= $dado->id; ?></td>
															<?php
																foreach ($fields as $key => $value) {
																	if(isset($dado->{$key})) { ?>
																		<td><?= $dado->{$key} ?></td>
																	<? }
																}
															?>
															<?php
																
																echo "<td class='buttons'>"; ?>
																	<div>
																		<a class="btn btn-info" href="usuarios-regras/permissoes/<?= $dado->id; ?>">Permissões</a>
																	</div>
																	<?php
																		if($buttons["edit"] || $buttons["delete"]) {
																			if($buttons["edit"]) { ?>
																				<div>
																					<a class="btn btn-success" href="usuarios-regras/editar/<?= $dado->id; ?>">Editar</a>
																				</div>
																			<? }
																			if($buttons["delete"]) { ?>
																				<div>
																					<a class="btn btn-danger" href="usuarios-regras/deletar/<?= $dado->id; ?>">Deletar</a>
																				</div>
																			<? }
																		}
																echo "</td>";
															?>
														</tr>
													<? }
												?>
											</tbody>
										</table>

										<?= $dados->links(); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>

    @include('layouts.footer')
</body>
</html>