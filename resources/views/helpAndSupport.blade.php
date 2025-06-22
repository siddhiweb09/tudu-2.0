@extends('layouts.frame')

@section('main')
<style>
    body {
        background: linear-gradient(to right, #eef2f3, #ffffff);
    }

    .card {
        border-radius: 1rem;
    }

    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        border-color: #86b7fe;
    }
</style>
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-primary mb-2 animate__animated animate__fadeInDown">🚀 Task Support Center</h2>
                <p class="text-muted animate__animated animate__fadeInUp">We're here to help you resolve issues or submit feedback related to your tasks.</p>
            </div>
            <div class="card border-0 animate__animated animate__fadeIn">
                <div class="card-body p-4">
                    <form method="POST" class="needs-validation" id="supportForm" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Your Name</label>
                            <input type="text" class="form-control form-control-lg" id="name" name="name" placeholder="Enter your name" required>
                            <div class="invalid-feedback">Please enter your name.</div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Enter your email" required>
                            <div class="invalid-feedback">Please provide a valid email.</div>
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control form-control-lg" id="subject" name="subject" placeholder="What is the issue about?" required>
                            <div class="invalid-feedback">Subject is required.</div>
                        </div>

                        <div class="mb-4">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control form-control-lg" id="message" name="message" rows="5" placeholder="Describe your issue or question..." required></textarea>
                            <div class="invalid-feedback">Please enter your message.</div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm animate__animated animate__delay-1s">Submit Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('customJs')
<script>
    $(document).ready(function() {
        (() => {
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                });
            });
        })();


        $('#supportForm').on('submit', function(e) {
            e.preventDefault();

            const form = $(this);
            const formData = form.serialize();

            $.ajax({
                url: "/store-support-ticket",
                method: "POST",
                data: formData,
                beforeSend: function() {
                    form.find('button[type="submit"]').prop('disabled', true).text('Submitting...');
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Submitted!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    });

                    form[0].reset();
                    form.removeClass('was-validated');

                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessages = Object.values(errors).map(msg => msg[0]).join("<br>");

                        Swal.fire({
                            icon: 'warning',
                            title: 'Validation Failed',
                            html: errorMessages,
                            confirmButtonColor: '#d33'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops!',
                            text: 'Something went wrong. Please try again.',
                            confirmButtonColor: '#d33'
                        });
                    }
                },
                complete: function() {
                    form.find('button[type="submit"]').prop('disabled', false).text('Submit Request');
                }
            });
        });

    });
</script>

@endsection