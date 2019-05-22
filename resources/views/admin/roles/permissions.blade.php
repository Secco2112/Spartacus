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
										<form id="permissions_form" method="POST" action="">
                                            <ul>
                                                <?php foreach ($dados as $i => $dado) { ?>
                                                    <li class="menu_permission" data-menu-id="<?= $dado["menu"]["id"] ?>">
                                                        <span class="title"><?= $dado["menu"]["name"]; ?></span>
                                                        <div class="list-permissions">
                                                            <?php foreach ($permissions as $j => $perm) { ?>
                                                                <div>
                                                                    <label for="<?= $dado["menu"]["id"] . "_" . $j ?>"><?= $perm ?></label>
                                                                    <input id="<?= $dado["menu"]["id"] . "_" . $j ?>" type="checkbox" name="permission[<?= $dado["menu"]["id"] ?>][<?= $j ?>]" <?= in_array($j, $dado["permissions"])? "checked": ""; ?> />
                                                                </div>
                                                            <? } ?>
                                                        </div>
                                                    </li>
                                                <? } ?>
                                            </ul>

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