<div>
    <section>
        @if (isset($message) && $message !== '')
            <div class="alert alert-success">
                {{ $message }}
            </div>
        @endif
        <div>
            <a class="fullwidth" href="/dns-records/create"><b><x-icon.plus class="icons"/> New DNS record</b></a>
            <table>
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Data</th>
                    <th>Priority</th>
                    <th>Port</th>
                    <th>TTL</th>
                    <th>Weight</th>
                    <th>Flags</th>
                    <th>Tag</th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($dnsRecords as $dnsRecord)
                    <tr wire:key="{{ $dnsRecord->id }}">
                        <td>{{ $dnsRecord->id }}</td>
                        <td>{{ $dnsRecord->type }}</td>
                        <td>{{ $dnsRecord->name }}</td>
                        <td>{{ str($dnsRecord->data)->limit(45) }}</td>
                        <td>{{ $dnsRecord->priority }}</td>
                        <td>{{ $dnsRecord->port }}</td>
                        <td>{{ $dnsRecord->ttl }}</td>
                        <td>{{ $dnsRecord->weight }}</td>
                        <td>{{ $dnsRecord->flags }}</td>
                        <td>{{ $dnsRecord->tag }}</td>
                        <td>
                            <button wire:click="edit({{ $dnsRecord->id }})"><x-icon.pencil class="icons"/> Edit</button>
                        </td>
                        <td>
                            <button
                                    type="button"
                                    wire:click="delete({{ $dnsRecord->id }})"
                                    wire:confirm="Are you sure you want to delete this record?"
                            >
                                <x-icon.trash class="icons"/> Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="12" class="cellislink"><a href="/dns-records/create" class="celllinktext"><x-icon.plus class="icons"/> New DNS record</a></td>
                </tr>
                </tbody>
            </table>
        </div>
    </section>
</div>