<div>
        <form wire:submit="save">
        <div x-data="{typeHint: [
                        {'':'The type of the DNS record.'},
                        {'A':'This record type is used to map an IPv4 address to a hostname.'},
                        {'AAAA':'This record type is used to map an IPv6 address to a hostname.'},
                        {'CAA':'As specified in RFC-6844, this record type can be used to restrict which certificate authorities are permitted to issue certificates for a domain.'},
                        {'CNAME':'This record type defines an alias for your canonical hostname (the one defined by an A or AAAA record).'},
                        {'MX':'This record type is used to define the mail exchanges used for the domain.'},
                        {'NS':'This record type defines the name servers that are used for this zone.'},
                        {'SRV':'This record type specifies the location (hostname and port number) of servers for specific services.'},
                        {'TXT':'This record type is used to associate a string of text with a hostname, primarily used for verification.'}
                ] }">
            <label for="type">Type</label>
            <template x-for="type in typeHint">
                <em x-text="type[$wire.type]"></em>
            </template>
            <select wire:model="type" id="type">
                <option value="">-- Select --</option>
                @foreach(['A','AAAA','CAA','CNAME','MX','NS','SRV','TXT'] as $type)
                    <option value="{{ $type }}">{{ $type }}</option>
                @endforeach
            </select>
            @error('type') <mark><em>{{ $message }}</em></mark>@enderror
        </div>

        <div x-data="{relatesOnType: [
                    {'':'Name'},
                    {'A':'HOSTNAME'},
                    {'AAAA':'HOSTNAME'},
                    {'CAA':'HOSTNAME'},
                    {'CNAME':'HOSTNAME'},
                    {'MX':'HOSTNAME'},
                    {'NS':'HOSTNAME'},
                    {'SRV':'HOSTNAME'},
                    {'TXT':'HOSTNAME'}
                ], typeHint: [
                    {'':'The host name, alias, or service being defined by the record.'},
                    {'A':'Enter @ or hostname'},
                    {'AAAA':'Enter @ or hostname'},
                    {'CAA':'Enter @ or hostname'},
                    {'CNAME':'Enter hostname'},
                    {'MX':'Enter @ or hostname'},
                    {'NS':'Enter @ or hostname'},
                    {'SRV':'e.g. _service._protocol'},
                    {'TXT':'Enter @ or hostname. Anything other than @ will have the domain appended to it automatically, e.g. testhost will become testhost.yourdomain.com'}
                ] }">
            <template x-for="type in relatesOnType">
                <label for="name" x-text="type[$wire.type]"></label>
            </template>
            <template x-for="type in typeHint">
                <em x-text="type[$wire.type]"></em>
            </template>
            <input type="text" wire:model="name" id="name">
            @error('name') <mark><em>{{ $message }}</em></mark>@enderror
        </div>



        <div x-data="{relatesOnType: [
                    {'':'Data'},
                    {'A':'WILL DIRECT TO'},
                    {'AAAA':'WILL DIRECT TO'},
                    {'CAA':'AUTHORITY GRANTED FOR'},
                    {'CNAME':'IS AN ALIAS OF'},
                    {'MX':'MAIL PROVIDERS MAIL SERVER'},
                    {'NS':'WILL DIRECT TO'},
                    {'SRV':'WILL DIRECT TO'},
                    {'TXT':'VALUE'}
                ], typeHint: [
                    {'':'Variable data depending on record type. For example, the data value for an A record would be the IPv4 address to which the domain will be mapped. For a CAA record, it would contain the domain name of the CA being granted permission to issue certificates.'},
                    {'A':'Enter IP address'},
                    {'AAAA':'Enter IPv6 address'},
                    {'CAA':'e.g. mydomain.com'},
                    {'CNAME':'Enter @ or hostname'},
                    {'MX':'e.g. aspmx.l.google.com.'},
                    {'NS':'Enter nameserver'},
                    {'SRV':'Enter @ or hostname (e.g. www or domain.com. <- notice the dot at the end, if you want your domain to be applied at the end, simply omit the dot. E.g. your domain is example.com and you put in example.com the result will be example.com.example.com)'},
                    {'TXT':'Paste TXT string here'}
                ] }">
                <template x-for="type in relatesOnType">
                    <label for="data" x-text="type[$wire.type]"></label>
                </template>
                <template x-for="type in typeHint">
                    <em x-text="type[$wire.type]"></em>
                </template>
                <textarea wire:model="data" id="data"></textarea>
                @error('data') <mark><em>{{ $message }}</em></mark>@enderror
        </div>

        <div x-show="['SRV', 'MX'].includes($wire.type)">
            <label for="priority">Priority</label>
            <em>e.g. 10</em>
            <input type="text" wire:model="priority" id="priority">
            @error('priority') <mark><em>{{ $message }}</em></mark>@enderror
        </div>

        <div x-show="$wire.type === 'SRV'">
            <label for="port">Port</label>
            <em>e.g. 5060</em>
            <input type="text" wire:model="port" id="port">
            @error('port') <mark><em>{{ $message }}</em></mark>@enderror
        </div>

        <div x-show="$wire.type === 'SRV'">
            <label for="weight">Weight</label>
            <em>e.g. 100</em>
            <input type="text" wire:model="weight" id="weight">
            @error('weight') <mark><em>{{ $message }}</em></mark>@enderror
        </div>

        <div x-show="$wire.type === 'CAA'">
            <label for="flags">Flags</label>
            <em>An unsigned integer between 0-255.</em>
            <input type="text" wire:model="flags" id="flags">
            @error('flags') <mark><em>{{ $message }}</em></mark>@enderror
        </div>

        <div x-show="$wire.type === 'CAA'">
            <label for="tag">Tag</label>
            <em>The parameter tag for CAA records.</em>
            <select wire:model="tag" id="tag">
                <option value="">-- Select --</option>
                @foreach(['issue','issuewild','iodef'] as $tag)
                    <option value="{{ $tag }}">{{ $tag }}</option>
                @endforeach
            </select>
            @error('tag') <mark><em>{{ $message }}</em></mark>@enderror
        </div>

        <div>
            <label for="ttl">TTL</label>
            <em>This value is the time to live for the record, in seconds. This defines the time frame that clients can cache queried information before a refresh should be requested.</em>
            <input type="text" wire:model="ttl" id="ttl">
            @error('ttl') <mark><em>{{ $message }}</em></mark>@enderror
        </div>

        <button type="submit">Save</button>
        <button type="button" class="cancelbutton" wire:click="cancel">Cancel</button>
    </form>
</div>