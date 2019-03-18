@extends('layouts.app')

@section('title', 'スケジュール管理画面')

@section('link')
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.css" /> --}}
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
@endpush