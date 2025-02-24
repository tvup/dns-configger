<div>
    <section>
        <div>
            <a class="fullwidth" href="/dns-records/create"><b><x-icon.plus class="icons"/> New DNS record</b></a>
            <table>
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Type</th>
                    <th>Name</th>
                    <th>{{ $provider=='hetzner' ? __('Value') : __('Data') }}</th>
                    @if($provider=='digitalocean') <th>Priority</th> @endif
                    @if($provider=='digitalocean') <th>Port</th> @endif
                    <th>TTL</th>
                    @if($provider=='digitalocean') <th>Weight</th> @endif
                    @if($provider=='digitalocean') <th>Flags</th> @endif
                    @if($provider=='digitalocean') <th>Tag</th> @endif
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
                        @if($provider=='digitalocean') <td>{{ $dnsRecord->priority }}</td> @endif
                        @if($provider=='digitalocean') <td>{{ $dnsRecord->port }}</td> @endif
                        <td>{{ $dnsRecord->ttl }}</td>
                        @if($provider=='digitalocean') <td>{{ $dnsRecord->weight }}</td> @endif
                        @if($provider=='digitalocean') <td>{{ $dnsRecord->flags }}</td> @endif
                        @if($provider=='digitalocean') <td>{{ $dnsRecord->tag }}</td> @endif
                        <td>
                            <button wire:click="edit({{ is_string($dnsRecord->id) ? ('\'' . $dnsRecord->id . '\'') : ($dnsRecord->id) }})"><x-icon.pencil class="icons"/> Edit</button>
                        </td>
                        <td>
                            @if(!in_array($dnsRecord->type, ['SOA', 'NS']))
                            <button
                                    type="button"
                                    wire:click="delete({{ is_string($dnsRecord->id) ? ('\'' . $dnsRecord->id . '\'') : ($dnsRecord->id) }})"
                                    wire:confirm="Are you sure you want to delete this record?"
                            >

                                <x-icon.trash class="icons"/> Delete
                            </button>
                            @endif
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
