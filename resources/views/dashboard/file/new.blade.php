@extends('layouts.app', ['title' => 'Add New Records'])

@section('content')
    <div id="new" class="my-content-wrapper">
        <div class="content-container">
            <div class="sectionWrap">
                {{-- SECTION HEADING --}}
                <h6 class="center sectionHeading">NEW FILE</h6>

                {{-- SECTION TABLE --}}
                <div class="sectionFormWrap z-depth-1" style="padding:24px;">
					<p class="formMsg grey lighten-3 left-align">
						Fill the form below with the file information and loadup the documents then submit.
					</p>
					<form action="{{ route('store_file') }}" method="POST" enctype="multipart/form-data" name="create_form" id="create_form" class="create_new_form">
						@csrf
						
						<fieldset class="row" id="personal_data" style="margin-top:8px;">
							<legend>FILE INFORMATION</legend>
							{{-- Filename --}}
							<div class="input-field col s12 l7">
								<input id="name" name="name" type="text" value="{{ old('name') }}" required>
								@if ($errors->has('name'))
									<span class="helper-text red-text">
										<strong>{{ $errors->first('name') }}</strong>
									</span>
								@endif
								<label for="name">* File name</label>
							</div>
							{{-- File Number --}}
							<div class="input-field col s12 l2">
								<input id="file_number" name="file_number" type="number" value="{{ old('file_number') }}" class="input_text" data-length="5" required>
								@if ($errors->has('file_number'))
									<span class="helper-text red-text">
										<strong>{{ $errors->first('file_number') }}</strong>
									</span>
								@endif
								<label for="file_number">* File No.</label>
							</div>
							{{-- Type --}}
							<div class="col s12 l3">
								<label for="type">* Select Type</label>
								<select id="type" name="type" class=" browser-default" required>
									<option disabled>Select Type</option>
									<option value="personnel" selected>Personnel</option>
									<option value="policy">Policy</option>
									<option value="archive">Archive</option>
								</select>
								@if ($errors->has('type'))
									<span class="helper-text red-text">
										<strong>{{ $errors->first('type') }}</strong>
									</span>
								@endif
							</div>
						</fieldset>
						<fieldset class="row" id="uploads" style="width:100%; margin-left: 0; margin-right: 0;">
							<legend>DOCUMENTS</legend>
							<div class="file-field col s12 l6 input-field">
								<div class="uploadBtn">
									<span>SELECT PASSPORT</span>
									<input type="file" name="passport" id="passport" accept="image/*">
								</div>
								<div class="file-path-wrapper">
									<input class="file-path validate" type="text" placeholder="Upload passport">
								</div>
							</div>
							<div class="file-field col s12 l6 input-field">
								<div class="uploadBtn">
									<span>SELECT SCANNED DOCUMENTS</span>
									<input type="file" name="file[]" id="file" accept="image/*" multiple>
								</div>
								<div class="file-path-wrapper">
									<input class="file-path validate" type="text" placeholder="Upload one or more files">
								</div>
							</div>

							<div class="input-field col s12 l3 right">
								<button class="submit btn waves-effect waves-light right" type="submit">
									<i class="material-icons right">send</i>ADD RECORD
								</button>
							</div>
							<br />
							<div class="progress" style="display:none;">
								<div class="indeterminate"></div>
							</div>
						</fieldset>

					</form>
				</div>
            </div>
        </div>
        <div class="footer z-depth-1">
            <p>&copy; Nigeria Security & Civil Defence Corps</p>
        </div>
    </div>
@endsection

