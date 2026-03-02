(function ($) {
  Drupal.behaviors.etlProgressCalendar = {
    attach: function (context, settings) {

      if (!$('#calendar', context).once('etl-calendar').length) {
        return;
      }

      $('#calendar').fullCalendar({

        defaultView: 'month',

        header: {
          left: 'prev,next today',
          center: 'title',
          right: 'month,agendaWeek,agendaDay'
        },

        editable: false,
        eventLimit: false,

        // Important for time positioning
        allDaySlot: false,
        slotDuration: '00:30:00',
        minTime: "00:00:00",
        maxTime: "24:00:00",

        events: settings.etl_calendar.events
      });

    }
  };
})(jQuery);