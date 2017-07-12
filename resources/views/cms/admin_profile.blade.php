@extends('layouts.admin')

@section('pagespecificstyles')

@stop

@section('content')
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Administrator Details</h3>
      </div>

      <div class="title_right">
        <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Search for...">
            <span class="input-group-btn">
              <button class="btn btn-default" type="button">Go!</button>
            </span>
          </div>
        </div>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Data</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
              <li><a class="close-link"><i class="fa fa-close"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <br>
            <form id="data-form" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
              <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="name" name="name" value="{{ $user->name }}" required="required" minlength="3" class="form-control col-md-7 col-xs-12" disabled>
                </div>
              </div>
              <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="email" id="email" name="email" value="{{ $user->email }}" required="required" class="form-control col-md-7 col-xs-12" disabled>
                </div>
              </div>
              <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Password
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="password" id="password" name="password" minlength="5" class="form-control col-md-7 col-xs-12" disabled>
                  <span>
                    <strong>Leave blank if there's no change.</strong>
                  </span>
                </div>
              </div>
              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <button type="" class="btn btn-success">Update</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('pagespecificscripts')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.7.2/parsley.min.js"></script>

<script type="text/javascript">
  let form    = $('#data-form');
  let button  = $('#data-form button');
  let adminId = null;

  $( document ).ready(function (e) {
    pathname = window.location.pathname.split('/');
    adminId  = parseInt(pathname[pathname.length - 1]);

    if (isNaN(adminId)) {adminId = '';}
  });

  button.on('click', function(e){
    e.preventDefault();

    if (button.hasClass('submit-button')) {
      if (form.parsley().validate()) {
        form.find('span.help-block').remove();
        form.find('.form-group').toggleClass('has-error', false);

        axios.put(gCmsApiBase + '/admin/' + adminId, form.serialize())
        .then(function (response) {
          button.removeClass('submit-button');
          form.find('input').prop('disabled', true);

          spawnNoty('Data successfully updated!', 'success');
        })
        .catch(function (error) {
          if (error.response.status === 422) {
            processError(error.response.data);
          }
        });

      }
    } else {
      button.addClass('submit-button');
      form.find('input').prop('disabled', false);
    }
  });

  function processError(invalidInputs) {
    for (var input in invalidInputs) {
      let el = form.find('input[name=' + input + ']');
      el.closest('.form-group').toggleClass('has-error', true);

      let errContainer = el.next('span.help-block');
      if (! errContainer.length) {
        el.after(
        `
          <span class="help-block">
          </span>
        `);
        errContainer = el.next('span.help-block');
      }

      invalidInputs[input].forEach(function (d, i, a){
        a[i] = '<strong>' + d + '</strong>';
      });

      let errorMsgs = invalidInputs[input].join('<br>');
      errContainer.html(errorMsgs);
    }
  }
</script>
@stop