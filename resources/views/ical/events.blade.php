BEGIN:VCALENDAR
VERSION:2.0
@foreach($reservations as $reservation)
BEGIN:VEVENT
DTSTART:{{$reservation->getEventStart()}}
DTEND:{{$reservation->getEventEnd()}}
SUMMARY:{{$reservation->getEventSummary()}}
END:VEVENT
@endforeach
END:VCALENDAR