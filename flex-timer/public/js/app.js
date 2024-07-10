document.addEventListener("DOMContentLoaded", function() {
    let timer;
    let isRunning = false;
    let workTime = 25 * 60; // Default 25 minutes in seconds
    let breakTime = 5 * 60; // Default 5 minutes in seconds
    let timeLeft = workTime;
    let isWorkSession = true; // To track if it's work or break
    let currentTask = null;
    let endSound = new Audio("alarm1.mp3");

    const timeDisplay = document.getElementById('timeDisplay');
    const timeCircle = document.getElementById('timeCircle');
    const resetButton = document.getElementById('resetButton');
    const toggleButton = document.getElementById('toggleButton');
    const toggleIcon = document.getElementById('toggleIcon'); // アイコン用の要素を取得
    const taskInput = document.getElementById('taskInput');
    const taskList = document.getElementById('taskList');
    const currentTaskDisplay = document.getElementById('currentTaskDisplay');
    const settingsButton = document.getElementById('settingsButton');
    const saveSettingsButton = document.getElementById('saveSettingsButton');

    function updateTimeDisplay() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timeDisplay.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
    }

    function startTimer() {
        timer = setInterval(function() {
            if (timeLeft <= 0) {
                clearInterval(timer);
                isRunning = false;
                endSound.play(); // Play the selected alarm sound
                // Switch between work and break sessions
                if (isWorkSession) {
                    timeLeft = breakTime;
                    timeDisplay.textContent = `${Math.floor(breakTime / 60)}:00`;
                    isWorkSession = false;
                    toggleIcon.className = 'bi bi-person-workspace'; // Change icon to work icon
                    document.body.classList.remove('work-session');
                    document.body.classList.add('break-session');
                } else {
                    timeLeft = workTime;
                    timeDisplay.textContent = `${Math.floor(workTime / 60)}:00`;
                    isWorkSession = true;
                    toggleIcon.className = 'bi bi-cup-fill'; // Change icon to break icon
                    document.body.classList.remove('break-session');
                    document.body.classList.add('work-session');
                }
                return;
            }
            timeLeft--;
            updateTimeDisplay();
        }, 1000);
        isRunning = true;
        //toggleIcon.className = 'bi bi-pause-fill'; // Remove icon change when timer starts
    }

    function stopTimer() {
        clearInterval(timer);
        isRunning = false;
        toggleIcon.className = isWorkSession ? 'bi bi-cup-fill' : 'bi bi-person-workspace'; // Keep icon based on session type
    }

    function resetTimer() {
        stopTimer();
        timeLeft = isWorkSession ? workTime : breakTime;
        updateTimeDisplay();
        toggleIcon.className = isWorkSession ? 'bi bi-cup-fill' : 'bi bi-person-workspace'; // Reset icon based on session type
    }

    function toggleSession() {
        isWorkSession = !isWorkSession;
        resetTimer();
        document.body.classList.toggle('work-session', isWorkSession);
        document.body.classList.toggle('break-session', !isWorkSession);
        toggleIcon.className = isWorkSession ? 'bi bi-cup-fill' : 'bi bi-person-workspace';
    }

    function saveSettings() {
        const workMinutes = parseInt(document.getElementById('workTime').value, 10);
        const breakMinutes = parseInt(document.getElementById('breakTime').value, 10);
        const selectedSound = document.getElementById('endSound').value;

        workTime = workMinutes * 60;
        breakTime = breakMinutes * 60;
        endSound.src = selectedSound;

        if (isWorkSession) {
            timeLeft = workTime;
        } else {
            timeLeft = breakTime;
        }

        updateTimeDisplay();
        $('#settingsModal').modal('hide');
    }

    timeCircle.addEventListener('click', function() {
        if (isRunning) {
            stopTimer();
        } else {
            startTimer();
        }
    });

    resetButton.addEventListener('click', resetTimer);
    toggleButton.addEventListener('click', toggleSession);
    settingsButton.addEventListener('click', function() {
        $('#settingsModal').modal('show');
    });
    saveSettingsButton.addEventListener('click', saveSettings);

    // Task management functions
    function addTask() {
        const taskText = taskInput.value.trim();
        if (taskText === "") {
            return;
        }

        $.ajax({
            url: '/task/create',
            type: 'POST',
            data: {
                task_title: taskText,
                task_status: 'pending',
                '_token': $('meta[name="csrf-token"]').attr('content') // CSRFトークンの取得
            },
            success: function(response, textStatus, xhr) {
                console.log(response);
                if (xhr.status === 200) { // ステータスコードが200の場合に後続の処理を実行
                    addTaskHandler(response.task_title, response.task_status, response.id);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', status, error);
            }
        });
    }

    document.getElementById('taskForm').addEventListener('submit', function(event) {
        event.preventDefault();
        addTask();
    });

    function updateCurrentTask() {
        const firstTask = taskList.querySelector('li');
        currentTask = firstTask ? firstTask.textContent : null;
        currentTaskDisplay.textContent = currentTask ? `Current Task: ${currentTask}` : 'No current task';
    }

    function addTaskHandler(task_title, task_status, task_id) {
        const listItem = document.createElement('li');
        listItem.className = 'list-group-item d-flex justify-content-between align-items-center';

        // ドラッグハンドル
        const dragHandle = document.createElement('span');
        dragHandle.className = 'drag-handle';
        dragHandle.innerHTML = '<i class="bi bi-list"></i>';

        const span = document.createElement('span');
        span.textContent = task_title;

        const deleteButton = document.createElement('button');
        deleteButton.className = 'btn btn-danger btn-sm';
        deleteButton.innerHTML = '<i class="bi bi-x"></i>';
        deleteButton.addEventListener('click', function() {
            $.ajax({
                url: '/task/destroy',
                type: 'POST',
                data: {
                    task_id: task_id,
                    '_token': $('meta[name="csrf-token"]').attr('content') // CSRFトークンの取得
                },
                success: function(response, textStatus, xhr) {
                    console.log(response);
                    if (xhr.status === 200) { // ステータスコードが200の場合に後続の処理を実行
                        taskList.removeChild(listItem);
                        updateCurrentTask();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', status, error);
                }
            });
        });

        listItem.appendChild(dragHandle); // ドラッグハンドルを追加
        listItem.appendChild(span);
        listItem.appendChild(deleteButton);
        taskList.appendChild(listItem);

        taskInput.value = "";
        updateCurrentTask();
    }

    function getTasks() {
        $.ajax({
            url: '/task/index',
            type: 'GET',
            success: function(response, textStatus, xhr) {
                if (xhr.status === 200) { // ステータスコードが200の場合に後続の処理を実行
                    response.forEach(function(task) {
                        addTaskHandler(task.task_title, task.task_status, task.id);
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', status, error);
            }
        });
    }

    getTasks();

    // Initialize display
    updateTimeDisplay();
    updateCurrentTask();

    // Make the task list sortable with drag handles
    new Sortable(taskList, {
        animation: 150,
        handle: '.drag-handle', // ドラッグハンドルを指定
        onEnd: function(evt) {
            updateCurrentTask();
        }
    });

    // YouTube Player API
    let player;

    window.onYouTubeIframeAPIReady = function() {
        player = new YT.Player('youtubePlayer', {
            events: {
                'onReady': onPlayerReady
            }
        });
    };

    function onPlayerReady(event) {
        const player = event.target;

        document.getElementById('playButton').addEventListener('click', () => player.playVideo());
        document.getElementById('pauseButton').addEventListener('click', () => player.pauseVideo());

        document.getElementById('volumeUpButton').addEventListener('click', () => {
            let volume = player.getVolume();
            if (volume < 100) player.setVolume(volume + 10);
        });

        document.getElementById('volumeDownButton').addEventListener('click', () => {
            let volume = player.getVolume();
            if (volume > 0) player.setVolume(volume - 10);
        });

        document.getElementById('playlistForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const playlistId = document.getElementById('playlistInput').value;
            player.loadPlaylist({list: playlistId, listType: 'playlist'});
        });
    }

    // Set initial background color
    document.body.classList.add('work-session');
});