@push('scripts')
	<script>
		
		$(document).ready(function(){
			$('.dob_datepicker').datepicker({
				format: 'dd/mm/yyyy',
				yearRange: [1930, 1999]
			});
			$('.dofa_datepicker').datepicker({
				format: 'dd/mm/yyyy',
				yearRange: [1960, 2015]
			});
			$('.dopa_datepicker').datepicker({
				format: 'dd/mm/yyyy',
				yearRange: [1960, 2021]
			});
			$('.doc_datepicker').datepicker({
				format: 'dd/mm/yyyy',
				yearRange: [2005, 2021]
			});
			$('.timepicker').timepicker({
				defaultTime: 'now'
			});
			$('input.input_text').characterCounter();
			$('input.autocomplete').autocomplete({
				data: {
					'National Headquarters' : null,
					'Abia State Command' : null,
					'Adamawa State Command' : null,
					'Akwa-ibom State Command' : null,
					'Anambra State Command' : null,
					'Bauchi State Command' : null,
					'Bayelsa State Command' : null,
					'Benue State Command' : null,
					'Borno State Command' : null,
					'Cross-river State Command' : null,
					'Delta State Command' : null,
					'Ebonyi State Command' : null,
					'Edo State Command' : null,
					'Ekiti State Command' : null,
					'Enugu State Command' : null,
					'FCT Command' : null,
					'Gombe State Command' : null,
					'Imo State Command' : null,
					'Jigawa State Command' : null,
					'Kaduna State Command' : null,
					'Kano State Command' : null,
					'Katsina State Command' : null,
					'Kebbi State Command' : null,
					'Kogi State Command' : null,
					'Kwara State Command' : null,
					'Lagos State Command' : null,
					'Nasarawa State Command' : null,
					'Niger State Command' : null,
					'Ogun State Command' : null,
					'Ondo State Command' : null,
					'Osun State Command' : null,
					'Oyo State Command' : null,
					'Plateau State Command' : null,
					'Rivers State Command' : null,
					'Sokoto State Command' : null,
					'Taraba State Command' : null,
					'Yobe State Command' : null,
					'Zamfara State Command' : null,
					'Zone A HQ, Lagos' : null,
					'Zone B HQ, Kaduna' : null,
					'Zone C HQ, Bauchi' : null,
					'Zone D HQ, Minna' : null,
					'Zone E HQ, Oweri' : null,
					'Zone F HQ, Abeokuta' : null,
					'Zone G HQ, Benin' : null,
					'Zone H HQ, Makurdi' : null,
					'College of Security Management, Abeokuta' : null,
					'College of Peace, Conflic Resolution &Desaster Management, Katsina' : null,
					'Civil Defence Academy, Sauka' : null
				},
			});

			// $('.tabs').tabs({
			// 	// swipeable: true
			// });

			$('.contact-data').click(function(){
				$('.tabs').tabs('select', 'contact-data');
			});
			$('.official-data').click(function(){
				$('.tabs').tabs('select', 'official-data');
			});
			$('.docs-upload').click(function(){
				$('.tabs').tabs('select', 'docs-upload');
			});

			$('#create_form').submit(function (e) { 
				$('.submit').prop('disabled', true).html('ADDING RECORD...');
				$('.progress').fadeIn();
			});

			// LOAD GL AFTER SELECTING CADRE
			$('#cadre').change(function() {
				let cadreSelected = $(this).val();
				if(cadreSelected == 'superintendent'){
					$('#gl').html('<option value="" disabled selected>Choose your option</option>');
					$(`<option value="18">18</option>`).appendTo('#gl');
					$(`<option value="17">17</option>`).appendTo('#gl');
					$(`<option value="16">16</option>`).appendTo('#gl');
					$(`<option value="15">15</option>`).appendTo('#gl');
					$(`<option value="14">14</option>`).appendTo('#gl');
					$(`<option value="13">13</option>`).appendTo('#gl');
					$(`<option value="12">12</option>`).appendTo('#gl');
					$(`<option value="11">11</option>`).appendTo('#gl');
					$(`<option value="10">10</option>`).appendTo('#gl');
					$(`<option value="9">9</option>`).appendTo('#gl');
					$(`<option value="8">8</option>`).appendTo('#gl');
				}else if(cadreSelected == 'inspectorate'){
					$('#gl').html('<option value="" disabled selected>Choose your option</option>');
					$(`<option value="7">7</option>`).appendTo('#gl');
					$(`<option value="6">6</option>`).appendTo('#gl');
				}else{
					$('#gl').html('<option value="" disabled selected>Choose your option</option>');
					$(`<option value="7">7</option>`).appendTo('#gl');
					$(`<option value="6">6</option>`).appendTo('#gl');
					$(`<option value="5">5</option>`).appendTo('#gl');
					$(`<option value="4">4</option>`).appendTo('#gl');
					$(`<option value="3">3</option>`).appendTo('#gl');
				}
			});

			// LOAD LGAs AFTER SELECTING STATE OF ORIGIN
			$('#soo').change(function() {
				let stateSelected = this.value;
				// GET ALL LOCAL GOVERNMENT AREAS IN NIGERIA
				axios.get(`{{ route('get_lgas','') }}/${stateSelected}`, )
					.then(function(response) {
						// console.log(response.data);
						let lgaArray = response.data;
						$('#lgoo').html('<option value="" disabled selected>Choose your option</option>');
						lgaArray.map(function(lga) {
							$(`<option value="${lga.id}">${lga.lg_name}</option>`).appendTo('#lgoo');
						});
					})
					.catch(function(error) {
						// handle error
						console.log(error.data);
					})
					.finally(function() {
						// always executed
					});
			});


			$('#importData').submit(function () {
				$('.importProgress').css('display', 'block');
				$('.importBtn').html('Importing...');
			});

			// $('.submit').click(function(){
			// 	$('.progress').fadeIn();
			// });

		});
	</script>
@endpush