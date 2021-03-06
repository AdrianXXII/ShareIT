
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

        </div>
    </div>
    <div class="card-body">
        @if ($reservation->conflicts()->count() > 0)
            <div class="alert alert-warning text-center" role="alert">
                {{ __('messages.conflicted') }}
            </div>
        @endif
        <p><span class="font-weight-bold">Priority:</span> {{ $reservation->getPriority() }}</p>
        <p>{{ $reservation->reason }}</p>

            @if(auth::user()->id == $reservation->user->id)
                <div class="text-right">
                    <a href="{{ route('reservations.show', ['id' => $reservation->id]) }}" class="btn btn-outline-primary">
                        <span class="oi oi-eye"></span>
                    </a>
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
                </div>
            @endif
    </div>
</div>