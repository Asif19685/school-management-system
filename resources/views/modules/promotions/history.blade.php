@extends('layouts.master')

@section('title', 'Student Promotion History')
@section('header-title', 'Student Promotion History')

@section('content')
<style>
    .timeline {
        border-left: 2px solid #0d6efd;
        padding-left: 20px;
        margin-left: 20px;
        position: relative;
    }
    .timeline-item {
        margin-bottom: 30px;
        position: relative;
    }
    .timeline-item::before {
        content: '';
        width: 16px;
        height: 16px;
        background: white;
        border: 4px solid #0d6efd;
        border-radius: 50%;
        position: absolute;
        left: -29px;
        top: 5px;
    }
    .timeline-item.admission::before {
        border-color: #198754;
    }
    .timeline-content {
        background: #f8f9fa;
        padding: 15px 20px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    }
</style>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('promotions.index') }}" class="text-decoration-none">Student Promotions</a></li>
                <li class="breadcrumb-item active" aria-current="page">History</li>
            </ol>
        </nav>
        <a href="{{ route('promotions.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Promotions
        </a>
    </div>
</div>

<div class="row">
    <!-- Student Summary Card -->
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm rounded-4 text-center h-100">
            <div class="card-body p-4">
                @php
                    $imagePath = $history['student']->studentImage ? asset('storage/' . $history['student']->studentImage->image_path) : asset('assets/images/default-avatar.png');
                @endphp
                <img src="{{ $imagePath }}" alt="Student" class="rounded-circle mb-3 border border-3 border-primary shadow-sm" width="120" height="120" style="object-fit: cover;">
                <h4 class="fw-bold mb-1">{{ $history['student']->first_name }} {{ $history['student']->last_name }}</h4>
                <p class="text-muted mb-3">{{ $history['student']->registration_no ?? 'No Reg No' }}</p>
                
                <hr>
                
                <div class="text-start mt-3">
                    <p class="mb-2"><i class="bi bi-person-badge text-primary me-2"></i> <strong>Current Class:</strong> {{ $history['current_class'] ? $history['current_class']->class_name : 'N/A' }}</p>
                    <p class="mb-2"><i class="bi bi-diagram-2 text-primary me-2"></i> <strong>Current Section:</strong> {{ $history['current_section'] ? $history['current_section']->section_name : 'N/A' }}</p>
                    <p class="mb-0"><i class="bi bi-calendar-event text-primary me-2"></i> <strong>Date of Birth:</strong> {{ $history['student']->dob ? $history['student']->dob->format('d M, Y') : 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- History Timeline -->
    <div class="col-md-8 mb-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom border-light py-3">
                <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-clock-history me-2"></i> Academic Timeline</h5>
            </div>
            <div class="card-body p-4">
                
                @if($history['promotion_history']->count() == 0 && !$history['admission_details'])
                    <div class="text-center py-5">
                        <i class="bi bi-folder-x text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No history records found for this student.</p>
                    </div>
                @else
                    <div class="timeline mt-3">
                        
                        <!-- Loop through promotions (most recent first) -->
                        @foreach($history['promotion_history'] as $promo)
                        <div class="timeline-item">
                            <div class="timeline-content border-start border-4 border-primary">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 fw-bold text-dark">Promoted to {{ $promo->toClass ? $promo->toClass->class_name : 'N/A' }}</h6>
                                    <span class="badge bg-primary-subtle text-primary">{{ $promo->academic_year }}</span>
                                </div>
                                <p class="small text-muted mb-2">
                                    <i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::parse($promo->promotion_date)->format('d M, Y') }}
                                    @if($promo->createdBy)
                                    <span class="ms-3"><i class="bi bi-person-gear me-1"></i> By {{ $promo->createdBy->name }}</span>
                                    @endif
                                </p>
                                <div class="row g-2 mt-2">
                                    <div class="col-6">
                                        <div class="p-2 bg-white rounded border">
                                            <small class="text-muted d-block">From</small>
                                            <strong>{{ $promo->fromClass ? $promo->fromClass->class_name : 'N/A' }}</strong> 
                                            <small>({{ $promo->fromSection ? $promo->fromSection->section_name : 'No Sec' }})</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-2 bg-white rounded border border-primary-subtle">
                                            <small class="text-muted d-block">To</small>
                                            <strong class="text-primary">{{ $promo->toClass ? $promo->toClass->class_name : 'N/A' }}</strong> 
                                            <small class="text-primary">({{ $promo->toSection ? $promo->toSection->section_name : 'No Sec' }})</small>
                                        </div>
                                    </div>
                                </div>
                                @if($promo->remarks)
                                <div class="mt-2 text-muted small">
                                    <i class="bi bi-chat-left-quote me-1"></i> <em>{{ $promo->remarks }}</em>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach

                        <!-- Initial Admission Details -->
                        @if($history['admission_details'])
                        <div class="timeline-item admission">
                            <div class="timeline-content border-start border-4 border-success bg-success-subtle bg-opacity-10">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 fw-bold text-success">Initial School Admission</h6>
                                </div>
                                <p class="small text-muted mb-2">
                                    <i class="bi bi-calendar3 me-1"></i> 
                                    {{ $history['admission_details']->admission_date ? \Carbon\Carbon::parse($history['admission_details']->admission_date)->format('d M, Y') : 'Unknown Date' }}
                                </p>
                                <div class="p-2 bg-white rounded border">
                                    <p class="mb-1"><small class="text-muted me-2">Admission No:</small> <strong>{{ $history['admission_details']->admission_no ?? 'N/A' }}</strong></p>
                                    <p class="mb-0"><small class="text-muted me-2">Initially Admitted To:</small> <strong>{{ $history['admission_details']->appliedClass ? $history['admission_details']->appliedClass->class_name : 'N/A' }}</strong></p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
