@extends('backend.layouts.master')

@section('title')	
	Path | List Job Location
@endsection

@section('css')
@endsection

@section('main-content')
	<div class="content d-flex flex-column flex-column-fluid" id="kt_content">

		<div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
			<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
				<div class="d-flex align-items-center flex-wrap mr-1">
					<div class="d-flex align-items-baseline flex-wrap mr-5">
						<h5 class="text-dark font-weight-bold my-1 mr-5">Job Location</h5>
						<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
							<li class="breadcrumb-item">
								<a href="{{route('editLocation', $location->id)}}" class="text-muted">Edit Job Location</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<div class="d-flex flex-column-fluid">
			<div class="container">
				<div class="card card-custom">
					<div class="card-header">
						<h3 class="card-title">Edit Job Location</h3>
						<div class="card-toolbar">
							<div class="example-tools justify-content-center">
								<span class="example-toggle" data-toggle="tooltip" title="View code"></span>
								<span class="example-copy" data-toggle="tooltip" title="Copy code"></span>
							</div>
						</div>
					</div>

					<form method="POST" action="{{route('updateLocation')}}" enctype="multipart/form-data">
						@csrf
						<div class="card-body">
							<div class="row">
								<div class="col-12">
									<div class="form-group">
										<label>Location <span class="text-danger">*</span></label>
										<input type="text" class="form-control"  placeholder="Enter Location Name" name="location" value="{{$location->location}}" required />
										@error('location')
											<span class="invalid-feedback" role="alert">
												{{ $message }}
											</span>
										@enderror
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer" style="text-align: end">
							<input type="hidden" name="id" value="{{$location->id}}"/>
							<button type="submit" class="btn btn-primary mr-2">Submit</button>
							<button type="reset" class="btn btn-secondary">Cancel</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('script')
@endsection