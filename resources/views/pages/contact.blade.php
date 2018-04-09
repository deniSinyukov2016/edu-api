@extends('layouts.app')

@section('content')
    @include('includes.banner-contact')
    <main class="main-content">

        <!-- Adress Nd Map -->
        <section>
            <div class="container contact-inner">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-5">
                        <div class="contact-address z-depth-1">
                            <h3>Контакты</h3>
                            <p>PO Box CT16122 Collins Street West, Victoria 8007, Australia.</p>
                            <ul class="address-list">
                                <li><i class="fa fa-phone"></i>+1 (2) 345 6789</li>
                                <li><i class="fa fa-fax"></i>+1 (2) 345 6789</li>
                                <li><i class="fa fa-envelope"></i>contact@yourdomain.com</li>
                            </ul>
                            <div class="social-icons-2">
                                <ul>
                                    <li><a class="fa fa-vk" href="#"></a></li>
                                    <li><a class="fa fa-facebook-official" href="#"></a></li>
                                    <li><a class="fa fa-send-o" href="#"></a></li>
                                    <li><a class="fa fa-instagram" href="#"></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-7">
                        <div id="contact-map" class="contact-map-holder z-depth-1"></div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Adress Nd Map -->

        <!-- Contact Form -->
        <section class="tc-padding white-bg">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                    <!-- Main Heading -->
                        <div class="main-heading style-2 add-p">
                            <h2>Запишитесь на курс</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing aelit, sed do eiusmod tempor incididunt.</p>
                        </div>
                    <!-- Main Heading -->
                    </div>
                </div>
                <!-- Form -->
                <form id="contact-form" class="row">
                    <div class="col-sm-4 col-xs-4 r-full-width padding-top-20">
                        <div class="form-group">
                            <input type="text" name="name" required autocomplete="off">
                            <label class="control-label">Имя</label><i class="bar"></i>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-4 r-full-width padding-top-20">
                        <div class="form-group">
                            <input type="text" name="email" required autocomplete="off">
                            <i class="bar"></i>
                            <label class="control-label">Email</label>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-4 r-full-width padding-top-20">
                        <div class="form-group">
                            <input type="text" name="phone" required autocomplete="off">
                            <label class="control-label">Телефон</label><i class="bar"></i>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xs-12 padding-top-20">
                        <div class="form-group m-0">
                            <textarea name="message" required autocomplete="off"></textarea>
                            <label class="control-label">Сообщение</label><i class="bar"></i>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xs-12 padding-top-20">
                        <button type="submit" class="btn-submit btn blue z-depth-1">Отправить<i class="fa fa-send"></i></button>
                    </div>
                </form>
                <!-- Form -->
            </div>
        </section>
        <!-- Contact Form -->
    </main>
@stop