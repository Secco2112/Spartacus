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
										<form class="form" method="post" action="">
											@csrf
											<div class="row">
												<div class="col-md-6 offset-md-3">
													<div class="form-body">
														<div class="form-group">
															<label for="semester">Semestre</label>
															<select class="form-control" name="semester" id="semester">
																<option value="0" disabled selected>Selecione...</option>
																<option value="1">1º semestre</option>
																<option value="2">2º semestre</option>
															</select>
														</div>
														<div class="form-group">
															<label for="year">Semestre</label>
															<select class="form-control" name="year" id="year">
																<option value="0" disabled selected>Selecione...</option>
																<?php
																	for($year = "2016"; $year <= "2035"; $year++) { ?>
																		<option value="<?= $year ?>"><?= $year ?></option>
																	<? }
																?>
															</select>
														</div>
													</div>
												</div>
											</div>

											<div class="form-actions center">
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