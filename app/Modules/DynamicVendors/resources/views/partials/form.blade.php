@php
    $document = $vendor?->vendor_json ?? [];
    $routePrefix = $routePrefix ?? 'admin.dynamic-vendors';
    $categoryCatalog = \App\Modules\DynamicVendors\Support\VendorCategoryFieldCatalog::all();
    $selectedCategory = old('category', data_get($document, 'identity.category', ''));
    $savedAttributes = old('attributes', collect(data_get($document, 'attributes', []))->map(function ($attribute) {
        $validation = $attribute['validation'] ?? []; $value = $attribute['value'] ?? '';
        return array_merge($attribute, $validation, ['value' => is_array($value) ? implode(', ', $value) : $value, 'allowed_values' => implode(', ', $validation['allowed_values'] ?? []), 'pricing_unit' => data_get($attribute, 'pricing.unit', 'fixed'), 'pricing_rate' => data_get($attribute, 'pricing.rate', '')]);
    })->all());
    $packages = old('packages', data_get($document, 'packages', []));
    $foodMenuItems = old('food_menu_items', data_get($document, 'food_menu_items', []));
    $additionalDetails = old('additional_details', data_get($document, 'additional_details', []));
    $existingImages = old('existing_images', data_get($document, 'media.images', []));
    $errorKeys = isset($errors) && $errors instanceof \Illuminate\Support\ViewErrorBag ? collect($errors->keys()) : collect();
    $initialStep = $errorKeys->contains(fn ($key) => str_starts_with($key, 'packages.') || str_starts_with($key, 'food_menu_items.')) ? 2
        : ($errorKeys->contains(fn ($key) => str_starts_with($key, 'images.') || str_starts_with($key, 'additional_details.') || in_array($key, ['description', 'short_description'], true)) ? 3 : 1);
@endphp

