@extends('layouts.app')

@section('content')
	<div class="my-content-wrapper">
		<div class="content-container white">
			<div class="sectionWrap">
				{{-- STATISTICS --}}
				<div id="card-stats">
					<div class="row mt-1" style="margin: 0;">
						<div class="col s12 m6 l3">
							<div class="card gradient-45deg-green-teal gradient-shadow min-height-100 white-text">
								<div class="padding-4">
									<div class="col s4 m4">
										<i class="fas fa-users fa-2x background-round mt-5"></i>
									</div>
									<div class="col s8 m8 right-align">
										<h5 class="mb-0">5000</h5>
										<p class="no-margin">Total Personnel</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col s12 m6 l3">
							<div class="card gradient-45deg-light-blue-cyan gradient-shadow min-height-100 white-text">
								<div class="padding-4">
									<div class="col s4 m4">
										<i class="fas fa-siren-on fa-2x background-round mt-5"></i>
									</div>
									<div class="col s8 m8 right-align">
										<h5 class="mb-0">75</h5>
										<p class="no-margin">Total Formations</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col s12 m6 l3">
							<div class="card gradient-45deg-amber-amber gradient-shadow min-height-100 white-text">
								<div class="padding-4">
									<div class="col s4 m4">
										<i class="material-icons small background-round mt-5">arrow_downward</i>
									</div>
									<div class="col s8 m8 right-align">
										<h5 class="mb-0">500</h5>
										<p class="no-margin">Commissioned</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col s12 m6 l3">
							<div class="card gradient-45deg-red-pink gradient-shadow min-height-100 white-text">
								<div class="padding-4">
									<div class="col s4 m4">
										<i class="material-icons small background-round mt-5">arrow_upward</i>
									</div>
									<div class="col s8 m8 right-align">
										<h5 class="mb-0">4500</h5>
										<p class="no-margin">Other Ranks</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="footer z-depth-1">
			<p>&copy; NSCDC ICT & Cybersecurity Department</p>
		</div>
	</div>
@endsection
@push('scripts')
    <script>
    </script>
@endpush