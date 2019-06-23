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
								</div>
								<div class="card-body collapse in">
									<div class="card-block">
										<form class="form form_materiais_estudante" method="post" action="/materiais/save_materials">
											@csrf
											<div class="row">
												<div class="col-md-6 offset-md-3">
													<div class="form-body">
														<div class="form-group school_years">
															<label for="name">Período letivo</label>
															<select name="school_year_id" id="school_year" class="form-control">
																<option selected disabled value="0">Selecione o período letivo</option>
																<?php
																	foreach ($school_years as $key => $sy) { ?>
																		<option value="<?= $sy->id; ?>"><?= $sy->name; ?></option>
																	<? }
																?>
															</select>
														</div>
													</div>
												</div>
											</div>

											<div class="list-subjects"></div>

											<div class="form-actions center" style="display: none;">
												<button type="button" class="btn btn-warning mr-1">
													<i class="icon-cross2"></i> Cancelar
												</button>
												<button type="submit" class="btn btn-primary">
													<i class="icon-check2"></i> Salvar
												</button>
											</div>
										</form>
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