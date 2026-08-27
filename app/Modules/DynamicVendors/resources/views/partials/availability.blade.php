<fieldset class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 p-5">
    <legend class="px-2 text-base font-bold text-slate-900">Service availability</legend>
    <p class="mb-4 text-sm text-slate-600">Keep this schedule current. Dates and times use the event's local time. Blank details mean availability needs confirmation. Unavailable dates always take priority.</p>
    <div class="grid gap-4 md:grid-cols-2">
        <label class="block text-sm font-semibold">Accepting events
            <select name="availability[is_available]" class="mt-1 w-full rounded-xl border border-slate-200 p-3">
                @foreach(['' => 'Not specified / use availability attribute', '1' => 'Yes', '0' => 'No — temporarily unavailable'] as $value => $label)
                    <option value="{{ $value }}" @selected((string) old('availability.is_available', data_get($document, 'availability.is_available')) === (string) $value)>{{ $label }}</option>
                @endforeach
            </select>
        </label>
        <label class="block text-sm font-semibold">Reason when unavailable
            <input name="availability[reason]" maxlength="500" value="{{ old('availability.reason', data_get($document, 'availability.reason')) }}" placeholder="e.g. Team booked for another event" class="mt-1 w-full rounded-xl border border-slate-200 p-3">
        </label>
        @foreach(['available_dates' => 'Available dates (only these dates, if supplied)', 'unavailable_dates' => 'Unavailable / booked dates', 'service_areas' => 'Service areas (exact city or area names)'] as $key => $label)
            @php($value = old('availability.'.$key, data_get($document, 'availability.'.$key, [])))
            <label class="block text-sm font-semibold">{{ $label }}
                <textarea name="availability[{{ $key }}]" rows="3" placeholder="{{ $key === 'service_areas' ? 'Pune, Mumbai' : 'YYYY-MM-DD, YYYY-MM-DD' }}" class="mt-1 w-full rounded-xl border border-slate-200 p-3">{{ is_array($value) ? implode(', ', $value) : $value }}</textarea>
                <span class="text-xs font-normal text-slate-500">Separate entries with commas or new lines.</span>
            </label>
        @endforeach
        <div class="grid grid-cols-2 gap-3">
            @foreach(['start_time' => 'Service starts', 'end_time' => 'Service ends'] as $key => $label)
                <label class="block text-sm font-semibold">{{ $label }}<input type="time" name="availability[{{ $key }}]" value="{{ old('availability.'.$key, data_get($document, 'availability.'.$key)) }}" class="mt-1 w-full rounded-xl border border-slate-200 p-3"></label>
            @endforeach
            <p class="col-span-2 text-xs text-slate-500">An end time before the start means service continues past midnight. This checks event start time, not duration.</p>
        </div>
    </div>
</fieldset>
