@extends('layouts.app')

@section('title', 'スケジュール管理画面')

@section('link')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.css" />
@endsection

@section('content')
<div class="card">
  <div class="card-body">
    <div id="calendar"></div>
  </div>
</div>

{{-- Modal Edit or Delete --}}
<div class="modal fade" id="calendarModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
    <div class="modal-content">
      <form method="POST" action="" data-apo-id="" accept-charset="UTF-8" id="modalForm">
        <input type="hidden" name="_method" value="">
        {{ csrf_field() }}
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modalBody">
          <div class="form-group row">
            <label for="appointment-title" class="col-sm-2 col-form-label">予定タイトル</label>
            <div class="col-sm-10">
              <input type="text" name="appointment-title" class="form-control" id="appointment-title">
            </div>
          </div>
          <div class="form-group row">
            <label for="appointment-description" class="col-sm-2 col-form-label">詳細</label>
            <div class="col-sm-10">
              <textarea class="form-control" id="appointment-description" name="appointment-description" rows="5"></textarea>
            </div>
          </div>
          <div class="form-group row">
            <label for="start-date" class="col-sm-2 col-form-label">予定日</label>
            <div class="col-sm-10">
              <input type="date" name="start-date" class="form-control" id="start-date" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="start-time" class="col-sm-2 col-form-label">開始時間</label>
            <div class="col-sm-10">
              <input type="time" name="start-time" class="form-control" id="start-time" step="300" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="end-time" class="col-sm-2 col-form-label">終了時間</label>
            <div class="col-sm-10">
              <input type="time" name="end-time" class="form-control" id="end-time" step="300" required>
            </div>
          </div>

          <div class="form-group row">
            <label for="team-member" class="col-sm-2 col-form-label">チーム</label>
            <div class="col-sm-10">
              <select id="team-member" class="selectpicker form-control" name="team-user-id[]" multiple title="選択してください"
                required>
                @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row">
            <label for="color-code" class="col-sm-2 col-form-label">カラー</label>
            <div class="col-sm-10">
              <div id="color-code" class="color-picker"></div>
              <input id="color" type="hidden" name="color" required>
              <input id="text-color" type="hidden" name="text-color" required>
            </div>
          </div>
        </div>
        <div class="modal-footer" id="modalFooter">
        </div>
      </form>
    </div>
  </div>
</div>
@endsection


@push('scripts')
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script> --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.js"></script> --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar-scheduler/1.9.4/scheduler.min.js"></script> --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/gcal.min.js"></script> --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/locale/ja.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.es5.min.js"></script>
<script>
$("#calendarModal").on("show.bs.modal", function () {
  var defaultColor = $("#color").attr('value');

  const pickr = new Pickr({
    el: '.color-picker',

    default: defaultColor,

    swatches: [
      'rgba(244, 67, 54, 1)',
      'rgba(233, 30, 99, 0.95)',
      'rgba(156, 39, 176, 0.9)',
      'rgba(103, 58, 183, 0.85)',
      'rgba(63, 81, 181, 0.8)',
      'rgba(33, 150, 243, 0.75)',
      'rgba(3, 169, 244, 0.7)',
      'rgba(0, 188, 212, 0.7)',
      'rgba(0, 150, 136, 0.75)',
      'rgba(76, 175, 80, 0.8)',
      'rgba(139, 195, 74, 0.85)',
      'rgba(205, 220, 57, 0.9)',
      'rgba(255, 235, 59, 0.95)',
      'rgba(255, 193, 7, 1)'
    ],

    components: {
      // Main components
      preview: true,
      opacity: true,
      hue: true,

      // Input / output Options
      interaction: {
        hex: true,
        rgba: true,
        input: true,
        clear: true,
        save: true
      }
    },
    strings: {
      clear: '取消', // Default for clear button
      save: '保存', // Default for save button
    }
  });

  // type="button"追加
  $('.pickr > button, .pcr-color-preview > button, .swatches > button').attr('type', 'button');

  pickr.on('save', function(hsva) {
    let eventColor = hsva.toHEX().toString();
    $("#color").attr('value', eventColor);
    $("#text-color").attr('value', blackOrWhite(eventColor));
  });
});


// textColor white OR Black
function blackOrWhite(hexcolor) {
  var r = parseInt(hexcolor.substr(1, 2), 16);
  var g = parseInt(hexcolor.substr(3, 2), 16);
  var b = parseInt(hexcolor.substr(5, 2), 16);
  return ((((r * 299) + (g * 587) + (b * 114)) / 1000) < 128) ? "#fff" : "#343a40";
}

$("#calendarModal").on("hidden.bs.modal", function () {
  // put your default event here
  gEventRenders = [];

  $("div .pickr").before('<div id="color-code" class="color-picker"></div>');
  $("div .pickr").remove();
  $("#color").attr('value', '');
  $("#text-color").attr('value', '');

  $("#calendar").fullCalendar('refetchEvents');
});



</script>
@endpush