<div class="space-y-6">
    <!-- رسائل النجاح -->
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
            class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg text-sm z-50">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                {{ session('message') }}
            </div>
        </div>
    @endif

    <!-- رسائل الخطأ -->
    @if (session()->has('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
            class="fixed bottom-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg text-sm z-50">
            <div class="flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif
    <x-page-title title="إدارة الأدوار والصلاحيات" align="right">
        @can('roles.create')
            <button wire:click="$set('showForm', true)"
                class="text-white font-bold px-4 py-2 rounded-xl shadow-md transition duration-300 text-sm"
                style="background: linear-gradient(to right, rgb(var(--primary-500)) 0%, rgb(var(--primary-600)) 100%);">
                + إضافة دور جديد
            </button>
        @endcan
    </x-page-title>


    <!-- حقل البحث -->
    <div class="bg-white rounded-xl shadow-md p-4">
        <div class="relative mt-1">
            <input wire:model.live="search" type="text"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-[rgb(var(--primary-500))] focus:border-[rgb(var(--primary-500))] focus:outline-none bg-white text-xs peer"
                placeholder=" ">
            <label
                class="absolute right-3 -top-2.5 px-1 bg-white text-xs text-gray-500 transition-all peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-[rgb(var(--primary-600))]">
                <i class="fas fa-search ml-1"></i>
                بحث في الأدوار
            </label>
        </div>
    </div>

    <!-- جدول الأدوار -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-xs text-right">
            <thead class="bg-[rgb(var(--primary-50))] text-[rgb(var(--primary-700))]">
                <tr>
                    <th class="px-3 py-2">اسم الدور</th>
                    <!-- تم حذف عمود الوصف -->
                    <th class="px-3 py-2">الصلاحيات</th>
                    <th class="px-3 py-2">عدد المستخدمين</th>
                    <th class="px-3 py-2">تاريخ الإنشاء</th>
                    <th class="px-3 py-2">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($roles as $role)
                    <tr class="hover:bg-[rgb(var(--primary-50))] transition duration-200">
                        <td class="px-3 py-2">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-sm">{{ $role->display_name ?? $role->name }}</span>
                                @if (in_array($role->name, ['super-admin', 'agency-admin']))
                                    <span
                                        class="px-2 py-0.5 rounded-full bg-red-100 text-red-700 text-[10px] font-semibold border border-red-200 shadow-sm">
                                        <i class="fas fa-shield-alt ml-1"></i>
                                        أساسي
                                    </span>
                                @endif
                            </div>
                            <div class="text-gray-500 text-xs">{{ $role->name }}</div>
                        </td>
                        <!-- زر عرض الصلاحيات -->
                        <td class="px-3 py-2 text-center">
                            <button wire:click="showRolePermissions({{ $role->id }})"
                                class="bg-[rgb(var(--primary-100))] hover:bg-[rgb(var(--primary-200))] text-[rgb(var(--primary-700))] px-2 py-1 rounded-lg text-xs font-medium transition duration-200">
                                <i class="fas fa-eye ml-1"></i>
                                عرض
                            </button>
                        </td>

                        <td class="px-3 py-2 text-center">{{ $role->users_count ?? 0 }}</td>
                        <td class="px-3 py-2">{{ $role->created_at->format('Y-m-d') }}</td>
                        <td class="px-3 py-2">
                            <div class="flex gap-2">
                                @can('roles.edit')
                                    <button wire:click="editRole({{ $role->id }})" class="text-xs font-medium"
                                        style="color: rgb(var(--primary-600));">
                                        تعديل
                                    </button>
                                @endcan

                                @can('roles.delete')
                                    @if (!in_array($role->name, ['super-admin', 'agency-admin']))
                                        <button wire:click="deleteRole({{ $role->id }})"
                                            onclick="return confirm('هل أنت متأكد من حذف هذا الدور؟')"
                                            class="text-xs font-medium text-red-600 hover:text-red-800">
                                            حذف
                                        </button>
                                    @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-400">لا توجد أدوار حالياً</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($roles->hasPages())
            <div class="px-4 py-2 border-t border-gray-200">
                {{ $roles->links() }}
            </div>
        @endif
    </div>

    <!-- النافذة المنبثقة لعرض الصلاحيات -->
    @if ($showPermissionsModal)
        <div class="fixed inset-0 z-50 bg-white/20 backdrop-blur-sm flex items-center justify-center">
            <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-6 space-y-4 text-sm">
                <h3 class="text-lg font-bold border-b pb-2 text-gray-700">
                    الصلاحيات الخاصة بـ: {{ $selectedRoleName }}
                </h3>

                <!-- عداد الصلاحيات -->
                <div
                    class="bg-[rgb(var(--primary-100))] text-[rgb(var(--primary-700))] border border-[rgb(var(--primary-200))] px-3 py-2 rounded-lg text-sm font-bold">
                    <i class="fas fa-check-circle mr-1"></i>
                    {{ count($selectedRolePermissions) }} صلاحية
                </div>

                <div class="flex flex-wrap gap-2 max-h-60 overflow-y-auto">
                    @if (count($selectedRolePermissions) > 0)
                        @foreach ($selectedRolePermissions as $permission)
                            <span
                                class="bg-[rgb(var(--primary-100))] text-[rgb(var(--primary-700))] px-3 py-1 rounded-full text-xs">
                                {{ $permission }}
                            </span>
                        @endforeach
                    @else
                        <span class="text-gray-500 text-sm">لا توجد صلاحيات لهذا الدور</span>
                    @endif
                </div>

                <div class="text-end pt-4">
                    <button wire:click="$set('showPermissionsModal', false)"
                        class="bg-white text-[rgb(var(--primary-700))] border border-[rgb(var(--primary-300))] px-4 py-1 rounded shadow hover:bg-[rgb(var(--primary-100))] hover:text-[rgb(var(--primary-800))] text-xs transition">
                        إغلاق
                    </button>

                </div>
            </div>
        </div>
    @endif

    <!-- نافذة إضافة/تعديل -->
    @if ($showForm)
        <div class="fixed inset-0 z-50 bg-black/10 flex items-center justify-center backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-xl mx-4 p-6 relative"
                style="max-height: 90vh; overflow-y: auto;">
                <button wire:click="closeForm"
                    class="absolute top-3 left-3 text-gray-400 hover:text-red-500 text-xl font-bold">&times;</button>

                <h3 class="text-xl font-bold mb-4 text-center text-[rgb(var(--primary-700))]">
                    {{ $editingRole ? 'تعديل الدور' : 'إضافة دور جديد' }}
                </h3>

                <form wire:submit.prevent="{{ $editingRole ? 'updateRole' : 'addRole' }}" class="space-y-4 text-sm">
                    <!-- اسم الدور -->
                    <div class="relative">
                        <input wire:model.defer="name" type="text"
                            class="w-full peer border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-[rgb(var(--primary-500))] focus:outline-none"
                            placeholder=" " />
                        <label
                            class="absolute right-3 top-2 text-xs text-gray-500 transition-all peer-focus:-top-2 peer-focus:text-[rgb(var(--primary-600))] bg-white px-1">
                            اسم الدور
                        </label>
                        @error('name')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- الصلاحيات -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">الصلاحيات</label>

                        <!-- أزرار اختيار الكل -->
                        <div class="flex flex-wrap gap-2 mb-3">
                            <button type="button" wire:click="selectAllPermissions"
                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded-lg shadow-md text-xs font-bold transition duration-200 transform hover:scale-105">
                                <i class="fas fa-check-double ml-1"></i>
                                اختيار الكل
                            </button>
                            <button type="button" wire:click="deselectAllPermissions"
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg shadow-md text-xs font-bold transition duration-200 transform hover:scale-105">
                                <i class="fas fa-times ml-1"></i>
                                إلغاء الكل
                            </button>
                            <button type="button" wire:click="$toggle('showPermissions')"
                                class="bg-[rgb(var(--primary-500))] hover:bg-[rgb(var(--primary-600))] text-white px-4 py-2 rounded-lg shadow-md text-xs font-bold transition duration-200 transform hover:scale-105">
                                <i class="fas {{ $showPermissions ? 'fa-eye-slash' : 'fa-eye' }} ml-1"></i>
                                {{ $showPermissions ? 'إخفاء الصلاحيات' : 'عرض الصلاحيات' }}
                            </button>

                            <!-- عداد الصلاحيات المختارة -->
                            <div
                                class="bg-[rgb(var(--primary-100))] text-[rgb(var(--primary-700))] px-3 py-2 rounded-lg text-xs font-bold border border-[rgb(var(--primary-200))]">
                                <i class="fas fa-check-circle ml-1"></i>
                                {{ count($selectedPermissions) }} صلاحية مختارة
                            </div>
                        </div>

                        @if ($showPermissions)
                            @php
                                $iconMap = [
                                    'users' => 'fa-user-friends',
                                    'roles' => 'fa-user-shield',
                                    'permissions' => 'fa-key',
                                    'service_types' => 'fa-cogs',
                                    'employees' => 'fa-id-badge',
                                    'reports' => 'fa-chart-bar',
                                    'settings' => 'fa-cog',
                                    'customers' => 'fa-users',
                                    'sales' => 'fa-cash-register',
                                    'departments' => 'fa-building',
                                    'branches' => 'fa-code-branch',
                                    'providers' => 'fa-truck',
                                    'collections' => 'fa-archive',
                                    'dynamic_lists' => 'fa-list',
                                    'intermediaries' => 'fa-user-tie',
                                    'default' => 'fa-layer-group',
                                ];
                                $grouped = $permissions->groupBy(function ($item) {
                                    return explode('.', $item->name)[0] ?? 'أخرى';
                                });
                                $openModules = $openModules ?? [];
                            @endphp

                            <!-- أزرار الأقسام -->
                            <!-- شبكة الأقسام -->
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mt-2 mb-3">
                                @foreach ($grouped as $module => $perms)
                                    @php
                                        $modulePermissions = $perms->pluck('name')->toArray();
                                        $selectedModulePermissions = array_intersect(
                                            $selectedPermissions,
                                            $modulePermissions,
                                        );
                                        $selectedCount = count($selectedModulePermissions);
                                        $totalCount = count($modulePermissions);
                                        $isFullySelected = $this->isModuleFullySelected($module);
                                        $isPartiallySelected = $this->isModulePartiallySelected($module);
                                    @endphp
                                    <button type="button" wire:click="toggleModule('{{ $module }}')"
                                        class="w-full text-center px-3 py-2 rounded-lg text-xs font-bold transition duration-200 transform hover:scale-105 border-2 relative
                @if (!empty($openModules[$module])) @if ($isFullySelected) bg-green-500 text-white border-green-600 shadow-lg @else bg-[rgb(var(--primary-500))] text-white border-[rgb(var(--primary-600))] shadow-lg @endif
@else
@if ($isFullySelected) bg-green-100 text-green-700 border-green-300 shadow-md @elseif($isPartiallySelected) bg-yellow-100 text-yellow-700 border-yellow-300 shadow-md @else bg-white text-[rgb(var(--primary-600))] border-[rgb(var(--primary-300))] hover:border-[rgb(var(--primary-500))] @endif
                @endif">
                                        <i
                                            class="fas {{ $iconMap[$module] ?? $iconMap['default'] }} ml-1 text-xs"></i>
                                        {{ __(ucfirst($module)) }}

                                        <!-- مؤشر حالة الاختيار -->
                                        @if ($selectedCount > 0)
                                            <div
                                                class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] rounded-full w-5 h-5 flex items-center justify-center font-bold shadow-md">
                                                {{ $selectedCount }}
                                            </div>
                                        @endif
                                    </button>
                                @endforeach
                            </div>

                            <!-- زر الإلغاء والإضافة -->
                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button" wire:click="closeForm"
                                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold px-4 py-2 rounded-xl shadow-md transition duration-200 transform hover:scale-105 text-sm">
                                    <i class="fas fa-times ml-1"></i>
                                    إلغاء
                                </button>

                                <button type="submit"
                                    class="text-white font-bold px-4 py-2 rounded-xl shadow-md transition duration-200 transform hover:scale-105 text-sm"
                                    style="background: linear-gradient(to right, rgb(var(--primary-500)) 0%, rgb(var(--primary-600)) 100%);">
                                    <i class="fas {{ $editingRole ? 'fa-save' : 'fa-plus' }} ml-1"></i>
                                    {{ $editingRole ? 'تحديث' : 'إضافة' }}
                                </button>
                            </div>



                            <!-- لوحات الصلاحيات تظهر يسار نافذة الإضافة مباشرة -->
                            <div class="absolute top-0 right-[calc(100%+0.75rem)] z-40 space-y-3" wire:ignore.self>
                                @foreach ($grouped as $module => $perms)
                                    @if (!empty($openModules[$module]))
                                        <div class="bg-white border border-gray-200 rounded-xl shadow p-4 w-[300px]">
                                            <!-- رأس -->
                                            <div class="flex justify-between items-center mb-2">
                                                <div
                                                    class="flex items-center gap-2 font-bold text-[rgb(var(--primary-700))] text-sm">
                                                    <i
                                                        class="fas {{ $iconMap[$module] ?? $iconMap['default'] }} text-[rgb(var(--primary-500))]"></i>
                                                    {{ __(ucfirst($module)) }}
                                                </div>
                                                <button type="button"
                                                    wire:click="toggleModule('{{ $module }}')"
                                                    class="text-gray-500 hover:text-red-500 text-lg font-bold">&times;</button>
                                            </div>

                                            <!-- أزرار اختيار الكل للقسم -->
                                            <div class="flex gap-1 mb-3">
                                                @php
                                                    $modulePermissions = $perms->pluck('name')->toArray();
                                                    $selectedModulePermissions = array_intersect(
                                                        $selectedPermissions,
                                                        $modulePermissions,
                                                    );
                                                    $selectedCount = count($selectedModulePermissions);
                                                    $totalCount = count($modulePermissions);
                                                @endphp

                                                <!-- عداد الصلاحيات المختارة في القسم -->
                                                <div
                                                    class="bg-[rgb(var(--primary-100))] text-[rgb(var(--primary-700))] px-2 py-1 rounded-lg text-xs font-medium border border-[rgb(var(--primary-200))]">
                                                    <i class="fas fa-check-circle ml-1"></i>
                                                    {{ $selectedCount }}/{{ $totalCount }}
                                                </div>

                                                @if ($this->isModuleFullySelected($module))
                                                    <button type="button"
                                                        wire:click="deselectAllModulePermissions('{{ $module }}')"
                                                        class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded-lg text-xs font-bold transition duration-200 transform hover:scale-105">
                                                        <i class="fas fa-times ml-1"></i>
                                                        إلغاء الكل
                                                    </button>
                                                @elseif($this->isModulePartiallySelected($module))
                                                    <button type="button"
                                                        wire:click="selectAllModulePermissions('{{ $module }}')"
                                                        class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded-lg text-xs font-bold transition duration-200 transform hover:scale-105">
                                                        <i class="fas fa-check ml-1"></i>
                                                        اختيار الكل
                                                    </button>
                                                    <button type="button"
                                                        wire:click="deselectAllModulePermissions('{{ $module }}')"
                                                        class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded-lg text-xs font-bold transition duration-200 transform hover:scale-105">
                                                        <i class="fas fa-times ml-1"></i>
                                                        إلغاء الكل
                                                    </button>
                                                @else
                                                    <button type="button"
                                                        wire:click="selectAllModulePermissions('{{ $module }}')"
                                                        class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded-lg text-xs font-bold transition duration-200 transform hover:scale-105">
                                                        <i class="fas fa-check ml-1"></i>
                                                        اختيار الكل
                                                    </button>
                                                @endif
                                            </div>

                                            <!-- قائمة الصلاحيات -->
                                            <div class="space-y-2 text-xs max-h-60 overflow-y-auto">
                                                @foreach ($perms as $perm)
                                                    @php
                                                        $isSelected = in_array($perm->name, $selectedPermissions);
                                                    @endphp
                                                    <label
                                                        class="flex items-center justify-between border-b pb-1 hover:bg-gray-50 p-1 rounded transition
                        @if ($isSelected) bg-green-50 border-green-200 @endif">
                                                        <span
                                                            class="flex items-center gap-2 text-gray-700 font-medium">
                                                            <i
                                                                class="fas {{ $isSelected ? 'fa-check-circle text-green-500' : 'fa-circle text-gray-300' }} text-sm"></i>
                                                            {{ $perm->name }}
                                                        </span>
                                                        <input type="checkbox" wire:model="selectedPermissions"
                                                            value="{{ $perm->name }}"
                                                            class="h-4 w-4 border-gray-300 rounded accent-[rgb(var(--primary-500))] focus:ring-2 focus:ring-[rgb(var(--primary-500))] transition" />
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        @error('selectedPermissions')
                            <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                </form>
            </div>
        </div>
    @endif

</div>
