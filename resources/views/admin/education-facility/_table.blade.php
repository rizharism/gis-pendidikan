{{-- <tbody class="divide-y divide-slate-50"> --}}
@forelse ($facilities as $facility)
    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/50 transition-colors group">
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-xl overflow-hidden border border-slate-100 dark:border-slate-600 shrink-0 bg-slate-50 dark:bg-slate-700 flex items-center justify-center">
                    @if ($facility->gallery && is_array($facility->gallery) && count($facility->gallery) > 0)
                        <img src="{{ Storage::disk('public')->url($facility->gallery[0]) }}"
                            class="w-full h-full object-cover">
                    @else
                        <span
                            class="text-[10px] font-black text-indigo-600 dark:text-white uppercase">{{ $facility->klas == 'universitas' ? 'Uni' : $facility->klas }}</span>
                    @endif
                </div>
                <div>
                    <div class="text-sm font-bold text-slate-800 dark:text-white">{{ $facility->name }}</div>
                    @if($facility->school_code)
                        <div class="text-[10px] text-slate-400 dark:text-slate-500 font-mono">NPSN: {{ $facility->school_code }}</div>
                    @endif
                </div>
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="text-sm text-slate-500 dark:text-slate-400 max-w-xs truncate">{{ $facility->address }}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center gap-2">
                <span class="text-sm text-slate-600 dark:text-slate-300 font-medium uppercase">{{ $facility->klas }}</span>
                @if($facility->accreditation)
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[10px] font-black
                        {{ $facility->accreditation === 'A' ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400' : '' }}
                        {{ $facility->accreditation === 'B' ? 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400' : '' }}
                        {{ $facility->accreditation === 'C' ? 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400' : '' }}
                        {{ $facility->accreditation === 'D' ? 'bg-rose-100 dark:bg-rose-900/40 text-rose-700 dark:text-rose-400' : '' }}
                    ">{{ $facility->accreditation }}</span>
                @endif
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <div class="flex items-center justify-center gap-2">
                {{-- Detail Button --}}
                <button type="button"
                    @click="$dispatch('open-detail', { url: '{{ route('admin.education-facility.show', $facility->id) }}' })"
                    class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 rounded-lg transition-all"
                    title="Detail">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>

                <a href="{{ route('admin.education-facility.edit', $facility->id) }}"
                    class="p-2 text-slate-400 hover:text-brand-accent hover:bg-blue-50 dark:hover:bg-brand-accent/20 rounded-lg transition-all"
                    title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                        </path>
                    </svg>
                </a>
                <form action="{{ route('admin.education-facility.destroy', $facility->id) }}" method="POST"
                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-lg transition-all"
                        title="Hapus">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="px-6 py-10 text-center text-slate-400 dark:text-slate-500 italic text-sm">
            @if ($search)
                Tidak ada hasil untuk pencarian "{{ $search }}"
            @else
                Belum ada data fasilitas pendidikan.
            @endif
        </td>
    </tr>
@endforelse
{{-- </tbody> --}}
