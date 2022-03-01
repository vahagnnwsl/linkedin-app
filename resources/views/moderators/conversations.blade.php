@extends('moderators.app')
@push('css')
<style>
    .container{max-width:1170px; margin:auto;}

    .recent_heading h4 {
        color: #05728f;
        font-size: 21px;
        margin: auto;
    }
    .srch_bar input{ border:1px solid #cdcdcd; border-width:0 0 1px 0; width:80%; padding:2px 0 4px 6px; background:none;}
    .srch_bar .input-group-addon button {
        background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
        border: medium none;
        padding: 0;
        color: #707070;
        font-size: 18px;
    }
    .srch_bar .input-group-addon { margin: 0 0 0 -27px;}

    .chat_ib h5{ font-size:15px; color:#464646; margin:0 0 8px 0;}
    .chat_ib h5 span{ font-size:13px; float:right;}
    .chat_ib p{ font-size:14px; color:#989898; margin:auto}

    .received_msg {
        display: inline-block;
        padding: 0 0 0 10px;
        vertical-align: top;
        width: 92%;
    }
    .received_withd_msg p {
        background: #ebebeb none repeat scroll 0 0;
        border-radius: 3px;
        color: #646464;
        font-size: 16px;
        margin: 0;
        padding: 5px 10px 5px 12px;
        width: 100%;
    }
    .time_date {
        color: #747474;
        display: block;
        font-size: 12px;
        margin: 8px 0 0;
    }
    .received_withd_msg { width: 57%;}
    .mesgs {
        padding: 30px 15px 0 25px;
        width: 100%;
    }

    .sent_msg p {
        background: #05728f none repeat scroll 0 0;
        border-radius: 3px;
        font-size: 16px;
        margin: 0; color:#fff;
        padding: 5px 10px 5px 12px;
        width:100%;
    }
    .outgoing_msg{ overflow:hidden; margin:26px 0 26px;}
    .sent_msg {
        float: right;
        width: 46%;
    }
    .input_msg_write input {
        background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
        border: medium none;
        color: #4c4c4c;
        font-size: 15px;
        min-height: 48px;
        width: 100%;
    }  .msg_history {
        height: 516px;
        overflow-y: auto;
    }
</style>
@endpush
@section('content')
    <div class="container">
        <div class="mesgs" style="border: 1px solid grey;margin-bottom: 100px">
            <div class="msg_history" id="msg_history" >
                @foreach($messages as $message)
                 @if($message->connection_id )
                <div class="incoming_msg">
                    <div class="received_msg">
                        <div class="received_withd_msg">
                           @if($message->text)
                                <p>{{$message->text}}</p>
                            @elseif($message->media)
                                <img src="{{$message->media['url']}}"></p>
                            @elseif($message->attachments)
                                @if(str_contains($message->attachments['mediaType'],'application') || str_contains($message->attachments['mediaType'],'text'))
                                    <a  target="_blank" href="{{$message->attachments['filePath']}}"> {{ $message->attachments['name'] }}</a>
                                @else
                                    <img  src="{{$message->attachments['filePath']}}" width="100">
                                @endif
                            @endif


                            <span class="time_date">{{ $message->date->format('M d, Y  H:i') }} | {{ $message->date->diffForHumans() }}</span></div>
                    </div>
                </div>
                    @else
                <div class="outgoing_msg">
                    <div class="sent_msg">
                        <p>{{$message->text}}</p>
                        <span class="time_date"> {{ $message->date->format('M d, Y  H:i') }} | {{ $message->date->diffForHumans() }}</span> </div>
                </div>
                    @endif
                @endforeach
            </div>

        </div>
    </div>
@endsection

@push('js')
    <script>
        function gotoBottom(){
            var element = document.getElementById("msg_history");
            element.scrollTop = element.scrollHeight - element.clientHeight;
        }
        gotoBottom()
    </script>
@endpush
