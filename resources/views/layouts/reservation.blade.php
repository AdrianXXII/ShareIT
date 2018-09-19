
<div class="card col-md-5 m-2 {{ strtolower($reservation->getDate()->format('M')) }}">
    <div class="card-header">
        {{ $slot }}
        <p>
            {{
                __('messages.reservation-from-to', [
                    'DATE' => $reservation->getDateStr(),
                    'FROM' => $reservation->getFromStr(),
                    'TO' => $reservation->getToStr(),
                ])
             }}
            @if(auth::user()->id == $reservation->user->id)
                <a href="{{ route('reservations.edit', ['id'=> $reservation->id] ) }}">
                    <span class="oi oi-pencil"></span>
                </a>
                <form action="{{ route('reservations.destroy', ['id' => $reservation->id]) }}" method="post" class="del-btn">
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        <span class="oi oi-trash"></span>
                    </button>
                </form>
            @endif
        </p>
    </div>
    <div class="card-body">
        <p class="">{{ __('messages.reservation-by', ['USERNAME' => $reservation->user->username]) }}</p>
        <p>Priority: {{ $reservation->getPriority() }}</p>
        <p>{{ $reservation->reason }}</p>
    </div>
</div>