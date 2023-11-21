<div>
    <h2>Records:</h2>
    @if (isset($message) && $message !== '')
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif
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
                    <button wire:click="edit({{ $dnsRecord->id }})">Edit</button>
                </td>
                <td>
                    <button
                            type="button"
                            wire:click="delete({{ $dnsRecord->id }})"
                            wire:confirm="Are you sure you want to delete this record?"
                    >
                        Delete
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>