<div class="admin-page vendor-wizard mx-auto max-w-6xl">
    @include('admin.partials.alerts')
    <div class="admin-hero mb-6 p-6 sm:p-8 rounded-2xl border border-[#D4AF37]/30 bg-gradient-to-r from-[#850625] via-[#6E0720] to-[#540417] text-white shadow-md">
        <a href="{{ route($routePrefix.'.index') }}" class="text-xs font-bold text-rose-200 hover:text-white transition">← Vendor list</a>
        <h1 class="mt-2 text-2xl sm:text-3xl font-extrabold text-white tracking-tight">{{ $title }}</h1>
        <p class="mt-1 text-xs sm:text-sm text-rose-100/90 font-medium">Complete the four guided steps. Information remains saved when you move backward.</p>
    </div>
    @if(isset($errors) && $errors->any())
        <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
            <b>Please review this information.</b>
            <p>{{ $errors->first() }}</p>
        </div>
    @endif

    <div class="mb-6 grid grid-cols-2 sm:grid-cols-4 gap-3">
        @foreach(['Basic details','Packages','Additional info','Review'] as $i => $label)
            <div data-progress="{{ $i+1 }}" class="rounded-xl border border-slate-200 bg-white p-3 text-center text-xs font-bold text-slate-600 transition-all shadow-xs is-pending-step">
                <span class="mx-auto mb-1.5 grid h-6 w-6 place-items-center rounded-full border border-slate-300 text-xs font-extrabold">{{ $i+1 }}</span>
                <span class="block truncate">{{ $label }}</span>
            </div>
        @endforeach
    </div>

    <form method="POST" action="{{ $action }}" enctype="multipart/form-data" id="vendor-form">
        @csrf
        @if($method !== 'POST') @method($method) @endif
        <input type="hidden" name="status" value="{{ old('status', $vendor?->status ?? (str_starts_with($routePrefix, 'admin.') ? 'active' : 'draft')) }}">

        <!-- Step 1: Basic details -->
        <section data-step="1" class="wizard-panel space-y-6">
            <div class="vendor-wizard-card p-6 sm:p-8">
                <div class="mb-6 flex items-center gap-3.5 pb-4 border-b border-slate-100">
                    <span class="vendor-step">1</span>
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">Basic Details</h2>
                        <p class="text-xs text-slate-500 font-medium">Select a category to load all related dynamic fields.</p>
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <label class="block">
                        <span class="vendor-label">Vendor Name *</span>
                        <input name="name" required maxlength="255" value="{{ old('name', data_get($document, 'identity.name')) }}" class="vendor-input" placeholder="e.g. Royal Wedding Decorators">
                    </label>
                    <label class="block">
                        <span class="vendor-label">Category *</span>
                        <select id="vendor-category" name="category" required class="vendor-input">
                            <option value="">Select category</option>
                            @if($selectedCategory && !array_key_exists($selectedCategory,$categoryCatalog))
                                <option selected>{{ $selectedCategory }}</option>
                            @endif
                            @foreach(array_keys($categoryCatalog) as $category)
                                <option value="{{ $category }}" @selected($selectedCategory===$category)>{{ $category }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>

                <div id="category-empty" class="vendor-empty-state mt-6 {{ $selectedCategory?'hidden':'' }}">
                    <b class="text-slate-700 font-bold">Select a category above to continue</b>
                    <p class="text-xs text-slate-500 mt-1">All attributes, package defaults, and requirements will load automatically.</p>
                </div>

                <div id="category-fields-wrap" class="mt-8 {{ $selectedCategory?'':'hidden' }}">
                    <h3 id="category-heading" class="mb-4 text-sm font-extrabold uppercase tracking-wide text-[#850625] flex items-center gap-2">
                        <span class="inline-block w-2 h-2 rounded-full bg-[#850625]"></span>
                        {{ $selectedCategory }} details
                    </h3>
                    <div id="guided-fields" class="grid gap-4 md:grid-cols-2">
                        @foreach($savedAttributes as $index=>$attribute)
                            @php($type=$attribute['type']??'text')
                            @php($allowed=array_filter(array_map('trim',explode(',',(string)($attribute['allowed_values']??'')))))
                            @php($value=$attribute['value']??'')
                            <div class="guided-field" data-guided-field>
                                <input type="hidden" name="attributes[{{ $index }}][id]" value="{{ $attribute['id']??'' }}">
                                <input type="hidden" name="attributes[{{ $index }}][label]" value="{{ $attribute['label']??'' }}">
                                <input type="hidden" name="attributes[{{ $index }}][type]" value="{{ $type }}">
                                <input type="hidden" name="attributes[{{ $index }}][required]" value="0">
                                <input type="hidden" name="attributes[{{ $index }}][allowed_values]" value="{{ $attribute['allowed_values']??'' }}">
                                <span class="vendor-label">{{ $attribute['label']??'Detail' }}</span>
                                @if(in_array($type,['textarea','multi_select']))
                                    <textarea name="attributes[{{ $index }}][value]" class="vendor-input">{{ $value }}</textarea>
                                @elseif($type==='dropdown')
                                    <select name="attributes[{{ $index }}][value]" class="vendor-input">
                                        <option value="">Select</option>
                                        @foreach($allowed as $option)
                                            <option value="{{ $option }}" @selected((string)$value===$option)>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                @elseif($type==='boolean')
                                    <select name="attributes[{{ $index }}][value]" class="vendor-input">
                                        <option value="0" @selected(!$value)>No</option>
                                        <option value="1" @selected((bool)$value)>Yes</option>
                                    </select>
                                @else
                                    <input type="{{ in_array($type,['number','currency'])?'number':($type==='email'?'email':($type==='phone'?'tel':'text')) }}" @if(in_array($type,['number','currency'])) step="0.01" min="0" @endif name="attributes[{{ $index }}][value]" value="{{ $value }}" class="vendor-input">
                                @endif
                                @if(($attribute['pricing_rate']??'')!=='')
                                    <details class="mt-3 rounded-xl border border-[#D4AF37]/30 bg-amber-50/40 p-3">
                                        <summary class="cursor-pointer text-xs font-bold text-[#850625]">Planning cost</summary>
                                        <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                            <label><span class="vendor-label">Price used in estimates</span><input type="number" min="0" step="0.01" name="attributes[{{ $index }}][pricing_rate]" value="{{ $attribute['pricing_rate'] }}" class="vendor-input"></label>
                                            <label><span class="vendor-label">Charged by</span><select name="attributes[{{ $index }}][pricing_unit]" class="vendor-input">@foreach(['fixed','per_guest','per_unit','per_hour','per_day'] as $unit)<option value="{{ $unit }}" @selected(($attribute['pricing_unit']??'fixed')===$unit)>{{ str_replace('_',' ',$unit) }}</option>@endforeach</select></label>
                                        </div>
                                    </details>
                                @elseif($type==='currency')
                                    <input type="hidden" name="attributes[{{ $index }}][pricing_unit]" value="{{ $attribute['pricing_unit']??'fixed' }}">
                                @endif
                                <button type="button" class="remove-guided-field admin-remove-field" title="Remove field">×</button>
                            </div>
                        @endforeach
                    </div>
                    <button id="add-simple-field" type="button" class="admin-add-detail-button mt-5 rounded-xl px-5 py-2.5 text-xs font-extrabold">+ Add another detail</button>
                </div>
            </div>
            <div class="flex justify-end pt-2">
                <button type="button" class="wizard-next admin-primary-button rounded-xl px-8 py-3 text-sm font-bold text-white shadow-sm hover:shadow transition">Next</button>
            </div>
        </section>

        <!-- Step 2: Packages -->
        <section data-step="2" class="wizard-panel hidden space-y-6">
            <div id="catering-menu-editor" class="vendor-wizard-card p-6 sm:p-8 {{ $selectedCategory === 'Catering' ? '' : 'hidden' }}">
                <div class="mb-6 flex items-start justify-between gap-3 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3.5">
                        <span class="vendor-step">2A</span>
                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900">Create Menu Items</h2>
                            <p class="text-xs text-slate-500 font-medium">Dishes and prices shown in the customer food questionnaire.</p>
                        </div>
                    </div>
                    <button id="add-menu-item" type="button" class="admin-add-detail-button rounded-xl px-4 py-2 text-xs font-extrabold">+ Add menu item</button>
                </div>
                <div id="menu-item-list" class="space-y-4">
                    @foreach($foodMenuItems as $i=>$item)
                        <div data-menu-item class="vendor-accent-card rounded-2xl border p-4">
                            <input type="hidden" name="food_menu_items[{{ $i }}][id]" value="{{ $item['id']??'' }}">
                            <div class="grid gap-4 md:grid-cols-3">
                                <label><span class="vendor-label">Menu item *</span><input name="food_menu_items[{{ $i }}][name]" value="{{ $item['name']??$item['title']??'' }}" class="vendor-input menu-item-name"></label>
                                <label><span class="vendor-label">Category</span><input name="food_menu_items[{{ $i }}][category]" value="{{ $item['category']??'' }}" class="vendor-input" placeholder="Starter, Main Course, Dessert"></label>
                                <label><span class="vendor-label">Price per person (₹)</span><input type="number" min="0" step="0.01" name="food_menu_items[{{ $i }}][price_per_person]" value="{{ $item['price_per_person']??$item['cost']??'' }}" class="vendor-input"></label>
                            </div>
                            <button type="button" class="remove-menu-item mt-3 text-xs font-bold text-rose-700 hover:text-rose-900">× Remove menu item</button>
                        </div>
                    @endforeach
                </div>
                <div id="menu-item-empty" class="vendor-empty-state {{ count($foodMenuItems)?'hidden':'' }}">
                    <b class="text-slate-700 font-bold">No menu items created yet</b>
                    <p class="text-xs text-slate-500 mt-1">Click "+ Add menu item" above to add dishes.</p>
                </div>
            </div>

            <div class="vendor-wizard-card p-6 sm:p-8">
                <div class="mb-6 flex items-start justify-between gap-3 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3.5">
                        <span class="vendor-step">2</span>
                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900">Create Packages</h2>
                            <p class="text-xs text-slate-500 font-medium">Packages created here appear for this vendor in the customer panel.</p>
                        </div>
                    </div>
                    <button id="add-package" type="button" class="admin-add-detail-button rounded-xl px-4 py-2 text-xs font-extrabold">+ Create package</button>
                </div>
                <div id="package-list" class="space-y-4">
                    @foreach($packages as $i=>$package)
                        <div data-package class="vendor-accent-card rounded-2xl border p-4">
                            <input type="hidden" name="packages[{{ $i }}][id]" value="{{ $package['id']??'' }}">
                            <div class="grid gap-4 md:grid-cols-2">
                                <label><span class="vendor-label">Package name *</span><input name="packages[{{ $i }}][name]" value="{{ $package['name']??'' }}" class="vendor-input package-name"></label>
                                <label><span class="vendor-label">Duration</span><input name="packages[{{ $i }}][duration]" value="{{ $package['duration']??'' }}" class="vendor-input" placeholder="Example: 8 hours"></label>
                                <label><span class="vendor-label">Minimum price (₹)</span><input type="number" min="0" step="0.01" name="packages[{{ $i }}][min_price]" value="{{ $package['min_price']??'' }}" class="vendor-input"></label>
                                <label><span class="vendor-label">Maximum price (₹)</span><input type="number" min="0" step="0.01" name="packages[{{ $i }}][max_price]" value="{{ $package['max_price']??'' }}" class="vendor-input"></label>
                                <label class="md:col-span-2"><span class="vendor-label">Description</span><textarea name="packages[{{ $i }}][description]" class="vendor-input">{{ $package['description']??'' }}</textarea></label>
                                <label class="md:col-span-2"><span class="vendor-label">Package inclusions</span><textarea name="packages[{{ $i }}][inclusions]" class="vendor-input" placeholder="One inclusion per line">{{ implode("\n",$package['inclusions']??[]) }}</textarea></label>
                            </div>
                            <button type="button" class="remove-package mt-3 text-xs font-bold text-rose-700 hover:text-rose-900">× Remove package</button>
                        </div>
                    @endforeach
                </div>
                <div id="package-empty" class="vendor-empty-state {{ count($packages)?'hidden':'' }}">
                    <b class="text-slate-700 font-bold">No packages created yet</b>
                    <p class="text-xs text-slate-500 mt-1">Click "+ Create package" above to configure your offerings.</p>
                </div>
            </div>

            <div class="flex justify-between pt-2">
                <button type="button" class="wizard-previous rounded-xl border border-slate-300 bg-white px-7 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50 shadow-xs transition">Previous</button>
                <button type="button" class="wizard-next admin-primary-button rounded-xl px-8 py-3 text-sm font-bold text-white shadow-sm hover:shadow transition">Next</button>
            </div>
        </section>

        <!-- Step 3: Additional info -->
        <section data-step="3" class="wizard-panel hidden space-y-6">
            <div class="vendor-wizard-card p-6 sm:p-8">
                <div class="mb-6 flex items-center gap-3.5 pb-4 border-b border-slate-100">
                    <span class="vendor-step">3</span>
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">Additional Information</h2>
                        <p class="text-xs text-slate-500 font-medium">Photos, introduction, descriptions, and custom rich-text details.</p>
                    </div>
                </div>

                <div id="existing-image-grid" class="mb-5 grid grid-cols-2 gap-3 sm:grid-cols-4">
                    @foreach($existingImages as $image)
                        <figure class="relative rounded-xl border border-slate-200 bg-slate-50 p-2">
                            <img src="{{ asset('storage/'.$image) }}" class="h-24 w-full rounded-lg object-cover">
                            <input type="hidden" name="existing_images[]" value="{{ $image }}">
                            <button type="button" class="remove-existing-image absolute right-3 top-3 grid h-7 w-7 place-items-center rounded-full bg-slate-900/80 text-white hover:bg-rose-700 transition">×</button>
                        </figure>
                    @endforeach
                </div>

                <label class="block">
                    <span class="vendor-label">Vendor Photos</span>
                    <input id="vendor-images" type="file" name="images[]" multiple accept="image/*" class="vendor-file">
                </label>
                <div id="vendor-image-preview" class="mt-4 hidden">
                    <div id="vendor-image-preview-grid" class="grid grid-cols-2 gap-3 sm:grid-cols-4"></div>
                </div>

                <div class="mt-6 grid gap-5">
                    <label class="block">
                        <span class="vendor-label">Short Introduction</span>
                        <textarea name="short_description" maxlength="1000" class="vendor-input" placeholder="Brief summary of the vendor">{{ old('short_description',data_get($document,'seo.short_description')) }}</textarea>
                    </label>
                    <label class="block">
                        <span class="vendor-label">Detailed Description</span>
                        <textarea name="description" rows="5" class="vendor-input" placeholder="Full overview of services, experience, and specialty">{{ old('description',data_get($document,'seo.description')) }}</textarea>
                    </label>
                </div>
                <input type="hidden" name="tags" value="{{ old('tags',implode(', ',data_get($document,'seo.tags',[]))) }}">
                <input type="hidden" name="keywords" value="{{ old('keywords',implode(', ',data_get($document,'seo.keywords',[]))) }}">

                <div class="mt-8 border-t border-slate-200 pt-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-extrabold text-slate-900">Custom Highlights & Details</h3>
                        <button id="add-additional-detail" type="button" class="admin-add-detail-button rounded-xl px-4 py-2 text-xs font-extrabold">+ Add detail</button>
                    </div>
                    <div id="additional-details" class="mt-4 space-y-4">
                        @foreach($additionalDetails as $i=>$detail)
                            <div data-additional class="vendor-accent-card rounded-2xl border p-4">
                                <div class="flex gap-2">
                                    <input name="additional_details[{{ $i }}][title]" value="{{ $detail['title']??'' }}" class="vendor-input" placeholder="Detail heading">
                                    <button type="button" class="remove-additional text-xl text-rose-700 hover:text-rose-900">×</button>
                                </div>
                                <div class="editor-toolbar mt-3 flex gap-1">
                                    <button type="button" data-command="bold" class="px-2 py-1 text-xs border rounded bg-white font-bold">B</button>
                                    <button type="button" data-command="italic" class="px-2 py-1 text-xs border rounded bg-white italic">I</button>
                                    <button type="button" data-command="insertUnorderedList" class="px-2 py-1 text-xs border rounded bg-white">• List</button>
                                </div>
                                <div contenteditable="true" class="detail-editor mt-2 min-h-28 rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-800">{!! $detail['content']??'' !!}</div>
                                <input type="hidden" name="additional_details[{{ $i }}][content]" value="{{ $detail['content']??'' }}">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="flex justify-between pt-2">
                <button type="button" class="wizard-previous rounded-xl border border-slate-300 bg-white px-7 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50 shadow-xs transition">Previous</button>
                <button type="button" class="wizard-next admin-primary-button rounded-xl px-8 py-3 text-sm font-bold text-white shadow-sm hover:shadow transition">Next</button>
            </div>
        </section>

        <!-- Step 4: Review and submit -->
        <section data-step="4" class="wizard-panel hidden space-y-6">
            <div class="vendor-wizard-card p-6 sm:p-8">
                <div class="mb-6 flex items-center gap-3.5 pb-4 border-b border-slate-100">
                    <span class="vendor-step">4</span>
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">Review & Submit</h2>
                        <p class="text-xs text-slate-500 font-medium">Verify all details before finalizing. Use "Previous" to make any adjustments.</p>
                    </div>
                </div>
                <div id="vendor-review" class="space-y-4 text-sm text-slate-800"></div>
            </div>
            <div class="flex justify-between pt-2">
                <button type="button" class="wizard-previous rounded-xl border border-slate-300 bg-white px-7 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50 shadow-xs transition">Previous</button>
                <button type="submit" class="admin-primary-button rounded-xl px-8 py-3 text-sm font-bold text-white shadow-sm hover:shadow transition">{{ $vendor?'Save Vendor Changes':'Submit Vendor' }}</button>
            </div>
        </section>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded',()=>{
 const catalog=@json($categoryCatalog), category=document.getElementById('vendor-category'), fields=document.getElementById('guided-fields'), panels=[...document.querySelectorAll('.wizard-panel')], form=document.getElementById('vendor-form');let step=1;
 const esc=v=>String(v??'').replace(/[&<>'"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[c]));
 const inputFor=(f,i)=>{const b=`attributes[${i}]`,o=f.options||[];let x=f.type==='textarea'||f.type==='multi_select'?`<textarea name="${b}[value]" class="vendor-input"></textarea>`:f.type==='dropdown'?`<select name="${b}[value]" class="vendor-input"><option value="">Select</option>${o.map(v=>`<option value="${esc(v)}">${esc(v)}</option>`).join('')}</select>`:f.type==='boolean'?`<select name="${b}[value]" class="vendor-input"><option value="0">No</option><option value="1">Yes</option></select>`:`<input type="${['number','currency'].includes(f.type)?'number':f.type==='email'?'email':f.type==='phone'?'tel':'text'}" ${['number','currency'].includes(f.type)?'step="0.01" min="0"':''} name="${b}[value]" class="vendor-input">`;return `<div class="guided-field" data-guided-field><input type="hidden" name="${b}[label]" value="${esc(f.label)}"><input type="hidden" name="${b}[type]" value="${esc(f.type)}"><input type="hidden" name="${b}[required]" value="0"><input type="hidden" name="${b}[allowed_values]" value="${esc(o.join(', '))}"><span class="vendor-label">${esc(f.label)}</span>${x}${f.type==='currency'?`<input type="hidden" name="${b}[pricing_unit]" value="${esc(f.unit||'fixed')}">`:''}<button type="button" class="remove-guided-field admin-remove-field" title="Remove field">×</button></div>`};
 const reindex=(container,selector,prefix)=>[...container.querySelectorAll(selector)].forEach((row,i)=>row.querySelectorAll('[name]').forEach(el=>el.name=el.name.replace(new RegExp(`${prefix}\\[\\d+\\]`),`${prefix}[${i}]`)));
 const bindFields=()=>document.querySelectorAll('.remove-guided-field').forEach(b=>b.onclick=()=>{b.closest('[data-guided-field]').remove();reindex(fields,'[data-guided-field]','attributes')});bindFields();
 category.onchange=()=>{const n=category.value;document.getElementById('category-empty').classList.toggle('hidden',!!n);document.getElementById('category-fields-wrap').classList.toggle('hidden',!n);document.getElementById('category-heading').textContent=`${n} details`;document.getElementById('catering-menu-editor').classList.toggle('hidden',n!=='Catering');fields.innerHTML=(catalog[n]||[]).map(inputFor).join('');bindFields()};
 document.getElementById('add-simple-field').onclick=async()=>{if(!window.Swal)return;const result=await Swal.fire({position:'center',title:'Add another basic detail',text:'Enter a clear name for this vendor detail.',input:'text',inputPlaceholder:'Example: Award received',showCancelButton:true,confirmButtonText:'Add detail',cancelButtonText:'Cancel',buttonsStyling:false,customClass:{popup:'admin-themed-popup',title:'admin-themed-popup-title',htmlContainer:'admin-themed-popup-copy',input:'admin-themed-popup-input',confirmButton:'admin-themed-popup-confirm',cancelButton:'admin-themed-popup-cancel'},inputValidator:v=>v?.trim()?undefined:'Please enter a detail name.'});if(result.isConfirmed){const l=result.value.trim();fields.insertAdjacentHTML('beforeend',inputFor({label:l,type:'text'},fields.children.length));bindFields();fields.lastElementChild.querySelector('input:not([type=hidden])')?.focus()}};
 const list=document.getElementById('package-list'),empty=document.getElementById('package-empty');const packageHtml=i=>`<div data-package class="vendor-accent-card rounded-2xl border p-4"><input type="hidden" name="packages[${i}][id]"><div class="grid gap-4 md:grid-cols-2"><label><span class="vendor-label">Package name *</span><input name="packages[${i}][name]" class="vendor-input package-name"></label><label><span class="vendor-label">Duration</span><input name="packages[${i}][duration]" class="vendor-input"></label><label><span class="vendor-label">Minimum price (₹)</span><input type="number" min="0" step="0.01" name="packages[${i}][min_price]" class="vendor-input"></label><label><span class="vendor-label">Maximum price (₹)</span><input type="number" min="0" step="0.01" name="packages[${i}][max_price]" class="vendor-input"></label><label class="md:col-span-2"><span class="vendor-label">Description</span><textarea name="packages[${i}][description]" class="vendor-input"></textarea></label><label class="md:col-span-2"><span class="vendor-label">Package inclusions</span><textarea name="packages[${i}][inclusions]" class="vendor-input" placeholder="One inclusion per line"></textarea></label></div><button type="button" class="remove-package mt-3 text-xs font-bold text-rose-700 hover:text-rose-900">× Remove package</button></div>`;
 const bindPackages=()=>{document.querySelectorAll('.remove-package').forEach(b=>b.onclick=()=>{b.closest('[data-package]').remove();reindex(list,'[data-package]','packages');empty.classList.toggle('hidden',!!list.children.length)});empty.classList.toggle('hidden',!!list.children.length)};bindPackages();document.getElementById('add-package').onclick=()=>{list.insertAdjacentHTML('beforeend',packageHtml(list.children.length));bindPackages();list.lastElementChild.querySelector('.package-name').focus()};
 const menuList=document.getElementById('menu-item-list'),menuEmpty=document.getElementById('menu-item-empty');const menuHtml=i=>`<div data-menu-item class="vendor-accent-card rounded-2xl border p-4"><input type="hidden" name="food_menu_items[${i}][id]"><div class="grid gap-4 md:grid-cols-3"><label><span class="vendor-label">Menu item *</span><input name="food_menu_items[${i}][name]" class="vendor-input menu-item-name"></label><label><span class="vendor-label">Category</span><input name="food_menu_items[${i}][category]" class="vendor-input" placeholder="Starter, Main Course, Dessert"></label><label><span class="vendor-label">Price per person (₹)</span><input type="number" min="0" step="0.01" name="food_menu_items[${i}][price_per_person]" class="vendor-input"></label></div><button type="button" class="remove-menu-item mt-3 text-xs font-bold text-rose-700 hover:text-rose-900">× Remove menu item</button></div>`;
 const bindMenuItems=()=>{document.querySelectorAll('.remove-menu-item').forEach(b=>b.onclick=()=>{b.closest('[data-menu-item]').remove();reindex(menuList,'[data-menu-item]','food_menu_items');menuEmpty.classList.toggle('hidden',!!menuList.children.length)});menuEmpty.classList.toggle('hidden',!!menuList.children.length)};bindMenuItems();document.getElementById('add-menu-item').onclick=()=>{menuList.insertAdjacentHTML('beforeend',menuHtml(menuList.children.length));bindMenuItems();menuList.lastElementChild.querySelector('.menu-item-name').focus()};
 const details=document.getElementById('additional-details'),detailHtml=i=>`<div data-additional class="vendor-accent-card rounded-2xl border p-4"><div class="flex gap-2"><input name="additional_details[${i}][title]" class="vendor-input" placeholder="Detail heading"><button type="button" class="remove-additional text-xl text-rose-700 hover:text-rose-900">×</button></div><div class="editor-toolbar mt-3 flex gap-1"><button type="button" data-command="bold" class="px-2 py-1 text-xs border rounded bg-white font-bold">B</button><button type="button" data-command="italic" class="px-2 py-1 text-xs border rounded bg-white italic">I</button><button type="button" data-command="insertUnorderedList" class="px-2 py-1 text-xs border rounded bg-white">• List</button></div><div contenteditable="true" class="detail-editor mt-2 min-h-28 rounded-xl border border-slate-300 bg-white p-3 text-sm text-slate-800"></div><input type="hidden" name="additional_details[${i}][content]"></div>`;
 const sync=()=>document.querySelectorAll('[data-additional]').forEach(r=>r.querySelector('input[type=hidden]').value=r.querySelector('.detail-editor').innerHTML);const bindDetails=()=>{document.querySelectorAll('.remove-additional').forEach(b=>b.onclick=()=>{b.closest('[data-additional]').remove();reindex(details,'[data-additional]','additional_details')});document.querySelectorAll('.editor-toolbar button').forEach(b=>b.onclick=()=>{document.execCommand(b.dataset.command);b.closest('[data-additional]').querySelector('.detail-editor').focus()})};bindDetails();document.getElementById('add-additional-detail').onclick=()=>{details.insertAdjacentHTML('beforeend',detailHtml(details.children.length));bindDetails()};
 const image=document.getElementById('vendor-images'),grid=document.getElementById('vendor-image-preview-grid'),wrap=document.getElementById('vendor-image-preview');let urls=[];const renderImages=()=>{urls.forEach(URL.revokeObjectURL);urls=[];grid.innerHTML='';const files=[...image.files];files.forEach((f,i)=>{const u=URL.createObjectURL(f);urls.push(u);const e=document.createElement('figure');e.className='relative rounded-xl bg-white border border-slate-200 p-2 shadow-xs';e.innerHTML=`<img src="${u}" class="h-28 w-full rounded-lg object-cover"><button type="button" class="absolute right-3 top-3 h-7 w-7 rounded-full bg-slate-900/80 text-white hover:bg-rose-700">×</button><figcaption class="truncate text-xs text-slate-700 mt-1">${esc(f.name)}</figcaption>`;e.querySelector('button').onclick=()=>{const d=new DataTransfer();files.forEach((v,n)=>{if(n!==i)d.items.add(v)});image.files=d.files;renderImages()};grid.append(e)});wrap.classList.toggle('hidden',!files.length)};image.onchange=renderImages;document.querySelectorAll('.remove-existing-image').forEach(b=>b.onclick=()=>b.closest('figure').remove());
 const review=()=>{
   sync();
   const valueOf=el=>!el?'—':el.tagName==='SELECT'?(el.selectedOptions[0]?.text||'—'):(el.value?.trim()||'—');
   const item=(label,value)=>`<div class="review-field"><span>${esc(label)}</span><p>${esc(value||'—')}</p></div>`;
   const basics=[item('Vendor name',form.elements.name.value),item('Category',category.selectedOptions[0]?.text||category.value),...[...fields.querySelectorAll('[data-guided-field]')].map(row=>item(row.querySelector('[name$="[label]"]').value,valueOf(row.querySelector('[name$="[value]"]'))))].join('');
   const packageReview=[...list.querySelectorAll('[data-package]')].map((row,index)=>{const get=suffix=>row.querySelector(`[name$="[${suffix}]"]`)?.value?.trim()||'—';return `<article class="review-subcard"><h4>Package ${index+1}: ${esc(get('name'))}</h4><div class="review-grid">${item('Minimum price',get('min_price')==='—'?'—':`₹${get('min_price')}`)}${item('Maximum price',get('max_price')==='—'?'—':`₹${get('max_price')}`)}${item('Duration',get('duration'))}${item('Description',get('description'))}${item('Inclusions',get('inclusions'))}</div></article>`}).join('');
   const existingPhotoElements=[...document.querySelectorAll('#existing-image-grid figure img')],existingPhotos=existingPhotoElements.map((photo,index)=>photo.alt||`Existing photo ${index+1}`),newPhotos=[...image.files].map(file=>file.name),photoNames=[...existingPhotos,...newPhotos],photoUrls=[...existingPhotoElements.map(photo=>photo.src),...urls];
   const photoPreview=photoUrls.length?`<div class="review-photo-grid">${photoUrls.map((url,index)=>`<figure><img src="${esc(url)}" alt="${esc(photoNames[index]||`Vendor photo ${index+1}`)}"><figcaption>${esc(photoNames[index]||`Vendor photo ${index+1}`)}</figcaption></figure>`).join('')}</div>`:'';
   const customDetails=[...details.querySelectorAll('[data-additional]')].map((row,index)=>`<article class="review-subcard"><h4>${esc(row.querySelector('[name$="[title]"]').value.trim()||`Additional detail ${index+1}`)}</h4><p class="whitespace-pre-line">${esc(row.querySelector('.detail-editor').innerText.trim()||'—')}</p></article>`).join('');
   document.getElementById('vendor-review').innerHTML=`<section class="vendor-review-card"><h3>Basic details</h3><div class="review-grid">${basics}</div></section><section class="vendor-review-card"><h3>Packages (${list.querySelectorAll('[data-package]').length})</h3>${packageReview||'<p class="text-xs text-slate-500">No packages created. Customers will see none.</p>'}</section><section class="vendor-review-card"><h3>Additional information</h3><div class="review-grid">${item('Short introduction',form.elements.short_description.value)}${item('Vendor description',form.elements.description.value)}${item('Photos',photoNames.length?`${photoNames.length} selected`:'No photos selected')}</div>${photoPreview}${customDetails?`<div class="mt-4 space-y-3"><h4 class="font-extrabold text-slate-800">More vendor details</h4>${customDetails}</div>`:''}</section>`;
 };
 const show=n=>{
   step=n;
   panels.forEach(p=>p.classList.toggle('hidden',+p.dataset.step!==n));
   document.querySelectorAll('[data-progress]').forEach(p=>{
     const a=+p.dataset.progress<=n;
     p.classList.toggle('is-active-step',a);
     p.classList.toggle('is-pending-step',!a);
   });
   if(n===4)review();
   window.scrollTo({top:0,behavior:'smooth'});
 };
 const valid=()=>{
   sync();
   if(step===1)for(const e of panels[0].querySelectorAll('[required]'))if(!e.reportValidity())return false;
   if(step===2)for(const e of list.querySelectorAll('.package-name'))if(!e.value.trim()){e.setCustomValidity('Enter the package name.');e.reportValidity();e.setCustomValidity('');return false}
   return true;
 };
 document.querySelectorAll('.wizard-next').forEach(b=>b.onclick=()=>{if(valid())show(Math.min(4,step+1))});
 document.querySelectorAll('.wizard-previous').forEach(b=>b.onclick=()=>show(Math.max(1,step-1)));
 form.addEventListener('submit',sync);
 show(@json($initialStep));
});
</script>