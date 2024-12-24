<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Entry Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px 0;
        }
        .form-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 30px;
        }
        .form-label {
            font-weight: 600;
            color: #495057;
        }
        .form-control, .form-select {
            padding: 10px;
            border-color: #ced4da;
        }
        .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #2980b9;
        }
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            .form-container {
                padding: 15px;
            }
            .row > .col-md-6 {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2 class="text-center mb-4">Customer Entry Form</h2>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('customers.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="full_name" class="form-label">Full Name *</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="passport_number" class="form-label">Passport Number *</label>
                        <input type="text" class="form-control" id="passport_number" name="passport_number" value="{{ old('passport_number') }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="institution" class="form-label">Institution *</label>
                        <select class="form-select" id="institution" name="institution" required>
                            <option value="">Select Institution</option>
                            <option value="UTM KL">UTM KL</option>
                            <option value="UTM JB">UTM JB</option>
                            <option value="Other Uni">Other University</option>
                            <option value="Other Org">Other Organization</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3" id="specific_institution_group" style="display:none;">
                        <label for="specific_institution" class="form-label">Specific Institution *</label>
                        <input type="text" class="form-control" id="specific_institution" name="specific_institution" value="{{ old('specific_institution') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="position" class="form-label">Position</label>
                        <input type="text" class="form-control" id="position" name="position" value="{{ old('position') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="entry_datetime" class="form-label">Entry Datetime</label>
                        <input type="datetime-local" class="form-control" id="entry_datetime" name="entry_datetime" value="{{ old('entry_datetime', now()->format('Y-m-d\TH:i')) }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="exit_datetime" class="form-label">Exit Datetime</label>
                        <input type="datetime-local" class="form-control" id="exit_datetime" name="exit_datetime" value="{{ old('exit_datetime') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="purpose_of_usage" class="form-label">Purpose of Lab Usage *</label>
                        <select class="form-select" id="purpose_of_usage" name="purpose_of_usage" required>
                            <option value="">Select Purpose</option>
                            <option value="Visit">Visit</option>
                            <option value="Analysis">Analysis</option>
                            <option value="Service/Maintenance">Service/Maintenance</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3" id="purpose_description_group" style="display:none;">
                    <label for="purpose_description" class="form-label">Purpose Description *</label>
                    <textarea class="form-control" id="purpose_description" name="purpose_description" rows="3">{{ old('purpose_description') }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="equipment_used" class="form-label">Equipment Used *</label>
                        <select class="form-select" id="equipment_used" name="equipment_used" required>
                            <option value="">Select Equipment</option>
                            <option value="SEM">SEM</option>
                            <option value="TEM">TEM</option>
                            <option value="AFM">AFM</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="supervisor_name" class="form-label">Supervisor Name</label>
                        <input type="text" class="form-control" id="supervisor_name" name="supervisor_name" value="{{ old('supervisor_name') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="usage_duration" class="form-label">Usage Duration (hours)</label>
                        <input type="number" step="0.01" class="form-control" id="usage_duration" name="usage_duration" value="{{ old('usage_duration') }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="suggestions" class="form-label">Suggestions</label>
                    <textarea class="form-control" id="suggestions" name="suggestions" rows="3">{{ old('suggestions') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="technical_issues" class="form-label">Technical Issues</label>
                    <textarea class="form-control" id="technical_issues" name="technical_issues" rows="3">{{ old('technical_issues') }}</textarea>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg">Submit Entry</button>
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

            // Institution dynamic behavior
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

            // Purpose of Usage dynamic behavior
            purposeSelect.addEventListener('change', function() {
                if (this.value === 'Service/Maintenance') {
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