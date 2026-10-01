<x-layouts.app title="Presensi Massal - PKKMB">
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Input Presensi Massal</h1>
        <p class="text-slate-500 text-sm mt-1">Pilih Sesi Acara dan Gugus untuk mulai melakukan pencatatan presensi peserta PKKMB</p>
    </div>

    <!-- Selector Card -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs max-w-3xl">
        <form method="GET" action="" id="attendanceSelectorForm">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">
                <!-- Select Event Day -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        1. Pilih Sesi Acara
                    </label>
                    <select name="event_day_id" id="event_day_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                        @foreach($eventDays as $day)
                            <option value="{{ $day->id }}" {{ $selectedEventDayId == $day->id ? 'selected' : '' }}>
                                {{ $day->full_session_title }} ({{ \Carbon\Carbon::parse($day->date)->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Select Group -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        2. Pilih Gugus PKKMB
                    </label>
                    <select name="group_id" id="group_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ $selectedGroupId == $group->id ? 'selected' : '' }}>
                                {{ $group->name }} {{ $group->pjUser ? '('.$group->pjUser->name.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="button" onclick="goToInputForm()" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Buka Form Presensi Massal &rarr;
                </button>
            </div>
        </form>
    </div>

    <script>
        function goToInputForm() {
            const eventDayId = document.getElementById('event_day_id').value;
            const groupId = document.getElementById('group_id').value;

            if(!eventDayId || !groupId) {
                alert('Silakan pilih Sesi Acara dan Gugus terlebih dahulu.');
                return;
            }

            window.location.href = "{{ url('/attendance/input') }}/" + eventDayId + "/" + groupId;
        }
    </script>
</x-layouts.app>
