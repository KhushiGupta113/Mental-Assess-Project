@extends('layouts.main')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="assessmentFilter()">
    <div class="text-center mb-12" data-aos="fade-down">
        <span class="badge-nature mb-3 inline-block">🧠 Self-Assessment</span>
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
                        <span class="text-2xl">{{ $assessment->icon ?? '💚' }}</span>
                    </div>
                    <span class="text-xs t-muted font-medium">~{{ $assessment->estimated_minutes ?? 5 }} min</span>
                </div>
                <span class="badge-{{ $assessment->color === 'teal' ? 'teal' : ($assessment->color === 'indigo' ? 'indigo' : 'nature') }} mb-3">{{ $assessment->category ?? 'Assessment' }}</span>
                <h3 class="font-serif font-bold t-text text-lg mb-2">{{ $assessment->title }}</h3>
                <p class="t-muted text-sm line-clamp-2 mb-4">{{ $assessment->description }}</p>
                <div class="flex items-center text-sage-400 text-sm font-medium group-hover:text-sage-600 transition-colors">
                    Begin Assessment
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    {{-- Empty State --}}
    <div x-show="!hasMatches()" x-cloak class="text-center py-20 glass-card-solid max-w-lg mx-auto mt-8">
        <p class="text-4xl mb-4">🔍</p>
        <h3 class="text-xl font-serif t-text mb-2">No assessments found</h3>
        <p class="t-muted text-sm">Try adjusting your search or filter criteria.</p>
        <button @click="searchQuery = ''; activeCategory = 'all'" class="mt-4 text-sage-600 hover:text-sage-800 text-sm font-medium underline">Clear all filters</button>
    </div>

    @if($assessments->isEmpty())
    <div class="text-center py-20 glass-card-solid max-w-lg mx-auto" data-aos="fade-in">
        <p class="text-4xl mb-4">🔍</p>
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
