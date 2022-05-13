<div class="modal" id='itemModal' tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New Item</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class='Item'>
			<div class='row'>
				<div class='col-lg-6'><label for='Name'>Name:</label></div>
				<div class='col-lg-6'><input type='text' name='Name' /></div>
			</div>
			<div class='row'>
				<div class='col-lg-6'><label for='Brand'>Brand:</label></div>
				<div class='col-lg-6'><input type='text' name='Brand' /></div>
			</div>
			<div class='row'>
				<div class='col-lg-6'><label for='Colors'>Colors:</label></div>
				<div class='col-lg-6'><input type='text' name='Colors' /></div>
			</div>
			<div class='row'>
				<div class='col-lg-6'><label for='Pattern'>Pattern:</label></div>
				<div class='col-lg-6'><input type='text' name='Pattern' /></div>
			</div>
			<div class='row'>
				<div class='col-lg-6'><label for='Image'>Image:</label></div>
				<div class='col-lg-6'><input type='file' name='Image' /></div>
			</div>
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>