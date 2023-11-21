<div>
    <h2>New dns record:</h2>

    <form wire:submit="save">
        <label>
            <span>Type</span>
            <input type="text" wire:model="type">
            @error('type') <em>{{ $message }}</em>@enderror
        </label>

        <label>
            <span>Data</span>
            <textarea wire:model="data"></textarea>
            @error('data') <em>{{ $message }}</em>@enderror
        </label>

        <button type="submit">Save</button>
    </form>
</div>