{{--{{dd($courses->category->name)}}--}}
@extends('layouts.app')

@section('title')
	EDU
@stop

@section('content')
	@include('includes.banner')
	<main class="main-content">
		<section class="services tc-padding gray-bg">
			<div class="container">
				<div class="row">
					<!-- Main Heading -->
					<div class="main-heading-holder">
						<div class="main-heading">
							<h2>Почему EDU?</h2>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing aelit, sed do eiusmod tempor incididunt.</p>
						</div>
					</div>
					<!-- Main Heading -->
				</div>
					<!-- Services Columns -->
				<div class="row">
					<div class="col-lg-4 col-md-4 col-xs-6 r-full-width">
						<div class="service-column style-3">
							<span class="service-icon"><i class="fa fa-graduation-cap"></i></span>
							<h3><a href="#">Graduation</a></h3>
							<p>Lorem ipsum dolor sit amet, consec adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna.</p>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-xs-6 r-full-width">
						<div class="service-column style-3">
							<span class="service-icon"><i class="fa fa-glass"></i></span>
							<h3><a href="#">Drink</a></h3>
							<p>Lorem ipsum dolor sit amet, consec adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna.</p>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-xs-6 r-full-width">
						<div class="service-column style-3">
							<span class="service-icon"><i class="fa fa-globe"></i></span>
							<h3><a href="#">Globe</a></h3>
							<p>Lorem ipsum dolor sit amet, consec adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna.</p>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-xs-6 r-full-width">
						<div class="service-column style-3 m-0">
							<span class="service-icon"><i class="fa fa-paperclip"></i></span>
							<h3><a href="#">Attachment</a></h3>
							<p>Lorem ipsum dolor sit amet, consec adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna.</p>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-xs-6 r-full-width">
						<div class="service-column style-3 m-0">
							<span class="service-icon"><i class="fa fa-music"></i></span>
							<h3><a href="#">audio</a></h3>
							<p>Lorem ipsum dolor sit amet, consec adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna.</p>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-xs-6 r-full-width">
						<div class="service-column style-3 m-0">
							<span class="service-icon"><i class="fa fa-cloud"></i></span>
							<h3><a href="#">Landscape</a></h3>
							<p>Lorem ipsum dolor sit amet, consec adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna.</p>
						</div>
					</div>
				</div>
					<!-- Services Columns -->
			</div>
		</section>
		<section class="comming-events tc-padding gray-bg">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<!-- Main Heading -->
						<div class="main-heading-holder">
							<div class="main-heading">
								<h2>Популярные курсы</h2>
							</div>
						</div>
						<!-- Main Heading -->
					</div>
				</div>
				<!-- Eventes Row -->
				<div class="row row-flex">
						@include('includes.popCours')
				</div>
					<div class="row">
					<div class="clearfix"></div>
					<div class="col-sm-12 margin-top-20 text-center">
						<a class="btn blank dark" href="{{route('site.categories.show')}}">Все курсы</a>
					</div>
				</div>
			</div>
		</section>
		<section class="tc-padding white-bg">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<!-- Main Heading -->
						<div class="main-heading style-2 add-p padding-bottom-30">
							<h2>Оставьте заявку</h2>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing aelit, sed do eiusmod tempor incididunt.</p>
						</div>
						<!-- Main Heading -->
						<!-- Form -->
						@include('includes.forms')
						<!-- Form -->
					</div>
				</div>
			</div>
		</section>

	</main>
@stop