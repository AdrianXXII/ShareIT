
<div class="card col-md-5 m-2 {{ strtolower($reservation->getDate()->format('M')) }}">
    <div class="card-header">
        {{ $slot }}
        <div class="card-title">
            <p>
                {{
                 __('messages.reservation-from-to', [
                     'DATE' => $reservation->getDateStr(),
                     'FROM' => $reservation->getFromStr(),
                     'TO' => $reservation->getToStr(),
                 ])
              }}
            </p>
            <p class="font-italic">{{ __('messages.reservation-by', ['USERNAME' => $reservation->user->username]) }}</p>

            @if(auth::user()->id == $reservation->user->id)
                <a href="{{ route('reservations.edit', ['id'=> $reservation->id] ) }}" class="btn btn-outline-primary">
                    <span class="oi oi-pencil"></span>
                </a>
                <form action="{{ route('reservations.destroy', ['id' => $reservation->id]) }}" method="post" class="del-btn">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        <span class="oi oi-trash"></span>
                    </button>
                </form>
            @endif
        </div>
    </div>
    <div class="card-body">
        <p><span class="font-weight-bold">Priority:</span> {{ $reservation->getPriority() }}</p>
        <p>{{ $reservation->reason }}</p>
    </div>
</div>