@extends('layouts.main')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-12" data-aos="fade-down">
        <span class="badge-nature mb-3 inline-block">🧠 Self-Assessment</span>
        <h1 class="section-heading mb-4">Mental Wellness Assessments</h1>
        <p class="section-subheading mx-auto">Choose a validated screening tool to understand your current mental well-being. All assessments are free, private, and take only a few minutes.</p>
        <div class="mt-4 inline-block bg-amber-50 text-amber-700 px-4 py-2 rounded-xl text-sm font-medium">⚠️ This platform is not a medical diagnosis system.</div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 max-w-6xl mx-auto">
        @foreach($assessments as $i => $assessment)
        <a href="{{ route('assessments.show', $assessment) }}" class="assessment-card" data-aos="fade-up" data-aos-delay="{{ $i * 60 }}">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="card-icon bg-{{ $assessment->color ?? 'sage' }}-100 text-{{ $assessment->color ?? 'sage' }}-600">
                        <span class="text-2xl">{{ $assessment->icon ?? '💚' }}</span>
                    </div>
                    <span class="text-xs text-sage-400 font-medium">~{{ $assessment->estimated_minutes ?? 5 }} min</span>
                </div>
                <span class="badge-{{ $assessment->color === 'teal' ? 'teal' : ($assessment->color === 'indigo' ? 'indigo' : 'nature') }} mb-3">{{ $assessment->category ?? 'Assessment' }}</span>
                <h3 class="font-serif font-bold text-sage-800 text-lg mb-2">{{ $assessment->title }}</h3>
                <p class="text-sage-500 text-sm line-clamp-2 mb-4">{{ $assessment->description }}</p>
                <div class="flex items-center text-sage-400 text-sm font-medium group-hover:text-sage-600 transition-colors">
                    Begin Assessment
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    @if($assessments->isEmpty())
    <div class="text-center py-20 glass-card-solid max-w-lg mx-auto" data-aos="fade-in">
        <p class="text-4xl mb-4">🔍</p>
        <h3 class="text-xl font-serif text-sage-800 mb-2">No Assessments Available</h3>
        <p class="text-sage-500 text-sm">Please run the database seeder to populate assessments.</p>
    </div>
    @endif
</div>
@endsection
