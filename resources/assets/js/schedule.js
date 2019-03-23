import $ from 'jquery';
import moment from 'moment';
import 'fullcalendar-scheduler';
import Pickr from '@simonwep/pickr/dist/pickr.min';
import selectpicker from 'bootstrap-select/dist/js/bootstrap-select.js';

let gEvents = [];
let gEventRenders = [];
let gResources = [];

// textColor white OR Black
function blackOrWhite(hexcolor) {
  var r = parseInt(hexcolor.substr(1, 2), 16);
  var g = parseInt(hexcolor.substr(3, 2), 16);
  var b = parseInt(hexcolor.substr(5, 2), 16);
  return ((((r * 299) + (g * 587) + (b * 114)) / 1000) < 128) ? "#fff" : "#343a40";
}


$(document).ready(function () {

  $('#modalForm').on('click', 'update-btn', function() {
    $('input[type="hidden"]' + 'input[name="_method"]').val("PUT");
  });

  $('#modalForm').on('click', '#delete-btn', function() {
    if(!confirm('本当に削除しますか？')){
          /* キャンセルの時の処理 */
          return false;
      } else {
          /*　OKの時の処理 */
          $('input[type="hidden"]' + 'input[name="_method"]').val("DELETE");
      }
  });

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
      let eventColor = hsva.toHEX();
      let colorData = "#";
      for (var i = 0; i < 3; i++) {
        colorData += eventColor[i].toUpperCase()
      }
      $("#color").attr('value', colorData);
      $("#text-color").attr('value', blackOrWhite(colorData));
    });
  });



  $("#calendarModal").on("hidden.bs.modal", function () {
    // put your default event here
    gEventRenders = [];

    $("div .pickr").before('<div id="color-code" class="color-picker"></div>');
    $("div .pickr").remove();
    $("#color").attr('value', '');
    $("#text-color").attr('value', '');

    $("#calendar").fullCalendar('refetchEvents');
  });

});

function eventDropFunc(event, delta, revertFunc, jsEvent, ui, view) {
  var team = [];
  var beforeStaffId;
  var affterStaffId = event.resourceId;
  // var gEventRenders = gEvents;
  gEventRenders.forEach(function (element) {
    if (event._id === element._id) {
      if (event.resourceId != element.resourceId) {
        beforeStaffId = element.resourceId;
      }
    }

    if (event.apoId === element.apoId) {
      if (beforeStaffId != element.resourceId && affterStaffId != element.resourceId) {
        const exists = team.some(c => c === element.resourceId);
        if (exists === false) {
          team.push(element.resourceId);
        }
      }
    }
  });

  team.push(affterStaffId);

  $("#modalForm").attr('data-apo-id', event.apoId);
  var dataApoId = $("#modalForm").attr('data-apo-id');
  var action = $("#modalForm").attr("action", `//${location.hostname}//appointments/${dataApoId}`);

  $("#modalTitle").html('スケジュール変更'); // モーダルのタイトルをセット

  var appointmentTitle = $("#appointment-title");
  appointmentTitle.attr('value', event.title);

  var appointmentDescription = $("#appointment-description");
  appointmentDescription.val(event.description);

  var startDate = moment(event.start._i);
  var endDate = moment(event.end._i);

  $("#start-date").attr('value', startDate.format("YYYY-MM-DD"));
  $("#start-time").attr('value', startDate.format("HH:mm"));
  $("#end-time").attr('value', endDate.format("HH:mm"));

  $(".selectpicker").selectpicker('val', team);

  $("#color").attr('value', event.color);
  $("#text-color").attr('value', event.textColor);

  // footer関連
  $("#modalFooter").empty();

  $('input[type="hidden"]' + 'input[name="_method"]').val("PUT");

  $("#modalFooter").append(
    '<button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>' +
    '<button type="submit" id="editStoreBtn" class="btn btn-primary">予定を変更</button>'
  );
  $("#calendarModal").modal(); // モーダル着火
}

function dayClickFunc(calEvent, jsEvent, view, resourceObj) {
  // console.log(calEvent, jsEvent, view, resourceObj);
  $("#modalForm").attr('data-apo-id', '');
  // var dataApoId = $("#modalForm").attr('data-apo-id');
  $("#modalForm").attr("action", `//${location.hostname}/appointments`);
  $('input[type="hidden"]' + 'input[name="_method"]').val("");

  $("#modalTitle").html('スケジュール作成'); // モーダルのタイトルをセット

  var appointmentTitle = $("#appointment-title");
  appointmentTitle.attr('value', '');

  var appointmentDescription = $("#appointment-description");
  appointmentDescription.val('');

  var dayMoment = moment(calEvent._i);
  $("#start-date").attr('value', dayMoment.format("YYYY-MM-DD"));
  $("#start-time").attr('value', dayMoment.format("HH:mm"));
  $("#end-time").attr('value', dayMoment.add(1, 'h').format("HH:mm"));

  $(".selectpicker").selectpicker('val', '');

  $("#color").attr('value', '#3a87ad');
  $("#text-color").attr('value', '#fff');

  $(".selectpicker").selectpicker('val', resourceObj.id);

  $("#modalFooter").empty();
  $("#modalFooter").append(
    '<button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>' +
    '<button type="submit" id="editStoreBtn" class="btn btn-primary">予定を作成</button>'
  );
  $("#calendarModal").modal(); // モーダル着火
}


