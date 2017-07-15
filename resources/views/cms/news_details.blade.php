@extends('layouts.admin')

@section('pagespecificmetas')
<meta name="context-id" content="{{ $data->id }}">
@stop

@section('pagespecificstyles')
<!-- datatables css-->
<link href="{{ asset('admin/css/tables.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>News Details</h3>
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
            <form id="data-form" method="POST" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
              <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Title <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="title" name="title" value="{{ $data->title }}" required="required" minlength="3" class="form-control col-md-7 col-xs-12" disabled>
                </div>
              </div>
              <div class="form-group {{ $errors->has('author') ? ' has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="author">Author
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="author" name="author" value="{{ $data->author }}" required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Publication Time
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="pubtime" name="pubtime" value="{{ Carbon\Carbon::parse($data->pubtime)->format('Y-m-d\TH:i:s') }}" class="form-control col-md-7 col-xs-12" required="required" type="datetime-local" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Category
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="category" name="category" value="{{ ucfirst($data->category->name) }}" class="form-control col-md-7 col-xs-12" required="required" type="text" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Source
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="source" name="source" value="{{ $data->source->source_name }}" class="form-control col-md-7 col-xs-12" required="required" type="text" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Content <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea id="content" name="content" class="form-control col-md-7 col-xs-12" required="required" data-parsley-minlength="400" disabled>
                  {!! $data->content !!}
                  </textarea>
                </div>
              </div>
              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <button type="submit" id="edit-button" class="btn btn-success">Update</button>
                  <a href="{{ $data->url }}" target="_blank"><button type="button" class="btn btn-info">View Original</button></a>
                  <button type="button" id="recycle-button" class="btn btn-{{ ($data->deleted_at) ? 'primary' : 'danger' }} {{ ($data->deleted_at) ? 'restore-button' : '' }}">{{ ($data->deleted_at) ? 'Restore' : 'Delete' }}</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Comments</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
              <li><a class="close-link"><i class="fa fa-close"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <table id="noox-comments" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Author</th>
                        <th># Reports</th>
                        <th># Replies</th>
                        <th>Comment Content</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Author</th>
                        <th># Reports</th>
                        <th># Replies</th>
                        <th>Comment Content</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Reports</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
              <li><a class="close-link"><i class="fa fa-close"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <table id="noox-news-reports" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Content</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Content</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('pagespecificscripts')
<script src="https://cdn.ckeditor.com/4.7.1/standard/ckeditor.js"></script>
<script type="text/javascript">
  let form    = $('#data-form');
  let button  = $('#edit-button');
  let targetId = null;
  let editor = null;

  $( document ).ready(function (e) {
    targetId = getContextId();

    if (isNaN(targetId)) {targetId = '';}

    editor = CKEDITOR.replace( 'content' );
  });

  button.on('click', function(e){
    e.preventDefault();

    if (button.hasClass('submit-button')) {
      if (editor.updateElement() && form.parsley().validate()) {
        form.find('span.help-block').remove();
        form.find('.form-group').toggleClass('has-error', false);

        axios.put(gCmsApiBase + '/news/' + targetId, form.serialize())
        .then(function (response) {
          button.removeClass('submit-button');
          form.find('input,textarea').prop('disabled', true);
          editor.setReadOnly(true);

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
      form.find('input,textarea').prop('disabled', false);
      editor.setReadOnly(false);
    }
  });

  function processError(invalidInputs) {
    for (var input in invalidInputs) {
      let el = form.find('input[name=' + input + '],textarea[name=' + input + ']');
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

<!-- delete button script -->
<script type="text/javascript">
  let delButton = $('#recycle-button');

  function deleteNews() {
    axios.delete(gCmsApiBase + '/news/' + targetId)
    .then(function (response) {
      delButton.toggleClass('restore-button', true);
      delButton.addClass('btn-primary').removeClass('btn-danger');
      delButton.html('Restore');
      swal(
        'Deleted!',
        'News deleted!',
        'success'
      )
    })
    .catch(function (error) {
      if (error.response.status === 422) {
        swal(
          'Error!',
          'News already deleted!',
          'error'
        )
      }
    });
  }

  function restoreNews() {
    axios.post(gCmsApiBase + '/news/' + targetId + '/restore')
    .then(function (response) {
      delButton.toggleClass('restore-button', false);
      delButton.addClass('btn-danger').removeClass('btn-primary');
      delButton.html('Delete');
      swal(
        'Restored!',
        'News restored!',
        'success'
      )
    })
    .catch(function (error) {
      if (error.response.status === 422) {
        swal(
          'Error!',
          'News not deleted!',
          'error'
        )
      }
    });
  }

  delButton.on('click', function (e){
    if (delButton.hasClass('restore-button')) {
      swalConfirm(restoreNews, 'Restore this news?', 'The news will be searchable by the users.');
    } else {
      swalConfirm(deleteNews, 'Are you sure?', 'Deleted news can be accessed through the deleted news page.');
    }
  });
</script>

<!-- tables instantiation script -->
<script type="text/javascript">
  $( document ).ready(function (e) {
    let contextId = getContextId();
    /**
     * Comment table.
     */
    attachDT('#noox-comments', 'news/' + contextId +'/comments', 
        {columns: [
                { data: 'id' },
                { data: 'author.name' },
                { data: 'reports_count', searchable: false },
                { data: 'replies_count', searchable: false },
                { data: 'content', searchable: false},
                { data: 'created_at' },
                { data: 'action', sortable: false, searchable: false }
            ],
        columnDefs: [ 
              {
                  targets: [0, 2, 3],
                  width: "5%"
              },
              {
                  targets: 1,
                  width: "10%"
              },
              {
                  targets: 4,
                  width: "50%"
              }
          ]
        }
    );

    /**
     * Reports table.
     */
    attachDT('#noox-news-reports', 'news/' + contextId +'/reports', 
      {columns: [
              { data: 'id' },
              { data: 'reporter.name' },
              { data: 'created_at' },
              { data: 'content', searchable: false},
              { data: 'action', sortable: false, searchable: false }
          ],
      columnDefs: [ 
              {
                  targets: [1, 2],
                  width: "20%"
              },
              {
                  targets: 3,
                  width: "50%"
              }
          ]
      }
    );
  });
</script>
@stop