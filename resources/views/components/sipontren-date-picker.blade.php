@props([
    'name',
    'value' => '',
    'mode' => 'date',
    'placeholder' => null,
    'autoSubmit' => true,
])

@php
    $id = 'picker_' . str_replace('-', '_', (string) \Illuminate\Support\Str::uuid());

    $placeholderText = $placeholder ?? ($mode === 'month' ? 'Pilih Bulan' : 'Pilih Tanggal');

    $label = $placeholderText;

    if ($value) {
        try {
            if ($mode === 'month') {
                $monthNames = [
                    1 => 'Januari',
                    2 => 'Februari',
                    3 => 'Maret',
                    4 => 'April',
                    5 => 'Mei',
                    6 => 'Juni',
                    7 => 'Juli',
                    8 => 'Agustus',
                    9 => 'September',
                    10 => 'Oktober',
                    11 => 'November',
                    12 => 'Desember',
                ];

                [$year, $month] = explode('-', $value);
                $label = ($monthNames[(int) $month] ?? $month) . ' ' . $year;
            } else {
                $label = \Carbon\Carbon::parse($value)->format('d/m/Y');
            }
        } catch (\Throwable $e) {
            $label = $placeholderText;
        }
    }
@endphp

<div
    id="{{ $id }}"
    class="relative sipontren-picker"
    data-picker-mode="{{ $mode }}"
    data-auto-submit="{{ $autoSubmit ? 'true' : 'false' }}"
