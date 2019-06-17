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
										<form class="form user-subjects" method="post" action="">
											@csrf
											<div class="row">
												<div class="col-md-7">
													<div class="form-body">
														<div class="list-subjects">
															<?php
																if(count($current_subjects) > 0) {
																	foreach($current_subjects as $key => $cs) { ?>
																		<div class="add-subject">
																			<div class="wrapper">
																				<label>Período letivo</label>
																				<select class="form-control" name="school_year[]">
																					<option disabled value="0">Selecione...</option>
																					<?php
																						foreach ($school_years as $key => $school_year) { ?>
																							<option <?= ($cs->school_year_id == $school_year->id? "selected": ""); ?> value="<?= $school_year->id; ?>"><?= $school_year->year . "/" . $school_year->semester; ?></option>
																						<? }
																					?>
																				</select>
																			</div>
																			<div class="wrapper">
																				<label>Matéria</label>
																				<select class="form-control" name="subject[]">
																					<option disabled value="0">Selecione...</option>
																					<?php
																						foreach ($available_subjects as $key => $subject) { ?>
																							<option <?= ($cs->subject_id == $subject->id? "selected": ""); ?> value="<?= $subject->id; ?>"><?= $subject->name; ?></option>
																						<? }
																					?>
																				</select>
																			</div>
																			<i class="icon-cross2 delete-subject"></i>
																		</div>
																	<? }
																} else { ?>
																	<div class="add-subject">
																		<div class="wrapper">
																			<label>Período letivo</label>
																			<select class="form-control" name="school_year[]">
																				<option selected disabled value="0">Selecione...</option>
																				<?php
																					foreach ($school_years as $key => $school_year) { ?>
																						<option value="<?= $school_year->id; ?>"><?= $school_year->year . "/" . $school_year->semester; ?></option>
																					<? }
																				?>
																			</select>
																		</div>
																		<div class="wrapper">
																			<label>Matéria</label>
																			<select class="form-control" name="subject[]">
																				<option selected disabled value="0">Selecione...</option>
																				<?php
																					foreach ($available_subjects as $key => $subject) { ?>
																						<option value="<?= $subject->id; ?>"><?= $subject->name; ?></option>
																					<? }
																				?>
																			</select>
																		</div>
																		<i class="icon-cross2 delete-subject"></i>
																	</div>
																<? }
															?>
														</div>
														<button id="btn-add-subject" class="btn btn-secondary">Adicionar matéria</button>
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