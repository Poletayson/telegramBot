<div id="message-{{$message->id}}" class="message @if($message->from_client) from_client @if(!$message->read) unread @endif @else from_operator @endif">
    <div class="pt-2 ps-4 pe-4">
        <div>
            {{$message->text}}
        </div>
    </div>
    <div class="w-100 text-end pb-1 ps-1 pe-1">
        @php
            $dateTime = (new DateTime ($message->created_at, new DateTimeZone('UTC')))->setTimezone(new DateTimeZone(config('app.timezone')));
        @endphp
        <div class="message_time" title="{{$dateTime->format('d.m.Y H:i:s')}}">
            {{$dateTime->format('H:i')}}
        </div>
    </div>
</div>
