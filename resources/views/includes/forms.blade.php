
<form id="contact-form" class="row" action="{{route('feedback.store')}}" method="POST">
    <div class="col-sm-4 col-xs-4 r-full-width padding-top-30">
        <div class="form-group">
            <input type="text" name="name" required autocomplete="off">
            <label class="control-label">Имя</label><i class="bar"></i>
        </div>
    </div>
    <div class="col-sm-4 col-xs-4 r-full-width padding-top-30">
        <div class="form-group">
            <input type="text" name="email" required autocomplete="off">
            <i class="bar"></i>
            <label class="control-label">Email</label>
        </div>
    </div>
    <div class="col-sm-4 col-xs-4 r-full-width padding-top-30">
        <div class="form-group">
            <input type="text" name="phone" id="phone_number" required autocomplete="off" maxlength="11">
            <label class="control-label">Телефон</label><i class="bar"></i>
        </div>
    </div>
    <div class="col-sm-12 col-xs-12 padding-top-30">
        <div class="form-group m-0">
            <textarea name="message" required autocomplete="off"></textarea>
            <label class="control-label">Сообщение</label><i class="bar"></i>
        </div>
    </div>
    <div class="col-sm-12 col-xs-12">
        <span class=" text-center modal-message"></span>
        <button type="submit" class="btn-submit btn blue z-depth-1" style="margin-top: 50px">Отправить<i class="fa fa-send"></i></button>
    </div>
</form>
