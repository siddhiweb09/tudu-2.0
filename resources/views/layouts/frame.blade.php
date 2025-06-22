<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/png" href="assets/images/logos/favicon.png" />
    <!-- Core Css -->
    <link rel="stylesheet" href="assets/css/styles.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />

    <title>Make Your Tasks Easy</title>
</head>

<body class="loading">

    <!-- Preloader -->
    <div class="preloader">
        <div class="scene">
            <div class="shadow"></div>
            <div class="jumper">
                <div class="spinner">
                    <div class="scaler">
                        <div class="loader">
                            <div class="cuboid">
                                <div class="cuboid__side"></div>
                                <div class="cuboid__side"></div>
                                <div class="cuboid__side"></div>
                                <div class="cuboid__side"></div>
                                <div class="cuboid__side"></div>
                                <div class="cuboid__side"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div id="main-wrapper">
        <!-- Navbar Start -->
        <nav class="navbar navbar-expand-lg navbar-light bg-blue d-flex flex-wrap p-0">
            <div class="col-12 border-bottom py-2">
                <div class="container">
                    <div class="d-flex justify-content-between">
                        <a class="navbar-brand" href="#"><img src="assets/images/logo.png" class="logo" /></a>

                        <!-- Example single danger button -->
                        <div class="dropdown-center">
                            <button class="btn p-0 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="assets/images/logo.png" class="rounded-circle profile-pic" />
                            </button>
                            <ul class="dropdown-menu profile-menu">
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <li><a class="dropdown-item" href="#">Logout</a></li>
                            </ul>
                        </div>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-12 py-2">
                <div class="container">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mb-2 mb-lg-0 d-flex justify-content-between w-100">
                            <li class="nav-item">
                                <a class="nav-link" href="../../index.html">
                                    <i class="ti ti-home-2 menu-icon"></i>
                                    <span class="menu-title">Dashboard</span>
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-list-details menu-icon"></i>
                                    <span class="menu-title">My Tasks</span>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="#">Tasks</a></li>
                                    <li><a class="dropdown-item" href="#">Pending Tasks</a></li>
                                    <li><a class="dropdown-item" href="#">Delayed Tasks</a></li>
                                    <li><a class="dropdown-item" href="#">Completed Tasks</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../index.html">
                                    <i class="ti ti-presentation-analytics menu-icon"></i>
                                    <span class="menu-title">Projects</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../index.html">
                                    <i class="ti ti-table-plus menu-icon"></i>
                                    <span class="menu-title">Quick Add Task </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../index.html">
                                    <i class="ti ti-calendar-week menu-icon"></i>
                                    <span class="menu-title">Calendar View</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../index.html">
                                    <i class="ti ti-tags menu-icon"></i>
                                    <span class="menu-title">Tags / Labels</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../index.html">
                                    <i class="ti ti-info-square-roundedx` menu-icon"></i>
                                    <span class="menu-title">Support</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <!--  Navbar End -->
        <div class="page-wrapper" style="height: 80vh;">
            @yield('main')
        </div>
    </div>

    <div id="addtask-trigger">
        <button class="btn" type="button" class="open-modal-btn" data-bs-toggle="modal" data-bs-target="#assign_task">
            <i class="ti ti-plus"></i>
        </button>
    </div>
    @extends('modalCreateTask')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        window.addEventListener('load', function() {
            const preloader = document.querySelector('.preloader');
            if (preloader) {
                preloader.style.opacity = '0';
                preloader.style.visibility = 'hidden';
                preloader.style.transition = 'opacity 0.5s ease';
            }
        });

        $(document).ready(function() {
            // Step navigation
            const $nextButtons = $('.next-step');
            const $prevButtons = $('.prev-step');
            const $formSteps = $('.form-step');
            const $stepIndicators = $('.step');

            $nextButtons.on('click', function() {
                const $currentStep = $('.form-step.active');
                const currentStepNumber = parseInt($currentStep.data('step'));
                const nextStepNumber = currentStepNumber + 1;

                if (validateStep(currentStepNumber)) {
                    $currentStep.removeClass('active').addClass('hidden');
                    $(`.form-step[data-step="${nextStepNumber}"]`).removeClass('hidden').addClass('active');
                    updateStepIndicators(nextStepNumber);
                }
            });

            $prevButtons.on('click', function() {
                const $currentStep = $('.form-step.active');
                const currentStepNumber = parseInt($currentStep.data('step'));
                const prevStepNumber = currentStepNumber - 1;

                $currentStep.removeClass('active').addClass('hidden');
                $(`.form-step[data-step="${prevStepNumber}"]`).removeClass('hidden').addClass('active');
                updateStepIndicators(prevStepNumber);
            });

            function updateStepIndicators(activeStep) {
                $stepIndicators.each(function() {
                    const $step = $(this);
                    const stepNumber = parseInt($step.data('step'));
                    const $stepNumberElement = $step.find('.step-number');
                    const $stepTitleElement = $step.find('.step-title');

                    if (stepNumber === activeStep) {
                        $stepNumberElement.removeClass('bg-gray-200 text-gray-700').addClass('bg-blue-600 text-white');
                        $stepTitleElement.removeClass('text-gray-500').addClass('text-gray-900');
                    } else if (stepNumber < activeStep) {
                        $stepNumberElement.removeClass('bg-gray-200 text-gray-700').addClass('bg-green-500 text-white');
                        $stepTitleElement.removeClass('text-gray-500').addClass('text-gray-900');
                    } else {
                        $stepNumberElement.removeClass('bg-blue-600 text-white bg-green-500').addClass('bg-gray-200 text-gray-700');
                        $stepTitleElement.removeClass('text-gray-900').addClass('text-gray-500');
                    }
                });
            }

            function validateStep(stepNumber) {
                let isValid = true;

                if (stepNumber === 1) {
                    const title = $('#taskTitleInput').val();
                    const priority = $('.btn-check').val();

                    if (!title.trim()) {
                        alert('Task title is required');
                        isValid = false;
                    } else if (!priority) {
                        alert('Priority is required');
                        isValid = false;
                    }
                }

                return isValid;
            }

            // Dynamic link fields
            const $linksContainer = $('#links-container');
            const $addLinkButton = $('#add-link');

            $addLinkButton.on('click', function() {
                const newLinkGroup = $(`
            <div class="link-input-group mb-2">
                <div class="flex">
                    <input type="url" name="links[]" placeholder="https://example.com"
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <button type="button" class="remove-link px-3 py-2 bg-red-500 text-white rounded-r-md hover:bg-red-600 focus:outline-none">
                        Remove
                    </button>
                </div>
            </div>
        `);
                $linksContainer.before(newLinkGroup);
            });

            $linksContainer.on('click', '.remove-link', function() {
                const $linkGroup = $(this).closest('.link-input-group');
                if ($('.link-input-group').length > 1) {
                    $linkGroup.remove();
                } else {
                    // Don't remove the last one, just clear it
                    $linkGroup.find('input').val('');
                }
            });

            // Voice recording functionality
            let mediaRecorder;
            let audioChunks = [];
            const $startRecordingButton = $('#start-recording');
            const $stopRecordingButton = $('#stop-recording');
            const $audioPlayback = $('#audio-playback');
            const $voiceNoteInput = $('#voice-note');

            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                $startRecordingButton.on('click', async function() {
                    try {
                        const stream = await navigator.mediaDevices.getUserMedia({
                            audio: true
                        });
                        mediaRecorder = new MediaRecorder(stream);
                        audioChunks = [];

                        mediaRecorder.ondataavailable = function(e) {
                            audioChunks.push(e.data);
                        };

                        mediaRecorder.onstop = function() {
                            const audioBlob = new Blob(audioChunks, {
                                type: 'audio/wav'
                            });
                            const audioUrl = URL.createObjectURL(audioBlob);
                            $audioPlayback.attr('src', audioUrl).removeClass('hidden');

                            // Create a File object from the Blob
                            const audioFile = new File([audioBlob], 'voice-note.wav', {
                                type: 'audio/wav'
                            });

                            // Create a DataTransfer object to set the file to the input
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(audioFile);
                            $voiceNoteInput[0].files = dataTransfer.files;
                        };

                        mediaRecorder.start();
                        $startRecordingButton.prop('disabled', true);
                        $stopRecordingButton.prop('disabled', false);
                    } catch (error) {
                        console.error('Error accessing microphone:', error);
                        alert('Could not access microphone. Please ensure you have granted permission.');
                    }
                });

                $stopRecordingButton.on('click', function() {
                    if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                        mediaRecorder.stop();
                        mediaRecorder.stream.getTracks().forEach(track => track.stop());
                        $startRecordingButton.prop('disabled', false);
                        $stopRecordingButton.prop('disabled', true);
                    }
                });
            } else {
                $startRecordingButton.prop('disabled', true);
                $stopRecordingButton.prop('disabled', true);
                console.warn('MediaDevices API or getUserMedia not supported in this browser');
            }

            let taskCounter = 1;

            // Add new task field
            $(document).on('click', '.add-task-btn', function() {
                taskCounter++;
                const newTask = `
                    <div class="task-item mb-3" data-task-id="${taskCounter}">
                        <div class="input-group">
                        <input type="text" class="form-control task-input me-3" name="tasks[]" placeholder="Enter task" required>
                        <button type="button" class="btn btn-inverse-danger remove-task-btn">
                            <i class="ti ti-minus"></i>
                        </button>
                        </div>
                    </div>
                    `;
                $('.task-container').append(newTask);
            });

            // Remove task field
            $(document).on('click', '.remove-task-btn', function() {
                if ($('.task-item').length > 1) {
                    $(this).closest('.task-item').remove();
                } else {
                    alert("You need at least one task field!");
                }
            });
        });
    </script>
    @yield('customJs')
</body>

</html>