function eventClickFunc(calEvent) {
  $("#modalForm").attr('data-apo-id', calEvent.apoId);
  var dataApoId = $("#modalForm").attr('data-apo-id');
  var action = $("#modalForm").attr("action", `//${location.hostname}/appointments/${dataApoId}`);

  $("#modalTitle").html('スケジュール編集'); // モーダルのタイトルをセット

  var appointmentTitle = $("#appointment-title");
  appointmentTitle.attr('value', calEvent.title);

  var appointmentDescription = $("#appointment-description");
  appointmentDescription.val(calEvent.description);

  var startDate = moment(calEvent.start._i);
  var endDate = moment(calEvent.end._i);
  $("#start-date").attr('value', startDate.format("YYYY-MM-DD"));
  $("#start-time").attr('value', startDate.format("HH:mm"));
  $("#end-time").attr('value', endDate.format("HH:mm"));

  $("#color").attr('value', calEvent.color);
  $("#text-color").attr('value', calEvent.textColor);

  var team = [];
  gEvents.forEach(function (gEvent) {
    if (gEvent.apoId === calEvent.apoId) {
      team.push(gEvent.resourceId);
    }
  });
  $(".selectpicker").selectpicker('val', team);

  // footer関連
  $("#modalFooter").empty();
  $("#modalFooter").append(
    '<button type="submit" id="delete-btn" class="btn btn-danger mr-auto">削除</button>' +
    '<button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>' +
    '<button type="submit" id="update-btn" class="btn btn-primary">変更を保存</button>'
  );
  $("#calendarModal").modal(); // モーダル着火
}

function getResources(handleData) {
  axios({
    method: "get",
    url: `//${location.hostname}/api/users`,
    params: {
      _: Date.now()
    }
  }).then(function (response) {
    var resData = response.data;
    var resources = [];
    resData.forEach(function (element) {
      resources.push({
        id: element.id,
        title: element.name,
      });
    });
    gResources = resources;
    handleData(resources);
  }).catch(function (error) {
    console.log('Error', error);
  });
  return;
}

function setCalendarList(startDate, endDate, timezone, callback) {
  axios({
    method: "get",
    url: `//${location.hostname}/api/appointments`,
    params: {
      start: startDate,
      end: endDate,
      timezone: timezone,
      _: Date.now()
    }
  }).then(function (response) {
    let resData = response.data;
    var events = [];

    resData.forEach(function (element) {
      events.push({
        apoId: element.id,
        title: element.title,
        description: element.description,
        start: moment(element.start_date + ' ' + element.start_time).format(),
        end: moment(element.end_date + ' ' + element.end_time).format(),
        resourceId: element.user_id,
        color: element.color,
        textColor: element.text_color
      });
    });

    gEvents = events;
    // コールバック設定
    callback(events);
  }).catch(function (error) {
    console.log('Error', error);
  });
  return;
}


$(function () {

  var containerEl = $('#calendar');
  containerEl.fullCalendar({
    header: {
      left: "prev,next today syncEvent",
      center: "title",
      right: "timelineCustom,timelineDay,timelineWeek,timelineMonth"
    },
    themeSystem: "bootstrap4",
    schedulerLicenseKey: "GPL-My-Project-Is-Open-Source",
    defaultView: "timelineCustom",
    views: {
      timelineCustom: {
        type: "timeline",
        buttonText: "1週間",
        resourceAreaWidth: "10%",
        dateIncrement: {
          weeks: 1
        },
        slotDuration: {
          days: 1
        },
        visibleRange: function (currentDate) {
          return {
            start: currentDate.clone().startOf("week").add({
              days: 0
            }),
            end: currentDate.clone().endOf("week").add({
              days: 1
            })
          };
        }
      }
    },
    customButtons: {
      syncEvent: {
        text: '最新',
        click: function(event, jsEvent, view){
          $("#calendar").fullCalendar('refetchEvents');
        }
      }
    },
    timeFormat: "HH:mm",
    timezone: "Asia/Tokyo",
    locale: "ja",
    height: 700,
    eventLimit: true,
    editable: true,
    slotEventOverlap: true,
    selectable: true,
    selectHelper: true,
    selectMinDistance: 1,
    resourceLabelText: "エリア",
    displayEventEnd: {
      default: true
    },
    monthNames: ['１月', '２月', '３月', '４月', '５月', '６月', '７月', '８月', '９月', '１０月', '１１月', '１２月'],
    dayNames: ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日'],
    dayNamesShort: ['日', '月', '火', '水', '木', '金', '土'],
    resources: function(callback) {
      getResources(function(resourceObjects) {
        callback(resourceObjects);
      });
    },
    eventSources: [
      // {
      //   googleCalendarApiKey: "{{ config('services.google-calendar.key') }}",
      //   googleCalendarId: 'japanese__ja@holiday.calendar.google.com',
      //   rendering: 'background',
      //   color: "#ffd0d0"
      // },
      {
        url: `//${location.hostname}/api/holidays`,
        rendering: 'background',
        color: '#ffd0d0',
        cache: true
      }

    ],
    events: function (start, end, timezone, callback) {
      // ページロード時に表示するカレンダーデータ取得イベント
      setCalendarList(
        start.format("YYYY-MM-DD"),
        end.format("YYYY-MM-DD"),
        timezone,
        callback
      );
      $(".selectpicker").selectpicker('render');
    },
    eventRender: function (event, element, view) {
      // Event表示を追加
      element.find('.fc-title').prepend("<br/>");
    },
    eventAfterRender: function (event, element, view) {
      // Event表示前後のEventデータをグローバル化
      gEventRenders.push(event);
    },
    eventClick: function (calEvent, jsEvent, view) {
      // EventClick
      eventClickFunc(calEvent);
    },
    dayClick: function (event, jsEvent, view, resourceObj) {
      // Event Create
      dayClickFunc(event, jsEvent, view, resourceObj);
    },
    eventDrop: function (event, delta, revertFunc, jsEvent, ui, view) {
      // Event D&D
      eventDropFunc(event, delta, revertFunc, jsEvent, ui, view);
    }
  });
});