<div>
<div class="py-8 px-2 sm:px-6 lg:px-12 xl:px-24">
    <!-- عنوان الداشبورد -->
    <x-page-title title="لوحة التحكم الرئيسية" align="right">
        مرحباً بك في نظام إدارة الوكالات
    </x-page-title>

    <!-- صف الكروت والرسم البياني -->
    <div class="flex flex-col lg:flex-row gap-8 items-stretch">
        <!-- الرسم البياني على اليمين -->
        <div class="w-full lg:w-1/4 flex justify-center lg:justify-start">
            <div
                class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-xs flex flex-col items-center h-full min-h-[320px]">
                <h2
                    class="text-xl font-extrabold text-black mb-4 flex items-center gap-2 justify-center lg:justify-start">

                    توزيع حالة الوكالات
                </h2>
                <div class="flex flex-col items-center w-full">
                    <div class="flex justify-center mb-4">
                        <div class="w-36 h-36">
                            <canvas id="agenciesStatusChart" width="144" height="144"
                                style="width:144px; height:144px; max-width:100%;"></canvas>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 w-full mt-2">
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-5 h-5 rounded-full border-2"
                                style="background: rgb(var(--primary-500)); border-color: rgb(var(--primary-500));"></span>
                            <span class="font-semibold" style="color: rgb(var(--primary-500));">نشطة</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-5 h-5 rounded-full border-2"
                                style="background: rgb(var(--primary-100)); border-color: rgb(var(--primary-100));"></span>
                            <span class="font-semibold" style="color: rgb(var(--primary-100));">معلقة</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-5 h-5 rounded-full border-2"
                                style="background: rgb(var(--primary-600)); border-color: rgb(var(--primary-600));"></span>
                            <span class="font-semibold" style="color: rgb(var(--primary-600));">غير نشطة</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- الكروت على اليسار -->
        <div class="w-full lg:w-3/4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8 h-full">
                <!-- كرت مدراء الوكالات -->
                <div
                    class="group rounded-3xl shadow-2xl p-6 flex flex-col items-center justify-center gap-4 transition-transform duration-200 hover:scale-105 cursor-pointer min-h-[220px] h-full bg-white">
                    <div class="rounded-full p-3 shadow-lg mb-2"
                        style="background-color: rgba(var(--primary-500), 0.1); color: rgb(var(--primary-500));">
                        <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                            <circle cx="8.5" cy="7" r="4" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 8v6M23 11h-6" />
                        </svg>
                    </div>
                    <div class="text-4xl font-extrabold text-black drop-shadow">{{ $totalAgencyAdmins }}</div>
                    <div class="text-gray-900 text-base font-semibold tracking-wide">مدراء الوكالات</div>
                </div>
                <!-- كرت الوكالات المعلقة -->
                <div
                    class="group rounded-3xl shadow-2xl p-6 flex flex-col items-center justify-center gap-4 transition-transform duration-200 hover:scale-105 cursor-pointer min-h-[220px] h-full bg-white">
                    <div class="rounded-full p-3 shadow-lg mb-2"
                        style="background-color: rgba(var(--primary-500), 0.1); color: rgb(var(--primary-500));">
                        <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-4xl font-extrabold text-black drop-shadow">{{ $pendingAgencies }}</div>
                    <div class="text-gray-900 text-base font-semibold tracking-wide">الوكالات المعلقة</div>
                </div>
                <!-- كرت الوكالات النشطة -->
                <div
                    class="group rounded-3xl shadow-2xl p-6 flex flex-col items-center justify-center gap-4 transition-transform duration-200 hover:scale-105 cursor-pointer min-h-[220px] h-full bg-white">
                    <div class="rounded-full p-3 shadow-lg mb-2"
                        style="background-color: rgba(var(--primary-500), 0.1); color: rgb(var(--primary-500));">
                        <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-4xl font-extrabold text-black drop-shadow">{{ $activeAgencies }}</div>
                    <div class="text-gray-900 text-base font-semibold tracking-wide">الوكالات النشطة</div>
                </div>
                <!-- كرت إجمالي الوكالات -->
                <div
                    class="group rounded-3xl shadow-2xl p-6 flex flex-col items-center justify-center gap-4 transition-transform duration-200 hover:scale-105 cursor-pointer min-h-[220px] h-full bg-white">
                    <div class="rounded-full p-3 shadow-lg mb-2"
                        style="background-color: rgba(var(--primary-500), 0.1); color: rgb(var(--primary-500));">
                        <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <div class="text-4xl font-extrabold text-black drop-shadow">{{ $totalAgencies }}</div>
                    <div class="text-gray-900 text-base font-semibold tracking-wide">إجمالي الوكالات</div>
                </div>
                <!-- كرت الوكالات غير النشطة -->
                <div
                    class="group rounded-3xl shadow-2xl p-6 flex flex-col items-center justify-center gap-4 transition-transform duration-200 hover:scale-105 cursor-pointer min-h-[220px] h-full bg-white">
                    <div class="rounded-full p-3 shadow-lg mb-2"
                        style="background-color: rgba(var(--primary-500), 0.1); color: rgb(var(--primary-500));">
                        <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div class="text-4xl font-extrabold text-black drop-shadow">{{ $inactiveAgencies }}</div>
                    <div class="text-gray-900 text-base font-semibold tracking-wide">الوكالات غير النشطة</div>
                </div>
            </div>
        </div>
    </div>

    <!-- جدول آخر الوكالات المضافة -->
    <div class="bg-white rounded-2xl shadow-xl p-6 mt-8">
        <h2 class="text-xl font-bold text-black mb-4 flex items-center gap-2">
            آخر الوكالات المضافة
        </h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-right text-gray-900 font-semibold">اسم الوكالة</th>
                        <th class="px-4 py-2 text-right text-gray-900 font-semibold">الهاتف</th>
                        <th class="px-4 py-2 text-right text-gray-900 font-semibold">البريد الإلكتروني</th>
                        <th class="px-4 py-2 text-right text-gray-900 font-semibold">المدير</th>
                        <th class="px-4 py-2 text-right text-gray-900 font-semibold">الحالة</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($recentAgencies as $agency)
                        <tr class="transition hover:bg-[rgba(var(--primary-500),0.08)]">
                            <td class="px-4 py-2 font-bold text-black">{{ $agency->name }}</td>
                            <td class="px-4 py-2">{{ $agency->phone }}</td>
                            <td class="px-4 py-2">{{ $agency->email }}</td>
                            <td class="px-4 py-2">
                                @if ($agency->admin)
                                    <span class="font-semibold text-black">{{ $agency->admin->name }}</span>
                                    <span class="block text-xs text-gray-700">{{ $agency->admin->email }}</span>
                                @else
                                    <span class="text-xs text-red-500">لم يتم تعيين مدير</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                @if ($agency->status === 'active')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                        style="background-color: rgba(var(--primary-500), 0.1); color: rgb(var(--primary-500));">نشطة</span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                        style="background-color: rgba(239, 68, 68, 0.1); color: rgb(239, 68, 68);">معلقة</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-400">لا توجد وكالات مضافة مؤخراً</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('layouts.partials.common')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        renderAgencyStatusChart(
            'agenciesStatusChart',
            {{ $activeAgencies }},
            {{ $pendingAgencies }},
            {{ $totalAgencies - $activeAgencies - $pendingAgencies }}
        );
    });
</script>
</div>
