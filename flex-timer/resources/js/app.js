// public/js/app.js
document.addEventListener('DOMContentLoaded', function () {
    let timer;
    let isRunning = false;
    const timeDisplay = document.getElementById('timeDisplay');
    const startButton = document.getElementById('startButton');
    const stopButton = document.getElementById('stopButton');

    startButton.addEventListener('click', () => {
        if (!isRunning) {
            isRunning = true;
            let time = 25 * 60; // 25 minutes
            timer = setInterval(() => {
                const minutes = Math.floor(time / 60);
                const seconds = time % 60;
                timeDisplay.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
                if (time > 0) {
                    time--;
                } else {
                    clearInterval(timer);
                    isRunning = false;
                }
            }, 1000);
        }
    });

    stopButton.addEventListener('click', () => {
        clearInterval(timer);
        isRunning = false;
    });

    // Task management
    const taskList = document.getElementById('taskList');
    const taskInput = document.getElementById('taskInput');
    const addTaskButton = document.getElementById('addTaskButton');

    addTaskButton.addEventListener('click', () => {
        const taskText = taskInput.value.trim();
        if (taskText) {
            const li = document.createElement('li');
            li.textContent = taskText;
            taskList.appendChild(li);
            taskInput.value = '';
        }
    });
});
