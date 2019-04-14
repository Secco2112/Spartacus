<form class="form" method="post" action="">
	<div class="row">
		<div class="col-md-6 offset-md-3">
			<div class="form-body">
				<div class="form-group">
					<label for="eventInput1">Full Name</label>
					<input type="text" id="eventInput1" class="form-control" placeholder="name" name="fullname">
				</div>

				<div class="form-group">
					<label for="eventInput2">Title</label>
					<input type="text" id="eventInput2" class="form-control" placeholder="title" name="title">
				</div>

				<div class="form-group">
					<label for="eventInput3">Company</label>
					<input type="text" id="eventInput3" class="form-control" placeholder="company" name="company">
				</div>

				<div class="form-group">
					<label for="eventInput4">Email</label>
					<input type="email" id="eventInput4" class="form-control" placeholder="email" name="email">
				</div>

				<div class="form-group">
					<label for="eventInput5">Contact Number</label>
					<input type="tel" id="eventInput5" class="form-control" name="contact" placeholder="contact number">
				</div>

				<div class="form-group">
					<label>Existing Customer</label>
					<div class="input-group">
						<label class="display-inline-block custom-control custom-radio ml-1">
							<input type="radio" name="customer1" class="custom-control-input">
							<span class="custom-control-indicator"></span>
							<span class="custom-control-description ml-0">Yes</span>
						</label>
						<label class="display-inline-block custom-control custom-radio">
							<input type="radio" name="customer1" checked class="custom-control-input">
							<span class="custom-control-indicator"></span>
							<span class="custom-control-description ml-0">No</span>
						</label>
					</div>
				</div>

			</div>
		</div>
	</div>

	<div class="form-actions center">
		<button type="button" class="btn btn-warning mr-1">
			<i class="icon-cross2"></i> Cancel
		</button>
		<button type="submit" class="btn btn-primary">
			<i class="icon-check2"></i> Save
		</button>
	</div>
</form>