<div>
    <div class="flex flex-col h-screen overflow-hidden">
        <!-- القسم العلوي الثابت -->
        <div class="flex-none p-4 bg-gray-50">
            <!-- عنوان الصفحة -->
            <x-page-title title="إدارة الوكالات" align="right" level="h1">
                عرض وإدارة جميع الوكالات المسجلة في النظام
            </x-page-title>

            <!-- بطاقة البحث والإجراءات -->
            <div class="bg-white rounded-xl shadow-md p-4">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <!-- حقل البحث -->
                    <div class="relative w-full md:w-1/3">
                        <input type="text" wire:model.debounce.500ms="search"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 pr-10 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none bg-white text-sm"
                            placeholder="ابحث عن وكالة...">
                        <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>

                    <!-- أزرار الإجراءات -->
                    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                        @if (!$showAll)
                            <select wire:model="perPage"
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400">
                                <option value="10">10 صفوف</option>
                                <option value="25">25 صف</option>
                                <option value="50">50 صف</option>
                                <option value="100">100 صف</option>
                            </select>
                        @endif

                        <button wire:click="toggleShowAll"
                            style="background: linear-gradient(to right, rgb(var(--primary-500)) 0%, rgb(var(--primary-600)) 100%); color: #fff;"
                            class="px-4 py-2 rounded-lg font-medium text-sm transition duration-200 shadow hover:shadow-md whitespace-nowrap">
                            {{ $showAll ? 'عرض الصفحات' : 'عرض الكل' }}
                        </button>

                        <a href="{{ route('admin.add-agency') }}"
                            style="background: linear-gradient(to right, rgb(var(--primary-500)) 0%, rgb(var(--primary-600)) 100%); color: #fff;"
                            class="px-4 py-2 rounded-lg font-medium text-sm transition duration-200 shadow hover:shadow-md whitespace-nowrap">
                            إضافة وكالة جديدة
                        </a>
                    </div>
                </div>
            </div>

            <!-- رسائل التنبيه -->
            @if (session('message'))
                <div
                    class="mt-4 p-3 bg-emerald-100 border border-emerald-200 text-emerald-800 rounded-lg text-center text-sm">
                    {{ session('message') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mt-4 p-3 bg-red-100 border border-red-200 text-red-800 rounded-lg text-center text-sm">
                    {{ session('error') }}
                </div>
            @endif
            @if (isset($successMessage) && $successMessage)
                <div
                    class="mt-4 p-3 bg-emerald-100 border border-emerald-200 text-emerald-800 rounded-lg text-center text-sm">
                    {{ $successMessage }}
                </div>
            @endif
        </div>

        <!-- القسم السفلي مع الجدول القابل للتمرير -->
        <div class="flex-1 overflow-hidden px-4 pb-4">
            <div class="bg-white rounded-xl shadow-md h-full flex flex-col">
                <div class="overflow-y-auto flex-1">
                    <table class="min-w-full divide-y divide-gray-200 text-xs text-right">
                        <thead class="bg-gray-100 text-gray-900 sticky top-0 z-10">
                            <tr>
                                <th class="px-3 py-2 whitespace-nowrap">الشعار</th>
                                <th class="px-3 py-2 whitespace-nowrap text-gray-900">اسم الوكالة</th>
                                <th class="px-3 py-2 whitespace-nowrap">الفرع الرئيسي</th>
                                <th class="px-3 py-2 whitespace-nowrap">البريد الإلكتروني</th>
                                <th class="px-3 py-2 whitespace-nowrap">الهاتف</th>
                                <th class="px-3 py-2 whitespace-nowrap">العملة</th>
                                <th class="px-3 py-2 whitespace-nowrap">العنوان</th>
                                <th class="px-3 py-2 whitespace-nowrap">رقم الرخصة</th>
                                <th class="px-3 py-2 whitespace-nowrap">السجل التجاري</th>
                                <th class="px-3 py-2 whitespace-nowrap">الرقم الضريبي</th>
                                <th class="px-3 py-2 whitespace-nowrap">الحالة</th>
                                <th class="px-3 py-2 whitespace-nowrap">انتهاء الرخصة</th>
                                <th class="px-3 py-2 whitespace-nowrap">بداية الاشتراك</th>
                                <th class="px-3 py-2 whitespace-nowrap">نهاية الاشتراك</th>
                                <th class="px-3 py-2 whitespace-nowrap">المستخدمين</th>
                                <th class="px-3 py-2 whitespace-nowrap">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($agencies as $agency)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-2">
                                        @if ($agency->logo)
                                            <img src="{{ asset('storage/' . $agency->logo) }}"
                                                class="h-8 w-8 rounded-full object-cover" alt="شعار الوكالة">
                                        @else
                                            <div
                                                class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                    </path>
                                                </svg>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 font-medium">{{ $agency->name }}</td>
                                    <td class="px-3 py-2">{{ $agency->main_branch_name }}</td>
                                    <td class="px-3 py-2">{{ $agency->email }}</td>
                                    <td class="px-3 py-2">
                                        <div>{{ $agency->phone }}</div>
                                        @if ($agency->landline)
                                            <div class="text-xs text-gray-500">{{ $agency->landline }}</div>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2">{{ $agency->currency }}</td>
                                    <td class="px-3 py-2 max-w-xs truncate">{{ $agency->address }}</td>
                                    <td class="px-3 py-2">{{ $agency->license_number }}</td>
                                    <td class="px-3 py-2">{{ $agency->commercial_record }}</td>
                                    <td class="px-3 py-2">{{ $agency->tax_number }}</td>
                                    <td class="px-3 py-2">
                                        @if ($agency->status == 'active')
                                            <span
                                                class="px-2 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-medium">
                                                نشطة
                                            </span>
                                        @elseif($agency->status == 'inactive')
                                            <span
                                                class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-medium">
                                                غير نشطة
                                            </span>
                                        @else
                                            <span
                                                class="px-2 py-1 rounded-full bg-red-100 text-red-800 text-xs font-medium">
                                                موقوفة
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2">{{ $agency->license_expiry_date->format('Y-m-d') }}</td>
                                    <td class="px-3 py-2">
                                        @if ($agency->subscription_start_date)
                                            <span
                                                class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 px-2 py-1 rounded-md">
                                                <svg class="h-4 w-4 text-emerald-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <rect x="3" y="4" width="18" height="18" rx="2"
                                                        stroke-width="2" />
                                                    <path stroke-width="2" d="M16 2v4M8 2v4M3 10h18" />
                                                </svg>
                                                {{ is_string($agency->subscription_start_date) ? $agency->subscription_start_date : $agency->subscription_start_date->format('Y-m-d') }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2">
                                        @if ($agency->subscription_end_date)
                                            <span
                                                class="inline-flex items-center gap-1 bg-rose-50 text-rose-700 px-2 py-1 rounded-md">
                                                <svg class="h-4 w-4 text-rose-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <rect x="3" y="4" width="18" height="18" rx="2"
                                                        stroke-width="2" />
                                                    <path stroke-width="2" d="M16 2v4M8 2v4M3 10h18" />
                                                </svg>
                                                {{ is_string($agency->subscription_end_date) ? $agency->subscription_end_date : $agency->subscription_end_date->format('Y-m-d') }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 text-center">{{ $agency->max_users }}</td>
                                    <td class="px-3 py-2 whitespace-nowrap">
                                        <a href="{{ route('admin.edit-agency', $agency->id) }}"
                                            style="background: linear-gradient(to right, rgb(var(--primary-500)) 0%, rgb(var(--primary-600)) 100%); color: #fff;"
                                            class="px-3 py-1 rounded-lg font-medium text-xs transition duration-200 shadow hover:shadow-md whitespace-nowrap">
                                            تعديل
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="text-center py-4 text-gray-400">لا توجد وكلات مسجلة</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- الترقيم -->
                @if (!$showAll)
                    <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                        {{ $agencies->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
