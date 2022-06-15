@extends('layouts.app', ['title' => 'Staff Profile' ])

@section('content')

    <div class="my-content-wrapper">
        <div class="content-container white">
            <div class="sectionWrap z-depth-0">
                <div class="sectionProfileWrap z-depth-0" style="margin-top:18px; padding:0;">
					<h5>{{ $file->name }}'s File</h5> 
					
					{{-- PROFILE INFO --}}
					<div class="profile">
						<div class="row infoWrap">
							{{-- BASIC INFORMATION --}}
							<div class="row">
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>File Number</h6>
										<p>{{ $file->file_number }}</p>
									</div>
								</div>
								<div class="col s12 l6">
									<div class="detailWrap">
										<h6>File name</h6>
										<p>{{ $file->name }}</p>
									</div>
								</div>
								<div class="col s12 l3">
									<div class="detailWrap">
										<h6>Type</h6>
										<p>{{ $file->type }}</p>
									</div>
								</div>
							</div>
						</div>
						<div class="sideColumn">
							<div class="profilePic">
								@if ($file->passport == NULL)
									<img src="{{ asset('storage/avaterMale.jpg') }}" alt="Profile Pic" width="100%">
								@else
									<img src="{{ asset('storage/files/'.$file->file_number.'/passport/'.$file->passport) }}" alt="Profile Pic" width="100%">
								@endif
							</div>
							
							
						</div>
					</div>

					{{-- PERSONNEL DOCUMENTS --}}
					<div class="fieldset">
						<legend><p>FILE DOCUMENTS</p></legend>
						<div class="docWrapper">
							@if(!$file->documents->isEmpty())
								@foreach($file->documents as $document)
									<ul>
										{{-- @hasanyrole('super admin|state admin') --}}
										<a href="#" class="deleteDocument" id="delete"><i class="tiny material-icons">close</i></a>
										{{-- @endhasanyrole --}}
										{{-- DELETE DOCUMENT FORM --}}
										<form action="{{ route('deleteFileDocument', $document->id) }}" method="post" id="deleteFileDocument">
											@method('delete')
											@csrf
										</form>

										<li>
											<a href="{{ asset('storage/files/'.$file->file_number.'/'.$document->file_name) }}" data-lightbox="documents"  data-title="{{ strtoupper($document->title) }}">
												<img src="{{ asset('storage/files/'.$file->file_number.'/'.$document->file_name) }}" width="80px">
											</a>
										</li>
										<li>{{ strtoupper($document->title) }}</li>
									</ul>
								@endforeach
							@else
								<tr>
									<td colspan="2" style="text-align:center;">No Documents Uploaded</td>
								</tr>
							@endif
						</div>
						@hasanyrole('super admin|state admin')
						<div class="table_form_wrapper">
							<form action="{{ route('personnel_upload_file', $file->id) }}" method="POST" class="card add_record_form" enctype="multipart/form-data" id="document_upload">
								@csrf
								<div class="row">
									<div class="col s12 l9 input-field">
										<input type="file" name="file[]" id="file" class="fillable" accept="image/*" style="border:none;" multiple>
									</div>
									{{-- BUTTON --}}
									<div class="input-field col s12 l3 right">
										<button class="document_upload btn waves-effect waves-light right" type="submit">
											<i class="material-icons right">send</i>UPLOAD DOCUMENT
										</button>
									</div>
								</div>
							</form>
						</div>
						@endhasanyrole
					</div>

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
		lightbox.option({
			'resizeDuration': 200,
			'wrapAround': true,
			'fitImagesInViewport': true,
			'maxHeight': 800,
			'disableScrolling': false,
		});
        $(function() {

			// PASSPORT UPLOAD
			$('#passport_upload').submit(function(){
				$('.upload_file').html(`Uploading <i class="fas fa-circle-notch fa-spin"></i>`);
			});

			// DOCS UPLOAD
			$('#document_upload').submit(function(){
				$('.document_upload').html(`Uploading <i class="fas fa-circle-notch fa-spin"></i>`);
			});

			$('.deleteDocument').click(function(event){
				event.preventDefault();
				if(confirm("Are you sure you want to delete document?")){
					event.currentTarget.nextElementSibling.submit();
				}
			});

			
        });
    </script>
@endpush