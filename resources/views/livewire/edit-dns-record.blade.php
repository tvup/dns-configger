<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit="update">
        <div>
            <label for="type">Type</label>
            <em>The type of the DNS record. For example: A, CNAME, TXT, ...</em>
            <input type="text" wire:model="type" id="type">
            @error('type') <em>{{ $message }}</em>@enderror
        </div>

        <div>
            <label for="name">Name</label>
            <em>The host name, alias, or service being defined by the record.</em>
            <input type="text" wire:model="name" id="name">
            @error('name') <em>{{ $message }}</em>@enderror
        </div>

        <div>
            <label for="data">Data</label>
            <em>Variable data depending on record type. For example, the "data" value for an A record would be the IPv4 address to which the domain will be mapped. For a CAA record, it would contain the domain name of the CA being granted permission to issue certificates.</em>
            <textarea wire:model="data" id="data"></textarea>
            @error('data') <em>{{ $message }}</em>@enderror
        </div>

        <div>
            <label for="priority">Priority</label>
            <em>The priority for SRV and MX records.</em>
            <input type="text" wire:model="priority" id="priority">
            @error('priority') <em>{{ $message }}</em>@enderror
        </div>

        <div>
            <label for="port">Port</label>
            <em>The port for SRV records.</em>
            <input type="text" wire:model="port" id="port">
            @error('port') <em>{{ $message }}</em>@enderror
        </div>

        <div>
            <label for="ttl">TTL</label>
            <em>The port for SRV records.</em>
            <input type="text" wire:model="ttl" id="ttl">
            @error('ttl') <em>{{ $message }}</em>@enderror
        </div>

        <div>
            <label for="weight">Weight</label>
            <em>The weight for SRV records.</em>
            <input type="text" wire:model="weight" id="weight">
            @error('weight') <em>{{ $message }}</em>@enderror
        </div>

        <div>
            <label for="flags">Flags</label>
            <em>An unsigned integer between 0-255 used for CAA records.</em>
            <input type="text" wire:model="flags" id="flags">
            @error('flags') <em>{{ $message }}</em>@enderror
        </div>

        <div>
            <label for="tag">Tag</label>
            <em>The parameter tag for CAA records. Valid values are "issue", "issuewild", or "iodef"</em>
            <input type="text" wire:model="tag" id="tag">
            @error('tag') <em>{{ $message }}</em>@enderror
        </div>

        <button type="submit">Save</button>
        <button type="button" class="cancelbutton" wire:click="cancel">Cancel</button>
    </form>
</div>