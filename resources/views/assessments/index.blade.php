@extends('layouts.main')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="assessmentFilter()">
    <div class="text-center mb-12" data-aos="fade-down">
        <span class="badge-nature mb-3 inline-flex items-center">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg> 
            Self-Assessment
        </span>
        <h1 class="section-heading mb-4">Mental Wellness Assessments</h1>
        <p class="section-subheading mx-auto">Choose a validated screening tool to understand your current mental well-being. All assessments are free, private, and take only a few minutes.</p>
        <div class="mt-4 inline-block bg-amber-50 text-amber-700 px-4 py-2 rounded-xl text-sm font-medium">⚠️ This platform is not a medical diagnosis system.</div>
    </div>

    {{-- Search and Filters --}}
    <div class="max-w-4xl mx-auto mb-10" data-aos="fade-up" data-aos-delay="100">
        <div class="relative mb-6">
            <svg class="w-5 h-5 absolute left-4 top-3.5 t-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" x-model="searchQuery" placeholder="Search assessments by name or description..." class="search-input text-lg">
            <button x-show="searchQuery" @click="searchQuery = ''" class="absolute right-4 top-3.5 t-muted hover:t-text">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-2">
            <button @click="activeCategory = 'all'" :class="activeCategory === 'all' ? 'active' : ''" class="filter-chip">All</button>
            @php
                $categories = $assessments->pluck('category')->filter()->unique()->values();
            @endphp
            @foreach($categories as $category)
            <button @click="activeCategory = '{{ $category }}'" :class="activeCategory === '{{ $category }}' ? 'active' : ''" class="filter-chip">{{ $category }}</button>
            @endforeach
        </div>
    </div>

    {{-- Assessment Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 max-w-6xl mx-auto">
        @foreach($assessments as $i => $assessment)
        <a href="{{ route('assessments.show', $assessment) }}" 
           class="assessment-card" 
           data-aos="fade-up" data-aos-delay="{{ $i * 60 }}"
           x-show="matchesFilter('{{ addslashes($assessment->title) }}', '{{ addslashes($assessment->description) }}', '{{ $assessment->category }}')"
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="opacity-0 transform scale-95"
           x-transition:enter-end="opacity-100 transform scale-100"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="opacity-100 transform scale-100"
           x-transition:leave-end="opacity-0 transform scale-95">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="card-icon bg-{{ $assessment->color ?? 'sage' }}-100 text-{{ $assessment->color ?? 'sage' }}-600 dark:bg-{{ $assessment->color ?? 'sage' }}-900/40 dark:text-{{ $assessment->color ?? 'sage' }}-300">
                        @if($assessment->type === 'phq9')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21v-2m-4 2v-2m8 2v-2"/></svg>
                        @elseif($assessment->type === 'gad7')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm3.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75z"/></svg>
                        @elseif($assessment->type === 'pss')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                        @elseif($assessment->type === 'who5')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.536a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                        @elseif($assessment->type === 'isi')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/></svg>
                        @elseif($assessment->type === 'asrs')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.25 9.75L16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z"/></svg>
                        @elseif($assessment->type === 'cbi')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 10.5h.375c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125H21M3 7.5h15c.828 0 1.5.672 1.5 1.5v6c0 .828-.672 1.5-1.5 1.5H3c-.828 0-1.5-.672-1.5-1.5v-6c0-.828.672-1.5 1.5-1.5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 10.5h3v3H6v-3z"/></svg>
                        @else
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                        @endif
                    </div>
                    <span class="text-xs t-muted font-medium">~{{ $assessment->estimated_minutes ?? 5 }} min</span>
                </div>
                <span class="badge-{{ $assessment->color === 'teal' ? 'teal' : ($assessment->color === 'indigo' ? 'indigo' : 'nature') }} mb-3">{{ $assessment->category ?? 'Assessment' }}</span>
                <h3 class="font-serif font-bold t-text text-lg mb-2">{{ $assessment->title }}</h3>
                <p class="t-muted text-sm line-clamp-2 mb-4">{{ $assessment->description }}</p>
                <div class="flex items-center t-light text-sm font-medium group-hover:t-muted transition-colors">
                    Begin Assessment
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    {{-- Empty State --}}
    <div x-show="!hasMatches()" x-cloak class="text-center py-20 glass-card-solid max-w-lg mx-auto mt-8">
        <svg class="w-16 h-16 mx-auto mb-4 t-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <h3 class="text-xl font-serif t-text mb-2">No assessments found</h3>
        <p class="t-muted text-sm">Try adjusting your search or filter criteria.</p>
        <button @click="searchQuery = ''; activeCategory = 'all'" class="mt-4 t-muted hover:t-text text-sm font-medium underline">Clear all filters</button>
    </div>

    @if($assessments->isEmpty())
    <div class="text-center py-20 glass-card-solid max-w-lg mx-auto" data-aos="fade-in">
        <svg class="w-16 h-16 mx-auto mb-4 t-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <h3 class="text-xl font-serif t-text mb-2">No Assessments Available</h3>
        <p class="t-muted text-sm">Please run the database seeder to populate assessments.</p>
    </div>
    @endif
</div>

<script>
function assessmentFilter() {
    return {
        searchQuery: '',
        activeCategory: 'all',
        assessmentsData: [
            @foreach($assessments as $a)
            { title: '{{ addslashes($a->title) }}', desc: '{{ addslashes($a->description) }}', cat: '{{ $a->category }}' },
            @endforeach
        ],
        matchesFilter(title, desc, category) {
            const matchesSearch = title.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                                  desc.toLowerCase().includes(this.searchQuery.toLowerCase());
            const matchesCat = this.activeCategory === 'all' || category === this.activeCategory;
            return matchesSearch && matchesCat;
        },
        hasMatches() {
            if (this.assessmentsData.length === 0) return true; // Handled by PHP empty state
            return this.assessmentsData.some(a => this.matchesFilter(a.title, a.desc, a.cat));
        }
    }
}
</script>
@endsection

