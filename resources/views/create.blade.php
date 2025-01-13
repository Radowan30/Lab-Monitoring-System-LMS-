<!DOCTYPE html>
<html lang="en">
<head>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Customer Entry Form</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            :root {
                --primary-color: #2563eb;
                --primary-hover: #1d4ed8;
                --bg-color: #f8fafc;
                --card-bg: #ffffff;
                --text-color: #1e293b;
                --border-color: #e2e8f0;
            }
    
            body {
                background-color: var(--bg-color);
                min-height: 100vh;
                color: var(--text-color);
                line-height: 1.6;
                padding: 2rem 0;
            }
    
            .form-container {
                background-color: var(--card-bg);
                border-radius: 1rem;
                box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
                padding: 2rem;
                max-width: 1000px;
                margin: 0 auto;
            }
    
            .form-title {
                color: var(--text-color);
                font-size: 1.875rem;
                font-weight: 700;
                margin-bottom: 2rem;
                text-align: center;
            }
    
            .section-title {
                color: var(--primary-color);
                font-size: 1.25rem;
                font-weight: 600;
                margin: 1.5rem 0 1rem;
                padding-bottom: 0.5rem;
                border-bottom: 2px solid var(--border-color);
            }
    
            .form-label {
                font-weight: 500;
                color: var(--text-color);
                margin-bottom: 0.5rem;
            }
    
            .form-control, .form-select {
                border: 1px solid var(--border-color);
                border-radius: 0.5rem;
                padding: 0.75rem;
                transition: all 0.2s ease;
            }
    
            .form-control:focus, .form-select:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
            }
    
            .required-field::after {
                content: " *";
                color: #dc2626;
            }
    
            .btn-primary {
                background-color: var(--primary-color);
                border: none;
                padding: 0.75rem 2rem;
                font-weight: 600;
                border-radius: 0.5rem;
                transition: all 0.2s ease;
            }
    
            .btn-primary:hover {
                background-color: var(--primary-hover);
                transform: translateY(-1px);
            }
    
            .alert {
                border-radius: 0.5rem;
                margin-bottom: 1.5rem;
            }
    
            @media (max-width: 768px) {
                .form-container {
                    margin: 1rem;
                    padding: 1.5rem;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="form-container">
                <h1 class="form-title">Laboratory Form</h1>
                
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
    
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
    
                <form action="{{ route('customers.store') }}" method="POST">
                    @csrf
                    
                    <h2 class="section-title">Personal Information</h2>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="full_name" class="form-label required-field">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="passport_number" class="form-label required-field">Passport Number</label>
                            <input type="text" class="form-control" id="passport_number" name="passport_number" value="{{ old('passport_number') }}" required>
                        </div>
                    </div>
    
                    <h2 class="section-title">Institutional Details</h2>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="institution" class="form-label required-field">Institution</label>
                            <select class="form-select" id="institution" name="institution" required>
                                <option value="">Select Institution</option>
                                <option value="UTM KL">UTM KL</option>
                                <option value="UTM JB">UTM JB</option>
                                <option value="Other Uni">Other University</option>
                                <option value="Other Org">Other Organization</option>
                            </select>
                        </div>
                        <div class="col-md-6" id="specific_institution_group" style="display:none;">
                            <label for="specific_institution" class="form-label required-field">Specify Institution</label>
                            <input type="text" class="form-control" id="specific_institution" name="specific_institution" value="{{ old('specific_institution') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="position" class="form-label">Position</label>
                            <input type="text" class="form-control" id="position" name="position" value="{{ old('position') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="supervisor_name" class="form-label">Supervisor Name</label>
                            <input type="text" class="form-control" id="supervisor_name" name="supervisor_name" value="{{ old('supervisor_name') }}">
                        </div>
                    </div>
    
                    <h2 class="section-title">Contact Information</h2>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                        </div>
                    </div>
    
                    <h2 class="section-title">Laboratory Usage Details</h2>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="entry_datetime" class="form-label">Entry Time</label>
                            <input type="datetime-local" class="form-control" id="entry_datetime" name="entry_datetime" value="{{ old('entry_datetime', now()->format('Y-m-d\TH:i')) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="exit_datetime" class="form-label">Exit Time</label>
                            <input type="datetime-local" class="form-control" id="exit_datetime" name="exit_datetime" value="{{ old('exit_datetime') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="purpose_of_usage" class="form-label required-field">Purpose of Visit</label>
                            <select class="form-select" id="purpose_of_usage" name="purpose_of_usage" required>
                                <option value="">Select Purpose</option>
                                <option value="Visit">Visit</option>
                                <option value="Analysis">Analysis</option>
                                <option value="Service/Maintenance">Service/Maintenance</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="equipment_used" class="form-label required-field">Equipment</label>
                            <select class="form-select" id="equipment_used" name="equipment_used" required>
                                <option value="">Select Equipment</option>
                                <option value="AFM">Atomic Force Microscopy (AFM)</option>
                                <option value="FETEM">Field Emission Transmission Electron Microscope (FETEM)</option>
                                <option value="FIB-SEM">DualBeam SEM/Focused Ion Beam (FIB-SEM)</option>
                                <option value="FESEM">Field Emission Scanning Electron Microscope (FESEM)</option>
                                <option value="LV-SEM">Low Vacuum Scanning Electron Microscope (LV-SEM)</option>
                                <option value="MLM">3D Measuring Laser Microscope (MLM)</option>
                                <option value="FM">Fluorescence Microscope (FM)</option>
                                <option value="DM">Digital Microscope (DM)</option>
                                <option value="SZM">Stereo Zoom Microscope (SZM)</option>
                            </select>
                        </div>
                        <div class="col-12" id="purpose_description_group" style="display:none;">
                            <label for="purpose_description" class="form-label required-field">Purpose Description</label>
                            <textarea class="form-control" id="purpose_description" name="purpose_description" rows="3" placeholder="Please provide details about your analysis or service/maintenance work">{{ old('purpose_description') }}</textarea>
                        </div>
                    </div>
    
                    <h2 class="section-title">Additional Information</h2>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="suggestions" class="form-label">Suggestions for Improvement</label>
                            <textarea class="form-control" id="suggestions" name="suggestions" rows="3">{{ old('suggestions') }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="technical_issues" class="form-label">Technical Issues Encountered</label>
                            <textarea class="form-control" id="technical_issues" name="technical_issues" rows="3">{{ old('technical_issues') }}</textarea>
                        </div>
                    </div>
    
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Submit Registration</button>
                    </div>
                </form>
            </div>
        </div>
    
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const institutionSelect = document.getElementById('institution');
                const specificInstitutionGroup = document.getElementById('specific_institution_group');
                const specificInstitutionInput = document.getElementById('specific_institution');
    
                const purposeSelect = document.getElementById('purpose_of_usage');
                const purposeDescriptionGroup = document.getElementById('purpose_description_group');
                const purposeDescriptionTextarea = document.getElementById('purpose_description');
    
                institutionSelect.addEventListener('change', function() {
                    if (this.value === 'Other Uni' || this.value === 'Other Org') {
                        specificInstitutionGroup.style.display = 'block';
                        specificInstitutionInput.required = true;
                    } else {
                        specificInstitutionGroup.style.display = 'none';
                        specificInstitutionInput.required = false;
                        specificInstitutionInput.value = '';
                    }
                });
    
                purposeSelect.addEventListener('change', function() {
                    if (this.value === 'Service/Maintenance' || this.value === 'Analysis') {
                        purposeDescriptionGroup.style.display = 'block';
                        purposeDescriptionTextarea.required = true;
                    } else {
                        purposeDescriptionGroup.style.display = 'none';
                        purposeDescriptionTextarea.required = false;
                        purposeDescriptionTextarea.value = '';
                    }
                });
            });
        </script>
    </body>
    </html>