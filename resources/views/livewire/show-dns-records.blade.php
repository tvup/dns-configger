<div>
    <h2>Records:</h2>

    <table>
        <thead>
        <tr>
            <th>Type</th>
            <th>Value</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($dnsRecords as $dnsRecord)
            <tr wire:key="{{ $dnsRecord->id }}">
                <td>{{ $dnsRecord->type }}</td>
                <td>{{ str($dnsRecord->data)->words(8) }}</td>
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