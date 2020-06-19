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
              <div class="form-group {{ $errors->has('author') ? ' has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="author">Author
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="author" name="author" value="{{ $data->author->name }}" required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Created At
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="created" name="created" value="{{ Carbon\Carbon::parse($data->created_at)->format('Y-m-d\TH:i:s') }}" class="form-control col-md-7 col-xs-12" required="required" type="datetime-local" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">News
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="news" name="news" value="{{ $data->news->title }}" class="form-control col-md-7 col-xs-12" required="required" type="text" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Is Reply
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="source" name="source" value="{{ ($data->parent_id) ? 'Yes' : 'No' }}" class="form-control col-md-7 col-xs-12" required="required" type="text" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Content
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea id="content" name="content" class="form-control col-md-7 col-xs-12" required="required" disabled>{{ $data->content }}</textarea>
                </div>
              </div>
              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <button type="button" id="recycle-button" class="btn btn-{{ ($data->deleted_at) ? 'primary' : 'danger' }} {{ ($data->deleted_at) ? 'restore-button' : '' }}">{{ ($data->deleted_at) ? 'Restore' : 'Delete' }}</button>
                  @can('permanentDelete', Noox\Models\Admin::class)
                  <button type="button" id="perma-delete-button" class="btn btn-danger">Permanent Delete</button>
                  @endcan
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    @if (! $data->parent_id)
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Replies</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
              <li><a class="close-link"><i class="fa fa-close"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <table id="noox-comment-replies" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Author</th>
                        <th># Reports</th>
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
    @endif
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
            <table id="noox-comment-reports" class="table table-striped table-bordered" cellspacing="0" width="100%">
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
@can('permanentDelete', Noox\Models\Admin::class)
<form id="perma-delete-form" action="{{ route('cms.news.comment.delete', $data->id) }}" method="POST" style="display: none;">
  {{ csrf_field() }}
</form>
@endcan
@endsection

@section('pagespecificscripts')
<script type="text/javascript">
  let targetId = null;

  $( document ).ready(function (e) {
    targetId = getContextId();
  });
</script>

<!-- delete button script -->
<script type="text/javascript">
  let delButton = $('#recycle-button');

  function deleteItem() {
    axios.delete(gCmsApiBase + '/news/comment/' + targetId)
    .then(function (response) {
      delButton.toggleClass('restore-button', true);
      delButton.addClass('btn-primary').removeClass('btn-danger');
      delButton.html('Restore');
      swal(
        'Deleted!',
        'Comment deleted!',
        'success'
      )
    })
    .catch(function (error) {
      if (error.response.status === 422) {
        swal(
          'Error!',
          'Comment already deleted!',
          'error'
        )
      }
    });
  }

  function restoreItem() {
    axios.post(gCmsApiBase + '/news/comment/' + targetId + '/restore')
    .then(function (response) {
      delButton.toggleClass('restore-button', false);
      delButton.addClass('btn-danger').removeClass('btn-primary');
      delButton.html('Delete');
      swal(
        'Restored!',
        'Comment restored!',
        'success'
      )
    })
    .catch(function (error) {
      if (error.response.status === 422) {
        swal(
          'Error!',
          'Comment not deleted!',
          'error'
        )
      }
    });
  }

  delButton.on('click', function (e){
    if (delButton.hasClass('restore-button')) {
      swalConfirm(restoreItem, 'Restore this comment?', 'The comment will be searchable by the users.');
    } else {
      swalConfirm(deleteItem, 'Are you sure?', 'Deleted comment can be accessed through the deleted comment page.');
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
    attachDT('#noox-comment-replies', 'news/comment/' + contextId +'/replies', 
        {columns: [
                { data: 'id' },
                { data: 'author.name' },
                { data: 'reports_count', searchable: false },
                { data: 'content', searchable: false},
                { data: 'created_at' },
                { data: 'action', sortable: false, searchable: false }
            ],
        columnDefs: [ 
              {
                  targets: [0, 2],
                  width: "5%"
              },
              {
                  targets: 1,
                  width: "10%"
              },
              {
                  targets: 3,
                  width: "50%"
              }
          ]
        }
    );

    /**
     * Reports table.
     */
    attachDT('#noox-comment-reports', 'news/comment/' + contextId +'/reports', 
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

@can('permanentDelete', Noox\Models\Admin::class)
<!-- permanent delete script -->
<script type="text/javascript">
  let perDelButton = $('#perma-delete-button');

  function permaDeleteItem() {
    form = $('#perma-delete-form');
    form.submit();
  }

  perDelButton.on('click', function (e){
      swalConfirm(permaDeleteItem, 'WARNING', 'This comment and its replies will be deleted permanently! Proceed?', 'warning');
  });
</script>
@endcan
@stop