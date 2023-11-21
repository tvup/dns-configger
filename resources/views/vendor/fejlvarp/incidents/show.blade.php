@extends('fejlvarp::layouts.captain')

@section('content')
    <div>
        <section>
            <div>
                <p><a href="{{ route('incidents.index') }}"><span
                                style="font-weight:bold;font-size:32px;line-height:8px;">&larr;</span>
                        List site errors</a></p>
                <h1> {!! $incident->subject !!}
                    @if($incident->resolved_at)
                        <span class="resolved">RESOLVED</span>
                    @else
                        <span class="open">OPEN</span>
                    @endif
                </h1>
            </div>

            <div class="resetmaxwidth">
                @if(!$incident->resolved_at)
                    <form class="resetmaxwidth" method="POST" action="{{ route('incident.delete' , ['hash' => $incident->hash]) }}">
                        @csrf
                        <div class="action">
                            <p>If the incident has been resolved, please mark it by pressing this button:</p>
                            <p><input type="submit" value="Mark Resolved"/></p>
                        </div>
                    </form>
                @endif

                <table class="definitionlist">
                    <tbody>
                    @foreach(['hash', 'occurrences', 'created_at', 'last_seen_at', 'resolved_at'] as $name)
                        @if(isset($incident[$name]))
                            <tr>
                                <th>{!! $name !!}</th>
                                <td>{!! $incident[$name] !!}</td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
                @if(isset($incident->data['error']['type']))
                    <h2>Error Details</h2>
                    <table class="definitionlist">
                        <tbody>
                        @foreach(['type', 'code', 'file', 'line'] as $name)
                            @if(isset($incident->data['error'][$name]))
                                <tr>
                                    <th> {!! $name !!}</th>
                                    <td>{!! $incident->data['error'][$name] !!}</td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                    <h2>Trace</h2>
                    <pre class="resetmaxwidth backgroundgray">{!! $incident->data['error']['trace'] !!}</pre>
                @endif

                @if(isset($incident->data['environment']['SERVER']))
                    <h2>Request Synopsis</h2>
                    <table class="definitionlist">
                        <tbody>
                        @foreach(['HTTP_HOST', 'REQUEST_URI', 'SERVER_ADDR', 'HTTP_REFERER'] as $name)
                            @if(isset($incident->data['environment']['SERVER'][$name]))
                                <tr>
                                    <th>{!! $name !!}</th>
                                    <td>{!! $incident->data['environment']['SERVER'][$name] !!}</td>
                                </tr>
                            @endif
                        @endforeach

                        @if ($user_agent)
                            <tr>
                                <th>HTTP_USER_AGENT</th>
                                <td>{!! $user_agent !!}
                                    <span id="useragent">Loading ...</span>
                                </td>
                            </tr>
                        @endif
                        @if ($geoip)
                            <tr>
                                <th>CLIENT_IP</th>
                                <td>{!! $geoip !!}<span id="geoip">Loading ...</span></td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                @endif


                @if (isset($incident->data['environment']))
                    <h2>Request Context</h2>
                    <pre class="resetmaxwidth backgroundgray">{!! var_export($incident->data['environment'], true) !!}</pre>
                @endif

                @if (!isset($incident->data['error']['type']) && !isset($incident->data['environment']))
                    {
                    <h2>Data</h2>
                    <pre>{!! var_export($incident->data, true) !!}</pre>
                @endif

            </div>

            @if ($user_agent)
                <script type="text/javascript">
                    function useragentCallback(data) {
                        document.getElementById("useragent").innerHTML = data.name ? ("[" + data.type + " - " + data.info + "]") : "";
                    }
                </script>
                <script type="text/javascript"
                        src="/api/useragent/?useragent={{ $user_agent }}&callback=useragentCallback"></script>
            @endif
            @if ($geoip)
                <script type="text/javascript">
                    function geoipCallback(data) {
                        document.getElementById("geoip").innerHTML = data.country_name ? ("[" + data.country_name + (data.region_name && (" - " + data.region_name)) + "]") : "";
                    }
                </script>
                <script type="text/javascript"
                        src="/api/geoip?ip={{ $geoip }}&callback=geoipCallback"></script>
            @endif
        </section>
    </div>

@endsection
