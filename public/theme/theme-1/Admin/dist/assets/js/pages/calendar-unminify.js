$(document).ready(function() {
    const URLROOT = 'http://localhost/lawyer-app';

    // Current URL
let currentURLForCalendar = window.location.href;
// Base URL
const baseURLCalendar = URLROOT + '/calendars/index'; 


if(currentURLForCalendar == baseURLCalendar){

    function clearEventForm() {
        $('#event-title').val('');
        $('#event-category').val('');
        $('#event-id').val(''); // Clear the hidden event ID field
        $('#form-event').removeClass('was-validated'); // Clear previous validation
    }

    function setupEventFormHandler(url) {
        $('#form-event').off('submit').on('submit', function(e) {
            e.preventDefault();
            var title = $('#event-title').val();
            var category = $('#event-category').val();
            var start = new Date().toISOString().slice(0, 10); // For new events, adjust as needed
            var end = start;

            if (url === '/calendars/add') {
                // New event
                start = $('#event-modal').data('dateStr') || new Date().toISOString().slice(0, 10);
                end = start;
            }

            // Validate the form
            if (!title) {
                $('#event-title').addClass('is-invalid');
                return;
            } else {
                $('#event-title').removeClass('is-invalid');
            }

            $.ajax({
                url: URLROOT + url,
                method: 'POST',
                data: {
                    id: url === '/calendars/update' ? $('#event-id').val() : null,
                    title: title,
                    category: category,
                    start: start,
                    end: end
                },
                success: function() {
                    $('#event-modal').modal('hide');
                    calendar.refetchEvents();
                }
            });
        });
    }

    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: ['interaction', 'dayGrid', 'timeGrid', 'list'],
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        timeZone: 'UTC',
        editable: true,
        droppable: true,
        selectable: true,
        
        events: function(fetchInfo, successCallback, failureCallback) {
            $.ajax({
                url: URLROOT + '/calendars/fetch',
                method: 'GET',
                success: function(data) {
                    var events = JSON.parse(data).map(function(event) {
                        return {
                            id: event.id,
                            title: event.title,
                            start: event.start_date,
                            end: event.end_date,
                            className: event.category
                        };
                    });
                    successCallback(events);
                }
            });
        },
        dateClick: function(info) {
            $('#event-modal').data('action', '/calendars/add').data('dateStr', info.dateStr);
            clearEventForm(); // Clear form fields for new event
            setupEventFormHandler('/calendars/add');
            $('#event-modal').modal('show');
        },
        eventClick: function(info) {
            $('#event-modal').data('action', '/calendars/update');
            $('#event-title').val(info.event.title);
            $('#event-category').val(info.event.classNames[0]);
            $('#event-id').val(info.event.id); // Add this line to store the event ID
            setupEventFormHandler('/calendars/update');

            $('#btn-delete-event').off('click').on('click', function() {
                $.ajax({
                    url: URLROOT + '/calendars/delete',
                    method: 'POST',
                    data: { id: info.event.id },
                    success: function() {
                        $('#event-modal').modal('hide');
                        calendar.refetchEvents();
                    }
                });
            });

            $('#event-modal').modal('show');
        },
        eventDrop: function(info) {
            var start = new Date(info.event.start);
            var end = new Date(info.event.end ? info.event.end : info.event.start);

            var formattedStart = start.toISOString().slice(0, 10);
            var formattedEnd = end.toISOString().slice(0, 10);

            $.ajax({
                url: URLROOT + '/calendars/drop',
                method: 'POST',
                data: {
                    id: info.event.id,
                    start: formattedStart,
                    end: formattedEnd
                },
                success: function() {
                    // alert('Event updated successfully');
                }
            });
        }
    });

    calendar.render();

    $('#btn-new-event').on('click', function() {
        $('#event-modal').data('action', '/calendars/add').removeData('dateStr');
        clearEventForm(); // Clear form fields for new event
        setupEventFormHandler('/calendars/add');
        $('#event-modal').modal('show');
    });

}
});

