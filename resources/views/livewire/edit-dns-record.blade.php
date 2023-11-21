<div>
    <h2>Edit dns record:</h2>

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit="update">
        <label>
            <span>Type</span>
            <em>The type of the DNS record. For example: A, CNAME, TXT, ...</em>
            <input type="text" wire:model="type">
            @error('type') <em>{{ $message }}</em>@enderror
        </label>

        <label>
            <span>Name</span>
            <em>The host name, alias, or service being defined by the record.</em>
            <input type="text" wire:model="name">
            @error('name') <em>{{ $message }}</em>@enderror
        </label>

        <label>
            <span>Data</span>
            <em>Variable data depending on record type. For example, the "data" value for an A record would be the IPv4 address to which the domain will be mapped. For a CAA record, it would contain the domain name of the CA being granted permission to issue certificates.</em>
            <textarea wire:model="data"></textarea>
            @error('data') <em>{{ $message }}</em>@enderror
        </label>

        <label>
            <span>Priority</span>
            <em>The priority for SRV and MX records.</em>
            <input type="text" wire:model="priority">
            @error('priority') <em>{{ $message }}</em>@enderror
        </label>

        <label>
            <span>Port</span>
            <em>The port for SRV records.</em>
            <input type="text" wire:model="port">
            @error('port') <em>{{ $message }}</em>@enderror
        </label>

        <label>
            <span>TTL</span>
            <em>The port for SRV records.</em>
            <input type="text" wire:model="ttl">
            @error('ttl') <em>{{ $message }}</em>@enderror
        </label>

        <label>
            <span>Weight</span>
            <em>The weight for SRV records.</em>
            <input type="text" wire:model="weight">
            @error('weight') <em>{{ $message }}</em>@enderror
        </label>

        <label>
            <span>Flags</span>
            <em>An unsigned integer between 0-255 used for CAA records.</em>
            <input type="text" wire:model="flags">
            @error('flags') <em>{{ $message }}</em>@enderror
        </label>

        <label>
            <span>Tag</span>
            <em>The parameter tag for CAA records. Valid values are "issue", "issuewild", or "iodef"</em>
            <input type="text" wire:model="tag">
            @error('tag') <em>{{ $message }}</em>@enderror
        </label>

        <button type="submit">Save</button>
    </form>
</div>