@extends('fejlvarp::layouts.captain')

@section('content')
<div>
    <section>
        <div>

        <div class="action">
            @if($show_all)
                <p>Show <a href="{{ route('incidents.index') }}">just open site errors</a> or <span>all site errors</span></p>
            @else
                <p>Show <span>just open site errors</span> or <a href="{{ route('incidents.index', ['show_all'=>'true']) }}">all site errors</a></p>
            @endif
        </div>

        @if(!$incidents)
            <p>There are no site errors to show</p>
        @else
            <table>
                <thead>
                <tr>
                    <th>State</th>
                    <th>Subject</th>
                    <th>Created</th>
                    <th>Last seen</th>
                    <th>Occurrences</th>
                    <th>Resolve?</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($incidents as $incident)
                    <tr>
                        <td class="nobreak">
                            @if($incident->resolved_at !== null)
                                <span class="resolved">RESOLVED</span>
                            @else
                                <span class="open">OPEN</span>
                            @endif
                        </td>
                        <td class="aligntextleft">
                            <a href="/incidents/{!! rawurlencode($incident->hash) !!}">{!! str($incident->subject)->limit(125) !!}</a>
                        </td>
                        <x-fejlvarp-ago :hash="$incident->hash" class="mt-4"/>
                        <td class="nobreak">{{ $incident->occurrences }}</td>

                        <td class="nobreak">
                            @if($incident->resolved_at === null)
                            <form class="nobox" method="POST" action="{{ route('incident.delete' , ['hash' => $incident->hash]) }}">
                                @csrf
                                <input type="submit" value="Mark Resolved"/>
                            </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        <br/>
        <div class="action">
            <form method="post" class="nobox" action="{{ route('incidents.delete') }}">
                @csrf
                <p><input type="submit" value="Prune old site errors"/></p>
            </form>
        </div>
        </div>
    </section>
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

@endsection
