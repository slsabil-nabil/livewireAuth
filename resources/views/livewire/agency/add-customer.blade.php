<div class="space-y-6">
    <!-- العنوان والتنبيه -->
    <x-page-title title="إدارة العملاء">
        @if (session('success'))
            <div class="bg-white rounded-md px-4 py-2 text-center shadow text-sm"
                style="color: rgb(var(--primary-700)); border: 1px solid rgba(var(--primary-200), 0.5);">
                {{ session('success') }}
            </div>
        @endif
    </x-page-title>

    <!-- نموذج الإضافة / التعديل -->
    <div class="bg-white rounded-xl shadow-md p-4">
        <x-section-title title="{{ $editingId ? 'تعديل عميل' : 'إضافة عميل جديد' }}" />

        <form wire:submit.prevent="save" class="space-y-4 text-sm">
            @php
                $fieldClass =
                    'w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-[rgb(var(--primary-500))] focus:border-[rgb(var(--primary-500))] focus:outline-none bg-white text-xs peer';
                $labelClass =
                    'absolute right-3 -top-2.5 px-1 bg-white text-xs text-gray-500 transition-all peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-[rgb(var(--primary-600))]';
                $containerClass = 'relative mt-1';
            @endphp

            <div class="grid md:grid-cols-4 gap-3">
                <div class="{{ $containerClass }}">
                    <input type="text" wire:model.defer="name" class="{{ $fieldClass }}"
                        placeholder="الاسم الكامل" />
                    <label class="{{ $labelClass }}">الاسم الكامل</label>
                    @error('name')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div class="{{ $containerClass }}">
                    <input type="email" wire:model.defer="email" class="{{ $fieldClass }}"
                        placeholder="البريد الإلكتروني" />
                    <label class="{{ $labelClass }}">البريد الإلكتروني</label>
                    @error('email')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div class="{{ $containerClass }}">
                    <input type="text" wire:model.defer="phone" class="{{ $fieldClass }}"
                        placeholder="رقم الهاتف" />
                    <label class="{{ $labelClass }}">رقم الهاتف</label>
                    @error('phone')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div class="{{ $containerClass }}">
                    <input type="text" wire:model.defer="address" class="{{ $fieldClass }}"
                        placeholder="العنوان" />
                    <label class="{{ $labelClass }}">العنوان</label>
                    @error('address')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- الأزرار -->
            <div class="flex flex-col sm:flex-row justify-center gap-3 pt-4">
                <button type="submit"
                    class="text-white font-bold px-4 py-2 rounded-xl shadow-md transition duration-300 text-sm w-full sm:w-auto"
                    style="background: linear-gradient(to right, rgb(var(--primary-500)) 0%, rgb(var(--primary-600)) 100%);">
                    {{ $editingId ? 'تأكيد التعديل' : 'حفظ العميل' }}
                </button>

                <button type="button" wire:click="resetFields"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold px-4 py-2 rounded-xl shadow transition duration-300 text-sm w-full sm:w-auto">
                    تنظيف الحقول
                </button>
            </div>
        </form>
    </div>

    <!-- جدول العملاء -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-xs text-right">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="px-2 py-1">الاسم</th>
                        <th class="px-2 py-1">البريد</th>
                        <th class="px-2 py-1">الهاتف</th>
                        <th class="px-2 py-1">العنوان</th>
                        <th class="px-2 py-1">تاريخ الإضافة</th>
                        <th class="px-2 py-1">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($customers as $customer)
                        <tr class="hover:bg-gray-50">
                            <td class="px-2 py-1">{{ $customer->name }}</td>
                            <td class="px-2 py-1">{{ $customer->email ?? '-' }}</td>
                            <td class="px-2 py-1">{{ $customer->phone ?? '-' }}</td>
                            <td class="px-2 py-1">{{ $customer->address ?? '-' }}</td>
                            <td class="px-2 py-1 whitespace-nowrap">{{ $customer->created_at->format('Y-m-d') }}</td>
                            <td class="px-2 py-1 whitespace-nowrap">
                                <button wire:click="edit({{ $customer->id }})" class="font-medium text-xs mx-1"
                                    style="color: rgb(var(--primary-600));">
                                    تعديل
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-400">لا يوجد عملاء حتى الآن</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($customers->hasPages())
            <div class="px-4 py-2 border-t border-gray-200">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>