>
    <input
        type="hidden"
        name="{{ $name }}"
        value="{{ $value }}"
        data-picker-input
    >

    <button
        type="button"
        data-picker-button
        class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] flex items-center justify-between gap-3 text-left"
    >
        <span
            data-picker-label
            class="truncate {{ $value ? 'text-slate-800' : 'text-slate-400' }}"
        >
            {{ $label }}
        </span>

        <span class="material-symbols-outlined text-slate-500 text-[20px] shrink-0">
            calendar_month
        </span>
    </button>

    <div
        data-picker-menu
        class="hidden absolute left-0 top-full mt-2 w-[300px] max-w-[calc(100vw-2rem)] bg-white border border-slate-200 rounded-3xl shadow-[0_20px_50px_rgba(15,23,42,0.18)] overflow-hidden z-[999]"
    >
        <div class="p-4 border-b border-slate-100">
            <div class="flex items-center justify-between gap-3">
                <button
                    type="button"
                    data-picker-prev
                    class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 hover:bg-[#D9E2FF] hover:text-[#004199] transition-colors flex items-center justify-center"
                >
                    <span class="material-symbols-outlined text-[20px]">
                        chevron_left
                    </span>
                </button>

                <div
                    data-picker-title
                    class="text-sm font-bold text-[#001847] text-center"
                ></div>

                <button
                    type="button"
                    data-picker-next
                    class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 hover:bg-[#D9E2FF] hover:text-[#004199] transition-colors flex items-center justify-center"
                >
                    <span class="material-symbols-outlined text-[20px]">
                        chevron_right
                    </span>
                </button>
            </div>
        </div>

        <div class="p-4">
            <div data-picker-weekdays class="grid grid-cols-7 gap-1 mb-2 text-center text-[11px] font-bold text-slate-400">
                <span>Min</span>
                <span>Sen</span>
                <span>Sel</span>
                <span>Rab</span>
                <span>Kam</span>
                <span>Jum</span>
                <span>Sab</span>
            </div>

            <div data-picker-grid></div>
        </div>

        <div class="px-4 py-3 bg-[#F8FAFC] border-t border-slate-100 flex items-center justify-between gap-3">
            <button
                type="button"
                data-picker-clear
                class="text-sm font-semibold text-slate-500 hover:text-red-600 transition-colors"
            >
                Clear
            </button>

            <button
                type="button"
                data-picker-today
                class="text-sm font-semibold text-[#004199] hover:text-[#00377F] transition-colors"
            >
                {{ $mode === 'month' ? 'Bulan Ini' : 'Hari Ini' }}
            </button>
        </div>
    </div>
</div>

@once
    @push('scripts')
        <script>
            function initSipontrenPickers() {
                const monthNames = [
                    'Januari',
                    'Februari',
                    'Maret',
                    'April',
                    'Mei',
                    'Juni',
                    'Juli',
                    'Agustus',
                    'September',
                    'Oktober',
                    'November',
                    'Desember',
                ];

                const shortMonthNames = [
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'Mei',
                    'Jun',
                    'Jul',
                    'Agu',
                    'Sep',
                    'Okt',
                    'Nov',
                    'Des',
                ];

                document.querySelectorAll('.sipontren-picker').forEach(function (picker) {
                    if (picker.dataset.initialized === 'true') {
                        return;
                    }

                    picker.dataset.initialized = 'true';

                    const mode = picker.dataset.pickerMode || 'date';
                    const autoSubmit = picker.dataset.autoSubmit === 'true';

                    const input = picker.querySelector('[data-picker-input]');
                    const button = picker.querySelector('[data-picker-button]');
                    const label = picker.querySelector('[data-picker-label]');
                    const menu = picker.querySelector('[data-picker-menu]');
                    const title = picker.querySelector('[data-picker-title]');
                    const grid = picker.querySelector('[data-picker-grid]');
                    const weekdays = picker.querySelector('[data-picker-weekdays]');
                    const prev = picker.querySelector('[data-picker-prev]');
                    const next = picker.querySelector('[data-picker-next]');
                    const clear = picker.querySelector('[data-picker-clear]');
                    const today = picker.querySelector('[data-picker-today]');

                    const now = new Date();

                    let currentYear = now.getFullYear();
                    let currentMonth = now.getMonth();

                    if (input.value) {
                        const parts = input.value.split('-');

                        if (parts.length >= 2) {
                            currentYear = Number(parts[0]) || currentYear;
                            currentMonth = (Number(parts[1]) || 1) - 1;
                        }
                    }

                    function pad(value) {
                        return String(value).padStart(2, '0');
                    }

                    function closeAllPickers() {
                        document.querySelectorAll('[data-picker-menu]').forEach(function (otherMenu) {
                            if (otherMenu !== menu) {
                                otherMenu.classList.add('hidden');
                            }
                        });
                    }

                    function submitParentForm() {
                        if (!autoSubmit) {
                            return;
                        }

                        const form = picker.closest('form');

                        if (form) {
                            form.submit();
                        }
                    }

                    function setLabel(text, isEmpty = false) {
                        label.textContent = text;

                        if (isEmpty) {
                            label.classList.add('text-slate-400');
                            label.classList.remove('text-slate-800');
                        } else {
                            label.classList.remove('text-slate-400');
                            label.classList.add('text-slate-800');
                        }
                    }

                    function setDateValue(year, monthIndex, day) {
                        const value = year + '-' + pad(monthIndex + 1) + '-' + pad(day);
                        input.value = value;
                        setLabel(pad(day) + '/' + pad(monthIndex + 1) + '/' + year);
                        menu.classList.add('hidden');
                        submitParentForm();
                    }

                    function setMonthValue(year, monthIndex) {
                        const value = year + '-' + pad(monthIndex + 1);
                        input.value = value;
                        setLabel(monthNames[monthIndex] + ' ' + year);
                        menu.classList.add('hidden');
                        submitParentForm();
                    }

                    function renderDatePicker() {
                        weekdays.classList.remove('hidden');
                        title.textContent = monthNames[currentMonth] + ' ' + currentYear;
                        grid.className = 'grid grid-cols-7 gap-1';

                        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
                        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

                        grid.innerHTML = '';

                        for (let i = 0; i < firstDay; i++) {
                            const empty = document.createElement('div');
                            empty.className = 'h-9';
                            grid.appendChild(empty);
                        }

                        for (let day = 1; day <= daysInMonth; day++) {
                            const value = currentYear + '-' + pad(currentMonth + 1) + '-' + pad(day);
                            const isSelected = input.value === value;

                            const dayButton = document.createElement('button');
                            dayButton.type = 'button';
                            dayButton.textContent = day;
                            dayButton.className = isSelected
                                ? 'h-9 rounded-xl bg-[#004199] text-white text-sm font-bold shadow-[0_8px_16px_rgba(0,65,153,0.22)]'
                                : 'h-9 rounded-xl text-slate-700 text-sm font-semibold hover:bg-[#D9E2FF] hover:text-[#004199] transition-colors';

                            dayButton.addEventListener('click', function () {
                                setDateValue(currentYear, currentMonth, day);
                            });

                            grid.appendChild(dayButton);
                        }
                    }

                    function renderMonthPicker() {
                        weekdays.classList.add('hidden');
                        title.textContent = currentYear;
                        grid.className = 'grid grid-cols-3 gap-2';
                        grid.innerHTML = '';

                        for (let month = 0; month < 12; month++) {
                            const value = currentYear + '-' + pad(month + 1);
                            const isSelected = input.value === value;

                            const monthButton = document.createElement('button');
                            monthButton.type = 'button';
                            monthButton.textContent = shortMonthNames[month];
                            monthButton.className = isSelected
                                ? 'h-11 rounded-2xl bg-[#004199] text-white text-sm font-bold shadow-[0_8px_16px_rgba(0,65,153,0.22)]'
                                : 'h-11 rounded-2xl text-slate-700 text-sm font-semibold hover:bg-[#D9E2FF] hover:text-[#004199] transition-colors';

                            monthButton.addEventListener('click', function () {
                                setMonthValue(currentYear, month);
                            });

                            grid.appendChild(monthButton);
                        }
                    }

                    function render() {
                        if (mode === 'month') {
                            renderMonthPicker();
                        } else {
                            renderDatePicker();
                        }
                    }

                    button.addEventListener('click', function () {
                        closeAllPickers();
                        menu.classList.toggle('hidden');
                        render();
                    });

                    prev.addEventListener('click', function () {
                        if (mode === 'month') {
                            currentYear--;
                        } else {
                            currentMonth--;

                            if (currentMonth < 0) {
                                currentMonth = 11;
                                currentYear--;
                            }
                        }

                        render();
                    });

                    next.addEventListener('click', function () {
                        if (mode === 'month') {
                            currentYear++;
                        } else {
                            currentMonth++;

                            if (currentMonth > 11) {
                                currentMonth = 0;
                                currentYear++;
                            }
                        }

                        render();
                    });

                    clear.addEventListener('click', function () {
                        input.value = '';
                        setLabel(mode === 'month' ? 'Pilih Bulan' : 'Pilih Tanggal', true);
                        menu.classList.add('hidden');
                        submitParentForm();
                    });

                    today.addEventListener('click', function () {
                        const date = new Date();

                        currentYear = date.getFullYear();
                        currentMonth = date.getMonth();

                        if (mode === 'month') {
                            setMonthValue(currentYear, currentMonth);
                        } else {
                            setDateValue(currentYear, currentMonth, date.getDate());
                        }
                    });

                    document.addEventListener('click', function (event) {
                        if (!picker.contains(event.target)) {
                            menu.classList.add('hidden');
                        }
                    });

                    render();
                });
            }

            document.addEventListener('DOMContentLoaded', initSipontrenPickers);
            document.addEventListener('livewire:navigated', initSipontrenPickers);
        </script>
    @endpush
@endonce