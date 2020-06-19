@extends('layouts.admin')

@section('pagespecificmetas')
<meta name="context-id" content="{{ $data->id }}">
<meta name="context-type" content="{{ $data->reportable_type }}">
@stop

@section('pagespecificstyles')

@stop

@section('content')
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Report Details</h3>
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
            <form id="data-form" class="form-horizontal form-label-left" novalidate="">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="reporter-name">Reporter
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="reporter-name" name="reporter-name" value="{{ $data->reporter->name }}" required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="date">Date
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="date" id="date" name="date" value="{{ $data->created_at->format('Y-m-d') }}" required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="reported-type">Reported Type
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="reported-type" name="reported-type" value="{{ ucfirst($data->reportable_type) }}" required="required" class="form-control col-md-7 col-xs-12" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Status</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div id="status" class="btn-group" data-toggle="buttons">
                    <label class="btn btn-danger {{ ($data->status->name == 'open') ? 'active' : '' }}" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                      <input type="radio" name="status" value="open" data-parsley-multiple="status" {{ ($data->status->name == 'open') ? 'checked' : '' }} disabled> &nbsp; Open &nbsp;
                    </label>
                    <label class="btn btn-warning {{ ($data->status->name == 'investigating') ? 'active' : '' }}" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                      <input type="radio" name="status" value="investigating" data-parsley-multiple="status" {{ ($data->status->name == 'investigating') ? 'checked' : '' }} disabled> &nbsp; Investigating &nbsp;
                    </label>
                    @if ($data->reportable_type === 'news')
                    <label class="btn btn-success {{ ($data->status->name == 'approved') ? 'active' : '' }}" data-toggle="tooltip" data-placement="top" data-original-title="Delete the news instead!" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                      <input type="radio" name="status" value="approved" data-parsley-multiple="status" {{ ($data->status->name == 'approved') ? 'checked' : '' }} disabled> Approved
                    </label>
                    @else
                    <label class="btn btn-default {{ ($data->status->name == 'closed') ? 'active' : '' }}" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                      <input type="radio" name="status" value="closed" data-parsley-multiple="status" {{ ($data->status->name == 'closed') ? 'checked' : '' }} disabled> &nbsp; Closed &nbsp;
                    </label>
                    <label class="btn btn-success {{ ($data->status->name == 'solved') ? 'active' : '' }}" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                      <input type="radio" name="status" value="solved" data-parsley-multiple="status" {{ ($data->status->name == 'solved') ? 'checked' : '' }} disabled> &nbsp; Solved &nbsp;
                    </label>
                    @endif
                  </div>
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
                  @if ($data->reportable_type == 'news')
                  <a href="{{ route('cms.news.details', $data->reportable_id) }}"><button type="button" id="edit-button" class="btn btn-primary">View Reported Item</button></a>
                  @elseif ($data->reportable_type == 'user')
                  <a href="{{ route('cms.user.profile', $data->reportable_id) }}"><button type="button" id="edit-button" class="btn btn-primary">View Reported Item</button></a>
                  @else
                  <a href="{{ route('cms.news.comment.details', $data->reportable_id) }}"><button type="button" id="edit-button" class="btn btn-primary">View Reported Item</button></a>
                  @endif
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
<script type="text/javascript">
  let targetType = null;
  let targetId = null;
  let currentStatus = null;

  $( document ).ready(function (e) {
    targetType = $('meta[name="context-type"]').prop('content');
    targetId   = getContextId();
    currentStatus = $('#status input[checked]').val();

    updateDisabledButton();
  });

  $('#status label').on('click', function (e){
    let status = $(this).find('input').val();

    if (currentStatus === 'solved' || currentStatus === 'approved' || (status === currentStatus)) {
      return undefined;
    }

    if ((currentStatus === 'investigating' || currentStatus === 'closed') && status === 'open') {
      return undefined;
    }
    if (currentStatus === 'solved' || currentStatus === 'approved') {
      nopeAlert();
      return undefined;
    }

    updateStatus(status);
  });

  function updateStatus(status, type=targetType) {
    let availableStatus = ['open', 'investigating', 'closed', 'solved', 'approved'];

    if (! availableStatus.includes(status)) {
      console.error('Invalid status supplied.');
      return undefined;
    }
    if (type === 'news' && status === 'solved') {
      console.error('This status is unavailable for news.');
      return undefined;
    }
    if (type !== 'news' && status === 'approved') {
      console.error('This status is unavailable for this item.');
      return undefined;
    }

    if (type === 'news' && status === 'approved') {
      swalConfirm(function (c) {

        axios.put(gCmsApiBase + '/report/' + targetId, {status: 'approved'})
        .then(function (response) {
          swal('Success!', 'Status updated!', 'success');
          $('#status input').attr('checked', false);
          $('#status input[value="'+ status +'"]').attr('checked', true);
          updateDisabledButton();
        })
        .catch(function (error) {
          swal('Error!', 'Failed to update the status!', 'error');
        });

      }, 'Attention!', 'Deleting the news will set all reports for this particular news to approved. It is recommended to delete the news instead of updating the report status. Continue?');
    } else {
      swalConfirm(function (c) {

        axios.put(gCmsApiBase + '/report/' + targetId, {status: status})
        .then(function (response) {
          swal('Success!', 'Status updated!', 'success');
          $('#status input').attr('checked', false);
          $('#status input[value="'+ status +'"]').attr('checked', true);
          updateDisabledButton();
        })
        .catch(function (error) {
          swal('Error!', 'Failed to update the status!', 'error');
        });

      }, 'Are you sure?', 'Once updated, the status cannot be reverted to previous status.', 'question');
    }
  }

  function updateDisabledButton() {
    currentStatus = $('#status input[checked]').val();

    $('#status label input[type="radio"]').each(function (i, el){
        $(el).attr('disabled', false);
        $(el).parent().attr('disabled', false);
    });

    if (currentStatus === 'solved' || currentStatus === 'approved' || currentStatus === 'closed' || currentStatus === 'investigating') {
      switch (currentStatus) {
        case 'investigating':
          elements = $('#status label input[type="radio"]:not([value="investigating"]):not([value="closed"]):not([value="solved"]):not([value="approved"])');
          break;

        case 'closed':
          elements = $('#status label input[type="radio"]:not([value="investigating"]):not([value="closed"]):not([value="solved"]):not([value="approved"])');
          break;

        default:
          elements = $('#status label input[type="radio"]:not([value="solved"]):not([value="approved"])');
          break;
      }

      elements.each(function (i, el){
        $(el).attr('disabled', true);
        $(el).parent().attr('disabled', true);
      })
    }
  }

  function nopeAlert() {
    swal('Warning!', 'You cannot update to this status!', 'error');
  }
</script>
@stop