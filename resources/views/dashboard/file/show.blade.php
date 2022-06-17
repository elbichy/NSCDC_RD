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
								<div style="'display:flex; align-items: center;">
									<span>No Documents Uploaded</span>
								</div>
							@endif
						</div>
						{{-- @hasanyrole('super admin|state admin') --}}
						<div class="table_form_wrapper">
							<form action="{{ route('file_upload_document', $file->id) }}" method="POST" class="card add_record_form" enctype="multipart/form-data" id="document_upload">
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
						{{-- @endhasanyrole --}}
					</div>

					{{-- MENU COLLECTION --}}
					<div class="fixed-action-btn">
						<a class="btn-floating btn-large waves-effect waves-light blue darken-3">
							{{-- <i class="large material-icons">mode_edit</i> --}}
							<i class="fas fa-bars fa-lg"></i>
						</a>
						<ul>
							<li>
								<a href="{{ route('file_edit', $file->id) }}" title="Edit File" class="btn-floating blue editFile">
									<i style="font-size: 1.33333em;" class="fas fa-pencil fa-lg"></i>
								</a>
							</li>

							<li>
								<a href="£" class="deleteFile btn-floating red" title="Delete File">
									<i style="font-size: 1.33333em;" class="fas fa-trash fa-lg"></i>
								</a>
								{{-- DELETE FILE FORM --}}
								<form action="{{ route('file_delete', $file->id) }}" method="post" id="deleteFile">
									@method('delete')
									@csrf
								</form>
							</li>

						</ul>
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
	@if ($errors->any())
    <script>
        $(function() {
            $('.editFileModal').modal('open');
        });
    </script>
	@endif
    <script>
		lightbox.option({
			'resizeDuration': 200,
			'wrapAround': true,
			'fitImagesInViewport': true,
			'maxHeight': 800,
			'disableScrolling': false,
		});
        $(function() {

			$('.fixed-action-btn').floatingActionButton({
				direction: 'left'
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

			$('.deleteFile').click(function(event){
				event.preventDefault();
				if(confirm("Are you sure you want to delete the entire file?")){
					event.currentTarget.nextElementSibling.submit();
				}
			});

			
        });
    </script>
@endpush