<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pomodoro Timer</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header>
        <div id="authLinks">
            @guest
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
            @endguest
            @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <x-dropdown-link :href="route('logout')"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-dropdown-link>
            </form>
            @endauth
        </div>
    </header>
    <main>
        <div id="currentTaskDisplay" class="my-3">No current task</div>
        <div id="timer" class="text-center my-3">
            <div id="timeCircle" class="mx-auto mb-3">
                <span id="timeDisplay">25:00</span>
            </div>
            <div>
                <button id="resetButton" class="btn btn-secondary mx-1">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </button>
                <button id="toggleButton" class="btn btn-primary mx-1">
                    <i class="bi bi-cup-fill" id="toggleIcon"></i>
                </button>
                <button id="settingsButton" class="btn btn-light mx-1">
                    <i class="bi bi-gear-fill"></i>
                </button>
            </div>
        </div>
        <div id="tasks" class="my-3">
            <h2>Tasks</h2>
            <form id="taskForm" class="input-group mb-3" onsubmit="return false;">
                <input type="text" id="taskInput" class="form-control" placeholder="New task">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>
            </form>
            <ul id="taskList" class="list-group"></ul>
        </div>
        <div id="youtubeMusic" class="my-3">
            <iframe id="youtubePlayer" src="https://www.youtube.com/embed/?enablejsapi=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen class="mb-3 w-100" style="height: 300px;"></iframe>
            <div id="controls" class="text-center mb-3">
                <button id="playButton" class="btn btn-success mx-1">
                    <i class="bi bi-play-fill"></i>
                </button>
                <button id="pauseButton" class="btn btn-warning mx-1">
                    <i class="bi bi-pause-fill"></i>
                </button>
                <button id="volumeUpButton" class="btn btn-info mx-1">
                    <i class="bi bi-volume-up-fill"></i>
                </button>
                <button id="volumeDownButton" class="btn btn-info mx-1">
                    <i class="bi bi-volume-down-fill"></i>
                </button>
            </div>
            <form id="playlistForm" class="input-group mb-3">
                <input type="text" id="playlistInput" class="form-control" placeholder="Enter YouTube Playlist ID">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Settings Modal -->
    <div class="modal fade" id="settingsModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Timer Settings</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="settingsForm">
                        <div class="form-group">
                            <label for="workTime">Work Time (minutes)</label>
                            <input type="number" id="workTime" class="form-control form-control-lg" min="1" max="60" value="25">
                        </div>
                        <div class="form-group">
                            <label for="breakTime">Break Time (minutes)</label>
                            <input type="number" id="breakTime" class="form-control form-control-lg" min="1" max="30" value="5">
                        </div>
                        <div class="form-group">
                            <label for="endSound">End Sound</label>
                            <select id="endSound" class="form-control form-control-lg">
                                <option value="alarm1.mp3">Alarm 1</option>
                                <option value="alarm2.mp3">Alarm 2</option>
                                <option value="alarm3.mp3">Alarm 3</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary btn-lg btn-block" id="saveSettingsButton">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script src="https://www.youtube.com/iframe_api"></script>
</body>
</html>
