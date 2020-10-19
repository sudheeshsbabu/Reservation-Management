var year = new Date().getFullYear();
var month = new Date().getMonth();
var day = new Date().getDate();

var eventData = {
  events : [
    //  {'id':1, 'start': new Date(year, month, day, 12), 'end': new Date(year, month, day, 13, 35),'title':'Lunch with Mike'},
    //  {'id':2, 'start': new Date(year, month, day, 14), 'end': new Date(year, month, day, 14, 45),'title':'Dev Meeting'},
    //  {'id':3, 'start': new Date(year, month, day + 1, 18), 'end': new Date(year, month, day + 1, 18, 45),'title':'Hair cut'},
    //  {'id':4, 'start': new Date(year, month, day - 1, 8), 'end': new Date(year, month, day - 1, 9, 30),'title':'Team breakfast'},
    //  {'id':5, 'start': new Date(year, month, day + 1, 14), 'end': new Date(year, month, day + 1, 16),'title':'Product showcase'},
    //  {'id':5, 'start': new Date(year, month, day + 1, 15), 'end': new Date(year, month, day + 1, 17),'title':'Overlay event'}
  ]
};

jQuery(document).ready(function() {
  jQuery('#calendar').weekCalendar({
    data: eventData,

    timeslotsPerHour: 4,
    allowCalEventOverlap: true,
    overlapEventsSeparate: true,
    totalEventsWidthPercentInOneColumn : 95,

    height: function($calendar) {
      return jQuery(window).height() - jQuery('h1').outerHeight(true);
    },
    eventRender: function(calEvent, $event) {
      if (calEvent.end.getTime() < new Date().getTime()) {
        $event.css('backgroundColor', '#aaa');
        $event.find('.time').css({
          backgroundColor: '#999',
          border:'1px solid #888'
        });
      }
    },
    eventNew: function(calEvent, $event) {
      displayMessage('<strong>Added timeslot</strong><br/>Start: ' + calEvent.start + '<br/>End: ' + calEvent.end);
      alert('You\'ve added a new timeslot.');
      console.log(calEvent.start);
      console.log($event);
      var data = {
        start: calEvent.start.getTime(),
        end: calEvent.end.getTime(),
        title: calEvent.title
      }
      jQuery.ajax({
        url: '/add-time-slot',
        async: true,
        type: 'POST',
        data: {
          'data': data
        },
        success: function (data) {
          console.log('data = ' + data);
        }
      });
    },
    eventDrop: function(calEvent, $event) {
      displayMessage('<strong>Moved Event</strong><br/>Start: ' + calEvent.start + '<br/>End: ' + calEvent.end);
    },
    eventResize: function(calEvent, $event) {
      displayMessage('<strong>Resized Event</strong><br/>Start: ' + calEvent.start + '<br/>End: ' + calEvent.end);
    },
    eventClick: function(calEvent, $event) {
      displayMessage('<strong>Clicked Event</strong><br/>Start: ' + calEvent.start + '<br/>End: ' + calEvent.end);
      console.log(calEvent);
      console.log($event);
      var data = {
        start: calEvent.start.getTime(),
        end: calEvent.end.getTime(),
        title: calEvent.title
      }
      jQuery.ajax({
        url: '/remove-time-slot',
        async: true,
        type: 'POST',
        data: {
          'data': data
        },
        success: function (data) {
          console.log('data = ' + data);
        }
      });
      $event.remove();
    },
    eventMouseover: function(calEvent, $event) {
      displayMessage('<strong>Mouseover Event</strong><br/>Start: ' + calEvent.start + '<br/>End: ' + calEvent.end);
    },
    eventMouseout: function(calEvent, $event) {
      displayMessage('<strong>Mouseout Event</strong><br/>Start: ' + calEvent.start + '<br/>End: ' + calEvent.end);
    },
    noEvents: function() {
      displayMessage('There are no events for this week');
    }
  });

  function displayMessage(message) {
    jQuery('#message').html(message).fadeIn();
  }

  jQuery('<div id="message" class="ui-corner-all"></div>').prependTo(jQuery('body'));
});