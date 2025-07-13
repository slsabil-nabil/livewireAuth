@php
    use App\Services\ThemeService;
    $themeName = strtolower(Auth::user()?->agency?->theme_color ?? 'emerald');
    $colors = ThemeService::getCurrentThemeColors($themeName);
@endphp

<div>
    <x-page-title title="القوائم الديناميكية">
        تعديل البنود والبنود الفرعية للقوائم الخاصة بالوكالة
    </x-page-title>

    <div class="space-y-6 p-4 bg-gray-50" wire:key="lists-container-{{ $lists->count() }}">
        @foreach ($lists as $list)
            <div class="bg-white rounded-xl shadow-md p-6">
                <!-- List Header -->
                <div class="flex justify-between items-center border-b pb-4 mb-4">
                    <h3 class="text-lg font-bold text-black">{{ $list->name }}</h3>
                    <button wire:click="toggleExpand({{ $list->id }})"
                        class="text-gray-500 hover:text-primary-600 transition"
                        aria-label="{{ in_array($list->id, $expandedLists) ? 'طي القائمة' : 'توسيع القائمة' }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 transform transition-transform duration-200"
                            :class="{ 'rotate-180': @js(in_array($list->id, $expandedLists)) }" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>

                @if (in_array($list->id, $expandedLists))
                    @foreach ($list->items as $item)
                        <div class="bg-gray-50 border rounded-lg p-4 mb-4">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-gray-800 font-medium">{{ $item->label }}</span>
                            </div>

                            <ul class="space-y-3">
                                @foreach ($item->subItems as $sub)
                                    <li class="flex justify-between items-center px-2">
                                        @if ($editingSubItemId === $sub->id)
                                            <form wire:submit.prevent="updateSubItem"
                                                class="flex items-center gap-2 w-full">
                                                <input type="text" wire:model.defer="editingSubItemLabel"
                                                    class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none bg-white"
                                                    style="border-color: rgb(var(--primary-500)); box-shadow: 0 0 0 2px rgba(var(--primary-500), 0.2);">
                                                <button type="submit"
                                                    class="text-white font-bold px-3 py-1.5 rounded-md text-xs transition"
                                                    style="background: linear-gradient(to right, rgb(var(--primary-500)) 0%, rgb(var(--primary-600)) 100%);">
                                                    حفظ
                                                </button>
                                                <button type="button" wire:click="$set('editingSubItemId', null)"
                                                    class="border border-gray-400 hover:bg-gray-100 px-3 py-1.5 rounded-md text-xs font-medium text-gray-600 transition">
                                                    إلغاء
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-sm text-gray-600">{{ $sub->label }}</span>
                                            <div class="flex items-center gap-2">
                                                <button wire:click="startEditSubItem({{ $sub->id }})"
                                                    class="text-white font-bold px-3 py-1.5 rounded-md text-xs transition"
                                                    style="background: linear-gradient(to right, rgb(var(--primary-500)) 0%, rgb(var(--primary-600)) 100%);">
                                                    تعديل
                                                </button>
                                                @if (!$list->is_system)
                                                    <button wire:click="deleteSubItem({{ $sub->id }})"
                                                        onclick="return confirm('هل أنت متأكد من الحذف؟')"
                                                        class="text-red-600 border border-red-500 hover:bg-red-50 px-3 py-1.5 rounded-md text-xs font-medium transition">
                                                        حذف
                                                    </button>
                                                @endif
                                            </div>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>

                            <div class="flex items-center gap-2 mt-4 relative">
                                <input type="text" wire:model.defer="subItemLabel.{{ $item->id }}"
                                    placeholder="اسم البند الفرعي"
                                    class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none bg-white peer"
                                    style="border-color: rgb(var(--primary-500)); box-shadow: 0 0 0 2px rgba(var(--primary-500), 0.2);">
                                <label
                                    class="absolute right-3 -top-2.5 px-1 bg-white text-xs text-gray-500 transition-all peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-primary-600"
                                    style="display: none;">اسم البند الفرعي</label>
                                <button wire:click="addSubItem({{ $item->id }})" wire:loading.attr="disabled"
                                    class="text-white font-bold px-4 py-2 rounded-md text-xs transition duration-300 hover:shadow-lg whitespace-nowrap"
                                    style="background: linear-gradient(to right, rgb(var(--primary-500)) 0%, rgb(var(--primary-600)) 100%);">
                                    <span wire:loading.remove wire:target="addSubItem({{ $item->id }})">+
                                        إضافة</span>
                                    <span wire:loading wire:target="addSubItem({{ $item->id }})">
                                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </span>
                                </button>
                            </div>

                            @error("subItemLabel.$item->id")
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    @endforeach
                @endif
            </div>
        @endforeach
    </div>
</div>
