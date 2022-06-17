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
					<form action="{{ route('file_update', $file->id) }}" method="POST" enctype="multipart/form-data" name="create_form" id="create_form" class="create_new_form">
						@method('PUT')
						@csrf
						
						<fieldset class="row" id="personal_data" style="margin-top:8px;">
							<legend>FILE INFORMATION</legend>
							{{-- Filename --}}
							<div class="input-field col s12 l7">
								<input id="name" name="name" type="text" value="{{ $file->name }}" required>
								@if ($errors->has('name'))
									<span class="helper-text red-text">
										<strong>{{ $errors->first('name') }}</strong>
									</span>
								@endif
								<label for="name">* File name</label>
							</div>
							{{-- File Number --}}
							<div class="input-field col s12 l2">
								<input id="file_number" name="file_number" type="number" value="{{ $file->file_number }}" class="input_text" data-length="5" required>
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
									<option value="personnel" {{ strtolower($file->type) == 'personnel' ? 'selected' : '' }}>Personnel</option>
									<option value="policy" {{ strtolower($file->type) == 'policy' ? 'selected' : '' }}>Policy</option>
									<option value="archive" {{ strtolower($file->type) == 'archive' ? 'selected' : '' }}>Archive</option>
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
							<div class="file-field col s12 input-field">
								<div class="uploadBtn">
									<span>SELECT PASSPORT</span>
									<input type="file" name="passport" id="passport" accept="image/*">
								</div>
								<div class="file-path-wrapper">
									<input class="file-path validate" type="text" placeholder="Upload passport">
								</div>
							</div>
							<div class="input-field col s12 l3 right">
								<button class="submit btn waves-effect waves-light right" type="submit">
									<i class="material-icons right">send</i>UPDATE RECORD
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
			
			$('#create_form').submit(function (e) { 
				$('.submit').prop('disabled', true).html('UPDATING RECORD <i class="fas fa-circle-notch fa-spin"></i>');
			});

		});
	</script>
@endpush